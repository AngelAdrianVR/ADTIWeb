<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Proyecto extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'proyectos';

    protected $fillable = [
        'key',
        'title_es',
        'title_en',
        'description_es',
        'description_en',
        'client_es',
        'client_en',
        'location_es',
        'location_en',
        'year',
        'category',
        'features_es',
        'features_en',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'features_es' => 'array',
        'features_en' => 'array',
        'year' => 'integer',
        'active' => 'boolean',
    ];

    // ── Media Collections ──
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('public');

        $this->addMediaCollection('gallery')
            ->useDisk('public');
    }

    // ── Accessors: bilingual helpers ──
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_es;
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_es;
    }

    public function getClientAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->{"client_{$locale}"} ?? $this->client_es;
    }

    public function getLocationAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->{"location_{$locale}"} ?? $this->location_es;
    }

    public function getFeaturesAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"features_{$locale}"} ?? $this->features_es ?? [];
    }
}
