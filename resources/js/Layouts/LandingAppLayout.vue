<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingNavBubble from '@/Components/LandingNavBubble.vue';
import ScrollIndicator from '@/Components/ScrollIndicator.vue';

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

    router.on('start', () => {
        isNavigating.value = true;
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
                    <div class="flex items-center h-20 justify-between">
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
        <footer class="bg-ghost-white border-t border-steel-grey/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <span class="text-xl font-bold tracking-tight text-deep-onyx">
                            ADTI<span class="text-brand-blue">.</span>
                        </span>
                        <p class="mt-2 text-sm text-steel-grey leading-relaxed">
                            {{ t('footer.tagline') }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-widest text-steel-grey mb-4">{{ t('footer.navigation') }}</h4>
                        <ul class="space-y-2">
                            <li>
                                <Link
                                    :href="route('servicios')"
                                    class="text-sm text-steel-grey hover:text-brand-blue transition-colors duration-200"
                                >
                                    {{ t('nav.services') }}
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('proyectos')"
                                    class="text-sm text-steel-grey hover:text-brand-blue transition-colors duration-200"
                                >
                                    {{ t('nav.projects') }}
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('nosotros')"
                                    class="text-sm text-steel-grey hover:text-brand-blue transition-colors duration-200"
                                >
                                    {{ t('nav.about') }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-widest text-steel-grey mb-4">{{ t('footer.contact') }}</h4>
                        <ul class="space-y-2 text-sm text-steel-grey">
                            <li>
                                <a href="mailto:info@adti.com" class="hover:text-brand-blue transition-colors duration-200">
                                    info@adti.com
                                </a>
                            </li>
                            <li>
                                <Link
                                    :href="route('terms.show')"
                                    class="hover:text-brand-blue transition-colors duration-200"
                                >
                                    {{ t('footer.terms') }}
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('policy.show')"
                                    class="hover:text-brand-blue transition-colors duration-200"
                                >
                                    {{ t('footer.privacy') }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-steel-grey/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-steel-grey">
                        &copy; {{ new Date().getFullYear() }} ADTI. {{ t('footer.rights') }}
                    </p>
                    <p class="text-xs text-steel-grey/50">
                        {{ t('footer.taglineShort') }}
                    </p>
                </div>
            </div>
        </footer>

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
