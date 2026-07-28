<script setup>
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();
</script>

<template>
    <footer class="relative bg-[#031120] overflow-hidden">
        <!-- ═══════ Animated background effects ═══════ -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Tech grid -->
            <div class="absolute inset-0 opacity-[0.025]"
                style="background-image: linear-gradient(rgba(0,212,255,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.5) 1px, transparent 1px); background-size: 70px 70px;"
            />

            <!-- Moving glow orbs -->
            <div class="absolute w-80 h-80 rounded-full bg-brand-blue/6 blur-3xl animate-float-slow"
                style="top: -10%; left: -5%; animation-delay: 0s;" />
            <div class="absolute w-64 h-64 rounded-full bg-electric-cyan/4 blur-3xl animate-float-slow"
                style="bottom: 20%; right: -5%; animation-delay: 2s;" />
            <div class="absolute w-48 h-48 rounded-full bg-brand-blue/5 blur-3xl animate-float-slow"
                style="top: 40%; left: 50%; animation-delay: 4s;" />

            <!-- Top accent line -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-blue/30 to-transparent" />
        </div>

        <!-- ═══════ Content ═══════ -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">

                <!-- ── Brand ── -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <img draggable="false" class="h-10 md:h-11" src="@/../../public/images/logo_3d.png" alt="ADTI">
                    <p class="mt-5 text-sm text-white/40 leading-relaxed max-w-xs">
                        {{ t('footer.tagline') }}
                    </p>
                </div>

                <!-- ── Navigation ── -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-brand-blue mb-6">
                        {{ t('footer.navigation') }}
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <Link :href="route('servicios')" class="text-sm text-white/40 hover:text-brand-blue transition-colors duration-200">
                                {{ t('nav.services') }}
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('proyectos')" class="text-sm text-white/40 hover:text-brand-blue transition-colors duration-200">
                                {{ t('nav.projects') }}
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('nosotros')" class="text-sm text-white/40 hover:text-brand-blue transition-colors duration-200">
                                {{ t('nav.about') }}
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('contacto')" class="text-sm text-white/40 hover:text-brand-blue transition-colors duration-200">
                                {{ t('nav.contact') }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- ── Contact ── -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-brand-blue mb-6">
                        {{ t('footer.contact') }}
                    </h4>
                    <ul class="space-y-4">
                        <!-- Phone -->
                        <li>
                            <a href="tel:+523336985016"
                                class="group flex items-start gap-3 text-sm text-white/40 hover:text-white transition-colors duration-200">
                                <span class="shrink-0 mt-0.5 size-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-brand-blue/20 transition-colors duration-300">
                                    <svg class="size-3.5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                </span>
                                <span class="leading-tight">+52 33 3698 5016</span>
                            </a>
                        </li>
                        <!-- Email -->
                        <li>
                            <a href="mailto:info@adti.com.mx"
                                class="group flex items-start gap-3 text-sm text-white/40 hover:text-white transition-colors duration-200">
                                <span class="shrink-0 mt-0.5 size-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-brand-blue/20 transition-colors duration-300">
                                    <svg class="size-3.5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </span>
                                <span class="leading-tight">info@adti.com.mx</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- ── Location ── -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-[0.2em] text-brand-blue mb-6">
                        {{ t('footer.location') }}
                    </h4>
                    <a href="https://maps.google.com/?q=Calzada+Federalismo+Norte+1971+Guadalajara+Jalisco"
                        target="_blank" rel="noopener"
                        class="group flex items-start gap-3 text-sm text-white/40 hover:text-white transition-colors duration-200">
                        <span class="shrink-0 mt-0.5 size-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-brand-blue/20 transition-colors duration-300">
                            <svg class="size-3.5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </span>
                        <span class="leading-tight">
                            Calzada Federalismo Norte N. 1971<br />
                            Jardines del Country, 44210<br />
                            Guadalajara, Jalisco, México
                        </span>
                    </a>
                </div>
            </div>

            <!-- ═══════ Bottom bar ═══════ -->
            <div class="mt-14 pt-6 border-t border-white/[0.06] flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-white/25">
                    &copy; {{ new Date().getFullYear() }} ADTI. {{ t('footer.rights') }}
                </p>
                <div class="flex items-center gap-3 text-xs text-white/20">
                    <Link :href="route('terms.show')" class="hover:text-white/40 transition-colors duration-200">
                        {{ t('footer.terms') }}
                    </Link>
                    <span class="text-white/10">|</span>
                    <Link :href="route('policy.show')" class="hover:text-white/40 transition-colors duration-200">
                        {{ t('footer.privacy') }}
                    </Link>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
@keyframes floatSlow {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    25% {
        transform: translate(30px, -20px) scale(1.1);
    }
    50% {
        transform: translate(-15px, -40px) scale(0.95);
    }
    75% {
        transform: translate(20px, 10px) scale(1.05);
    }
}

.animate-float-slow {
    animation: floatSlow 12s ease-in-out infinite;
}
</style>
