<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useI18n } from "vue-i18n";

const { locale } = useI18n();

// ── MÉTRICAS ──
const stats = [
    { key: "delivery",    value: 98, suffix: "%",  labelEs: "Entregas a Tiempo",        labelEn: "On-Time Delivery",    color: "#0082A8" },
    { key: "satisfaction",value: 96, suffix: "%",  labelEs: "Satisfacción del Cliente", labelEn: "Client Satisfaction",  color: "#00D4FF" },
    { key: "support",     value: 24, suffix: "/7", labelEs: "Soporte Técnico",           labelEn: "Technical Support",    color: "#6D6E71" },
    { key: "efficiency",  value: 40, suffix: "%",  labelEs: "Reducción de Costos",      labelEn: "Cost Reduction",       color: "#0082A8" },
];

// ── WORKFLOW ──
const workflowSteps = [
    { id: "quote",        stepNumber: "01", tagEs: "Inicio",    tagEn: "Start",      titleEs: "Cotización del Proyecto",     titleEn: "Project Quotation",          descEs: "Análisis de requerimientos técnicos, alcances operativos y presentación de una propuesta económica y cronograma detallado.",                     descEn: "Analysis of technical requirements, operational scope, and delivery of a detailed cost proposal and timeline.",                                       color: "#00D4FF" },
    { id: "advance",      stepNumber: "02", tagEs: "Arranque",  tagEn: "Kickoff",    titleEs: "Anticipo para Arranque",      titleEn: "Advance Payment & Kickoff",  descEs: "Formalización del proyecto mediante el anticipo acordado para asegurar recursos, materiales y programar actividades.",                                descEn: "Project formalization through the agreed advance payment to secure resources, materials, and schedule activities.",                                    color: "#0082A8" },
    { id: "simulation",   stepNumber: "03", tagEs: "Ingeniería",tagEn: "Engineering", titleEs: "Diseño en Simulador 3D",      titleEn: "3D Simulation & Design",     descEs: "Construcción del modelo virtual en software especializado para validar cinemática, espacios y funcionalidad antes de fabricar.",                        descEn: "Virtual model construction in specialized software to validate kinematics, spaces, and functionality before fabrication.",                              color: "#00D4FF" },
    { id: "electrical",   stepNumber: "04", tagEs: "Planos",    tagEn: "Schematics", titleEs: "Diagramas Eléctricos",         titleEn: "Electrical Diagrams",        descEs: "Elaboración de planos unifilares, arquitectura de control y cableado cumpliendo con normativas industriales internacionales.",                          descEn: "Engineering of single-line diagrams, control architecture, and wiring in compliance with international industrial standards.",                          color: "#0082A8" },
    { id: "assembly",     stepNumber: "05", tagEs: "Ensamble",  tagEn: "Assembly",   titleEs: "Compra y Ensamble",            titleEn: "Procurement & Assembly",     descEs: "Adquisición de componentes certificados de primeras marcas, fabricación mecánica e integración eléctrica en taller.",                                   descEn: "Procurement of top-tier certified components, mechanical fabrication, and electrical integration in our workshop.",                                     color: "#00D4FF" },
    { id: "commissioning",stepNumber: "06", tagEs: "Entrega",   tagEn: "Delivery",   titleEs: "Flete y Puesta en Marcha",     titleEn: "Freight & Commissioning",    descEs: "Logística de traslado seguro a planta, instalación final, pruebas funcionales en sitio y arranque operativo exitoso.",                                 descEn: "Secure logistics to client site, final installation, on-site functional testing, and successful operational startup.",                                   color: "#0082A8" },
    { id: "support",      stepNumber: "07", tagEs: "Garantía",  tagEn: "Warranty",   titleEs: "Soporte Técnico Continuo",     titleEn: "Ongoing Technical Support",  descEs: "Acompañamiento post-entrega, asistencia técnica rápida y mantenimiento preventivo para maximizar la disponibilidad.",                                   descEn: "Post-delivery support, rapid technical assistance, and preventive maintenance to maximize equipment availability.",                                     color: "#00D4FF" },
];

const animatedValues = ref(stats.map(() => 0));
const sectionVisible = ref(false);
const cardVisible = ref(stats.map(() => false));
const workflowVisible = ref(false);

// ── DRAG TO SCROLL ──
const pipelineRef = ref(null);
const isDragging = ref(false);
const dragStart = ref({ x: 0, scrollLeft: 0 });

