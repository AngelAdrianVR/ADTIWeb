<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const containerRef = ref(null);
const scale = ref(1);
const lineProgress = ref(0);
const textVisible = ref(false);
const sectionActive = ref(false);

let ticking = false;
let observer = null;

function onScroll() {
    if (!ticking) {
        requestAnimationFrame(() => {
            if (!containerRef.value) {
                ticking = false;
                return;
            }

            const rect = containerRef.value.getBoundingClientRect();
            const viewH = window.innerHeight;
            const elCenter = rect.top + rect.height / 2;

            // Scale: from 1.0 (when section enters) to 1.0 (stays normal), then grows toward end
            // We want the video to grow as the user scrolls deeper into the section
            const startPoint = viewH * 0.8; // When top of section hits 80% of viewport
            const endPoint = viewH * 0.05;  // Almost at top

            if (rect.top < startPoint && rect.bottom > 0) {
                sectionActive.value = true;
                // Progress from 0 (just entered) to 1 (scrolled through)
                const progress = Math.min(1, Math.max(0,
                    (startPoint - rect.top) / (startPoint - endPoint)
                ));
                // Scale from 1 to 1.15
                scale.value = 1 + progress * 0.15;
            }

            // Line drawing at 80% of section scrolled
            if (rect.top < viewH * 0.2 && rect.bottom > viewH * 0.2) {
                const lineProgressRaw = Math.min(1, Math.max(0,
                    (viewH * 0.2 - rect.top) / (viewH * 0.15)
                ));
                lineProgress.value = lineProgressRaw;
                if (lineProgressRaw > 0.95) {
                    textVisible.value = true;
                }
            }

            ticking = false;
        });
        ticking = true;
    }
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    // Initial check
    setTimeout(onScroll, 100);

    observer = new IntersectionObserver(
        ([entry]) => {
            if (!entry.isIntersecting) {
                sectionActive.value = false;
            }
        },
        { threshold: 0 }
    );
    if (containerRef.value) observer.observe(containerRef.value);
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
    if (observer) observer.disconnect();
});
</script>

<template>
    <section
        ref="containerRef"
        class="relative bg-deep-onyx overflow-hidden"
        :style="{ height: '250vh' }"
    >
        <!-- Sticky video container that scales with scroll -->
        <div class="sticky top-0 h-screen flex items-center justify-center overflow-hidden">
            <!-- Video placeholder (replace with real <video> tag) -->
            <div
                class="relative w-full h-full transition-transform duration-100 ease-linear"
                :style="{ transform: `scale(${scale})` }"
            >
                <!-- Simulated video / gradient background -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#0a1628] via-[#0F172A] to-[#0d2137]">
                    <!-- Animated grid/tech pattern -->
                    <div class="absolute inset-0 opacity-[0.04]"
                        style="background-image: linear-gradient(rgba(0,212,255,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.3) 1px, transparent 1px); background-size: 60px 60px;"
                    />
                    <!-- Pulse glow -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 size-96 rounded-full bg-brand-blue/10 blur-3xl animate-pulse" />
                </div>

                <!-- Centered play hint -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="size-20 sm:size-28 rounded-full border-2 border-ghost-white/30 flex items-center justify-center
                        hover:border-brand-blue hover:scale-110 transition-all duration-500 cursor-pointer group">
                        <div class="size-0 border-t-[12px] sm:border-t-[16px] border-t-transparent
                            border-b-[12px] sm:border-b-[16px] border-b-transparent
                            border-l-[20px] sm:border-l-[28px] border-l-ghost-white/70
                            ml-1.5 group-hover:border-l-brand-blue transition-colors duration-300" />
                    </div>
                </div>

                <!-- Bottom gradient overlay for text readability -->
                <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-gradient-to-t from-deep-onyx to-transparent pointer-events-none" />
            </div>
        </div>

        <!-- Horizontal line + text reveal (absolute positioned at 80% of the section) -->
        <div class="absolute inset-0 pointer-events-none" style="top: 70%;">
            <div class="sticky top-[20vh] flex flex-col items-center px-4">
                <!-- Animated horizontal line -->
                <div class="relative w-full max-w-3xl h-px overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-brand-blue to-transparent transition-all duration-700 ease-out"
                        :style="{ transform: `scaleX(${lineProgress})`, transformOrigin: 'center' }"
                    />
                </div>

                <!-- Text reveal -->
                <p
                    class="mt-8 text-2xl sm:text-3xl lg:text-4xl font-black text-ghost-white text-center leading-tight tracking-tight transition-all duration-700 ease-out"
                    :class="textVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    {{ t('video.tagline') }}
                </p>
            </div>
        </div>
    </section>
</template>
