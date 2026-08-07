<?php

namespace App\Http\Controllers;

use App\Models\Certificacion;
use App\Models\Proyecto;
use App\Models\Servicio;
use Inertia\Inertia;

class LandingController extends Controller
{
    /**
     * Splash / Loading screen.
     */
    public function splash()
    {
        return Inertia::render('Landing/Splash');
    }

    /**
     * Home / Inicio.
     */
    public function inicio()
    {
        return Inertia::render('Landing/Inicio', [
            'servicios' => $this->activeServicios(),
            'proyectos' => $this->activeProyectos(),
            'certificaciones' => $this->activeCertificaciones(),
        ]);
    }

    /**
     * Services / Servicios.
     */
    public function servicios()
    {
        return Inertia::render('Landing/Servicios', [
            'servicios' => $this->activeServicios(),
        ]);
    }

    /**
     * Projects / Proyectos.
     */
    public function proyectos()
    {
        return Inertia::render('Landing/Proyectos', [
            'proyectos' => $this->activeProyectos(),
        ]);
    }

    /**
     * About / Nosotros.
     */
    public function nosotros()
    {
        return Inertia::render('Landing/Nosotros');
    }

    /**
     * Contact / Contacto.
     */
    public function contacto()
    {
        return Inertia::render('Landing/Contacto');
    }

    /**
     * Terms of Service.
     */
    public function terms()
    {
        return Inertia::render('TermsOfService');
    }

    /**
     * Privacy Policy.
     */
    public function policy()
    {
        return Inertia::render('PrivacyPolicy');
    }

    /**
     * Active services with media URLs, ordered by sort_order.
     */
    private function activeServicios()
    {
        return Servicio::where('active', true)->orderBy('sort_order')->get()
            ->map(fn($s) => array_merge($s->toArray(), [
                'image_url' => parse_url($s->getFirstMediaUrl('image'), PHP_URL_PATH) ?: $s->getFirstMediaUrl('image'),
                'gallery' => $s->getMedia('gallery')->map(fn($m) => [
                    'id' => $m->id,
                    'url' => parse_url($m->getUrl(), PHP_URL_PATH) ?: $m->getUrl(),
                ])->values(),
            ]))
            ->values();
    }

    /**
     * Active projects with media URLs, ordered by sort_order.
     */
    private function activeProyectos()
    {
        return Proyecto::where('active', true)->orderBy('sort_order')->get()
            ->map(fn($p) => array_merge($p->toArray(), [
                'image_url' => parse_url($p->getFirstMediaUrl('image'), PHP_URL_PATH) ?: $p->getFirstMediaUrl('image'),
                'video_id' => $p->getFirstMedia('video')?->id,
                'video_file_url' => parse_url($p->getFirstMediaUrl('video'), PHP_URL_PATH) ?: $p->getFirstMediaUrl('video'),
                'gallery' => $p->getMedia('gallery')->map(fn($m) => [
                    'id' => $m->id,
                    'url' => parse_url($m->getUrl(), PHP_URL_PATH) ?: $m->getUrl(),
                ])->values(),
            ]))
            ->values();
    }

    /**
     * Active certifications with media URLs, ordered by sort_order.
     */
    private function activeCertificaciones()
    {
        return Certificacion::where('active', true)->orderBy('sort_order')->get()
            ->map(fn($c) => array_merge($c->toArray(), [
                'image_id' => $c->getFirstMedia('image')?->id,
                'image_url' => parse_url($c->getFirstMediaUrl('image'), PHP_URL_PATH) ?: $c->getFirstMediaUrl('image'),
                'gallery' => $c->getMedia('gallery')->map(fn($m) => [
                    'id' => $m->id,
                    'url' => parse_url($m->getUrl(), PHP_URL_PATH) ?: $m->getUrl(),
                ])->values(),
            ]))
            ->values();
    }
}
