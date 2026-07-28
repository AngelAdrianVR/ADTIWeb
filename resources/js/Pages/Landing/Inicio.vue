<script setup>
import { Head } from '@inertiajs/vue3';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';

defineOptions({
    layout: LandingAppLayout,
});
</script>

<template>
    <Head title="Inicio" />

    <!-- Hero Section -->
    <section class="relative flex flex-col items-center justify-center min-h-[calc(100vh-5rem)] px-4 text-center">
        <!-- Subtle radial glow -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,130,168,0.04),transparent_70%)] pointer-events-none" />

        <div class="relative z-10 max-w-4xl mx-auto">
            <p class="text-xs sm:text-sm uppercase tracking-[0.25em] text-brand-blue mb-6">
                Automatización Industrial
            </p>

            <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold tracking-tight text-deep-onyx leading-[1.1]">
                Ingeniería que
                <span class="text-brand-blue">impulsa</span>
                <br />
                la industria
            </h1>

            <p class="mt-6 max-w-2xl mx-auto text-base sm:text-lg text-steel-grey leading-relaxed">
                Diseñamos, programamos e integramos soluciones de automatización industrial de alto nivel —
                desde HMI y SCADA hasta tableros de control — para toda América.
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a
                    :href="route('servicios')"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full
                           bg-deep-onyx text-white font-medium text-sm
                           hover:bg-deep-onyx/85
                           shadow-lg shadow-deep-onyx/15
                           transition-all duration-300 ease-out"
                >
                    Nuestros Servicios
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a
                    :href="route('contacto')"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full
                           bg-transparent text-deep-onyx font-medium text-sm
                           border border-steel-grey/30
                           hover:border-brand-blue/40 hover:text-brand-blue
                           transition-all duration-300 ease-out"
                >
                    Contáctanos
                </a>
            </div>
        </div>

        <!-- Bottom scroll hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-steel-grey/30">
            <span class="text-[10px] uppercase tracking-[0.3em]">Scroll</span>
            <svg class="size-4 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
    </section>
</template>
