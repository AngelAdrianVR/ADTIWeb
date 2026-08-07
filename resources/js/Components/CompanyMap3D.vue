<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from "vue";
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();

// ── ÁREAS DEL CROQUIS ──
const areas = [
    {
        id: "troquelado",
        x: 22, y: 25,
        color: "#0082A8", glow: "#00D4FF",
        icon: "M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4",
        image: "/images/troquelado-3d.webp",
        es: {
            name: "TROQUELADO",
            desc: "Área equipada con torno de alta precisión y maquinaria tipo torno 3D para el conformado, estampado y corte especializado de piezas industriales.",
        },
        en: {
            name: "DIE CUTTING",
            desc: "Area equipped with high-precision lathes and 3D-type lathe machinery for forming, stamping, and specialized cutting of industrial parts.",
        },
    },
    {
        id: "tableros",
        x: 75, y: 20,
        color: "#0F172A", glow: "#6D6E71",
        icon: "M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z",
        image: "/images/tableros-3d.webp",
        es: {
            name: "TABLEROS",
            desc: "Zona de armado con una gran mesa central cuadrada y 3 tableros de diferentes escalas alrededor para ensamble y cableado de sistemas de control.",
        },
        en: {
            name: "PANELS",
            desc: "Assembly area with a large central square table and 3 control panels of different scales around it for system assembly and wiring.",
        },
    },
    {
        id: "ensambles",
        x: 25, y: 55,
        color: "#0082A8", glow: "#00D4FF",
        icon: "M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10",
        image: "/images/ensambles-3d.webp",
        es: {
            name: "ENSAMBLES",
            desc: "Línea de producción equipada con brazo robótico automatizado, cintas transportadoras, máquinas en proceso de montaje y gabinetes técnicos.",
        },
        en: {
            name: "ASSEMBLY",
            desc: "Production line equipped with automated robotic arm, conveyor belts, machines in assembly process, and technical cabinets.",
        },
    },
    {
        id: "corte_cnc",
        x: 75, y: 48,
        color: "#6D6E71", glow: "#0082A8",
        icon: "M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z",
        image: "/images/corte-cnc-3d.webp",
        es: {
            name: "CORTE CNC",
            desc: "Centro de corte de precisión con maquinaria CNC de gran formato especializada en placas de acero y perfilería industrial pesada.",
        },
        en: {
            name: "CNC CUTTING",
            desc: "Precision cutting center with large-format CNC machinery specialized in steel plates and heavy industrial profiling.",
        },
    },
    {
        id: "programacion",
        x: 78, y: 75,
        color: "#0F172A", glow: "#00D4FF",
        icon: "M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
        image: "/images/programacion-3d.webp",
        es: {
            name: "PROGRAMACIÓN",
            desc: "Estación de ingeniería de software y automatización conformada por una mesa colaborativa y 3 estaciones de cómputo especializadas.",
        },
        en: {
            name: "PROGRAMMING",
            desc: "Software and automation engineering station consisting of a collaborative table and 3 specialized computing workstations.",
        },
    },
    {
        id: "embarques",
        x: 25, y: 85,
        color: "#0082A8", glow: "#00D4FF",
        icon: "M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4",
        image: "/images/embarques-3d.webp",
        es: {
            name: "EMBARQUES",
            desc: "Área logística con cajas de protección e insumos listos, albergando maquinaria terminada y paletizada para envío internacional y nacional.",
        },
        en: {
            name: "SHIPPING",
            desc: "Logistics area with protective boxes and ready supplies, housing finished and palletized machinery for international and domestic shipping.",
        },
    },
];

// ── ESTADO ──
const containerRef = ref(null);
const sectionRef = ref(null);
const croquisImageRef = ref(null);
const buttonRefs = ref({});
const scrollProgress = ref(0);
const unlockProgress = ref(0);
const sectionVisible = ref(false);
const croquisLoaded = ref(false);
const activeAreaId = ref(null);
const dropdownStyle = ref({});
const dropdownAbove = ref(false);

// ── COMPUTED ──
const currentArea = computed(() =>
    activeAreaId.value ? areas.find(a => a.id === activeAreaId.value) : null
);

