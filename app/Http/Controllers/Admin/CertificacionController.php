<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificacion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CertificacionController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Certificaciones', [
            'certificaciones' => Certificacion::orderBy('sort_order')->get()
                ->map(fn($c) => array_merge($c->toArray(), [
                    'image_id' => $c->getFirstMedia('image')?->id,
                    'image_url' => parse_url($c->getFirstMediaUrl('image'), PHP_URL_PATH) ?: $c->getFirstMediaUrl('image'),
                    'gallery' => $c->getMedia('gallery')->map(fn($m) => [
                        'id' => $m->id,
                        'url' => parse_url($m->getUrl(), PHP_URL_PATH) ?: $m->getUrl(),
                    ])->values(),
                ])),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:certificaciones,key',
            'title' => 'required|string|max:255',
            'description_es' => 'nullable|string',
            'description_en' => 'nullable|string',
            'issuer' => 'nullable|string|max:255',
            'year_obtained' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $cert = Certificacion::create($data);

        if ($request->hasFile('image')) {
            $cert->addMediaFromRequest('image')->toMediaCollection('image');
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $cert->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return redirect()->back()->with('success', 'Certificación creada.');
    }

    public function update(Request $request, Certificacion $certificacion)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:certificaciones,key,' . $certificacion->id,
            'title' => 'required|string|max:255',
            'description_es' => 'nullable|string',
            'description_en' => 'nullable|string',
            'issuer' => 'nullable|string|max:255',
            'year_obtained' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'remove_gallery_ids' => 'nullable|array',
        ]);

        $certificacion->update($data);

        if ($request->hasFile('image')) {
            $certificacion->clearMediaCollection('image');
            $certificacion->addMediaFromRequest('image')->toMediaCollection('image');
        }
        if ($request->has('remove_gallery_ids')) {
            $certificacion->media()->whereIn('id', $request->remove_gallery_ids)->get()->each->delete();
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $certificacion->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return redirect()->back()->with('success', 'Certificación actualizada.');
    }

    public function destroy(Certificacion $certificacion)
    {
        $certificacion->delete();
        return redirect()->back()->with('success', 'Certificación eliminada.');
    }
}
