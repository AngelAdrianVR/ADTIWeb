<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();

// ── Projects data ──
const projects = [
    {
        id: 'scada-alimentos',
        es: {
            title: 'Sistema SCADA para Planta de Alimentos',
            category: 'SCADA & Control',
            desc: 'Implementación de un sistema SCADA integral con control de temperatura de precisión ±0.5°C, registro histórico FDA 21 CFR Part 11, y dashboards en tiempo real accesibles desde toda la planta. Reducción del 78% en tiempo de respuesta ante desviaciones.',
        },
        en: {
            title: 'SCADA System for Food Processing',
            category: 'SCADA & Control',
            desc: 'Comprehensive SCADA system with ±0.5°C precision temperature control, FDA 21 CFR Part 11 historical logging, and plant-wide real-time dashboards. 78% reduction in deviation response time.',
        },
        gradient: 'from-[#0082A8] via-[#0b2740] to-[#0F172A]',
        accent: '#0082A8',
        accentLight: '#00D4FF',
        stats: [
            { value: '78%', labelEs: 'Respuesta más rápida', labelEn: 'Faster Response' },
            { value: '18', suffix: '+', labelEs: 'Tanques controlados', labelEn: 'Controlled Tanks' },
        ],
        techs: ['Ignition SCADA', 'Allen-Bradley', 'EtherNet/IP', 'FDA 21 CFR Part 11'],
        icon: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
    },
    {
        id: 'tableros-agua',
        es: {
            title: 'Tableros de Control Planta Tratadora',
            category: 'Tableros de Control',
            desc: 'Fabricación de 12 tableros UL 508A con arrancadores suaves para bombas de 250 HP, variadores para aireación, y monitoreo de calidad de agua con sensores de pH, turbidez y oxígeno disuelto. Puesta en marcha récord en 5 días.',
        },
        en: {
            title: 'Control Panels for Water Treatment',
            category: 'Control Panels',
            desc: '12 UL 508A control panels with soft starters for 250 HP pumps, VFDs for aeration, and water quality monitoring with pH, turbidity & dissolved oxygen sensors. Record 5-day field commissioning.',
        },
        gradient: 'from-[#6D6E71] via-[#2d3748] to-[#0F172A]',
        accent: '#6D6E71',
        accentLight: '#A0A0A5',
        stats: [
            { value: '12', labelEs: 'Tableros fabricados', labelEn: 'Panels Built' },
            { value: '5', suffix: ' días', labelEs: 'Puesta en marcha', labelEn: 'Commissioning' },
        ],
        techs: ['UL 508A', 'NEMA 4X', 'Allen-Bradley PowerFlex', 'Siemens SIRIUS'],
        icon: 'M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z',
    },
];

// ── State ──
const mounted = ref(false);
const hoveredCard = ref(null);
const mousePos = ref({ x: 0.5, y: 0.5 });

// ── Mouse parallax tracking ──
function onMouseMove(e, cardEl) {
    const rect = cardEl.getBoundingClientRect();
    mousePos.value = {
        x: (e.clientX - rect.left) / rect.width,
        y: (e.clientY - rect.top) / rect.height,
    };
}

function onMouseLeave() {
    hoveredCard.value = null;
}

// ── Entrance observer ──
let observer = null;

onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                setTimeout(() => { mounted.value = true; }, 150);
                observer?.unobserve(entry.target);
            }
        },
        { threshold: 0.2 }
    );

    const el = document.getElementById('featured-projects');
    if (el) observer.observe(el);
});

onUnmounted(() => {
    observer?.disconnect();
});
</script>

