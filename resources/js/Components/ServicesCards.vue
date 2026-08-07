<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const props = defineProps({
    servicios: {
        type: Array,
        default: () => [],
    },
});

const { locale } = useI18n();

// Gradient cycle for card image backgrounds
const imageGradients = [
    'from-[#0082A8] via-[#007090] to-[#00D4FF]',
    'from-[#6D6E71] via-[#4a4b4d] to-[#0F172A]',
    'from-[#0082A8] via-[#005f7a] to-[#0F172A]',
    'from-[#0F172A] via-[#1e3a5f] to-[#0082A8]',
    'from-[#0082A8] via-[#0d2137] to-[#00D4FF]',
    'from-[#6D6E71] via-[#0F172A] to-[#0082A8]',
];

// Services (from DB)
const services = computed(() =>
    (props.servicios || []).map((s, i) => ({
        id: s.id,
        es: {
            title: s.title_es,
            subtitle: s.features_es?.[0] || '',
            description: s.description_es,
            features: s.features_es || [],
        },
        en: {
            title: s.title_en,
            subtitle: s.features_en?.[0] || '',
            description: s.description_en,
            features: s.features_en || [],
        },
        image: s.image_url,
        imageBg: imageGradients[i % imageGradients.length],
        techs: s.techs || [],
        gallery: s.gallery || [],
    }))
);

const activeService = ref(null);
const drawerOpen = ref(false);
const revealedCards = ref(new Set());
const sectionVisible = ref(false);
const cardRefs = ref([]);
let observer = null;

const currentData = (service) =>
    locale.value === 'en' ? service.en : service.es;

function setCardRef(el, index) {
    if (el) cardRefs.value[index] = el;
}

function openDrawer(service) {
    activeService.value = service;
    drawerOpen.value = true;
    document.body.style.overflow = 'hidden';
}

function closeDrawer() {
    drawerOpen.value = false;
    document.body.style.overflow = '';
}

function onBackdropClick() {
    closeDrawer();
}

// Lock scroll when drawer open
watch(drawerOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
});

// Keyboard escape
function onKeydown(e) {
    if (e.key === 'Escape' && drawerOpen.value) closeDrawer();
}

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const index = Number(entry.target.dataset.index);
                    const next = new Set(revealedCards.value);
                    next.add(index);
                    revealedCards.value = next;
                    sectionVisible.value = true;
                }
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
    );

    cardRefs.value.forEach((el) => { if (el) observer.observe(el); });
    window.addEventListener('keydown', onKeydown);
});

onUnmounted(() => {
    if (observer) observer.disconnect();
    window.removeEventListener('keydown', onKeydown);
    document.body.style.overflow = '';
});
</script>

