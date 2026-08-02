<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

// images
import etiquetado from '@/../../public/images/etiquetado.webp';
import celdaRobotica from '@/../../public/images/celda-robotica.webp';
import bandaTransportadora from '@/../../public/images/banda_transportacion.webp';
import finalLinea from '@/../../public/images/final_de_linea.webp';
import programacion from '@/../../public/images/programacion.webp';
import manufactura from '@/../../public/images/manufactura.webp';
import tableros from '@/../../public/images/tableros.webp';

const { t, locale } = useI18n();

// Service categories / tabs
const tabs = [
    { key: 'labeling', labelEs: 'Sistemas de Etiquetado', labelEn: 'Labeling Systems' },
    { key: 'conveyors', labelEs: 'Bandas de Transportación', labelEn: 'Conveyor Belts' },
    { key: 'eol', labelEs: 'Automatización Fin de Línea', labelEn: 'End-of-Line Automation' },
    { key: 'robotics', labelEs: 'Celdas Robóticas', labelEn: 'Robotic Cells' },
    { key: 'machining', labelEs: 'Manufactura Mecánica', labelEn: 'Mechanical Manufacturing' },
    { key: 'controls', labelEs: 'Automatización de Controles', labelEn: 'Control Automation' },
    { key: 'panels', labelEs: 'Tableros de Control', labelEn: 'Control Panels' },
];

const activeTab = ref('labeling');