const currentData = computed(() => {
    if (!currentArea.value) return null;
    return locale.value === "en" ? currentArea.value.en : currentArea.value.es;
});

const showUnlockBar = computed(() => scrollProgress.value >= 0.95 && unlockProgress.value < 1);

// ── TRANSFORM 3D ──
const croquisTransform = computed(() => {
    const p = Math.min(scrollProgress.value, 1);
    const scale = 0.62 + p * 0.48;
    const rotateX = 54 - p * 39;
    const rotateZ = -34 + p * 34;
    const translateY = p * 20;
    return {
        transform: `perspective(1600px) rotateX(${rotateX}deg) rotateZ(${rotateZ}deg) scale(${scale}) translateY(${translateY}px)`,
        transition: scrollProgress.value >= 1 ? "transform 0.6s cubic-bezier(0.22, 1, 0.36, 1)" : "transform 0.08s linear",
    };
});

// ── SCROLL ──
function handleScroll() {
    if (!containerRef.value) return;
    const el = containerRef.value;
    const rect = el.getBoundingClientRect();
    const wh = window.innerHeight;
    const totalH = el.offsetHeight - wh;
    if (totalH <= 0) return;
    const scrolled = -rect.top;
    const raw = Math.max(0, Math.min(1, scrolled / totalH));
    const rotationEnd = 0.6;
    scrollProgress.value = Math.min(1, raw / rotationEnd);
    if (raw > rotationEnd) {
        unlockProgress.value = Math.min(1, (raw - rotationEnd) / (1 - rotationEnd));
    } else {
        unlockProgress.value = 0;
    }
}

// ── INTERSECTION OBSERVER ──
let sectionObserver = null;
onMounted(() => {
    sectionObserver = new IntersectionObserver(
        ([entry]) => { if (entry.isIntersecting) { sectionVisible.value = true; sectionObserver?.disconnect(); } },
        { threshold: 0.15 }
    );
    if (sectionRef.value) sectionObserver.observe(sectionRef.value);
    window.addEventListener("scroll", handleScroll, { passive: true });
    handleScroll();
    if (croquisImageRef.value) {
        if (croquisImageRef.value.complete) { croquisLoaded.value = true; }
        else { croquisImageRef.value.addEventListener("load", () => { croquisLoaded.value = true; }); }
    }
});

onUnmounted(() => {
    sectionObserver?.disconnect();
    window.removeEventListener("scroll", handleScroll);
});

// ── TOGGLE ÁREA ──
function setButtonRef(id, el) { if (el) buttonRefs.value[id] = el; }

function toggleArea(id) {
    if (activeAreaId.value === id) { activeAreaId.value = null; return; }
    activeAreaId.value = id;
    nextTick(() => {
        const btn = buttonRefs.value[id];
        if (!btn) return;
        const r = btn.getBoundingClientRect();
        const cw = 380, ch = 480, gap = 16;
        const vw = window.innerWidth, vh = window.innerHeight;
        let left = r.left + r.width / 2 - cw / 2;
        let top = r.bottom + gap;
        if (left < 16) left = 16;
        if (left + cw > vw - 16) left = vw - cw - 16;
        dropdownAbove.value = false;
        if (top + ch > vh - 16) { top = r.top - ch - gap; dropdownAbove.value = true; }
        if (top < 16) top = 16;
        dropdownStyle.value = { position: "fixed", left: left + "px", top: top + "px", zIndex: 120 };
    });
}

function closeDropdown() { activeAreaId.value = null; }

function onKeydown(e) { if (e.key === "Escape" && activeAreaId.value) closeDropdown(); }

watch(activeAreaId, (val) => {
    if (val) window.addEventListener("keydown", onKeydown);
    else window.removeEventListener("keydown", onKeydown);
});
</script>

