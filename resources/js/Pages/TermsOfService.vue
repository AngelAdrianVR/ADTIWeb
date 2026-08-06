<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <Head :title="t('legal.termsTitle')" />

    <div class="min-h-screen bg-[#031120] flex flex-col items-center justify-center relative overflow-hidden">
        <!-- Tech grid -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.025]"
            style="background-image: linear-gradient(rgba(0,212,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.5) 1px, transparent 1px); background-size: 70px 70px;"
        />

        <!-- Glow orb -->
        <div class="absolute w-96 h-96 rounded-full bg-brand-blue/5 blur-3xl" style="top: 50%; left: 50%; transform: translate(-50%, -50%);" />

        <!-- Top accent line -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-blue/30 to-transparent" />

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center gap-8 px-4">
            <Link href="/">
                <img draggable="false" class="h-14 md:h-16 hover:opacity-80 transition-opacity duration-200" src="/images/logo_3d.png" alt="ADTI">
            </Link>

            <div class="h-px w-24 bg-gradient-to-r from-transparent via-brand-blue/40 to-transparent" />

            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white">
                {{ t('legal.termsTitle') }}
            </h1>

            <p class="text-sm text-white/30 max-w-md text-center leading-relaxed">
                {{ t('legal.comingSoon') }}
            </p>

            <Link :href="route('inicio')"
                class="mt-4 inline-flex items-center gap-2 text-sm text-brand-blue hover:text-electric-cyan transition-colors duration-200">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ t('common.back') }}
            </Link>
        </div>
    </div>
</template>
