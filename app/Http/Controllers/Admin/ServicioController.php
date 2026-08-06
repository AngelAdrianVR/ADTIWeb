<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicioController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Servicios', [
            'servicios' => Servicio::orderBy('sort_order')->get()
                ->map(fn($s) => array_merge($s->toArray(), [
                    'image_url' => $s->getFirstMediaUrl('image'),
                    'gallery' => $s->getMedia('gallery')->map(fn($m) => [
                        'id' => $m->id,
                        'url' => $m->getUrl(),
                    ])->values(),
                ])),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:servicios,key',
            'title_es' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_es' => 'required|string',
            'description_en' => 'required|string',
            'features_es' => 'nullable|array',
            'features_en' => 'nullable|array',
            'techs' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        $servicio = Servicio::create($data);

        if ($request->hasFile('image')) {
            $servicio->addMediaFromRequest('image')->toMediaCollection('image');
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $servicio->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return redirect()->back()->with('success', 'Servicio creado.');
    }

    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:servicios,key,' . $servicio->id,
            'title_es' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_es' => 'required|string',
            'description_en' => 'required|string',
            'features_es' => 'nullable|array',
            'features_en' => 'nullable|array',
            'techs' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'active' => 'boolean',
            'image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
            'remove_gallery_ids' => 'nullable|array',
        ]);

        $servicio->update($data);

        if ($request->hasFile('image')) {
            $servicio->clearMediaCollection('image');
            $servicio->addMediaFromRequest('image')->toMediaCollection('image');
        }
        if ($request->has('remove_gallery_ids')) {
            $servicio->media()->whereIn('id', $request->remove_gallery_ids)->get()->each->delete();
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $servicio->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return redirect()->back()->with('success', 'Servicio actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->back()->with('success', 'Servicio eliminado.');
    }
}
