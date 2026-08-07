<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from "vue";
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();

const props = defineProps({
    certificaciones: {
        type: Array,
        default: () => [],
    },
});

// ── CERTIFICACIONES (respaldo si no hay registros en el gestor) ──
const sampleCertifications = [
    {
        id: "ul508a",
        es: {
            title: "UL 508A – Tableros de Control Industrial",
            desc: "Certificación que avala el diseño y fabricación de tableros de control industrial conforme a los estándares de Underwriters Laboratories. Garantiza seguridad eléctrica, cumplimiento normativo y calidad en cada panel que entregamos.",
        },
        en: {
            title: "UL 508A – Industrial Control Panels",
            desc: "Certification that validates the design and manufacturing of industrial control panels in compliance with Underwriters Laboratories standards. It guarantees electrical safety, regulatory compliance, and quality in every panel we deliver.",
        },
        images: [
            { src: "/images/brazo-robotico-1.webp", alt: "UL 508A Panel 1" },
            { src: "/images/brazo-robotico-2.webp", alt: "UL 508A Panel 2" },
            { src: "/images/line-1.png", alt: "UL 508A Panel 3" },
        ],
    },
    {
        id: "iso9001",
        es: {
            title: "ISO 9001 – Gestión de Calidad",
            desc: "Nuestro sistema de gestión de calidad cumple con los requisitos de la norma ISO 9001, asegurando procesos estandarizados, mejora continua y satisfacción del cliente en cada proyecto de automatización industrial.",
        },
        en: {
            title: "ISO 9001 – Quality Management",
            desc: "Our quality management system meets ISO 9001 requirements, ensuring standardized processes, continuous improvement, and customer satisfaction in every industrial automation project.",
        },
        images: [],
    },
    {
        id: "ignition",
        es: {
            title: "Ignition Certified Integrator",
            desc: "Somos integradores certificados por Inductive Automation para la plataforma Ignition SCADA. Esto nos permite ofrecer soluciones de visualización, alarmas, históricos y MES con el más alto nivel de competencia técnica.",
        },
        en: {
            title: "Ignition Certified Integrator",
            desc: "We are certified integrators by Inductive Automation for the Ignition SCADA platform. This allows us to deliver visualization, alarm, historian, and MES solutions with the highest level of technical competence.",
        },
        images: [
            { src: "/images/line-1.png", alt: "Ignition SCADA 1" },
            { src: "/images/brazo-robotico-1.webp", alt: "Ignition SCADA 2" },
        ],
    },
    {
        id: "iec61439",
        es: {
            title: "IEC 61439 – Conjuntos de Baja Tensión",
            desc: "Diseñamos y verificamos tableros conforme a la norma internacional IEC 61439, garantizando desempeño térmico, rigidez dieléctrica y selectividad en instalaciones eléctricas de baja tensión a nivel global.",
        },
        en: {
            title: "IEC 61439 – Low-Voltage Switchgear",
            desc: "We design and verify panels in accordance with the international IEC 61439 standard, guaranteeing thermal performance, dielectric strength, and selectivity in low-voltage electrical installations worldwide.",
        },
        images: [],
    },
    {
        id: "siemens",
        es: {
            title: "Siemens Solution Partner – Automation",
            desc: "Como socios certificados de Siemens en el ramo de automatización, tenemos acceso preferencial a tecnología, soporte técnico y capacitación continua en plataformas como TIA Portal, SIMATIC y SINAMICS.",
        },
        en: {
            title: "Siemens Solution Partner – Automation",
            desc: "As certified Siemens partners in automation, we have preferential access to technology, technical support, and continuous training on platforms such as TIA Portal, SIMATIC, and SINAMICS.",
        },
        images: [
            { src: "/images/brazo-robotico-2.webp", alt: "Siemens Automation 1" },
            { src: "/images/brazo-robotico-1.webp", alt: "Siemens Automation 2" },
            { src: "/images/line-1.png", alt: "Siemens Automation 3" },
            { src: "/images/brazo-robotico-2.webp", alt: "Siemens Automation 4" },
        ],
    },
    {
        id: "nema",
        es: {
            title: "NEMA – Estándares de Gabinetes Eléctricos",
            desc: "Nuestros gabinetes y envolventes cumplen con las clasificaciones NEMA para protección contra polvo, agua y ambientes corrosivos, asegurando durabilidad en las condiciones industriales más exigentes.",
        },
        en: {
            title: "NEMA – Electrical Enclosure Standards",
            desc: "Our cabinets and enclosures meet NEMA classifications for protection against dust, water, and corrosive environments, ensuring durability in the most demanding industrial conditions.",
        },
        images: [],
    },
];

// ── ESTADO ──
const sectionRef = ref(null);
const sectionVisible = ref(false);
const carouselRefs = ref({});
const carouselIndices = ref({});
const carouselCanScroll = ref({});
const carouselMaxScroll = ref({});

