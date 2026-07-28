<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

// Client logo images
import barcel from '@/../../public/images/clients_logos/barcel-logo.webp';
import borgWarner from '@/../../public/images/clients_logos/borg-warmer.webp';
import corteva from '@/../../public/images/clients_logos/corteva.webp';
import hersheys from '@/../../public/images/clients_logos/hersheys.webp';
import jabil from '@/../../public/images/clients_logos/jabil.webp';
import poly from '@/../../public/images/clients_logos/Poly-logo.webp';
import ppm from '@/../../public/images/clients_logos/ppm-logo.webp';
import psf from '@/../../public/images/clients_logos/PSF-logo.webp';
import regald from '@/../../public/images/clients_logos/regald-logo.webp';

const { t } = useI18n();

const clients = [
    { src: barcel, alt: 'Barcel' },
    { src: borgWarner, alt: 'Borg Warner' },
    { src: corteva, alt: 'Corteva' },
    { src: hersheys, alt: 'Hershey\'s' },
    { src: jabil, alt: 'Jabil' },
    { src: poly, alt: 'Poly' },
    { src: ppm, alt: 'PPM' },
    { src: psf, alt: 'PSF' },
    { src: regald, alt: 'Regald' },
];

const sectionRevealed = ref(false);
const ribbonPaused = ref(false);
let observer = null;

onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                sectionRevealed.value = true;
            }
        },
        { threshold: 0.2 }
    );

    const el = document.getElementById('clients-section');
    if (el) observer.observe(el);
});

onUnmounted(() => {
    if (observer) observer.disconnect();
});
</script>

<template>
    <section
        id="clients-section"
        class="py-20 overflow-hidden bg-[#6b7485]"
    >
        <!-- Section header -->
        <div
            class="text-center mb-14 px-4 transition-all duration-700 ease-out"
            :class="sectionRevealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        >
            <span class="inline-block text-xs uppercase tracking-[0.3em] text-white font-semibold px-4 py-1.5 rounded-full border border-white">
                {{ t('clients.title') }}
            </span>
        </div>

        <!-- Infinite scroll ribbon -->
        <div
            class="relative transition-all duration-1000 ease-out delay-200 mx-auto"
            :class="[sectionRevealed ? 'opacity-100' : 'opacity-0', 'w-[90%] sm:w-[85%] lg:w-[80%]']"
        >
            <!-- Top decorative line -->
            <div class="absolute -top-4 left-0 right-0 flex items-center gap-4 opacity-60">
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent" />
                <div class="flex gap-1.5">
                    <span class="size-1.5 rounded-full bg-brand-blue/60" />
                    <span class="size-1.5 rounded-full bg-white/40" />
                    <span class="size-1.5 rounded-full bg-brand-blue/60" />
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/40 to-transparent" />
            </div>

            <!-- Bottom decorative line -->
            <div class="absolute -bottom-4 left-0 right-0 flex items-center gap-4 opacity-60">
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/30 to-transparent" />
                <div class="flex gap-1.5">
                    <span class="size-1 rounded-full bg-white/25" />
                    <span class="size-1 rounded-full bg-brand-blue/40" />
                    <span class="size-1 rounded-full bg-white/25" />
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/30 to-transparent" />
            </div>

            <!-- Ribbon container -->
            <div
                class="relative overflow-hidden py-6"
                @mouseenter="ribbonPaused = true"
                @mouseleave="ribbonPaused = false"
            >
                <!-- Scrolling track -->
                <div
                    class="flex gap-12 sm:gap-16 lg:gap-20 w-max animate-scroll-ribbon"
                    :style="{ animationPlayState: ribbonPaused ? 'paused' : 'running' }"
                >
                    <!-- Double the clients for seamless loop -->
                    <template v-for="n in 2" :key="n">
                        <div
                            v-for="(client, idx) in clients"
                            :key="`${n}-${idx}`"
                            class="shrink-0 flex items-center justify-center h-12 sm:h-14 lg:h-16"
                        >
                            <img
                                :src="client.src"
                                :alt="client.alt"
                                class="h-[150%] w-auto object-contain opacity-40 grayscale
                                    hover:opacity-90 hover:grayscale-0
                                    transition-all duration-400 ease-out select-none"
                                draggable="false"
                            />
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes scrollRibbon {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-scroll-ribbon {
    animation: scrollRibbon 30s linear infinite;
}
</style>
