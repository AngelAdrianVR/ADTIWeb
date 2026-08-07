<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    servicios: {
        type: Array,
        default: () => [],
    },
});

const { t, locale } = useI18n();

// ── Service categories / tabs (from DB) ──
const tabs = computed(() =>
    (props.servicios || []).map(s => ({
        key: s.key,
        labelEs: s.title_es,
        labelEn: s.title_en,
    }))
);

const activeTab = ref(props.servicios?.[0]?.key ?? '');

// ── Service data — bilingual (from DB) ──
const servicesData = computed(() => {
    const data = {};
    (props.servicios || []).forEach(s => {
        data[s.key] = {
            es: {
                title: s.title_es,
                description: s.description_es,
                features: s.features_es || [],
            },
            en: {
                title: s.title_en,
                description: s.description_en,
                features: s.features_en || [],
            },
            image: s.image_url,
            techs: s.techs || [],
            gallery: s.gallery || [],
        };
    });
    return data;
});

const active = computed(() => servicesData.value[activeTab.value] || null);
const activeData = computed(() => {
    if (!active.value) return { title: '', description: '', features: [] };
    return locale.value === 'en' ? active.value.en : active.value.es;
});

// Entrance animation trigger
const mounted = ref(false);

// Tabs scroll navigation
const tabsScrollRef = ref(null);
const canScrollLeft = ref(false);
const canScrollRight = ref(false);

function checkTabsScroll() {
    const el = tabsScrollRef.value;
    if (!el) return;
    canScrollLeft.value = el.scrollLeft > 2;
    canScrollRight.value = el.scrollLeft + el.clientWidth < el.scrollWidth - 3;
}

function scrollTabs(direction) {
    const el = tabsScrollRef.value;
    if (!el) return;
    const amount = el.clientWidth * 0.55;
    el.scrollBy({ left: direction === 'left' ? -amount : amount, behavior: 'smooth' });
}

// Lightbox state
const lightboxIndex = ref(null);
const lightboxOpen = computed(() => lightboxIndex.value !== null);

function openLightbox(index) {
    lightboxIndex.value = index;
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    lightboxIndex.value = null;
    document.body.style.overflow = '';
}

// ── Gallery scroll reveal observer ──
let galleryObserver = null;
function observeGallery() {
    if (galleryObserver) galleryObserver.disconnect();
    const items = document.querySelectorAll('.gallery-item');
    if (!items.length) return;
    galleryObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('gallery-revealed');
                }, i * 120);
                galleryObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    items.forEach(el => galleryObserver.observe(el));
}

onMounted(() => {
    // Trigger entrance animations after mount
    requestAnimationFrame(() => {
        mounted.value = true;
    });

    nextTick(() => {
        // Check tabs scroll state
        checkTabsScroll();
        window.addEventListener('resize', checkTabsScroll);

        // Observe gallery items
        observeGallery();
    });
});

// Re-observe gallery when switching tabs
watch(activeTab, () => {
    nextTick(observeGallery);
});
</script>