function onDragStart(e) {
    if (!pipelineRef.value) return;
    isDragging.value = true;
    dragStart.value = { x: e.pageX, scrollLeft: pipelineRef.value.scrollLeft };
}

function onDragMove(e) {
    if (!isDragging.value || !pipelineRef.value) return;
    e.preventDefault();
    const dx = e.pageX - dragStart.value.x;
    pipelineRef.value.scrollLeft = dragStart.value.scrollLeft - dx;
}

function onDragEnd() {
    isDragging.value = false;
}

// ── OBSERVERS ──
let statsObserver = null;
let wfObserver = null;

onMounted(() => {
    statsObserver = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting && !sectionVisible.value) {
                sectionVisible.value = true;
                stats.forEach((_, i) => {
                    setTimeout(() => { cardVisible.value[i] = true; }, 150 + i * 120);
                });
                animateCounters();
            }
        },
        { threshold: 0.2 }
    );
    const el = document.getElementById("stats-section");
    if (el) statsObserver.observe(el);

    wfObserver = new IntersectionObserver(
        ([entry]) => { if (entry.isIntersecting) workflowVisible.value = true; },
        { threshold: 0.1 }
    );
    const wf = document.getElementById("workflow-section");
    if (wf) wfObserver.observe(wf);

    window.addEventListener("mousemove", onDragMove);
    window.addEventListener("mouseup", onDragEnd);
});

onUnmounted(() => {
    statsObserver?.disconnect();
    wfObserver?.disconnect();
    window.removeEventListener("mousemove", onDragMove);
    window.removeEventListener("mouseup", onDragEnd);
});

