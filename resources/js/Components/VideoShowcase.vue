<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const containerRef = ref(null);
const videoScale = ref(0.3);
const videoRadius = ref(32);
const lineProgress = ref(0);
const textVisible = ref(false);
const isFullScreen = ref(false);

let ticking = false;
let observer = null;

function clamp(v, min, max) {
    return Math.min(max, Math.max(min, v));
}

function onScroll() {
    if (!ticking) {
        requestAnimationFrame(() => {
            if (!containerRef.value) { ticking = false; return; }

            const rect = containerRef.value.getBoundingClientRect();
            const viewH = window.innerHeight;

            // Section progress: 0 = top of section at viewport bottom, 1 = bottom of section at viewport top
            const sectionProgress = clamp(
                (viewH - rect.top) / (viewH + rect.height),
                0, 1
            );

            // Video scale: 0.3 (small) → 1.0 (fullscreen)
            // Goes from 30% to fullscreen as we scroll through ~70% of the section
            const scaleStart = 0.05;
            const scaleEnd = 0.75;
            const rawScale = clamp(
                (sectionProgress - scaleStart) / (scaleEnd - scaleStart),
                0, 1
            );

            videoScale.value = 0.3 + rawScale * 0.7;
            // Radius: 32px rounded → 0px (full screen)
            videoRadius.value = Math.round(32 * (1 - rawScale));
            isFullScreen.value = rawScale > 0.98;

            // Line reveal: starts at ~85% of the scale animation
            const lineStart = 0.85;
            lineProgress.value = clamp(
                (rawScale - lineStart) / (1 - lineStart),
                0, 1
            );

            // Text visible when line is nearly done
            textVisible.value = lineProgress.value > 0.7;

            ticking = false;
        });
        ticking = true;
    }
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    setTimeout(onScroll, 50);

    observer = new IntersectionObserver(
        ([entry]) => {
            if (!entry.isIntersecting) {
                videoScale.value = 0.3;
                lineProgress.value = 0;
                textVisible.value = false;
                isFullScreen.value = false;
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
        class="relative bg-deep-onyx"
        :style="{ height: '300vh' }"
    >
        <!-- Sticky video wrapper -->
        <div class="sticky top-0 h-screen flex items-center justify-center overflow-hidden">
            <!-- Video container that scales -->
            <div
                class="relative flex items-center justify-center transition-[width,height,border-radius] duration-200 ease-linear"
                :class="isFullScreen ? 'shadow-none' : 'shadow-[0_0_80px_rgba(0,130,168,0.25)]'"
                :style="{
                    width: `${videoScale * 100}vw`,
                    height: `${videoScale * 100}vh`,
                    borderRadius: `${videoRadius}px`,
                }"
            >
                <!-- Video -->
                <video
                    autoplay
                    muted
                    loop
                    playsinline
                    class="absolute inset-0 w-full h-full object-cover"
                    :style="{ borderRadius: `${videoRadius}px` }"
                >
                    <source src="/videos/video-robot-arms.mp4" type="video/mp4" />
                </video>

                <!-- Subtle border glow -->
                <div
                    class="absolute inset-0 pointer-events-none rounded-[inherit] ring-1 ring-inset ring-white/10"
                />

                <!-- Line + Text overlay (appears near full screen) -->
                <div
                    v-if="lineProgress > 0"
                    class="absolute bottom-[12%] left-1/2 -translate-x-1/2 w-[80%] max-w-3xl flex flex-col items-center gap-8 pointer-events-none z-10 transition-opacity duration-500"
                    :class="lineProgress > 0.05 ? 'opacity-100' : 'opacity-0'"
                >
                    <!-- Animated line -->
                    <div class="w-full h-px overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-transparent via-brand-blue to-transparent transition-transform duration-700 ease-out origin-center"
                            :style="{ transform: `scaleX(${lineProgress})` }"
                        />
                    </div>

                    <!-- Text -->
                    <p
                        class="text-2xl sm:text-3xl lg:text-4xl font-black text-ghost-white text-center leading-tight tracking-tight drop-shadow-[0_4px_20px_rgba(0,0,0,0.6)] transition-all duration-600 ease-out"
                        :class="textVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    >
                        {{ t('video.tagline') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

