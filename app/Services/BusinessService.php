<?php

namespace App\Services;

use App\Models\Business;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BusinessService
{
    public function create(array $data): Business
    {
        if (isset($data['logo_path'])) {
            $data['logo_path'] = $data['logo_path']->store('logos', 'public');
        }
        $data['slug'] = $this->generateSlug($data);
        return Business::create($data);
    }

    public function update(Business $business, array $data)
    {
        if (!empty($data['logo_path']) && $data['logo_path'] instanceof \Illuminate\Http\UploadedFile) {
            if ($business->logo_path && Storage::disk('public')->exists($business->logo_path)) {
                Storage::disk('public')->delete($business->logo_path);
            }
            $data['logo_path'] = $data['logo_path']->store('logos', 'public');
        } else {
            unset($data['logo_path']);
        }

        $fillableFields = ['category_id', 'name', 'slug', 'description', 'phone_number', 'email', 'address', 'city', 'logo_path'];
        foreach ($fillableFields as $field) {
            if (!array_key_exists($field, $data)) {
                $data[$field] = $business->{$field};
            }
        }

        $business->update($data);

        return $business->fresh();
    }

    public function delete(Business $business): void
    {
        DB::transaction(function () use ($business) {
            if (!empty($business->logo_path) && Storage::disk('public')->exists($business->logo_path)) {
                Storage::disk('public')->delete($business->logo_path);
            }

            $business->delete();
        });
    }

    protected function generateSlug(array $data, $ignoreId = null): string
    {
        $slug = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        $exists = Business::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'slug' => ['The slug has already been taken. Please choose another one.']
            ]);
        }

        return $slug;
    }
}