function animateCounters() {
    const duration = 2000;
    const start = performance.now();
    function tick(now) {
        const p = Math.min(1, (now - start) / duration);
        const eased = 1 - Math.pow(1 - p, 3);
        animatedValues.value = stats.map((s) => Math.round(s.value * eased));
        if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}
</script>

<template>
    <div class="bg-white">
        <!-- ═══════════════════════════════ -->
        <!-- ── STATS ── -->
        <!-- ═══════════════════════════════ -->
        <section id="stats-section" class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <div
                    class="text-center mb-16 transition-all duration-800 ease-out"
                    :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                >
                    <span class="inline-block text-[10px] sm:text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/15 bg-brand-blue/[0.03]">
                        {{ locale === "en" ? "By the Numbers" : "En Números" }}
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-deep-onyx tracking-tight leading-[1.15]">
                        {{ locale === "en" ? "Proven" : "Resultados" }}
                        <span class="text-brand-blue">{{ locale === "en" ? "Results" : "Comprobados" }}</span>
                    </h2>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <div
                        v-for="(stat, i) in stats"
                        :key="stat.key"
                        class="transition-all duration-700 ease-out"
                        :class="cardVisible[i] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                        :style="{ transitionDelay: (i * 0.08) + 's' }"
                    >
                        <div class="flex flex-col items-center text-center p-6 sm:p-8">
                            <!-- Ring -->
                            <div class="relative size-24 sm:size-28 mb-4">
                                <svg class="size-full -rotate-90" viewBox="0 0 120 120">
                                    <circle cx="60" cy="60" r="52" fill="none" stroke="#E2E8F0" stroke-width="5" />
                                    <circle
                                        cx="60" cy="60" r="52" fill="none"
                                        :stroke="stat.color"
                                        stroke-width="5"
                                        stroke-linecap="round"
                                        :stroke-dasharray="`${(animatedValues[i] / stat.value) * 327} 327`"
                                        class="transition-[stroke-dasharray] duration-1000 ease-out"
                                    />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl sm:text-3xl font-black text-deep-onyx tabular-nums">
                                        {{ animatedValues[i] }}<span class="text-lg text-brand-blue">{{ stat.suffix }}</span>
                                    </span>
                                </div>
                            </div>
                            <p class="text-xs sm:text-sm text-steel-grey font-medium tracking-wide">
                                {{ locale === "en" ? stat.labelEn : stat.labelEs }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════ -->
        <!-- ── WORKFLOW ── -->
        <!-- ═══════════════════════════════ -->
        <section id="workflow-section" class="relative pb-24 sm:pb-32 px-4 sm:px-6 lg:px-8">
            <div class="max-w-full mx-auto">
                <div
                    class="text-center mb-14 transition-all duration-800 ease-out"
                    :class="workflowVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                >
                    <span class="inline-block text-[10px] sm:text-xs uppercase tracking-[0.3em] text-electric-cyan font-semibold mb-4 px-4 py-1.5 rounded-full border border-electric-cyan/20 bg-electric-cyan/[0.04]">
                        {{ locale === "en" ? "Our Process" : "Nuestro Proceso" }}
                    </span>
                    <h3 class="text-3xl sm:text-4xl lg:text-5xl font-black text-deep-onyx tracking-tight leading-[1.15]">
                        {{ locale === "en" ? "Project Workflow & " : "Metodología de Trabajo y " }}
                        <span class="text-brand-blue">{{ locale === "en" ? "Execution" : "Desarrollo" }}</span>
                    </h3>
                    <p class="mt-4 text-steel-grey text-sm sm:text-base max-w-xl mx-auto">
                        {{ locale === "en" ? "Structured end-to-end engineering pipeline designed to guarantee precision and transparency." : "Flujo de ingeniería estructurado de principio a fin para garantizar máxima precisión y transparencia." }}
                    </p>
                </div>

                <!-- Pipeline draggeable -->
                <div
                    ref="pipelineRef"
                    @mousedown.prevent="onDragStart"
                    class="pipeline-track flex items-stretch gap-0 overflow-x-auto scrollbar-none select-none pb-4"
                    :class="[
                        workflowVisible ? 'opacity-100' : 'opacity-0',
                        isDragging ? 'cursor-grabbing' : 'cursor-grab',
                    ]"
                    style="transition: opacity 0.7s ease 0.2s;"
                >
                    <template v-for="(step, index) in workflowSteps" :key="step.id">
                        <!-- Card -->
                        <div class="pipeline-card shrink-0 w-[260px] sm:w-[300px] flex flex-col bg-white rounded-2xl p-6 sm:p-7
                            border border-slate-200/60 hover:border-brand-blue/20
                            shadow-[0_2px_8px_rgba(0,0,0,0.03)] hover:shadow-[0_6px_24px_rgba(0,0,0,0.07)]
                            transition-all duration-300"
                        >
                            <!-- Step number -->
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-[11px] font-black text-slate-300 tabular-nums tracking-widest">
                                    {{ step.stepNumber }}
                                </span>
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                                    :style="{ color: step.color, backgroundColor: step.color + '10' }"
                                >
                                    {{ locale === "en" ? step.tagEn : step.tagEs }}
                                </span>
                            </div>

                            <h4 class="text-base sm:text-lg font-bold text-deep-onyx tracking-tight mb-2 leading-snug">
                                {{ locale === "en" ? step.titleEn : step.titleEs }}
                            </h4>
                            <p class="text-xs sm:text-sm text-steel-grey/75 leading-relaxed flex-1">
                                {{ locale === "en" ? step.descEn : step.descEs }}
                            </p>

                            <!-- Color accent bar at bottom -->
                            <div class="mt-5 h-1 rounded-full w-12" :style="{ backgroundColor: step.color }" />
                        </div>

                        <!-- Connector -->
                        <div
                            v-if="index < workflowSteps.length - 1"
                            class="shrink-0 flex items-center px-2 sm:px-3"
                        >
                            <div class="flex items-center gap-1 sm:gap-1.5">
                                <span class="size-1 rounded-full bg-slate-300" />
                                <span class="w-6 sm:w-10 h-px bg-slate-300" />
                                <span class="size-1 rounded-full bg-brand-blue/40" />
                                <span class="w-6 sm:w-10 h-px bg-slate-300" />
                                <span class="size-1 rounded-full bg-slate-300" />
                            </div>
                        </div>
                    </template>

                    <!-- Spacer at end -->
                    <div class="shrink-0 w-8 sm:w-16" />
                </div>

                <!-- Drag hint -->
                <div
                    class="flex items-center justify-center gap-2 mt-6 text-[11px] sm:text-xs text-steel-grey/40 transition-all duration-500"
                    :class="workflowVisible ? 'opacity-100' : 'opacity-0'"
                    style="transition-delay: 0.6s;"
                >
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                    <span>{{ locale === "en" ? "Drag to explore the full process" : "Arrastra para explorar el proceso completo" }}</span>
                    <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.scrollbar-none {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-none::-webkit-scrollbar { display: none; }

.pipeline-track {
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
    mask-image: linear-gradient(to right, transparent 0%, black 3%, black 97%, transparent 100%);
}
</style>