// Service data — bilingual
const servicesData = {
    labeling: {
        es: {
            title: 'Sistemas de Etiquetado Industrial',
            description:
                'Diseñamos e integramos sistemas de etiquetado y codificación de alta precisión para líneas de producción. Nuestras soluciones abarcan desde etiquetadoras automáticas, impresoras de inyección de tinta (CIJ), láser y transferencia térmica, hasta sistemas de visión para verificación de códigos y trazabilidad completa.',
            features: [
                'Etiquetado automático de alta velocidad',
                'Codificación láser e inyección de tinta',
                'Sistemas de visión para verificación',
                'Trazabilidad y serialización',
            ],
        },
        en: {
            title: 'Industrial Labeling Systems',
            description:
                'We design and integrate high-precision labeling and coding systems for production lines. Our solutions range from automatic labelers, continuous inkjet printers (CIJ), laser and thermal transfer printers, to vision systems for code verification and complete traceability.',
            features: [
                'High-speed automatic labeling',
                'Laser & inkjet coding',
                'Vision inspection systems',
                'Traceability & serialization',
            ],
        },
        image: etiquetado,
        techs: ['Markem-Imaje', 'Videojet', 'Domino', 'Cognex'],
    },
    conveyors: {
        es: {
            title: 'Bandas de Transportación',
            description:
                'Fabricamos y automatizamos sistemas de transportación industrial a la medida: bandas modulares, de rodillos, sanitarias y sistemas de acumulación. Integramos sensores, variadores de velocidad y controles inteligentes para optimizar el flujo de materiales en tu planta.',
            features: [
                'Bandas modulares y de rodillos',
                'Transportadores sanitarios (FDA)',
                'Sistemas de acumulación y desvío',
                'Control de velocidad variable',
            ],
        },
        en: {
            title: 'Conveyor Belt Systems',
            description:
                'We manufacture and automate custom industrial conveyor systems: modular belts, roller conveyors, sanitary conveyors, and accumulation systems. We integrate sensors, variable frequency drives, and intelligent controls to optimize material flow in your plant.',
            features: [
                'Modular & roller conveyors',
                'Sanitary conveyors (FDA compliant)',
                'Accumulation & diverter systems',
                'Variable speed control',
            ],
        },
        image: bandaTransportadora,
        techs: ['Intralox', 'Habasit', 'Dorner', 'FlexLink'],
    },
    eol: {
        es: {
            title: 'Automatización Fin de Línea',
            description:
                'Optimizamos el final de tu línea de producción con soluciones integrales: encajonadoras, paletizadores robóticos, envolvedoras de stretch y sistemas de inspección final. Automatizamos el empaque secundario para maximizar tu productividad y reducir costos operativos.',
            features: [
                'Encajonadoras y formadoras de cajas',
                'Paletizado robótico',
                'Envolvedoras de stretch y flejado',
                'Inspección y control de calidad final',
            ],
        },
        en: {
            title: 'End-of-Line Automation',
            description:
                'We optimize the end of your production line with comprehensive solutions: case packers, robotic palletizers, stretch wrappers, and final inspection systems. We automate secondary packaging to maximize your productivity and reduce operational costs.',
            features: [
                'Case erectors & packers',
                'Robotic palletizing',
                'Stretch wrapping & strapping',
                'Final quality inspection',
            ],
        },
        image: finalLinea,
        techs: ['ABB Palletizing', 'FANUC', 'KUKA', 'Robopac'],
    },
    robotics: {
        es: {
            title: 'Celdas Robóticas',
            description:
                'Diseñamos celdas robóticas llave en mano para aplicaciones de pick & place, soldadura, ensamblaje, pintura y manipulación de materiales. Integramos robots de 6 ejes, colaborativos (cobots) y SCARA con sistemas de seguridad perimetral y validación de procesos.',
            features: [
                'Robots de 6 ejes y colaborativos',
                'Pick & place y paletizado',
                'Soldadura y ensamblaje robotizado',
                'Celdas con seguridad funcional',
            ],
        },
        en: {
            title: 'Robotic Cells',
            description:
                'We design turnkey robotic cells for pick & place, welding, assembly, painting, and material handling applications. We integrate 6-axis robots, collaborative robots (cobots), and SCARA systems with perimeter safety and process validation.',
            features: [
                '6-axis & collaborative robots',
                'Pick & place & palletizing',
                'Robotic welding & assembly',
                'Functional safety cells',
            ],
        },
        image: celdaRobotica,
        techs: ['FANUC', 'Universal Robots', 'Yaskawa', 'ABB Robotics'],
    },
    machining: {
        es: {
            title: 'Manufactura Mecánica de Precisión',
            description:
                'Ofrecemos servicios de maquinado CNC, fresado, torneado y fabricación de piezas mecánicas bajo planos. Trabajamos con tolerancias estrictas y materiales diversos: aceros, aluminios, plásticos de ingeniería y aleaciones especiales para la industria automatizada.',
            features: [
                'Fresado y torneado CNC',
                'Fabricación bajo plano (DFM)',
                'Mecanizado de precisión multicavidad',
                'Acabados superficiales industriales',
            ],
        },
        en: {
            title: 'Precision Mechanical Manufacturing',
            description:
                'We offer CNC machining, milling, turning, and mechanical parts fabrication from engineering drawings. We work with strict tolerances and diverse materials: steels, aluminum, engineering plastics, and special alloys for the automated industry.',
            features: [
                'CNC milling & turning',
                'DFM-based fabrication',
                'Multi-cavity precision machining',
                'Industrial surface finishing',
            ],
        },
        image: manufactura,
        techs: ['Haas CNC', 'Mazak', 'DMG MORI', 'Mastercam'],
    },
    controls: {
        es: {
            title: 'Automatización de Controles',
            description:
                'Programamos y configuramos sistemas de control industrial con PLCs, HMIs, variadores y redes de comunicación industrial. Desarrollamos lógicas de control avanzadas, recetas de producción y sistemas de adquisición de datos para la toma de decisiones en tiempo real.',
            features: [
                'Programación de PLCs multi-marca',
                'Redes Profinet, EtherNet/IP, Modbus',
                'SCADA e historiadores de datos',
                'Puesta en marcha y validación',
            ],
        },
        en: {
            title: 'Control Automation',
            description:
                'We program and configure industrial control systems with PLCs, HMIs, drives, and industrial communication networks. We develop advanced control logic, production recipes, and data acquisition systems for real-time decision making.',
            features: [
                'Multi-brand PLC programming',
                'Profinet, EtherNet/IP, Modbus networks',
                'SCADA & data historians',
                'Commissioning & validation',
            ],
        },
        image: programacion,
        techs: ['Siemens TIA Portal', 'Allen-Bradley', 'Omron Sysmac', 'Ignition SCADA'],
    },
    panels: {
        es: {
            title: 'Tableros de Control',
            description:
                'Diseñamos y fabricamos tableros de control eléctrico conforme a las normativas UL 508A, IEC 61439 y NEMA. Desde el diagrama unifilar hasta el armado final, garantizamos calidad, seguridad y eficiencia energética en cada panel que entregamos.',
            features: [
                'Diseño conforme a UL 508A / IEC',
                'Diagramas unifilares y de control',
                'Cálculo térmico y selectividad',
                'Pruebas FAT y puesta en marcha',
            ],
        },
        en: {
            title: 'Control Panels',
            description:
                'We design and manufacture electrical control panels compliant with UL 508A, IEC 61439, and NEMA standards. From single-line diagrams to final assembly, we guarantee quality, safety, and energy efficiency in every panel we deliver.',
            features: [
                'UL 508A / IEC compliant design',
                'Single-line & control diagrams',
                'Thermal & selectivity calculations',
                'FAT testing & commissioning',
            ],
        },
        image: tableros,
        techs: ['UL 508A', 'IEC 61439', 'NEMA', 'EPLAN'],
    },
};