<template>
    <div ref="containerRef" class="relative w-full bg-white" style="height: 400vh;">
        <div class="sticky top-0 h-screen w-full overflow-hidden flex flex-col">
            <!-- ── HEADER ── -->
            <div
                ref="sectionRef"
                class="shrink-0 pt-10 sm:pt-14 pb-2 px-4 sm:px-6 text-center transition-all duration-1000 ease-out"
                :class="sectionVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <span class="inline-block text-[10px] sm:text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-3 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.04]">
                    {{ t("companyMap.badge") }}
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-deep-onyx tracking-tight leading-[1.15] max-w-2xl mx-auto">
                    {{ t("companyMap.title") }}<span class="text-brand-blue">{{ t("companyMap.titleHighlight") }}</span>
                </h2>
                <p class="mt-3 text-steel-grey text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                    {{ t("companyMap.subtitle") }}
                </p>
            </div>

            <!-- ── ESCENA 3D ── -->
            <div class="relative flex-1 w-full flex items-center justify-center p-2 sm:p-4 md:p-6 min-h-0">
                <div class="relative w-full max-w-5xl aspect-[16/10] flex items-center justify-center" :style="croquisTransform">
                    <!-- Imagen del croquis con entrada elegante -->
                    <div
                        class="relative w-full h-full"
                        :class="sectionVisible && croquisLoaded ? 'opacity-100 scale-100 blur-0' : 'opacity-0 scale-90 blur-sm'"
                        style="transition: opacity 1.2s cubic-bezier(0.22,1,0.36,1), transform 1.2s cubic-bezier(0.22,1,0.36,1), filter 1s ease;"
                    >
                        <img
                            ref="croquisImageRef"
                            src="/images/croquis_3d_adti.png"
                            alt="Croquis 3D ADTI"
                            class="w-full h-full object-contain select-none"
                            :class="scrollProgress >= 0.95 ? 'drop-shadow-[0_20px_40px_rgba(0,0,0,0.15)]' : 'drop-shadow-[0_8px_16px_rgba(0,0,0,0.08)]'"
                            style="transition: filter 0.8s ease;"
                        />
                    </div>

                    <!-- ── BOTONES FLOTANTES ── -->
                    <TransitionGroup v-if="scrollProgress >= 0.95" name="pill-pop">
                        <div
                            v-for="(area, idx) in areas"
                            :key="area.id"
                            class="absolute z-30 -translate-x-1/2 -translate-y-1/2"
                            :style="{ left: area.x + '%', top: area.y + '%', transitionDelay: (idx * 100) + 'ms' }"
                        >
                            <button
                                :ref="(el) => setButtonRef(area.id, el)"
                                @click.stop="toggleArea(area.id)"
                                class="area-pill group relative flex items-center gap-2 sm:gap-2.5 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full
                                    bg-white/90 hover:bg-white text-deep-onyx
                                    border border-slate-200/60 hover:border-brand-blue/40
                                    shadow-[0_4px_20px_rgba(0,0,0,0.08)] hover:shadow-[0_8px_30px_rgba(0,130,168,0.18)]
                                    backdrop-blur-md transition-all duration-300 hover:scale-110"
                                :class="{ '!bg-brand-blue !text-white !border-brand-blue !shadow-[0_0_24px_rgba(0,130,168,0.35)] !scale-110': activeAreaId === area.id }"
                            >
                                <span
                                    class="flex items-center justify-center size-5 sm:size-6 rounded-full transition-colors duration-300"
                                    :class="activeAreaId === area.id ? 'bg-white/20 text-white' : 'bg-brand-blue/8 text-brand-blue group-hover:bg-brand-blue/15'"
                                >
                                    <svg class="size-3 sm:size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" :d="area.icon" />
                                    </svg>
                                </span>
                                <span class="text-[10px] sm:text-xs font-bold tracking-wide uppercase whitespace-nowrap">
                                    {{ locale === "en" ? area.en.name : area.es.name }}
                                </span>
                                <span class="pill-pulse-ring" :style="{ '--glow': area.glow }" />
                                <span class="pill-pulse-ring-2" :style="{ '--glow': area.glow }" />
                            </button>
                        </div>
                    </TransitionGroup>
                </div>

                <!-- ── DROPDOWN ── -->
                <Teleport to="body">
                    <Transition name="drop-fade">
                        <div v-if="activeAreaId" class="fixed inset-0 z-[115]" @click="closeDropdown" />
                    </Transition>
                    <Transition name="drop-card">
                        <div
                            v-if="activeAreaId && currentArea"
                            :style="dropdownStyle"
                            class="drop-card w-[340px] sm:w-[380px] bg-white rounded-2xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.18)] border border-slate-200/70"
                            @click.stop
                        >
                            <div class="relative h-40 sm:h-48 overflow-hidden bg-slate-100">
                                <img :src="currentArea.image" :alt="currentData.name" class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/10 to-transparent" />
                            </div>
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex items-center justify-center size-8 rounded-xl text-white font-bold shadow-md" :style="{ backgroundColor: currentArea.color }">
                                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" :d="currentArea.icon" />
                                            </svg>
                                        </span>
                                        <h3 class="text-lg sm:text-xl font-black text-deep-onyx tracking-tight">{{ currentData.name }}</h3>
                                    </div>
                                    <button @click="closeDropdown" class="size-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-steel-grey hover:text-deep-onyx transition-colors">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-steel-grey leading-relaxed">{{ currentData.desc }}</p>
                            </div>
                        </div>
                    </Transition>
                </Teleport>
            </div>

            <!-- ── BARRA INFERIOR DE DESBLOQUEO ── -->
            <div
                class="shrink-0 w-full transition-all duration-500 ease-out"
                :class="showUnlockBar ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4 pointer-events-none'"
            >
                <div class="text-center pb-2">
                    <p class="text-xs sm:text-sm font-semibold text-steel-grey tracking-wide">
                        {{ unlockProgress >= 1 ? t("companyMap.unlockDone") : t("companyMap.unlockBar") }}
                    </p>
                </div>
                <div class="relative mx-auto max-w-xs sm:max-w-sm h-2 rounded-full bg-slate-200/80 overflow-hidden">
                    <div
                        class="absolute inset-y-0 left-0 rounded-full transition-all duration-150 ease-out"
                        :class="unlockProgress >= 1 ? 'bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.5)]' : 'bg-gradient-to-r from-brand-blue to-electric-cyan'"
                        :style="{ width: (unlockProgress * 100) + '%' }"
                    />
                </div>
                <div class="flex justify-center pt-2 pb-4">
                    <div
                        class="transition-all duration-500"
                        :class="unlockProgress >= 1 ? 'text-emerald-500 scale-110' : 'text-steel-grey/50 animate-bounce'"
                    >
                        <svg v-if="unlockProgress >= 1" class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7 7 7-7" />
                        </svg>
                        <svg v-else class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* ── Botones pop-in con rebote escalonado ── */
