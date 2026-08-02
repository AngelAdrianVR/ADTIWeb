<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingNavBubble from '@/Components/LandingNavBubble.vue';
import ScrollIndicator from '@/Components/ScrollIndicator.vue';
import FooterLanding from '@/Components/FooterLanding.vue';

const { t } = useI18n();

defineProps({
    title: String,
});

const logoReady = ref(false);
const navReady = ref(false);
const isNavigating = ref(false);

onMounted(() => {
    requestAnimationFrame(() => {
        logoReady.value = true;
        setTimeout(() => {
            navReady.value = true;
        }, 150);
    });

    router.on('start', (event) => {
        // Only show overlay for page navigations (GET), not form submissions
        if (event.detail.visit.method === 'get') {
            isNavigating.value = true;
        }
    });

    router.on('finish', () => {
        setTimeout(() => {
            isNavigating.value = false;
        }, 150);
    });
});

onUnmounted(() => {
    router.on('start', undefined);
    router.on('finish', undefined);
});
</script>

<template>
    <div class="min-h-screen flex flex-col text-deep-onyx">
        <!-- Scroll Progress Indicator -->
        <ScrollIndicator />

        <!-- Page Content -->
        <main class="flex-1 relative">
            <!-- Header (inside main, overlays the page content) -->
            <section class="sticky top-0 left-0 w-full z-40 bg-transparent">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center h-16 justify-between">
                        <Link
                            :href="route('inicio')"
                            class="flex items-center gap-2"
                            :class="logoReady ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-16'"
                            style="transition: all 0.7s cubic-bezier(0.34, 1.56, 0.64, 1)"
                        >
                            <img draggable="false" class="h-12 md:h-16" src="@/../../public/images/logo_3d.png" alt="logo">
                        </Link>


                        <div
                            :class="navReady ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-16'"
                            style="transition: all 0.7s cubic-bezier(0.34, 1.56, 0.64, 1)"
                        >
                            <LandingNavBubble />
                        </div>
                    </div>
                </div>
            </section>

            <slot />
        </main>

        <!-- Footer -->
        <FooterLanding />

        <!-- Page Transition Overlay -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-400 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isNavigating"
                class="fixed inset-0 z-[200] bg-white pointer-events-none"
            />
        </Transition>
    </div>
</template>
