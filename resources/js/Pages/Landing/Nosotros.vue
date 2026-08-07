<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';
import CompanyMap3D from '@/Components/CompanyMap3D.vue';

defineOptions({ layout: LandingAppLayout });

const { t, locale } = useI18n();

// ── STATE ──
const mounted = ref(false);
const videoLoaded = ref(false);
const revealedSections = ref({});

// ── CONTENT ──
const mission = {
    es: {
        title: 'Nuestra Misión',
        desc: 'Impulsar la productividad de la industria en las Américas mediante soluciones de automatización y control de vanguardia, integrando ingeniería de precisión, tecnología avanzada y un servicio excepcional que supere las expectativas de nuestros clientes.',
    },
    en: {
        title: 'Our Mission',
        desc: 'Drive productivity across the Americas\' industry through cutting-edge automation and control solutions, integrating precision engineering, advanced technology, and exceptional service that exceeds our clients\' expectations.',
    },
};

const vision = {
    es: {
        title: 'Nuestra Visión',
        desc: 'Ser el referente número uno en automatización industrial en las Américas, reconocidos por transformar fábricas tradicionales en ecosistemas inteligentes, eficientes y sostenibles que marquen el estándar de la Industria 4.0.',
    },
    en: {
        title: 'Our Vision',
        desc: 'Become the number one benchmark in industrial automation across the Americas, recognized for transforming traditional factories into intelligent, efficient, and sustainable ecosystems that set the standard for Industry 4.0.',
    },
};

const values = [
    {
        icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
        color: '#0082A8',
        es: { title: 'Calidad', desc: 'Cada proyecto cumple con los más altos estándares internacionales. No entregamos hasta que sea perfecto.' },
        en: { title: 'Quality', desc: 'Every project meets the highest international standards. We don\'t deliver until it\'s perfect.' },
    },
    {
        icon: 'M13 10V3L4 14h7v7l9-11h-7z',
        color: '#00D4FF',
        es: { title: 'Innovación', desc: 'Nos mantenemos a la vanguardia tecnológica. Investigamos, probamos y aplicamos lo último en automatización.' },
        en: { title: 'Innovation', desc: 'We stay at the technological forefront. We research, test, and apply the latest in automation.' },
    },
    {
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        color: '#6D6E71',
        es: { title: 'Compromiso', desc: 'Con nuestros clientes, colaboradores y comunidad. Cada relación es una alianza de largo plazo.' },
        en: { title: 'Commitment', desc: 'To our clients, collaborators, and community. Every relationship is a long-term partnership.' },
    },
    {
        icon: 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
        color: '#0F172A',
        es: { title: 'Adaptabilidad', desc: 'Cada industria es diferente. Diseñamos soluciones a la medida, sin fórmulas genéricas ni atajos.' },
        en: { title: 'Adaptability', desc: 'Every industry is different. We design tailor-made solutions — no generic formulas or shortcuts.' },
    },
];

// ── SCROLL REVEAL ──
onMounted(() => {
    requestAnimationFrame(() => { mounted.value = true; });

    nextTick(() => {
        const sections = document.querySelectorAll('.reveal-section');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const id = entry.target.dataset.sectionId;
                    if (id) revealedSections.value[id] = true;
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });

        sections.forEach(el => observer.observe(el));
    });
});
</script>