<template>
    <Head :title="t('pages.services.title')" />

    <div class="min-h-screen bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- ═══════ Header ═══════ -->
            <div
                class="mb-10 transition-all duration-700 ease-out"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
            >
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.04]">
                    {{ locale === 'en' ? 'Our Services' : 'Nuestros Servicios' }}
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-deep-onyx tracking-tight leading-[1.1] max-w-2xl">
                    {{ locale === 'en' ? 'Industrial Automation' : 'Automatización Industrial' }}
                    <span class="text-brand-blue">{{ locale === 'en' ? 'Solutions' : 'a tu Medida' }}</span>
                </h1>
            </div>
            <!-- Empty state -->
            <div v-if="!tabs.length" class="text-center py-24">
                <p class="text-steel-grey text-lg">{{ locale === 'en' ? 'No services available yet.' : 'No hay servicios disponibles por el momento.' }}</p>
            </div>

            <template v-else>
            <!-- ═══════ Tabs (horizontal scroll with nav arrows) ═══════ -->
            <div
                class="relative mb-16 border-b border-gray-100 pb-px transition-all duration-700 ease-out delay-150"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'"
            >
                <!-- Left arrow -->
                <Transition name="fade">
                    <button
                        v-if="canScrollLeft"
                        @click="scrollTabs('left')"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 size-8 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center text-steel-grey hover:text-brand-blue hover:border-brand-blue/30 transition-all duration-200"
                    >
                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                </Transition>

                <!-- Scrollable tabs -->
                <div
                    ref="tabsScrollRef"
                    class="tabs-scroll flex flex-nowrap overflow-x-auto gap-1"
                    @scroll="checkTabsScroll"
                >
                    <button
                        v-for="tab in tabs"
                        :key="tab.key"
                        @click="activeTab = tab.key"
                        class="relative shrink-0 px-5 sm:px-6 py-4 text-sm font-semibold transition-colors duration-200"
                        :class="activeTab === tab.key
                            ? 'text-deep-onyx'
                            : 'text-steel-grey/50 hover:text-steel-grey'"
                    >
                        {{ locale === 'en' ? tab.labelEn : tab.labelEs }}
                        <span
                            v-if="activeTab === tab.key"
                            class="absolute bottom-0 left-5 sm:left-6 right-5 sm:right-6 h-0.5 bg-brand-blue rounded-full"
                        />
                    </button>
                </div>

                <!-- Right arrow + gradient fade -->
                <Transition name="fade">
                    <div v-if="canScrollRight" class="absolute right-0 top-0 bottom-0 flex items-center">
                        <div class="h-full w-16 bg-gradient-to-l from-white to-transparent pointer-events-none" />
                        <button
                            @click="scrollTabs('right')"
                            class="size-8 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center text-steel-grey hover:text-brand-blue hover:border-brand-blue/30 transition-all duration-200 shrink-0"
                        >
                            <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </Transition>
            </div>

            <!-- ═══════ Main Content — Split Layout ═══════ -->
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 mb-20">
                <!-- Text content with fade transition (second on mobile, first on desktop) -->
                <div
                    class="order-2 lg:order-1 transition-all duration-700 ease-out delay-300"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
                >
                    <Transition name="fade" mode="out-in">
                        <div :key="activeTab" class="space-y-8">
                            <h2 class="text-3xl sm:text-4xl font-black text-deep-onyx tracking-tight leading-tight">
                                {{ activeData.title }}
                            </h2>
                            <p class="text-steel-grey leading-relaxed text-lg">
                                {{ activeData.description }}
                            </p>

                            <!-- Features list -->
                            <ul class="space-y-3 pt-4">
                                <li
                                    v-for="(feat, i) in activeData.features"
                                    :key="i"
                                    class="flex items-start gap-3 text-sm text-steel-grey"
                                >
                                    <span class="shrink-0 mt-0.5 size-5 rounded-full bg-brand-blue/10 flex items-center justify-center">
                                        <svg class="size-2.5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </span>
                                    {{ feat }}
                                </li>
                            </ul>
                        </div>
                    </Transition>
                </div>

                <!-- Main image with diagonal-fade transition (first on mobile, second on desktop) -->
                <div
                    class="order-1 lg:order-2 self-start transition-all duration-700 ease-out delay-400"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
                >
                    <Transition name="diagonal-fade" mode="out-in">
                        <div
                            :key="activeTab"
                            class="relative w-full aspect-[4/3] lg:aspect-[3/4] overflow-hidden"
                        >
                            <img
                                v-if="active.image"
                                :src="active.image"
                                :alt="activeData.title"
                                class="absolute inset-0 lg:-mt-28 w-full h-full object-contain"
                                draggable="false"
                            />
                            <div v-else class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-deep-onyx/5 to-brand-blue/10 rounded-3xl">
                                <svg class="size-16 text-steel-grey/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                                </svg>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>

            <!-- ═══════ Tech Tags ═══════ -->
            <div
                class="mb-20 transition-all duration-700 ease-out delay-500"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'"
            >
                <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/40 font-semibold mb-4">
                    {{ locale === 'en' ? 'Technologies' : 'Tecnologías' }}
                </p>
                <div v-if="active.techs?.length" class="flex flex-wrap gap-2.5">
                    <span
                        v-for="tech in active.techs"
                        :key="tech"
                        class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-gray-50 text-steel-grey border border-gray-100 hover:border-brand-blue/30 hover:text-brand-blue transition-all duration-200"
                    >
                        {{ tech }}
                    </span>
                </div>
                <p v-else class="text-sm text-steel-grey/50 italic">{{ locale === 'en' ? 'No technologies listed.' : 'Sin tecnologías registradas.' }}</p>
            </div>

            <!-- ═══════ Image Gallery ═══════ -->
            <div
                v-if="active.gallery?.length"
                class="mb-20 transition-all duration-700 ease-out delay-[600ms]"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'"
            >
                <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/40 font-semibold mb-6">
                    {{ locale === 'en' ? 'Project Gallery' : 'Galería de Proyectos' }}
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div
                        v-for="(img, i) in active.gallery"
                        :key="img.id"
                        @click="openLightbox(i)"
                        class="gallery-item bg-gray-50 border border-gray-100 rounded-2xl overflow-hidden hover:border-brand-blue/20 transition-all duration-500 ease-out cursor-pointer group aspect-square"
                    >
                        <img
                            :src="img.url"
                            :alt="activeData.title + ' - ' + (i + 1)"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>

            <!-- ═══════ CTA ═══════ -->
            <div
                class="text-center py-12 px-8 rounded-[2.5rem] bg-gradient-to-br from-deep-onyx to-[#0d2137] transition-all duration-700 ease-out delay-700"
                :class="mounted ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'"
            >
                <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-3">
                    {{ locale === 'en' ? 'Ready to automate?' : '¿Listo para automatizar?' }}
                </h3>
                <p class="text-steel-grey/50 mb-8 max-w-md mx-auto">
                    {{ locale === 'en' ? 'Tell us about your project and we\'ll design the perfect solution for your operation.' : 'Cuéntanos sobre tu proyecto y diseñaremos la solución perfecta para tu operación.' }}
                </p>
                <Link
                    :href="route('contacto')"
                    class="inline-flex items-center gap-3 px-10 py-4 rounded-full bg-brand-blue hover:bg-brand-blue/90 text-white font-bold text-base
                        hover:shadow-[0_8px_30px_rgba(0,130,168,0.4)] hover:-translate-y-0.5
                        transition-all duration-300 ease-out group"
                >
                    {{ locale === 'en' ? 'Request a Quote' : 'Cotizar Servicio' }}
                    <svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
            </div>

            <!-- ═══════ Projects Button ═══════ -->
            <div class="mt-12 text-center">
                <Link
                    :href="route('proyectos')"
                    class="inline-flex items-center gap-3 px-10 py-4 rounded-full border-2 border-deep-onyx/10 hover:border-brand-blue/40 text-deep-onyx font-bold text-base
                        hover:bg-brand-blue/[0.04] hover:-translate-y-0.5
                        transition-all duration-300 ease-out group"
                >
                    {{ locale === 'en' ? 'View Our Projects' : 'Ver nuestros proyectos' }}
                    <svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
            </div>

            </template>
        </div>
    </div>

    <!-- ═══════ Lightbox Modal ═══════ -->
    <Teleport to="body">
        <Transition name="lightbox-fade">
            <div
                v-if="lightboxOpen"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 sm:p-8"
                @click.self="closeLightbox"
            >
                <button
                    @click="closeLightbox"
                    class="absolute top-6 right-6 size-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors duration-200 z-10"
                >
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="max-w-5xl max-h-[85vh] w-full rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 flex items-center justify-center">
                    <img
                        v-if="active?.gallery?.[lightboxIndex]"
                        :src="active.gallery[lightboxIndex].url"
                        :alt="activeData.title"
                        class="w-full h-full max-h-[85vh] object-contain"
                    />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
