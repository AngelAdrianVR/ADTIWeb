<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const textTranslateY = ref(0);
const imageTranslateY = ref(0);
const imageRevealed = ref(false);
const subtitleRevealed = ref(false);
const bgTextRevealed = ref(false);
const scrollRevealed = ref(false);

let ticking = false;

function onScroll() {
    if (!ticking) {
        requestAnimationFrame(() => {
            const scrollY = window.scrollY;
            textTranslateY.value = scrollY * 0.4;
            imageTranslateY.value = scrollY * 0.08;
            ticking = false;
        });
        ticking = true;
    }
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    // Entrance animation sequence — slow cinematic reveal
    setTimeout(() => { bgTextRevealed.value = true; }, 200);
    setTimeout(() => { imageRevealed.value = true; }, 600);
    setTimeout(() => { subtitleRevealed.value = true; }, 1400);
    setTimeout(() => { scrollRevealed.value = true; }, 2000);
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <section class="relative flex items-center justify-center min-h-screen overflow-hidden bg-gradient-to-b from-[#a4bbcf] to-[#F5F0E8]">
        <!-- Parallax Background Text -->
        <h1
            class="absolute inset-0 flex items-start mt-20 md:mt-4 justify-center text-[12vw] sm:text-[10vw] md:text-[6vw] font-black uppercase leading-none text-[#ffffff] select-none text-center z-0 pointer-events-none"
            :class="bgTextRevealed ? 'opacity-100 blur-0' : 'opacity-0 blur-sm'"
            style="transition: opacity 1.5s ease-out, filter 1.5s ease-out;"
            :style="{ transform: `translate3d(0, ${textTranslateY}px, 0)` }"
        >
            {{ t('hero.title') }}
        </h1>

        <!-- Hero Image (overlapping the text) -->
        <div
            class="relative z-10 w-full max-w-5xl mx-auto px-4"
            :class="imageRevealed ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-16 scale-95'"
            style="transition: all 1.4s cubic-bezier(0.16, 1, 0.3, 1);"
            :style="{ transform: `translate3d(0, ${imageTranslateY}px, 0)` }"
        >
            <img
                src="@/../../public/images/line-1.png"
                alt="Industrial Production Line"
                class="w-full h-auto object-cover"
                draggable="false"
            />
        </div>

        <!-- Bottom scroll indicator -->
        <div
            class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-deep-onyx/30 z-20"
            :class="scrollRevealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
            style="transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);"
        >
            <span class="text-[10px] uppercase tracking-[0.3em] font-medium">{{ t('common.scroll') }}</span>
            <svg class="size-4 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>

        <div
            class="absolute bottom-0 left-20 flex space-x-5"
            :class="subtitleRevealed ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-12'"
            style="transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1);"
        >
            <p class="h-44 border-l border-gray-400"></p>
            <p class="w-72 text-gray-600 text-lg uppercase font-bold">{{ t('hero.subtitle') }}</p>
        </div>
    </section>
</template>
