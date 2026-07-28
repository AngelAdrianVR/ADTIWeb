<script setup>
import { Head } from '@inertiajs/vue3';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';

defineOptions({ layout: LandingAppLayout });
</script>

<template>
    <Head title="Proyectos" />
    <section class="min-h-[calc(100vh-5rem)] flex items-center justify-center px-4">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-deep-onyx tracking-tight">Proyectos</h1>
            <p class="mt-4 text-steel-grey">Conoce nuestros proyectos realizados.</p>
        </div>
    </section>
</template>