<template>
    <section class="py-24 sm:py-32 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- ── Section Header ── -->
        <div class="text-center mb-20">
            <span
                class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.04]"
            >
                {{ locale === 'en' ? 'What We Do' : 'Lo Que Hacemos' }}
            </span>
            <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-deep-onyx tracking-tight leading-[1.1]">
                {{ locale === 'en' ? 'Our' : 'Nuestros' }}
                <span class="text-brand-blue">{{ locale === 'en' ? 'Services' : 'Servicios' }}</span>
            </h2>
            <p class="mt-4 text-steel-grey text-lg max-w-xl mx-auto">
                {{ locale === 'en' ? 'End-to-end industrial automation solutions tailored to your operation.' : 'Soluciones integrales de automatización industrial adaptadas a tu operación.' }}
            </p>
        </div>

        <!-- ── Empty state ── -->
        <div v-if="!services.length" class="text-center py-16">
            <p class="text-steel-grey text-lg">{{ locale === 'en' ? 'No services available yet.' : 'No hay servicios disponibles por el momento.' }}</p>
        </div>

        <!-- ── Cards Grid ── -->
        <div v-else class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <div
                v-for="(service, i) in services"
                :key="service.id"
                :ref="(el) => setCardRef(el, i)"
                :data-index="i"
                role="button"
                tabindex="0"
                class="group relative cursor-pointer rounded-[2rem] overflow-hidden"
                :class="revealedCards.has(i)
                    ? 'opacity-100 translate-y-0'
                    : 'opacity-0 translate-y-20'"
                :style="{ transition: `all 0.8s cubic-bezier(0.22, 1, 0.36, 1) ${i * 0.12}s` }"
                @click="openDrawer(service)"
                @keydown.enter="openDrawer(service)"
                @keydown.space.prevent="openDrawer(service)"
            >
                <!-- Card background & gradient -->
                <div class="relative bg-white rounded-[2rem] h-full flex flex-col overflow-hidden
                    shadow-[0_4px_30px_rgba(0,0,0,0.04)]
                    group-hover:shadow-[0_20px_50px_rgba(0,0,0,0.10)]
                    group-hover:-translate-y-1
                    transition-all duration-500 ease-out">

                    <!-- Image block -->
                    <div class="relative h-52 sm:h-56 overflow-hidden bg-gradient-to-br" :class="service.imageBg">
                        <img
                            v-if="service.image"
                            :src="service.image"
                            :alt="currentData(service).title"
                            class="w-full h-full object-cover mix-blend-overlay opacity-60
                                group-hover:scale-110 group-hover:opacity-80
                                transition-all duration-700 ease-out"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <svg class="size-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                            </svg>
                        </div>
                        <!-- Gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-white via-white/20 to-transparent" />
                    </div>

                    <!-- Content -->
                    <div class="p-7 sm:p-8 flex flex-col flex-1">

                    <!-- Title -->
                    <h3 class="text-xl sm:text-2xl font-bold text-deep-onyx tracking-tight group-hover:text-brand-blue transition-colors duration-300">
                        {{ currentData(service).title }}
                    </h3>

                    <!-- Subtitle -->
                    <p class="mt-2 text-sm text-steel-grey leading-relaxed line-clamp-2 flex-1">
                        {{ currentData(service).subtitle }}
                    </p>

                    <!-- Bottom arrow hint -->
                    <div class="mt-6 flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-steel-grey/40
                        group-hover:text-brand-blue group-hover:gap-3 transition-all duration-300">
                        <span>{{ locale === 'en' ? 'View details' : 'Ver detalles' }}</span>
                        <svg class="size-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- "See more" CTA -->
        <div
            class="mt-16 text-center transition-all duration-700 ease-out"
            :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        >
            <Link
                :href="route('servicios')"
                class="inline-flex items-center gap-3 px-8 py-4 rounded-full border-2 border-brand-blue text-brand-blue font-bold text-sm uppercase tracking-wider
                    hover:bg-brand-blue hover:text-white hover:shadow-[0_8px_30px_rgba(0,130,168,0.3)] hover:-translate-y-0.5
                    transition-all duration-300 ease-out group"
            >
                {{ locale === 'en' ? 'View All Services' : 'Ver Todos los Servicios' }}
                <svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </Link>
        </div>
    </section>

    <!-- ═══════════════════════════════════════ -->
    <!-- ── DRAWER OVERLAY ── -->
    <!-- ═══════════════════════════════════════ -->
    <Teleport to="body">
        <Transition name="drawer">
            <div
                v-if="drawerOpen"
                class="fixed inset-0 z-[100] flex justify-end"
                @click.self="onBackdropClick"
            >
                <!-- Backdrop -->
                <div class="drawer-backdrop absolute inset-0 bg-deep-onyx/50" />

                <!-- Drawer panel -->
                <div
                    v-if="activeService"
                    class="drawer-panel relative w-full max-w-lg lg:max-w-xl bg-white h-full overflow-y-auto shadow-2xl flex flex-col"
                    @click.stop
                >
                    <!-- Close button -->
                    <button
                        @click="closeDrawer"
                        class="absolute top-5 right-5 z-20 size-10 rounded-full bg-white/90 backdrop-blur border border-gray-200 flex items-center justify-center text-steel-grey hover:text-deep-onyx hover:border-gray-300 hover:scale-110 transition-all duration-200"
                    >
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Gradient header -->
                    <div class="relative px-8 pt-16 pb-10 bg-gradient-to-br overflow-hidden" :class="activeService.imageBg">
                        <!-- Background image -->
                        <img
                            v-if="activeService.image"
                            :src="activeService.image"
                            :alt="currentData(activeService).title"
                            class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
                        <div class="relative text-white">
                            <p class="text-xs uppercase tracking-[0.2em] text-white/60 font-semibold">
                                {{ locale === 'en' ? 'Service' : 'Servicio' }}
                            </p>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight mt-1">
                                {{ currentData(activeService).title }}
                            </h2>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 px-8 py-8 space-y-8">
                        <!-- Description -->
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/50 font-semibold mb-3">
                                {{ locale === 'en' ? 'Overview' : 'Descripción General' }}
                            </p>
                            <p class="text-steel-grey leading-relaxed text-base">
                                {{ currentData(activeService).description }}
                            </p>
                        </div>

                        <!-- Features -->
                        <div v-if="currentData(activeService).features?.length">
                            <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/50 font-semibold mb-3">
                                {{ locale === 'en' ? 'Key Features' : 'Características Principales' }}
                            </p>
                            <ul class="space-y-2.5">
                                <li
                                    v-for="(feat, i) in currentData(activeService).features"
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

                        <!-- Gallery -->
                        <div v-if="activeService.gallery?.length">
                            <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/50 font-semibold mb-4">
                                {{ locale === 'en' ? 'Gallery' : 'Galería' }}
                            </p>
                            <div class="grid grid-cols-3 gap-3">
                                <div
                                    v-for="img in activeService.gallery"
                                    :key="img.id"
                                    class="aspect-square rounded-xl overflow-hidden bg-gray-50 border border-gray-100"
                                >
                                    <img
                                        :src="img.url"
                                        :alt="currentData(activeService).title"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Tech tags -->
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/50 font-semibold mb-4">
                                {{ locale === 'en' ? 'Technologies & Standards' : 'Tecnologías & Estándares' }}
                            </p>
                            <div v-if="activeService.techs?.length" class="flex flex-wrap gap-2.5">
                                <span
                                    v-for="tech in activeService.techs"
                                    :key="tech"
                                    class="px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-[#0F172A] shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                >
                                    {{ tech }}
                                </span>
                            </div>
                            <p v-else class="text-sm text-steel-grey/50 italic">{{ locale === 'en' ? 'No technologies listed.' : 'Sin tecnologías registradas.' }}</p>
                        </div>
                    </div>

                    <!-- Footer CTA -->
                    <div class="sticky bottom-0 px-8 py-5 bg-white border-t border-gray-100">
                        <Link
                            :href="route('contacto')"
                            class="flex items-center justify-center gap-3 w-full bg-brand-blue hover:bg-brand-blue/90 text-white font-bold text-base py-4 rounded-2xl transition-all duration-300 hover:shadow-[0_8px_30px_rgba(0,130,168,0.35)] hover:-translate-y-0.5"
                        >
                            {{ locale === 'en' ? 'Request a Quote' : 'Solicitar Cotización' }}
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
/* ── Drawer transitions ── */
.drawer-backdrop {
    will-change: opacity;
}
.drawer-panel {
    will-change: transform;
}

/* Enter */
.drawer-enter-active {
    transition: opacity 0.3s ease-out;
}
.drawer-enter-active .drawer-backdrop {
    transition: opacity 0.3s ease-out;
}
.drawer-enter-active .drawer-panel {
    transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

.drawer-enter-from {
    opacity: 0;
}
.drawer-enter-from .drawer-backdrop {
    opacity: 0;
}
.drawer-enter-from .drawer-panel {
    transform: translateX(100%);
}

.drawer-enter-to .drawer-backdrop {
    opacity: 1;
}
.drawer-enter-to .drawer-panel {
    transform: translateX(0);
}

/* Leave */
.drawer-leave-active {
    transition: opacity 0.25s ease-in;
}
.drawer-leave-active .drawer-backdrop {
    transition: opacity 0.25s ease-in;
}
.drawer-leave-active .drawer-panel {
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.drawer-leave-from .drawer-backdrop {
    opacity: 1;
}
.drawer-leave-from .drawer-panel {
    transform: translateX(0);
}

.drawer-leave-to {
    opacity: 0;
}
.drawer-leave-to .drawer-backdrop {
    opacity: 0;
}
.drawer-leave-to .drawer-panel {
    transform: translateX(100%);
}
</style>
