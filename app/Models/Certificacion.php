<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Certificacion extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'certificaciones';

    protected $fillable = [
        'key',
        'title',
        'description_es',
        'description_en',
        'issuer',
        'year_obtained',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'year_obtained' => 'integer',
        'active' => 'boolean',
    ];

    // ── Media Collections ──
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('public');
    }

    // ── Accessors: bilingual helpers ──
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_es;
    }
}