// ── Certificaciones desde el gestor (o respaldo) ──
const certifications = computed(() => {
    if (!props.certificaciones.length) return sampleCertifications;

    return props.certificaciones.map((c) => {
        const gallery = (c.gallery || []).map((g) => ({ src: g.url, alt: c.title }));
        const images = [
            ...(c.image_url ? [{ src: c.image_url, alt: c.title }] : []),
            ...gallery,
        ];
        return {
            id: c.key || c.id,
            es: { title: c.title, desc: c.description_es || "" },
            en: { title: c.title, desc: c.description_en || c.description_es || "" },
            images,
        };
    });
});

// Computed: certificaciones con datos según locale
const items = computed(() =>
    certifications.value.map((c) => ({
        ...c,
        title: locale.value === "en" ? c.en.title : c.es.title,
        desc: locale.value === "en" ? c.en.desc : c.es.desc,
    }))
);

// ── INTERSECTION OBSERVER ──
let sectionObserver = null;
onMounted(() => {
    sectionObserver = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                sectionVisible.value = true;
                sectionObserver?.disconnect();
            }
        },
        { threshold: 0.1 }
    );
    if (sectionRef.value) sectionObserver.observe(sectionRef.value);
});

onUnmounted(() => {
    sectionObserver?.disconnect();
});

// ── CAROUSEL ──
function initCarousel(id, el) {
    if (!el) return;
    carouselRefs.value[id] = el;
    carouselIndices.value[id] = carouselIndices.value[id] ?? 0;
    updateCarouselState(id);
}

function updateCarouselState(id) {
    const el = carouselRefs.value[id];
    if (!el) return;
    const maxScroll = el.scrollWidth - el.clientWidth;
    carouselMaxScroll.value[id] = maxScroll;
    carouselCanScroll.value[id] = maxScroll > 4;

    // Sync index from scroll position
    const itemWidth = el.children[0]?.offsetWidth || 0;
    if (itemWidth > 0) {
        carouselIndices.value[id] = Math.round(el.scrollLeft / itemWidth);
    }
}

function scrollCarousel(id, direction) {
    const el = carouselRefs.value[id];
    if (!el) return;
    const itemWidth = el.children[0]?.offsetWidth || 300;
    const gap = 16;
    const scrollBy = (itemWidth + gap) * direction;
    el.scrollBy({ left: scrollBy, behavior: "smooth" });
}

function goToSlide(id, index) {
    const el = carouselRefs.value[id];
    if (!el) return;
    const itemWidth = el.children[0]?.offsetWidth || 300;
    const gap = 16;
    el.scrollTo({ left: index * (itemWidth + gap), behavior: "smooth" });
    carouselIndices.value[id] = index;
}

function onCarouselScroll(id) {
    updateCarouselState(id);
}

// Recalcular al redimensionar
let resizeTimer = null;
function onResize() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        Object.keys(carouselRefs.value).forEach(updateCarouselState);
    }, 200);
}

