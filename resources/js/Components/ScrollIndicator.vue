<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const DOT_COUNT = 18;
const progress = ref(0);
const isVisible = ref(false);
const isDragging = ref(false);

const trackRef = ref(null);
let ticking = false;

function updateProgress() {
    if (!ticking) {
        requestAnimationFrame(() => {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            progress.value = docHeight > 0 ? Math.min(1, Math.max(0, scrollTop / docHeight)) : 0;
            ticking = false;
        });
        ticking = true;
    }
}

function scrollToProgress(value) {
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    window.scrollTo({ top: value * docHeight, behavior: 'instant' });
}

function onTrackClick(e) {
    if (!trackRef.value) return;
    const rect = trackRef.value.getBoundingClientRect();
    const y = e.clientY - rect.top;
    const pct = Math.min(1, Math.max(0, y / rect.height));
    scrollToProgress(pct);
}

function onDragStart(e) {
    isDragging.value = true;
    document.body.style.userSelect = 'none';
    document.body.style.cursor = 'grabbing';
    onDragMove(e);
}

function onDragMove(e) {
    if (!isDragging.value || !trackRef.value) return;
    const rect = trackRef.value.getBoundingClientRect();
    const y = e.clientY - rect.top;
    const pct = Math.min(1, Math.max(0, y / rect.height));
    progress.value = pct;
    scrollToProgress(pct);
}

function onDragEnd() {
    isDragging.value = false;
    document.body.style.userSelect = '';
    document.body.style.cursor = '';
}

onMounted(() => {
    setTimeout(() => { isVisible.value = true; }, 1200);
    window.addEventListener('scroll', updateProgress, { passive: true });
    window.addEventListener('mousemove', onDragMove);
    window.addEventListener('mouseup', onDragEnd);
    updateProgress();
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateProgress);
    window.removeEventListener('mousemove', onDragMove);
    window.removeEventListener('mouseup', onDragEnd);
    document.body.style.userSelect = '';
    document.body.style.cursor = '';
});
</script>

<template>
    <div
        class="fixed right-4 sm:right-6 top-0 h-full z-50 flex items-center mt-8"
        :class="isVisible ? 'opacity-100' : 'opacity-0'"
        style="transition: opacity 1s ease-out 0.8s;"
    >
        <div
            ref="trackRef"
            class="relative flex flex-col items-center cursor-pointer py-4"
            style="height: 90vh; margin: 5vh 0;"
            @click="onTrackClick"
        >
            <!-- Dots -->
            <span
                v-for="i in DOT_COUNT"
                :key="i"
                class="relative size-[.275rem] rounded-full transition-all duration-500 ease-out pointer-events-none"
                :class="(i - 1) / DOT_COUNT <= progress
                    ? 'bg-brand-blue shadow-[0_0_6px_rgba(0,130,168,0.6)]'
                    : 'bg-gray-300/20'"
                :style="{ flex: '1 0 auto' }"
            />

            <!-- Glowing circle indicator (draggable) -->
            <div
                class="absolute left-1/2 -translate-x-1/2 size-6 rounded-full z-10 cursor-grab active:cursor-grabbing"
                :class="isDragging ? 'scale-125' : ''"
                :style="{
                    top: `${progress * 100}%`,
                    transform: `translate(-50%, -50%) ${isDragging ? 'scale(1.25)' : 'scale(1)'}`,
                    transition: isDragging ? 'none' : 'top 0.15s linear, transform 0.2s ease',
                }"
                @mousedown.prevent="onDragStart"
            >
                <!-- Outer glow ring -->
                <div class="absolute inset-0 rounded-full bg-brand-blue/30 animate-ping-slow" />
                <!-- Inner ring -->
                <div class="absolute -inset-0.5 rounded-full border border-brand-blue/80" />
                <!-- Center dot -->
                <div class="absolute inset-[2px] rounded-full bg-brand-blue shadow-[0_0_10px_rgba(0,212,255,0.8)]" />
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes pingSlow {
    0% {
        transform: scale(1);
        opacity: 0.4;
    }
    100% {
        transform: scale(2.5);
        opacity: 0;
    }
}

.animate-ping-slow {
    animation: pingSlow 2.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
