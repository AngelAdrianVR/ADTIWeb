<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const visible = ref(true);
const lettersReady = ref(false);
const fadeOut = ref(false);
const showLoader = ref(false);

const letters = ['A', 'D', 'T', 'I'];

const spreadOffsets = ['-120vw', '-40vw', '40vw', '120vw'];

function getLetterStyle(i) {
    if (!lettersReady.value && !fadeOut.value) {
        return {
            transform: `translateX(${spreadOffsets[i]}) scale(0.85)`,
            opacity: 0,
            filter: 'blur(8px)',
            transition: 'all 1s cubic-bezier(0.22, 1, 0.36, 1)',
        };
    }
    if (fadeOut.value) {
        return {
            transform: 'translateY(-60px) scale(0.9)',
            opacity: 0,
            filter: 'blur(6px)',
            transition: `all 0.7s cubic-bezier(0.4, 0, 0.2, 1) ${i * 0.06}s`,
        };
    }
    return {
        transform: 'translateX(0) translateY(0) scale(1)',
        opacity: 1,
        filter: 'blur(0px)',
        transition: `all 0.9s cubic-bezier(0.34, 1.56, 0.64, 1) ${0.15 + i * 0.12}s`,
    };
}

function getAccentStyle() {
    if (!lettersReady.value && !fadeOut.value) {
        return { opacity: 0, transition: 'opacity 0.8s ease-out' };
    }
    if (fadeOut.value) {
        return { opacity: 0, transition: 'opacity 0.5s ease-in' };
    }
    return { opacity: 1, transition: 'opacity 1s ease-out 0.6s' };
}

onMounted(() => {
    requestAnimationFrame(() => {
        lettersReady.value = true;
    });

    // Show gear loader once letters are settled
    setTimeout(() => {
        showLoader.value = true;
    }, 500);

    // Fade everything out and navigate
    setTimeout(() => {
        fadeOut.value = true;
        visible.value = false;
        setTimeout(() => {
            router.get('/inicio');
        }, 900);
    }, 3800);
});
</script>

<template>
    <div
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-white overflow-hidden"
        :class="visible ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        style="transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.4s"
    >
        <!-- Top accent line -->
        <div
            class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-brand-blue/40 to-transparent"
            :style="getAccentStyle()"
        />

        <!-- Spread letters -->
        <div class="flex items-center justify-center gap-4 sm:gap-6 md:gap-8">
            <span
                v-for="(letter, i) in letters"
                :key="i"
                class="text-[5rem] sm:text-8xl md:text-9xl font-bold tracking-tighter text-deep-onyx select-none"
                :style="getLetterStyle(i)"
            >
                {{ letter }}
            </span>
        </div>

        <!-- Tagline -->
        <p
            class="mt-10 text-xs uppercase tracking-[0.3em] text-steel-grey/50"
            :class="lettersReady && !fadeOut ? 'opacity-100' : 'opacity-0'"
            style="transition: opacity 0.6s ease-out 1.2s"
        >
            Industrial Automation
        </p>

        <!-- Minimalist Spinning Gear Loader -->
        <div
            class="mt-8 flex flex-col items-center gap-3"
            :class="showLoader && !fadeOut ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
            style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1)"
        >
            <!-- Gear SVG -->
            <svg
                class="size-12 text-steel-grey/40 animate-spin"
                viewBox="0 0 40 40"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                style="animation-duration: 3s"
            >
                <!-- Outer ring with teeth -->
                <circle cx="20" cy="20" r="17" stroke="currentColor" stroke-width="1.5"
                        stroke-dasharray="4 2.5" />
                <!-- Inner ring -->
                <circle cx="20" cy="20" r="11" stroke="currentColor" stroke-width="1"
                        stroke-dasharray="2 3" opacity="0.5" />
                <!-- Center dot -->
                <circle cx="20" cy="20" r="3" fill="currentColor" opacity="0.6" />
                <!-- Cross spokes -->
                <line x1="20" y1="6" x2="20" y2="14" stroke="currentColor" stroke-width="1" opacity="0.35" />
                <line x1="20" y1="26" x2="20" y2="34" stroke="currentColor" stroke-width="1" opacity="0.35" />
                <line x1="6" y1="20" x2="14" y2="20" stroke="currentColor" stroke-width="1" opacity="0.35" />
                <line x1="26" y1="20" x2="34" y2="20" stroke="currentColor" stroke-width="1" opacity="0.35" />
            </svg>
            <span class="text-[10px] uppercase tracking-[0.25em] text-steel-grey/35">Cargando</span>
        </div>

        <!-- Bottom accent line -->
        <div
            class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-brand-blue/30 to-transparent"
            :style="getAccentStyle()"
        />
    </div>
</template>