<template>
    <section id="featured-projects" class="relative py-24 sm:py-32 overflow-hidden bg-[#F4F8FA]">
        <!-- ── Background grid ── -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.025]"
            style="background-image: linear-gradient(#0082A8 1px, transparent 1px), linear-gradient(90deg, #0082A8 1px, transparent 1px); background-size: 60px 60px;"
        />

        <!-- ── Floating glow orbs ── -->
        <div class="absolute w-[600px] h-[600px] rounded-full bg-[#0082A8]/3 blur-3xl"
            style="top: -20%; right: -10%;"
        />
        <div class="absolute w-[400px] h-[400px] rounded-full bg-[#00D4FF]/3 blur-3xl"
            style="bottom: -10%; left: -5%;"
        />

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- ── Header ── -->
            <div class="text-center mb-16 lg:mb-20">
                <span
                    class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-5 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.06] transition-all duration-700"
                    :class="mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                >
                    {{ locale === 'en' ? 'Featured Projects' : 'Proyectos Destacados' }}
                </span>
                <h2
                    class="text-4xl sm:text-5xl lg:text-6xl font-black text-deep-onyx tracking-tight leading-[1.1] max-w-3xl mx-auto transition-all duration-700 delay-150"
                    :class="mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    {{ locale === 'en' ? 'Solutions we\'ve ' : 'Soluciones que hemos ' }}
                    <span class="text-brand-blue">{{ locale === 'en' ? 'delivered' : 'entregado' }}</span>
                </h2>
            </div>

            <!-- ── Project Cards ── -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                <div
                    v-for="(project, idx) in projects"
                    :key="project.id"
                    @mouseenter="(e) => { hoveredCard = idx; onMouseMove(e, $event.target.closest('.project-card')); }"
                    @mousemove="(e) => onMouseMove(e, $event.target.closest('.project-card'))"
                    @mouseleave="onMouseLeave"
                    class="project-card group relative cursor-pointer transition-all duration-700 ease-[cubic-bezier(0.23,1,0.32,1)]"
                    :style="{ transitionDelay: mounted ? `${300 + idx * 200}ms` : '0ms' }"
                    :class="[
                        mounted ? 'opacity-100 translate-y-0 rotate-0' : 'opacity-0 translate-y-12',
                        idx === 0 ? 'lg:translate-x-0' : 'lg:translate-x-0',
                        idx === 1 ? 'rotate-0' : '-rotate-0',
                        hoveredCard === idx ? 'scale-[1.02] z-20' : (hoveredCard !== null ? 'scale-[0.98] opacity-80' : 'scale-100 z-10')
                    ]"
                >
                    <!-- Card body -->
                    <div
                        class="relative overflow-hidden rounded-[2.5rem] bg-white shadow-xl shadow-slate-200/50 border border-slate-100 transition-all duration-500"
                        :class="hoveredCard === idx ? 'shadow-2xl shadow-slate-300/40' : ''"
                    >
                        <!-- ── Top gradient area (icon zone) ── -->
                        <div
                            class="relative h-64 sm:h-72 lg:h-80 flex items-center justify-center overflow-hidden transition-all duration-500 bg-gradient-to-br"
                            :class="project.gradient"
                        >
                            <!-- Pattern overlay -->
                            <div class="absolute inset-0 opacity-10"
                                style="background-image: radial-gradient(circle at 40% 50%, rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 18px 18px;"
                            />

                            <!-- Glow behind icon -->
                            <div class="absolute w-40 h-40 rounded-full bg-white/5 blur-2xl transition-all duration-700"
                                :style="{
                                    transform: hoveredCard === idx
                                        ? `translate(${(mousePos.x - 0.5) * 20}px, ${(mousePos.y - 0.5) * 20}px) scale(1.3)`
                                        : 'translate(0, 0) scale(1)'
                                }"
                            />

                            <!-- Main icon -->
                            <div class="relative z-10 transition-all duration-500"
                                :style="{
                                    transform: hoveredCard === idx
                                        ? `translate(${(mousePos.x - 0.5) * -12}px, ${(mousePos.y - 0.5) * -12}px) scale(1.1)`
                                        : 'translate(0, 0) scale(1)'
                                }"
                            >
                                <div class="size-24 sm:size-28 rounded-3xl bg-white/10 backdrop-blur-md flex items-center justify-center ring-1 ring-white/20 shadow-2xl transition-all duration-500"
                                    :class="hoveredCard === idx ? 'shadow-[0_0_40px_rgba(255,255,255,0.1)]' : ''"
                                >
                                    <svg class="size-11 sm:size-12 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="project.icon" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Floating tech bubbles (visible on hover) -->
                            <div
                                v-for="(tech, tIdx) in project.techs.slice(0, 3)"
                                :key="tech"
                                class="absolute px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 text-[10px] font-bold text-white/80 transition-all duration-500"
                                :class="hoveredCard === idx ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-3'"
                                :style="{
                                    top: `${15 + tIdx * 22}%`,
                                    right: tIdx === 0 ? '8%' : tIdx === 1 ? '14%' : '6%',
                                    transitionDelay: hoveredCard === idx ? `${150 + tIdx * 80}ms` : '0ms',
                                }"
                            >
                                {{ tech.length > 16 ? tech.slice(0, 16) + '…' : tech }}
                            </div>

                            <!-- Category badge -->
                            <div class="absolute top-6 left-6">
                                <span
                                    class="inline-block px-3.5 py-1.5 rounded-full text-[10px] font-extrabold uppercase tracking-[0.2em] bg-white/15 backdrop-blur-sm text-white/90 border border-white/10 transition-all duration-300"
                                    :class="hoveredCard === idx ? 'opacity-100 -translate-y-0' : 'opacity-90 -translate-y-0'"
                                >
                                    {{ locale === 'en' ? project.en.category : project.es.category }}
                                </span>
                            </div>

                            <!-- Stats badge (bottom right) -->
                            <div
                                class="absolute bottom-6 right-6 flex gap-2 transition-all duration-500"
                                :class="hoveredCard === idx ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                            >
                                <div
                                    v-for="stat in project.stats"
                                    :key="stat.value"
                                    class="bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2 text-center border border-white/10"
                                >
                                    <div class="text-white font-black text-sm leading-none">
                                        {{ stat.value }}<span v-if="stat.suffix" class="text-[10px]">{{ stat.suffix }}</span>
                                    </div>
                                    <div class="text-white/50 text-[9px] uppercase tracking-wider font-semibold mt-0.5">
                                        {{ locale === 'en' ? stat.labelEn : stat.labelEs }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ── Bottom content area ── -->
                        <div class="p-6 sm:p-8 transition-all duration-500"
                            :class="hoveredCard === idx ? 'pb-8' : 'pb-6'"
                        >
                            <h3 class="text-xl sm:text-2xl font-black text-deep-onyx tracking-tight leading-snug transition-colors duration-300"
                                :class="hoveredCard === idx ? 'text-brand-blue' : ''"
                            >
                                {{ locale === 'en' ? project.en.title : project.es.title }}
                            </h3>

                            <!-- Description (reveals on hover) -->
                            <div
                                class="overflow-hidden transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]"
                                :style="{
                                    maxHeight: hoveredCard === idx ? '120px' : '0px',
                                    opacity: hoveredCard === idx ? 1 : 0,
                                    marginTop: hoveredCard === idx ? '12px' : '0px',
                                }"
                            >
                                <p class="text-sm text-steel-grey leading-relaxed">
                                    {{ locale === 'en' ? project.en.desc : project.es.desc }}
                                </p>
                            </div>

                            <!-- Inline tech tags (reveal on hover) -->
                            <div
                                class="overflow-hidden transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]"
                                :style="{
                                    maxHeight: hoveredCard === idx ? '40px' : '0px',
                                    opacity: hoveredCard === idx ? 1 : 0,
                                    marginTop: hoveredCard === idx ? '16px' : '0px',
                                }"
                            >
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="tech in project.techs"
                                        :key="tech"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-deep-onyx border border-slate-200"
                                    >
                                        {{ tech }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- ── Hover accent bar ── -->
                        <div
                            class="absolute bottom-0 left-0 h-1 transition-all duration-500 rounded-full"
                            :style="{
                                width: hoveredCard === idx ? '100%' : '0%',
                                background: `linear-gradient(90deg, ${project.accent}, ${project.accentLight})`,
                            }"
                        />
                    </div>
                </div>
            </div>

            <!-- ── View All CTA ── -->
            <div
                class="mt-16 text-center transition-all duration-700 delay-[800ms]"
                :class="mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <!-- Connecting line -->
                <div class="flex items-center justify-center gap-4 mb-8">
                    <div class="h-px w-16 bg-gradient-to-r from-transparent to-brand-blue/30" />
                    <div class="size-2 rounded-full bg-brand-blue/30" />
                    <div class="h-px w-16 bg-gradient-to-l from-transparent to-brand-blue/30" />
                </div>

                <Link
                    :href="route('proyectos')"
                    class="group inline-flex items-center gap-4 px-10 py-5 rounded-full bg-deep-onyx hover:bg-brand-blue text-white font-bold text-base tracking-wide
                        shadow-xl shadow-deep-onyx/10 hover:shadow-[0_12px_40px_rgba(0,130,168,0.35)] hover:-translate-y-1
                        transition-all duration-500 ease-[cubic-bezier(0.23,1,0.32,1)]"
                >
                    {{ locale === 'en' ? 'View All Projects' : 'Ver todos los proyectos' }}
                    <span class="relative size-10 rounded-full bg-white/15 flex items-center justify-center overflow-hidden">
                        <svg class="size-4 transition-all duration-500 group-hover:translate-x-6 group-hover:opacity-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                        <svg class="size-4 absolute transition-all duration-500 -translate-x-6 opacity-0 group-hover:translate-x-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </Link>

                <p class="mt-5 text-sm text-steel-grey/40">
                    {{ locale === 'en' ? 'Explore our complete portfolio of industrial automation projects' : 'Explora nuestro portafolio completo de proyectos de automatización industrial' }}
                </p>
            </div>
        </div>
    </section>
</template>
