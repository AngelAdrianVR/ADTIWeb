<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Servicio extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'servicios';

    protected $fillable = [
        'key',
        'title_es',
        'title_en',
        'description_es',
        'description_en',
        'features_es',
        'features_en',
        'techs',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'features_es' => 'array',
        'features_en' => 'array',
        'techs' => 'array',
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

    public function getFeaturesAttribute(): array
    {
        $locale = app()->getLocale();
        return $this->{"features_{$locale}"} ?? $this->features_es ?? [];
    }
}