.pill-pop-enter-active {
    animation: pill-bounce-in 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.pill-pop-leave-active { transition: all 0.3s ease-in; }
.pill-pop-leave-to { opacity: 0; transform: translate(-50%, -50%) scale(0.5); }

@keyframes pill-bounce-in {
    0%   { opacity: 0; transform: translate(-50%, -50%) scale(0) rotate(-15deg); }
    45%  { opacity: 1; transform: translate(-50%, -50%) scale(1.18) rotate(3deg); }
    65%  { transform: translate(-50%, -50%) scale(0.92) rotate(-1deg); }
    80%  { transform: translate(-50%, -50%) scale(1.04) rotate(0.5deg); }
    100% { opacity: 1; transform: translate(-50%, -50%) scale(1) rotate(0deg); }
}

/* ── Anillos pulsantes ── */
.area-pill { position: relative; }
.pill-pulse-ring, .pill-pulse-ring-2 {
    position: absolute; inset: -4px; border-radius: 999px;
    border: 1.5px solid var(--glow); opacity: 0; pointer-events: none;
    animation: pill-pulse 2.2s ease-out infinite;
}
.pill-pulse-ring-2 { animation-delay: 1.1s; }
@keyframes pill-pulse {
    0% { transform: scale(1); opacity: 0.5; }
    100% { transform: scale(1.25); opacity: 0; }
}

/* ── Dropdown ── */
.drop-fade-enter-active, .drop-fade-leave-active { transition: opacity 0.25s ease; }
.drop-fade-enter-from, .drop-fade-leave-to { opacity: 0; }
.drop-card-enter-active { transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); }
.drop-card-leave-active { transition: all 0.2s ease-in; }
.drop-card-enter-from { opacity: 0; transform: scale(0.85) translateY(16px); }
.drop-card-leave-to { opacity: 0; transform: scale(0.9) translateY(8px); }
</style>
