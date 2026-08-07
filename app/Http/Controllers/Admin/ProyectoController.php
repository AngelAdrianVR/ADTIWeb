<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProyectoController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Proyectos', [
            'proyectos' => Proyecto::orderBy('sort_order')->get()
                ->map(fn($p) => array_merge($p->toArray(), [
                    'image_url' => parse_url($p->getFirstMediaUrl('image'), PHP_URL_PATH) ?: $p->getFirstMediaUrl('image'),
                    'video_id' => $p->getFirstMedia('video')?->id,
                    'video_file_url' => parse_url($p->getFirstMediaUrl('video'), PHP_URL_PATH) ?: $p->getFirstMediaUrl('video'),
                    'gallery' => $p->getMedia('gallery')->map(fn($m) => [
                        'id' => $m->id,
                        'url' => parse_url($m->getUrl(), PHP_URL_PATH) ?: $m->getUrl(),
                    ])->values(),
                ])),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:proyectos,key',
            'title_es' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_es' => 'required|string',
            'description_en' => 'required|string',
            'client_es' => 'nullable|string|max:255',
            'client_en' => 'nullable|string|max:255',
            'location_es' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'category' => 'nullable|string|max:255',
            'features_es' => 'nullable|array',
            'features_en' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'video_url' => 'nullable|string|max:500',
            'video' => 'nullable|file|mimes:mp4,webm,mov,ogv|max:102400',
        ]);

        $proyecto = Proyecto::create($data);

        if ($request->hasFile('image')) {
            $proyecto->addMediaFromRequest('image')->toMediaCollection('image');
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $proyecto->addMedia($file)->toMediaCollection('gallery');
            }
        }
        if ($request->hasFile('video')) {
            $proyecto->addMediaFromRequest('video')->toMediaCollection('video');
        }

        return redirect()->back()->with('success', 'Proyecto creado.');
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:proyectos,key,' . $proyecto->id,
            'title_es' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_es' => 'required|string',
            'description_en' => 'required|string',
            'client_es' => 'nullable|string|max:255',
            'client_en' => 'nullable|string|max:255',
            'location_es' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'category' => 'nullable|string|max:255',
            'features_es' => 'nullable|array',
            'features_en' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'remove_gallery_ids' => 'nullable|array',
            'video_url' => 'nullable|string|max:500',
            'video' => 'nullable|file|mimes:mp4,webm,mov,ogv|max:102400',
        ]);

        $proyecto->update($data);

        if ($request->hasFile('image')) {
            $proyecto->clearMediaCollection('image');
            $proyecto->addMediaFromRequest('image')->toMediaCollection('image');
        }
        if ($request->has('remove_gallery_ids')) {
            $proyecto->media()->whereIn('id', $request->remove_gallery_ids)->get()->each->delete();
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $proyecto->addMedia($file)->toMediaCollection('gallery');
            }
        }
        if ($request->hasFile('video')) {
            $proyecto->clearMediaCollection('video');
            $proyecto->addMediaFromRequest('video')->toMediaCollection('video');
        }

        return redirect()->back()->with('success', 'Proyecto actualizado.');
    }

    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();
        return redirect()->back()->with('success', 'Proyecto eliminado.');
    }
}