onMounted(() => {
    window.addEventListener("resize", onResize, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener("resize", onResize);
});
</script>

<template>
    <section ref="sectionRef" class="relative py-24 sm:py-32 lg:py-36 px-4 sm:px-6 lg:px-8 bg-white overflow-hidden">
        <div class="relative max-w-6xl mx-auto">
            <!-- ── SECTION HEADER ── -->
            <div
                class="text-center mb-16 lg:mb-20 transition-all duration-1000 ease-out"
                :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
            >
                <span
                    class="inline-block text-[10px] sm:text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.04]"
                >
                    {{ t("certifications.badge") }}
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-deep-onyx tracking-tight leading-[1.15]">
                    {{ t("certifications.title") }}
                    <span class="text-brand-blue">{{ t("certifications.titleHighlight") }}</span>
                </h2>
                <p class="mt-4 text-steel-grey text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                    {{ t("certifications.subtitle") }}
                </p>
            </div>

            <!-- ── CERTIFICATION CARDS ── -->
            <div class="space-y-6 sm:space-y-8">
                <div
                    v-for="(cert, i) in items"
                    :key="cert.id"
                    class="group relative"
                    :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
                    :style="{
                        transition: 'all 0.8s cubic-bezier(0.22, 1, 0.36, 1) ' + (0.15 + i * 0.1) + 's',
                    }"
                >
                    <!-- Card -->
                    <div
                        class="relative rounded-2xl sm:rounded-3xl border border-slate-200/60 bg-white
                            shadow-[0_2px_12px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]
                            transition-all duration-500 overflow-hidden"
                    >
                        <!-- Grid: alternating layout on desktop -->
                        <div
                            class="flex flex-col"
                            :class="i % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'"
                        >
                            <!-- Text content -->
                            <div class="flex-1 p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
                                <!-- Number badge -->
                                <div class="flex items-center gap-3 mb-4">
                                    <span
                                        class="flex items-center justify-center size-8 sm:size-9 rounded-xl bg-brand-blue/8 text-brand-blue text-xs font-black tabular-nums"
                                    >
                                        {{ String(i + 1).padStart(2, "0") }}
                                    </span>
                                    <div class="h-px flex-1 bg-gradient-to-r from-brand-blue/20 to-transparent" />
                                </div>

                                <h3 class="text-xl sm:text-2xl font-black text-deep-onyx tracking-tight leading-tight group-hover:text-brand-blue transition-colors duration-300">
                                    {{ cert.title }}
                                </h3>
                                <p class="mt-3 text-sm sm:text-base text-steel-grey leading-relaxed max-w-prose">
                                    {{ cert.desc }}
                                </p>
                            </div>

                            <!-- Image gallery (if images exist) -->
                            <div
                                v-if="cert.images && cert.images.length > 0"
                                class="flex-1 relative bg-slate-50/50 border-t lg:border-t-0 lg:border-l border-slate-100"
                                :class="i % 2 === 0 ? 'lg:border-l' : 'lg:border-r'"
                            >
                                <!-- Carousel container -->
                                <div class="relative p-4 sm:p-6 lg:p-8 h-full">
                                    <!-- Track -->
                                    <div
                                        :ref="(el) => initCarousel(cert.id, el)"
                                        @scroll="onCarouselScroll(cert.id)"
                                        class="carousel-track flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth
                                            scrollbar-none pb-2"
                                    >
                                        <div
                                            v-for="(img, j) in cert.images"
                                            :key="j"
                                            class="carousel-slide shrink-0 snap-center w-[75%] sm:w-[65%] lg:w-[55%]"
                                        >
                                            <div class="relative aspect-[4/3] rounded-xl sm:rounded-2xl overflow-hidden shadow-sm border border-slate-200/50">
                                                <img
                                                    :src="img.src"
                                                    :alt="img.alt"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                                />
                                                <div class="absolute inset-0 rounded-xl sm:rounded-2xl ring-1 ring-inset ring-black/5 pointer-events-none" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Controls (only if scrollable) -->
                                    <div
                                        v-if="carouselCanScroll[cert.id]"
                                        class="flex items-center justify-between mt-3 px-1"
                                    >
                                        <!-- Prev -->
                                        <button
                                            @click="scrollCarousel(cert.id, -1)"
                                            class="size-9 sm:size-10 rounded-full bg-white border border-slate-200 hover:border-brand-blue/40 hover:bg-brand-blue/5
                                                flex items-center justify-center text-steel-grey hover:text-brand-blue
                                                transition-all duration-200 hover:scale-105 active:scale-95 shadow-sm"
                                        >
                                            <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>

                                        <!-- Dots -->
                                        <div class="flex items-center gap-2">
                                            <button
                                                v-for="(img, j) in cert.images"
                                                :key="'dot-' + j"
                                                @click="goToSlide(cert.id, j)"
                                                class="transition-all duration-300 rounded-full"
                                                :class="(carouselIndices[cert.id] || 0) === j
                                                    ? 'size-2.5 bg-brand-blue shadow-[0_0_6px_rgba(0,130,168,0.4)]'
                                                    : 'size-2 bg-slate-300 hover:bg-slate-400'"
                                            />
                                        </div>

                                        <!-- Next -->
                                        <button
                                            @click="scrollCarousel(cert.id, 1)"
                                            class="size-9 sm:size-10 rounded-full bg-white border border-slate-200 hover:border-brand-blue/40 hover:bg-brand-blue/5
                                                flex items-center justify-center text-steel-grey hover:text-brand-blue
                                                transition-all duration-200 hover:scale-105 active:scale-95 shadow-sm"
                                        >
                                            <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Image counter -->
                                    <div class="absolute top-6 sm:top-8 right-6 sm:right-8 z-10">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm border border-slate-200/60 text-[10px] sm:text-xs font-semibold text-steel-grey shadow-sm">
                                            <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <rect x="3" y="3" width="18" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21" />
                                            </svg>
                                            {{ (carouselIndices[cert.id] || 0) + 1 }} / {{ cert.images.length }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── FOOTER TEXT ── -->
            <div
                class="mt-16 lg:mt-20 text-center transition-all duration-1000 ease-out"
                :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                style="transition-delay: 0.9s;"
            >
                <p class="text-steel-grey/80 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                    {{ t("certifications.footer") }}
                </p>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Hide scrollbar on carousel track */
.scrollbar-none {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-none::-webkit-scrollbar {
    display: none;
}

/* Subtle fade edges on carousel */
.carousel-track {
    -webkit-mask-image: linear-gradient(
        to right,
        transparent 0%,
        black 6%,
        black 94%,
        transparent 100%
    );
    mask-image: linear-gradient(
        to right,
        transparent 0%,
        black 6%,
        black 94%,
        transparent 100%
    );
}
</style>