const active = computed(() => servicesData[activeTab.value]);
const activeData = computed(() => locale.value === 'en' ? active.value.en : active.value.es);

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

// Placeholder images
const galleryPlaceholders = [
    { w: 'w-full', h: 'h-48', rounded: 'rounded-2xl' },
    { w: 'w-full', h: 'h-24', rounded: 'rounded-xl' },
    { w: 'w-full', h: 'h-24', rounded: 'rounded-xl' },
    { w: 'w-full', h: 'h-24', rounded: 'rounded-xl' },
];

onMounted(() => {
    // Trigger entrance animations after mount
    requestAnimationFrame(() => {
        mounted.value = true;
    });

    nextTick(() => {
        // Check tabs scroll state
        checkTabsScroll();
        window.addEventListener('resize', checkTabsScroll);

        // Intersection Observer for gallery scroll reveal
        const galleryItems = document.querySelectorAll('.gallery-item');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('gallery-revealed');
                    }, i * 120);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        galleryItems.forEach(el => observer.observe(el));
    });
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
                                :src="active.image"
                                :alt="activeData.title"
                                class="absolute inset-0 lg:-mt-28 w-full h-full object-contain"
                                draggable="false"
                            />
                            <!-- <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent" /> -->
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
                <div class="flex flex-wrap gap-2.5">
                    <span
                        v-for="tech in active.techs"
                        :key="tech"
                        class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-gray-50 text-steel-grey border border-gray-100 hover:border-brand-blue/30 hover:text-brand-blue transition-all duration-200"
                    >
                        {{ tech }}
                    </span>
                </div>
            </div>

            <!-- ═══════ Image Gallery ═══════ -->
            <div
                class="mb-20 transition-all duration-700 ease-out delay-[600ms]"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'"
            >
                <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/40 font-semibold mb-6">
                    {{ locale === 'en' ? 'Project Gallery' : 'Galería de Proyectos' }}
                </p>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div
                        v-for="(img, i) in galleryPlaceholders"
                        :key="i"
                        @click="openLightbox(i)"
                        :class="[img.h, img.rounded, i === 0 ? 'col-span-2 row-span-2 lg:row-span-2 h-full' : '']"
                        class="gallery-item bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden hover:border-brand-blue/20 transition-all duration-500 ease-out cursor-pointer group"
                    >
                        <div class="flex flex-col items-center gap-2 text-steel-grey/20 group-hover:text-brand-blue/30 transition-colors duration-300">
                            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <span class="text-[10px] uppercase tracking-wider font-medium">
                                {{ locale === 'en' ? 'Image' : 'Imagen' }} {{ i + 1 }}
                            </span>
                        </div>
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
                <div class="max-w-5xl max-h-[85vh] w-full rounded-2xl overflow-hidden bg-gray-50 border border-gray-100">
                    <div class="flex flex-col items-center gap-4 p-8 text-steel-grey/20">
                        <svg class="size-20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <span class="text-lg uppercase tracking-wider font-semibold">
                            {{ locale === 'en' ? 'Image' : 'Imagen' }} {{ lightboxIndex + 1 }}
                        </span>
                    </div>
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
