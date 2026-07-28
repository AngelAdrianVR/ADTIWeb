<?php

namespace App\Http\Controllers;

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
        return Inertia::render('Landing/Inicio');
    }

    /**
     * Services / Servicios.
     */
    public function servicios()
    {
        return Inertia::render('Landing/Servicios');
    }

    /**
     * Projects / Proyectos.
     */
    public function proyectos()
    {
        return Inertia::render('Landing/Proyectos');
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
}