/* ── Horizontal scroll tabs ── */
.tabs-scroll {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.tabs-scroll::-webkit-scrollbar {
    display: none;
}

/* ── Fade transition (text) ── */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.35s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* ── Diagonal-fade transition (image) ── */
.diagonal-fade-enter-active {
    transition: all 0.45s cubic-bezier(0.22, 1, 0.36, 1);
}
.diagonal-fade-leave-active {
    transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}
.diagonal-fade-enter-from {
    opacity: 0;
    transform: translate(-24px, 24px);
}
.diagonal-fade-leave-to {
    opacity: 0;
    transform: translate(24px, -24px);
}

/* ── Gallery scroll reveal (creative stagger) ── */
.gallery-item {
    opacity: 0;
    transform: rotate(-1.5deg) scale(0.88) translateY(30px);
    transition: all 0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.gallery-item:nth-child(odd) {
    transform: rotate(1.8deg) scale(0.85) translateY(40px);
}
.gallery-item:nth-child(3n) {
    transform: rotate(-2.2deg) scale(0.82) translateY(50px);
}
.gallery-item:first-child {
    transform: rotate(0.8deg) scale(0.9) translateY(20px);
}
.gallery-item.gallery-revealed {
    opacity: 1;
    transform: rotate(0deg) scale(1) translateY(0);
}

/* ── Lightbox transition ── */
.lightbox-fade-enter-active {
    transition: opacity 0.3s ease;
}
.lightbox-fade-leave-active {
    transition: opacity 0.25s ease;
}
.lightbox-fade-enter-from,
.lightbox-fade-leave-to {
    opacity: 0;
}
</style>
