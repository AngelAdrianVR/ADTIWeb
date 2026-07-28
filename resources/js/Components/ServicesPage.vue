<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();

// Service categories / tabs
const tabs = [
    { key: 'hmi', labelEs: 'HMI & SCADA', labelEn: 'HMI & SCADA' },
    { key: 'panels', labelEs: 'Tableros de Control', labelEn: 'Control Panels' },
    { key: 'design', labelEs: 'Diseño Industrial', labelEn: 'Industrial Design' },
    { key: 'integration', labelEs: 'Integración', labelEn: 'Integration' },
];

const activeTab = ref('hmi');

// Service data — bilingual
const servicesData = {
    hmi: {
        es: {
            title: 'Programación HMI & SCADA',
            description:
                'Desarrollamos interfaces hombre-máquina intuitivas y sistemas SCADA de alto rendimiento para el monitoreo y control en tiempo real de tus procesos industriales. Nuestras soluciones integran visualización avanzada, alarmas inteligentes, registro histórico de datos y conectividad con PLCs y dispositivos de campo.',
            features: [
                'Pantallas de operador personalizadas',
                'Sistemas de alarmas y eventos',
                'Históricos y tendencias',
                'Conectividad OPC-UA / MQTT',
            ],
        },
        en: {
            title: 'HMI & SCADA Programming',
            description:
                'We develop intuitive human-machine interfaces and high-performance SCADA systems for real-time monitoring and control of your industrial processes. Our solutions integrate advanced visualization, intelligent alarms, historical data logging, and connectivity with PLCs and field devices.',
            features: [
                'Custom operator screens',
                'Alarm & event systems',
                'Historical data & trends',
                'OPC-UA / MQTT connectivity',
            ],
        },
        imageBg: 'from-[#0082A8] to-[#00D4FF]',
        techs: ['Ignition SCADA', 'Wonderware', 'Siemens WinCC', 'FactoryTalk'],
    },
    panels: {
        es: {
            title: 'Diseño de Tableros de Control',
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
            title: 'Control Panel Design',
            description:
                'We design and manufacture electrical control panels compliant with UL 508A, IEC 61439, and NEMA standards. From single-line diagrams to final assembly, we guarantee quality, safety, and energy efficiency in every panel we deliver.',
            features: [
                'UL 508A / IEC compliant design',
                'Single-line & control diagrams',
                'Thermal & selectivity calculations',
                'FAT testing & commissioning',
            ],
        },
        imageBg: 'from-[#6D6E71] to-[#0F172A]',
        techs: ['UL 508A', 'IEC 61439', 'NEMA', 'EPLAN'],
    },
    design: {
        es: {
            title: 'Diseño Industrial & Prototipado',
            description:
                'Ofrecemos diseño industrial integral: modelado 3D, planos de fabricación, prototipado rápido y optimización de procesos productivos. Convertimos tus conceptos en soluciones tangibles, funcionales y estéticamente superiores.',
            features: [
                'Modelado 3D paramétrico',
                'Planos de fabricación (DFM)',
                'Prototipado rápido',
                'Análisis de factibilidad',
            ],
        },
        en: {
            title: 'Industrial Design & Prototyping',
            description:
                'We offer comprehensive industrial design: 3D modeling, manufacturing drawings, rapid prototyping, and production process optimization. We turn your concepts into tangible, functional, and aesthetically superior solutions.',
            features: [
                'Parametric 3D modeling',
                'DFM manufacturing drawings',
                'Rapid prototyping',
                'Feasibility analysis',
            ],
        },
        imageBg: 'from-[#0082A8] to-[#0F172A]',
        techs: ['SolidWorks', 'AutoCAD', 'Fusion 360', 'Inventor'],
    },
    integration: {
        es: {
            title: 'Integración de Sistemas',
            description:
                'Conectamos todos los eslabones de tu cadena de automatización: PLCs, variadores, sensores, robots y sistemas MES. Nuestra integración garantiza una comunicación fluida entre dispositivos y una operación sincronizada de planta.',
            features: [
                'Integración multi-marca (Siemens, AB, Omron)',
                'Protocolos industriales (Profinet, EtherNet/IP)',
                'Robótica y visión artificial',
                'Sistemas MES/MOM',
            ],
        },
        en: {
            title: 'Systems Integration',
            description:
                'We connect every link in your automation chain: PLCs, drives, sensors, robots, and MES systems. Our integration ensures seamless communication between devices and synchronized plant operation.',
            features: [
                'Multi-brand integration (Siemens, AB, Omron)',
                'Industrial protocols (Profinet, EtherNet/IP)',
                'Robotics & machine vision',
                'MES/MOM systems',
            ],
        },
        imageBg: 'from-[#0F172A] to-[#00D4FF]',
        techs: ['Siemens TIA Portal', 'Allen-Bradley', 'Omron Sysmac', 'ABB Robots'],
    },
};

