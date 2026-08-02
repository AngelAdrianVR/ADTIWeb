<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';

defineOptions({ layout: LandingAppLayout });

const { t, locale } = useI18n();

// ── PROJECTS DATA ──
const projects = [
    {
        id: 'linea-ensamble',
        es: {
            title: 'Línea de Ensamble Automatizada',
            category: 'Automatización Industrial',
            desc: 'Diseño, fabricación y puesta en marcha de una línea de ensamble completamente automatizada para una empresa líder en manufactura electrónica. El sistema integra 4 celdas robóticas colaborativas, 120 metros de bandas transportadoras inteligentes con sistema de acumulación y desvío, visión artificial Cognex para control de calidad en tiempo real, y un sistema SCADA Ignition centralizado con trazabilidad completa desde materia prima hasta producto terminado. Se logró un incremento del 180% en throughput y una reducción del 65% en defectos de ensamble.',
        },
        en: {
            title: 'Automated Assembly Line',
            category: 'Industrial Automation',
            desc: 'Design, manufacturing, and commissioning of a fully automated assembly line for a leading electronics manufacturing company. The system integrates 4 collaborative robotic cells, 120 meters of smart conveyor belts with accumulation and diversion, Cognex machine vision for real-time quality control, and a centralized Ignition SCADA system with complete traceability from raw material to finished product. Achieved a 180% increase in throughput and a 65% reduction in assembly defects.',
        },
        color: 'from-[#0082A8] via-[#0b2740] to-[#0F172A]',
        accent: '#0082A8',
        images: [
            { icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
            { icon: 'M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5' },
            { icon: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4' },
            { icon: 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z' },
        ],
        video: 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        techs: ['Ignition SCADA', 'FANUC CRX', 'Cognex In-Sight', 'Siemens S7-1500', 'Profinet'],
    },
    {
        id: 'scada-alimentos',
        es: {
            title: 'Sistema SCADA para Planta de Alimentos',
            category: 'SCADA & Control',
            desc: 'Implementación de un sistema SCADA integral para una planta procesadora de alimentos con 18 tanques de mezclado, 6 líneas de envasado y 3 túneles de congelación. El sistema incluye control de temperatura y humedad con precisión de ±0.5°C, registro histórico de variables críticas para cumplimiento FDA 21 CFR Part 11, sistema de alarmas predictivas con notificación SMS/email, y dashboards de producción en tiempo real accesibles desde tablets en toda la planta. La solución permitió reducir el tiempo de respuesta ante desviaciones en un 78%.',
        },
        en: {
            title: 'SCADA System for Food Processing Plant',
            category: 'SCADA & Control',
            desc: 'Implementation of a comprehensive SCADA system for a food processing plant with 18 mixing tanks, 6 packaging lines, and 3 freezing tunnels. The system includes temperature and humidity control with ±0.5°C precision, historical logging of critical variables for FDA 21 CFR Part 11 compliance, predictive alarm system with SMS/email notification, and real-time production dashboards accessible from tablets throughout the plant. The solution reduced deviation response time by 78%.',
        },
        color: 'from-[#0F172A] via-[#10344d] to-[#00D4FF]',
        accent: '#00D4FF',
        images: [
            { icon: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z' },
            { icon: 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z' },
            { icon: 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' },
        ],
        video: null,
        techs: ['Ignition SCADA', 'Allen-Bradley ControlLogix', 'EtherNet/IP', 'FDA 21 CFR Part 11', 'SQL Historian'],
    },
    {
        id: 'tableros-agua',
        es: {
            title: 'Tableros de Control para Planta Tratadora',
            category: 'Tableros de Control',
            desc: 'Fabricación de 12 tableros de control UL 508A para una planta tratadora de agua residual con capacidad de 500 L/s. Los tableros incluyen arrancadores suaves para bombas de 250 HP, variadores de frecuencia para aireación, sistema de respaldo con transferencia automática, y un sistema de monitoreo de calidad del agua con sensores de pH, turbidez, cloro residual y oxígeno disuelto. Todo el sistema fue sometido a pruebas FAT en nuestras instalaciones antes del envío, logrando un tiempo de puesta en marcha en campo récord de 5 días.',
        },
        en: {
            title: 'Control Panels for Water Treatment Plant',
            category: 'Control Panels',
            desc: 'Manufacturing of 12 UL 508A control panels for a 500 L/s wastewater treatment plant. The panels include soft starters for 250 HP pumps, variable frequency drives for aeration, automatic transfer switch backup system, and a water quality monitoring system with pH, turbidity, residual chlorine, and dissolved oxygen sensors. The entire system underwent FAT testing at our facilities before shipment, achieving a record field commissioning time of 5 days.',
        },
        color: 'from-[#6D6E71] via-[#2d3748] to-[#0F172A]',
        accent: '#6D6E71',
        images: [
            { icon: 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z' },
            { icon: 'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z' },
            { icon: 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' },
            { icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' },
        ],
        video: 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        techs: ['UL 508A', 'NEMA 4X', 'Allen-Bradley PowerFlex', 'Siemens SIRIUS', 'EPLAN Pro Panel'],
    },
];

// ── STATE ──
const mounted = ref(false);
const activeIndex = ref(0);
const galleryOpen = ref(null);
const videoPlaying = ref(false);

// ── COMPUTED ──
const currentProject = computed(() => projects[activeIndex.value]);

const currentGallery = computed(() => {
    if (!galleryOpen.value) return null;
    const { pIdx, iIdx } = galleryOpen.value;
    return { 
        project: projects[pIdx], 
        image: projects[pIdx].images[iIdx], 
        index: iIdx, 
        total: projects[pIdx].images.length 
    };
});

// ── NAVIGATION METHODS ──
function nextProject() {
    videoPlaying.value = false;
    activeIndex.value = (activeIndex.value + 1) % projects.length;
}

function prevProject() {
    videoPlaying.value = false;
    activeIndex.value = (activeIndex.value - 1 + projects.length) % projects.length;
}

function selectProject(index) {
    if (activeIndex.value === index) return;
    videoPlaying.value = false;
    activeIndex.value = index;
}

// ── STACKED CARD VISUAL CALCULATIONS ──
// Calcula el estilo de cada tarjeta en el mazo 3D en función de su distancia al activo
function getCardStackStyle(index) {
    const total = projects.length;
    let diff = (index - activeIndex.value + total) % total;
    
    // Si la tarjeta está detrás (ej. tarjeta anterior en un array corto), la colocamos al final
    if (diff > Math.floor(total / 2)) {
        diff -= total;
    }

    if (diff === 0) {
        return {
            transform: 'translateY(0px) scale(1)',
            zIndex: 30,
            opacity: 1,
            pointerEvents: 'auto'
        };
    } else if (diff === 1 || diff === -2) {
        return {
            transform: 'translateY(-35px) scale(0.92)',
            zIndex: 20,
            opacity: 0.65,
            pointerEvents: 'auto'
        };
    } else {
        return {
            transform: 'translateY(-65px) scale(0.84)',
            zIndex: 10,
            opacity: 0.35,
            pointerEvents: 'auto'
        };
    }
}

// ── GALLERY METHODS ──
function openGallery(pIdx, iIdx) {
    galleryOpen.value = { pIdx, iIdx };
    document.body.style.overflow = 'hidden';
}
function closeGallery() {
    galleryOpen.value = null;
    document.body.style.overflow = '';
}
function galleryNext() {
    if (!galleryOpen.value) return;
    const { pIdx, iIdx } = galleryOpen.value;
    const total = projects[pIdx].images.length;
    galleryOpen.value = { pIdx, iIdx: (iIdx + 1) % total };
}
function galleryPrev() {
    if (!galleryOpen.value) return;
    const { pIdx, iIdx } = galleryOpen.value;
    const total = projects[pIdx].images.length;
    galleryOpen.value = { pIdx, iIdx: (iIdx - 1 + total) % total };
}

function onKeyDown(e) {
    if (galleryOpen.value) {
        if (e.key === 'Escape') closeGallery();
        if (e.key === 'ArrowRight') galleryNext();
        if (e.key === 'ArrowLeft') galleryPrev();
    } else {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') nextProject();
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') prevProject();
    }
}

onMounted(() => {
    requestAnimationFrame(() => { mounted.value = true; });
    window.addEventListener('keydown', onKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', onKeyDown);
});
</script>

<template>
    <Head :title="t('pages.projects.title')" />

    <div class="min-h-screen bg-slate-50/50 text-deep-onyx py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- ═══════ Header ═══════ -->
            <div
                class="mb-16 transition-all duration-700 ease-out"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
            >
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.06]">
                    {{ locale === 'en' ? 'Featured Projects' : 'Proyectos Destacados' }}
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] max-w-3xl">
                    {{ locale === 'en' ? 'Solutions that ' : 'Soluciones que ' }}
                    <span class="text-brand-blue">{{ locale === 'en' ? 'speak for themselves' : 'hablan por sí mismas' }}</span>
                </h1>
            </div>

            <!-- ═══════ Interactive Showcase (2 Columns) ═══════ -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                
                <!-- ── Columna Izquierda: Stacked Cards Deck (5 cols) ── -->
                <div class="lg:col-span-5 flex flex-col items-center lg:items-stretch select-none sticky top-28">
                    
                    <!-- Contenedor del mazo 3D -->
                    <div class="relative w-full max-w-md mx-auto aspect-[4/4.2] sm:aspect-[4/3.8] pt-16">
                        
                        <div
                            v-for="(project, idx) in projects"
                            :key="project.id"
                            @click="selectProject(idx)"
                            :style="getCardStackStyle(idx)"
                            class="absolute inset-x-0 bottom-0 aspect-[4/3.4] rounded-3xl bg-gradient-to-br overflow-hidden shadow-2xl cursor-pointer transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)] border border-white/10 group"
                            :class="project.color"
                        >
                            <!-- Patrón decorativo y degradados -->
                            <div class="absolute inset-0 opacity-20"
                                style="background-image: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.4) 1px, transparent 1px); background-size: 20px 20px;"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent" />

                            <!-- Icono / Visual central del proyecto -->
                            <div class="absolute inset-0 flex items-center justify-center pb-12">
                                <div class="size-20 sm:size-24 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center ring-1 ring-white/20 shadow-2xl transition-transform duration-500 group-hover:scale-110">
                                    <svg class="size-9 sm:size-10 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="project.images[0].icon" />
                                    </svg>
                                </div>
                            </div>

                            <!-- TÍTULO SUPERPUESTO EN LA TARJETA -->
                            <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8 flex flex-col justify-end">
                                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-white/70 mb-1">
                                    {{ locale === 'en' ? project.en.category : project.es.category }}
                                </span>
                                <h3 class="text-xl sm:text-2xl font-black text-white leading-snug drop-shadow-md">
                                    {{ locale === 'en' ? project.en.title : project.es.title }}
                                </h3>
                            </div>
                        </div>

                    </div>

                    <!-- Botones Anterior y Siguiente con Contador -->
                    <div class="mt-8 flex items-center justify-between w-full max-w-md mx-auto px-2">
                        <div class="text-sm font-bold tracking-widest text-steel-grey">
                            <span class="text-brand-blue font-black text-lg">0{{ activeIndex + 1 }}</span>
                            <span class="opacity-40"> / 0{{ projects.length }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                @click="prevProject"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white hover:bg-slate-100 text-deep-onyx font-bold text-xs uppercase tracking-wider border border-slate-200 shadow-sm hover:shadow transition-all duration-200 active:scale-95"
                            >
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                                {{ locale === 'en' ? 'Prev' : 'Anterior' }}
                            </button>

                            <button
                                @click="nextProject"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-deep-onyx hover:bg-brand-blue text-white font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition-all duration-200 active:scale-95"
                            >
                                {{ locale === 'en' ? 'Next' : 'Siguiente' }}
                                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- ── Columna Derecha: Información del Proyecto con Fade Suave (7 cols) ── -->
                <div class="lg:col-span-7 min-h-[500px]">
                    <Transition name="fade-project" mode="out-in">
                        <div :key="currentProject.id" class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-100/80 space-y-8">
                            
                            <!-- Cabecera de Descripción -->
                            <div class="space-y-4">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <span
                                        class="inline-block text-xs uppercase tracking-[0.25em] font-extrabold px-3.5 py-1.5 rounded-full border"
                                        :style="{ color: currentProject.accent, borderColor: currentProject.accent + '40', backgroundColor: currentProject.accent + '10' }"
                                    >
                                        {{ locale === 'en' ? currentProject.en.category : currentProject.es.category }}
                                    </span>

                                    <!-- Botón para expandir al Lightbox -->
                                    <button
                                        @click="openGallery(activeIndex, 0)"
                                        class="text-xs font-semibold text-steel-grey hover:text-brand-blue flex items-center gap-1.5 transition-colors"
                                    >
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                                        </svg>
                                        {{ locale === 'en' ? 'Open Gallery' : 'Galería Completa' }}
                                    </button>
                                </div>

                                <h2 class="text-2xl sm:text-4xl font-black text-deep-onyx tracking-tight leading-tight">
                                    {{ locale === 'en' ? currentProject.en.title : currentProject.es.title }}
                                </h2>

                                <p class="text-steel-grey leading-relaxed text-base sm:text-lg">
                                    {{ locale === 'en' ? currentProject.en.desc : currentProject.es.desc }}
                                </p>
                            </div>

                            <!-- Muestra de Tecnologías -->
                            <div class="pt-2 border-t border-slate-100">
                                <p class="text-[11px] uppercase tracking-[0.2em] text-steel-grey/60 font-bold mb-3">
                                    {{ locale === 'en' ? 'Technologies Used' : 'Tecnologías Utilizadas' }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="tech in currentProject.techs"
                                        :key="tech"
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-slate-100/80 text-deep-onyx border border-slate-200/60"
                                    >
                                        {{ tech }}
                                    </span>
                                </div>
                            </div>

                            <!-- Galería Visual del Proyecto & Video -->
                            <div class="space-y-4 pt-2 border-t border-slate-100">
                                <div class="flex items-center justify-between">
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-steel-grey/60 font-bold">
                                        {{ locale === 'en' ? 'Project Preview' : 'Vistas del Proyecto' }}
                                    </p>

                                    <!-- Botón para ver video en el mismo panel -->
                                    <button
                                        v-if="currentProject.video"
                                        @click="videoPlaying = !videoPlaying"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 hover:text-red-700 transition-colors"
                                    >
                                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                        {{ videoPlaying ? (locale === 'en' ? 'Hide Video' : 'Ocultar Video') : (locale === 'en' ? 'Watch Video' : 'Ver Video') }}
                                    </button>
                                </div>

                                <!-- Miniaturas del proyecto activo -->
                                <div v-if="!videoPlaying" class="grid grid-cols-4 gap-3">
                                    <div
                                        v-for="(img, iIdx) in currentProject.images"
                                        :key="iIdx"
                                        @click="openGallery(activeIndex, iIdx)"
                                        class="group/thumb relative aspect-video rounded-xl bg-gradient-to-br overflow-hidden border border-slate-200 cursor-pointer transition-all duration-300 hover:scale-[1.03] hover:shadow-md"
                                        :class="currentProject.color"
                                    >
                                        <div class="absolute inset-0 bg-black/20 group-hover/thumb:bg-transparent transition-colors" />
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <svg class="size-6 text-white/80 group-hover/thumb:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" :d="img.icon" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reproductor de video condicional -->
                                <div
                                    v-else-if="currentProject.video && videoPlaying"
                                    class="aspect-video rounded-2xl overflow-hidden shadow-lg border border-slate-200 bg-black"
                                >
                                    <iframe
                                        :src="currentProject.video + '?autoplay=1&rel=0'"
                                        class="w-full h-full"
                                        frameborder="0"
                                        allow="autoplay; encrypted-media"
                                        allowfullscreen
                                    />
                                </div>
                            </div>

                        </div>
                    </Transition>
                </div>

            </div>

            <!-- ═══════ CTA ═══════ -->
            <div
                class="mt-28 text-center py-12 px-8 rounded-[2.5rem] bg-gradient-to-br from-deep-onyx to-[#0d2137] transition-all duration-700 ease-out"
                :class="mounted ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'"
            >
                <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight mb-3">
                    {{ locale === 'en' ? 'Have a project in mind?' : '¿Tienes un proyecto en mente?' }}
                </h3>
                <p class="text-steel-grey/50 mb-8 max-w-md mx-auto">
                    {{ locale === 'en' ? 'Let\'s build something extraordinary together. Tell us about your challenge and we\'ll design the perfect solution.' : 'Construyamos algo extraordinario juntos. Cuéntanos tu desafío y diseñaremos la solución perfecta.' }}
                </p>
                <Link
                    :href="route('contacto')"
                    class="inline-flex items-center gap-3 px-10 py-4 rounded-full bg-brand-blue hover:bg-brand-blue/90 text-white font-bold text-base hover:shadow-[0_8px_30px_rgba(0,130,168,0.4)] hover:-translate-y-0.5 transition-all duration-300 ease-out group"
                >
                    {{ locale === 'en' ? 'Start Your Project' : 'Cotizar Proyecto' }}
                    <svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
            </div>

        </div>
    </div>

    <!-- ═══════ Gallery Lightbox ═══════ -->
    <Teleport to="body">
        <Transition name="lightbox-fade">
            <div
                v-if="galleryOpen"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/92 backdrop-blur-sm p-4 sm:p-8"
                @click.self="closeGallery"
            >
                <button
                    @click="closeGallery"
                    class="absolute top-6 right-6 size-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors duration-200 z-10"
                >
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <button
                    @click="galleryPrev"
                    class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 size-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors duration-200 z-10"
                >
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <button
                    @click="galleryNext"
                    class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 size-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors duration-200 z-10"
                >
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <div
                    v-if="currentGallery"
                    class="max-w-4xl max-h-[80vh] w-full aspect-[4/3] rounded-2xl bg-gradient-to-br overflow-hidden shadow-2xl relative"
                    :class="currentGallery.project.color"
                >
                    <div class="absolute inset-0 opacity-15"
                        style="background-image: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.3) 1px, transparent 1px); background-size: 24px 24px;"
                    />
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="flex flex-col items-center gap-4 text-white/30">
                            <svg class="size-24 sm:size-32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.8">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="currentGallery.image.icon" />
                            </svg>
                            <span class="text-sm uppercase tracking-wider font-semibold">
                                {{ locale === 'en' ? 'Image' : 'Imagen' }} {{ currentGallery.index + 1 }} / {{ currentGallery.total }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
/* Transición del panel de información del proyecto (Derecha) */
.fade-project-enter-active,
.fade-project-leave-active {
    transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-project-enter-from {
    opacity: 0;
    transform: translateY(12px);
}

.fade-project-leave-to {
    opacity: 0;
    transform: translateY(-12px);
}

/* Transición para el Lightbox */
.lightbox-fade-enter-active { transition: opacity 0.3s ease; }
.lightbox-fade-leave-active { transition: opacity 0.25s ease; }
.lightbox-fade-enter-from,
.lightbox-fade-leave-to { opacity: 0; }
</style>