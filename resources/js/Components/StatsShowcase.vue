<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();

const stats = [
    {
        key: 'delivery',
        value: 98,
        suffix: '%',
        labelEs: 'Entregas a Tiempo',
        labelEn: 'On-Time Delivery',
        color: '#0082A8',
        glow: 'rgba(0,130,168,0.4)',
    },
    {
        key: 'satisfaction',
        value: 96,
        suffix: '%',
        labelEs: 'Satisfacción del Cliente',
        labelEn: 'Client Satisfaction',
        color: '#00D4FF',
        glow: 'rgba(0,212,255,0.4)',
    },
    {
        key: 'support',
        value: 24,
        suffix: '/7',
        labelEs: 'Soporte Técnico',
        labelEn: 'Technical Support',
        color: '#6D6E71',
        glow: 'rgba(109,110,113,0.4)',
    },
    {
        key: 'efficiency',
    value: 40,
        suffix: '%',
        labelEs: 'Reducción de Costos',
        labelEn: 'Cost Reduction',
        color: '#0082A8',
        glow: 'rgba(0,130,168,0.4)',
    },
];

const animatedValues = ref(stats.map(() => 0));
const sectionVisible = ref(false);
const cardVisible = ref([false, false, false, false]);

let observer = null;

function polarToCartesian(cx, cy, r, angleDeg) {
    const rad = ((angleDeg - 90) * Math.PI) / 180;
    return { x: cx + r * Math.cos(rad), y: cy + r * Math.sin(rad) };
}

function describeArc(cx, cy, r, startAngle, endAngle) {
    const start = polarToCartesian(cx, cy, r, endAngle);
    const end = polarToCartesian(cx, cy, r, startAngle);
    const large = endAngle - startAngle <= 180 ? '0' : '1';
    return `M ${start.x} ${start.y} A ${r} ${r} 0 ${large} 0 ${end.x} ${end.y}`;
}

onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting && !sectionVisible.value) {
                sectionVisible.value = true;
                // Stagger card reveals
                [200, 400, 600, 800].forEach((delay, i) => {
                    setTimeout(() => { cardVisible.value[i] = true; }, delay);
                });
                // Animate counters
                animateCounters();
            }
        },
        { threshold: 0.2 }
    );
    const el = document.getElementById('stats-section');
    if (el) observer.observe(el);
});

onUnmounted(() => {
    if (observer) observer.disconnect();
});

function animateCounters() {
    const duration = 1800;
    const startTime = performance.now();

    function tick(now) {
        const elapsed = now - startTime;
        const progress = Math.min(1, elapsed / duration);
        const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic

        animatedValues.value = stats.map((s) => Math.round(s.value * eased));

        if (progress < 1) {
            requestAnimationFrame(tick);
        }
    }

    requestAnimationFrame(tick);
}
</script>

<template>
    <section
        id="stats-section"
        class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-deep-onyx overflow-hidden"
    >
        <!-- Background effects -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image: linear-gradient(rgba(0,212,255,0.4) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.4) 1px, transparent 1px); background-size: 60px 60px;"
            />
            <div class="absolute top-10 right-[20%] size-72 rounded-full bg-brand-blue/5 blur-3xl" />
            <div class="absolute bottom-0 left-[10%] size-64 rounded-full bg-electric-cyan/4 blur-3xl" />
        </div>

        <div class="relative max-w-6xl mx-auto">
            <!-- Header -->
            <div
                class="text-center mb-16 transition-all duration-700 ease-out"
                :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.06]">
                    {{ locale === 'en' ? 'By the Numbers' : 'En Números' }}
                </span>
                <h2 class="text-4xl sm:text-5xl font-black text-ghost-white tracking-tight leading-[1.1]">
                    {{ locale === 'en' ? 'Proven' : 'Resultados' }}
                    <span class="text-brand-blue">{{ locale === 'en' ? 'Results' : 'Comprobados' }}</span>
                </h2>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <div
                    v-for="(stat, i) in stats"
                    :key="stat.key"
                    class="relative group"
                    :class="cardVisible[i] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
                    :style="{ transition: `all 0.7s cubic-bezier(0.16, 1, 0.3, 1) ${i * 0.1}s` }"
                >
                    <div class="relative bg-white/[0.03] backdrop-blur-sm rounded-[2rem] p-6 sm:p-8 text-center
                        border border-white/[0.06] hover:border-brand-blue/20
                        hover:bg-white/[0.05]
                        transition-all duration-500 ease-out h-full flex flex-col items-center justify-center gap-4">

                        <!-- SVG Ring Chart -->
                        <div class="relative size-24 sm:size-28">
                            <svg class="size-full -rotate-90" viewBox="0 0 120 120">
                                <!-- Background ring -->
                                <circle cx="60" cy="60" r="50" fill="none"
                                    stroke="currentColor"
                                    class="text-white/[0.06]"
                                    stroke-width="6"
                                />
                                <!-- Animated foreground ring -->
                                <circle
                                    cx="60" cy="60" r="50" fill="none"
                                    :stroke="stat.color"
                                    stroke-width="6"
                                    stroke-linecap="round"
                                    :stroke-dasharray="`${(animatedValues[i] / stat.value) * 314} 314`"
                                    class="transition-[stroke-dasharray] duration-700 ease-out"
                                    :style="{ filter: `drop-shadow(0 0 8px ${stat.glow})` }"
                                />
                            </svg>
                            <!-- Center number -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl sm:text-3xl font-black text-ghost-white tabular-nums tracking-tight">
                                    {{ animatedValues[i] }}<span class="text-base text-brand-blue">{{ stat.suffix }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Label -->
                        <p class="text-xs sm:text-sm text-steel-grey/60 font-medium tracking-wide leading-tight">
                            {{ locale === 'en' ? stat.labelEn : stat.labelEs }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom subtle line -->
            <div class="mt-16 flex items-center gap-3 opacity-40">
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-brand-blue/30 to-transparent" />
                <div class="flex gap-1.5">
                    <span class="size-1 rounded-full bg-brand-blue/50" />
                    <span class="size-1 rounded-full bg-white/20" />
                    <span class="size-1 rounded-full bg-brand-blue/50" />
                </div>
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-brand-blue/30 to-transparent" />
            </div>
        </div>
    </section>
</template>