const active = computed(() => servicesData[activeTab.value]);
const activeData = computed(() => locale.value === 'en' ? active.value.en : active.value.es);

// Placeholder images
const galleryPlaceholders = [
    { w: 'w-full', h: 'h-48', rounded: 'rounded-2xl' },
    { w: 'w-full', h: 'h-24', rounded: 'rounded-xl' },
    { w: 'w-full', h: 'h-24', rounded: 'rounded-xl' },
    { w: 'w-full', h: 'h-24', rounded: 'rounded-xl' },
];
</script>

<template>
    <Head :title="t('pages.services.title')" />

    <div class="min-h-screen bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">

            <!-- ═══════ Header ═══════ -->
            <div class="mb-16">
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.04]">
                    {{ locale === 'en' ? 'Our Services' : 'Nuestros Servicios' }}
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-deep-onyx tracking-tight leading-[1.1] max-w-2xl">
                    {{ locale === 'en' ? 'Industrial Automation' : 'Automatización Industrial' }}
                    <span class="text-brand-blue">{{ locale === 'en' ? 'Solutions' : 'a tu Medida' }}</span>
                </h1>
            </div>

            <!-- ═══════ Tabs ═══════ -->
            <div class="flex flex-wrap gap-1 mb-16 border-b border-gray-100">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    @click="activeTab = tab.key"
                    class="relative px-5 sm:px-6 py-4 text-sm font-semibold transition-colors duration-200"
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

            <!-- ═══════ Main Content — Split Layout ═══════ -->
            <div class="grid lg:grid-cols-5 gap-12 lg:gap-16 mb-20">
                <!-- Left: Text content (3 cols) -->
                <div class="lg:col-span-3 space-y-8">
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

                <!-- Right: Main image (2 cols) -->
                <div class="lg:col-span-2">
                    <div
                        class="relative w-full aspect-[4/3] rounded-3xl bg-gradient-to-br overflow-hidden shadow-lg"
                        :class="active.imageBg"
                    >
                        <!-- Decorative tech pattern inside -->
                        <div class="absolute inset-0 opacity-20"
                            style="background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.3) 1px, transparent 1px); background-size: 24px 24px;"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent" />

                        <!-- Placeholder center element -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="size-20 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center
                                ring-1 ring-white/20 shadow-2xl">
                                <svg class="size-8 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══════ Tech Tags ═══════ -->
            <div class="mb-20">
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
            <div class="mb-20">
                <p class="text-xs uppercase tracking-[0.2em] text-steel-grey/40 font-semibold mb-6">
                    {{ locale === 'en' ? 'Project Gallery' : 'Galería de Proyectos' }}
                </p>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div
                        v-for="(img, i) in galleryPlaceholders"
                        :key="i"
                        :class="[img.h, img.rounded, i === 0 ? 'col-span-2 row-span-2 lg:row-span-2 h-full' : '']"
                        class="bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden hover:border-brand-blue/20 transition-colors duration-300 group"
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
            <div class="text-center py-12 px-8 rounded-[2.5rem] bg-gradient-to-br from-deep-onyx to-[#0d2137]">
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

        </div>
    </div>
</template>