<template>
    <Head :title="t('pages.about.title')" />

    <div class="min-h-screen bg-white">

        <!-- ═══════════════════════════════════════════ -->
        <!-- VIDEO HERO HEADER                           -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="relative w-full h-[70vh] sm:h-[80vh] overflow-hidden">
            <!-- Background video -->
            <video
                autoplay
                muted
                loop
                playsinline
                class="absolute inset-0 w-full h-full object-cover"
                @loadeddata="videoLoaded = true"
            >
                <source src="/videos/video-robot-arms.mp4" type="video/mp4" />
            </video>

            <!-- Overlay gradient -->
            <div class="absolute inset-0 bg-gradient-to-b from-deep-onyx/70 via-deep-onyx/40 to-deep-onyx/80" />
            <div class="absolute inset-0 bg-gradient-to-r from-deep-onyx/50 via-transparent to-deep-onyx/50" />

            <!-- Pattern overlay -->
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(circle at 30% 50%, rgba(0,212,255,0.4) 1px, transparent 1px); background-size: 32px 32px;"
            />

            <!-- Content -->
            <div
                class="relative z-10 h-full flex flex-col items-center justify-center text-center px-4 sm:px-6 transition-all duration-1000 ease-out"
                :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
            >
                <span class="inline-block text-xs sm:text-sm uppercase tracking-[0.3em] text-electric-cyan font-semibold mb-6 px-5 py-2 rounded-full border border-electric-cyan/30 bg-electric-cyan/[0.06] backdrop-blur-sm">
                    {{ locale === 'en' ? 'About ADTI' : 'Acerca de ADTI' }}
                </span>
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-[1.05] max-w-4xl">
                    {{ locale === 'en' ? 'Engineering the ' : 'Ingeniería que ' }}
                    <span class="text-electric-cyan">{{ locale === 'en' ? 'future of industry' : 'impulsa la industria' }}</span>
                </h1>
                <p class="mt-6 max-w-2xl text-base sm:text-lg text-white/60 leading-relaxed">
                    {{ locale === 'en'
                        ? 'We are a team of engineers, designers, and technologists united by a single purpose: transforming industrial processes into intelligent, efficient, and reliable systems.'
                        : 'Somos un equipo de ingenieros, diseñadores y tecnólogos unidos por un solo propósito: transformar procesos industriales en sistemas inteligentes, eficientes y confiables.'
                    }}
                </p>

                <!-- Scroll indicator -->
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
                    <span class="text-[10px] uppercase tracking-[0.3em] text-white/40 font-semibold">
                        {{ locale === 'en' ? 'Scroll' : 'Desliza' }}
                    </span>
                    <svg class="size-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- MISSION + VISION                              -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20">

                <!-- Mission -->
                <div
                    data-section-id="mission"
                    class="reveal-section group/mv relative overflow-hidden rounded-3xl p-8 sm:p-10 bg-gradient-to-br from-deep-onyx to-[#0d2137] transition-all duration-800 ease-out"
                    :class="revealedSections['mission'] ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'"
                    style="transition-duration: 800ms; transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
                >
                    <div class="absolute top-0 right-0 w-48 h-48 bg-brand-blue/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2" />
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex items-center justify-center size-10 rounded-xl bg-brand-blue/15 text-brand-blue">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                                </svg>
                            </span>
                            <span class="text-xs uppercase tracking-[0.25em] text-brand-blue font-bold">
                                {{ locale === 'en' ? mission.en.title : mission.es.title }}
                            </span>
                        </div>
                        <p class="text-white/80 text-lg leading-relaxed">
                            {{ locale === 'en' ? mission.en.desc : mission.es.desc }}
                        </p>
                    </div>
                </div>

                <!-- Vision -->
                <div
                    data-section-id="vision"
                    class="reveal-section group/mv relative overflow-hidden rounded-3xl p-8 sm:p-10 bg-gradient-to-br from-[#0a1a2e] to-deep-onyx border border-electric-cyan/10 transition-all duration-800 ease-out"
                    :class="revealedSections['vision'] ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'"
                    style="transition-duration: 800ms; transition-delay: 150ms; transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
                >
                    <div class="absolute top-0 right-0 w-48 h-48 bg-electric-cyan/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2" />
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex items-center justify-center size-10 rounded-xl bg-electric-cyan/15 text-electric-cyan">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <span class="text-xs uppercase tracking-[0.25em] text-electric-cyan font-bold">
                                {{ locale === 'en' ? vision.en.title : vision.es.title }}
                            </span>
                        </div>
                        <p class="text-white/80 text-lg leading-relaxed">
                            {{ locale === 'en' ? vision.en.desc : vision.es.desc }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- VALUES                                        -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 sm:pb-28">
            <div
                data-section-id="values-header"
                class="reveal-section text-center mb-16 transition-all duration-700 ease-out"
                :class="revealedSections['values-header'] ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
            >
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.04]">
                    {{ locale === 'en' ? 'Our Values' : 'Nuestros Valores' }}
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-deep-onyx tracking-tight leading-[1.15] max-w-2xl mx-auto">
                    {{ locale === 'en' ? 'The principles that ' : 'Los principios que ' }}
                    <span class="text-brand-blue">{{ locale === 'en' ? 'define us' : 'nos definen' }}</span>
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    v-for="(val, idx) in values"
                    :key="idx"
                    :data-section-id="'value-' + idx"
                    class="reveal-section group/val relative bg-white rounded-2xl p-6 sm:p-8 border border-gray-100 hover:border-brand-blue/20
                        shadow-[0_2px_12px_rgba(0,0,0,0.03)] hover:shadow-[0_12px_40px_rgba(0,130,168,0.08)]
                        transition-all duration-500 ease-out cursor-default"
                    :class="revealedSections['value-' + idx] ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
                    :style="{
                        transitionDelay: (idx * 100) + 'ms',
                        transitionDuration: '700ms',
                        transitionTimingFunction: 'cubic-bezier(0.22, 1, 0.36, 1)',
                    }"
                >
                    <div class="flex flex-col items-center text-center gap-5">
                        <div
                            class="relative flex items-center justify-center size-16 rounded-2xl transition-all duration-500 group-hover/val:scale-110 group-hover/val:shadow-lg"
                            :style="{ backgroundColor: val.color + '12', color: val.color }"
                        >
                            <svg class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="val.icon" />
                            </svg>
                            <div class="absolute inset-0 rounded-2xl opacity-0 group-hover/val:opacity-100 transition-opacity duration-500"
                                :style="{ boxShadow: '0 0 30px ' + val.color + '20' }"
                            />
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-deep-onyx tracking-tight mb-2">
                                {{ locale === 'en' ? val.en.title : val.es.title }}
                            </h3>
                            <p class="text-sm text-steel-grey leading-relaxed">
                                {{ locale === 'en' ? val.en.desc : val.es.desc }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- COMPANY MAP 3D                                -->
        <!-- ═══════════════════════════════════════════ -->
        <div
            data-section-id="map"
            class="reveal-section transition-all duration-800 ease-out"
            :class="revealedSections['map'] ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'"
            style="transition-duration: 800ms; transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
        >
            <CompanyMap3D />
        </div>

        <!-- ═══════════════════════════════════════════ -->
        <!-- CTA                                           -->
        <!-- ═══════════════════════════════════════════ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 sm:pb-28">
            <div
                data-section-id="cta"
                class="reveal-section text-center py-14 px-8 rounded-[2.5rem] bg-gradient-to-br from-deep-onyx to-[#0d2137] transition-all duration-800 ease-out"
                :class="revealedSections['cta'] ? 'translate-y-0 opacity-100 scale-100' : 'translate-y-8 opacity-0 scale-95'"
                style="transition-duration: 800ms; transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
            >
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight mb-3">
                    {{ locale === 'en' ? 'Let\'s build the future together' : 'Construyamos el futuro juntos' }}
                </h3>
                <p class="text-steel-grey/50 mb-8 max-w-lg mx-auto text-base sm:text-lg">
                    {{ locale === 'en'
                        ? 'Every great project starts with a conversation. Tell us about your vision and let\'s make it happen.'
                        : 'Cada gran proyecto empieza con una conversación. Cuéntanos tu visión y hagámosla realidad.'
                    }}
                </p>
                <Link
                    :href="route('contacto')"
                    class="inline-flex items-center gap-3 px-10 py-4 rounded-full bg-brand-blue hover:bg-brand-blue/90 text-white font-bold text-base
                        hover:shadow-[0_8px_30px_rgba(0,130,168,0.4)] hover:-translate-y-0.5
                        transition-all duration-300 ease-out group"
                >
                    {{ locale === 'en' ? 'Contact Us' : 'Contáctanos' }}
                    <svg class="size-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
            </div>
        </div>

    </div>
</template>

<style scoped>
/* ── Smooth scroll behavior ── */
html {
    scroll-behavior: smooth;
}
</style>
