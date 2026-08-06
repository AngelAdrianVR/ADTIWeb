<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';
import ContactForm from '@/Components/ContactForm.vue';

defineOptions({ layout: LandingAppLayout });

const { t, locale } = useI18n();

// ── STATE ──
const mounted = ref(false);
const videoLoaded = ref(false);
const revealedSections = ref({});

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
    <Head :title="t('pages.contact.title')" />

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
                    {{ locale === 'en' ? 'Get in Touch' : 'Contáctanos' }}
                </span>
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-[1.05] max-w-4xl">
                    {{ locale === 'en' ? 'Let\'s Build the ' : 'Construyamos el ' }}
                    <span class="text-electric-cyan">{{ locale === 'en' ? 'Future' : 'Futuro' }}</span>
                </h1>
                <p class="mt-6 max-w-2xl text-base sm:text-lg text-white/60 leading-relaxed">
                    {{ locale === 'en'
                        ? 'Tell us about your project and discover how we can transform your industrial operation with cutting-edge automation.'
                        : 'Cuéntanos sobre tu proyecto y descubre cómo podemos transformar tu operación industrial con automatización de vanguardia.'
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
        <!-- CONTACT INFO + FORM                          -->
        <!-- ═══════════════════════════════════════════ -->
        <section class="relative bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
                <div class="grid lg:grid-cols-5 gap-16 lg:gap-20">

                    <!-- ── Left: Contact Cards ── -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Header -->
                        <div
                            data-section-id="info-header"
                            class="reveal-section transition-all duration-800 ease-out"
                            :class="revealedSections['info-header'] ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
                            style="transition-duration: 700ms; transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
                        >
                            <span class="inline-block text-xs uppercase tracking-[0.25em] text-brand-blue font-semibold mb-3 px-4 py-1.5 rounded-full border border-brand-blue/15 bg-brand-blue/[0.04]">
                                {{ locale === 'en' ? 'Reach Us' : 'Contáctanos' }}
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-black text-deep-onyx tracking-tight leading-[1.15] mt-4">
                                {{ locale === 'en' ? 'Direct' : 'Canales' }}
                                <span class="text-brand-blue">{{ locale === 'en' ? 'Channels' : 'Directos' }}</span>
                            </h2>
                            <p class="mt-3 text-steel-grey text-sm leading-relaxed">
                                {{ locale === 'en' ? 'Choose the channel that suits you best. We\'re ready to help.' : 'Elige el canal que más te convenga. Estamos listos para ayudarte.' }}
                            </p>
                        </div>

                        <!-- Phone Card -->
                        <a
                            href="tel:+523336985016"
                            data-section-id="card-phone"
                            class="reveal-section group block p-6 rounded-2xl border border-gray-100 bg-white hover:bg-brand-blue/[0.02] hover:border-brand-blue/20 transition-all duration-400 hover:shadow-[0_12px_40px_rgba(0,130,168,0.08)] hover:-translate-y-0.5"
                            :class="revealedSections['card-phone'] ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
                            style="transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1); transition-delay: 100ms;"
                        >
                            <div class="flex items-start gap-5">
                                <div class="shrink-0 size-12 rounded-xl bg-brand-blue/[0.06] flex items-center justify-center group-hover:bg-brand-blue group-hover:scale-105 transition-all duration-400">
                                    <svg class="size-5 text-brand-blue group-hover:text-white transition-colors duration-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-[0.15em] text-steel-grey font-semibold mb-1">
                                        {{ locale === 'en' ? 'Phone' : 'Teléfono' }}
                                    </p>
                                    <p class="text-base font-bold text-deep-onyx group-hover:text-brand-blue transition-colors duration-300">
                                        +52 33 3698 5016
                                    </p>
                                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-brand-blue opacity-0 group-hover:opacity-100 transition-all duration-400 translate-x-0 group-hover:translate-x-1">
                                        {{ locale === 'en' ? 'Call now' : 'Llamar ahora' }}
                                        <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Email Card -->
                        <a
                            href="mailto:info@adti.com.mx"
                            data-section-id="card-email"
                            class="reveal-section group block p-6 rounded-2xl border border-gray-100 bg-white hover:bg-brand-blue/[0.02] hover:border-brand-blue/20 transition-all duration-400 hover:shadow-[0_12px_40px_rgba(0,130,168,0.08)] hover:-translate-y-0.5"
                            :class="revealedSections['card-email'] ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
                            style="transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1); transition-delay: 200ms;"
                        >
                            <div class="flex items-start gap-5">
                                <div class="shrink-0 size-12 rounded-xl bg-brand-blue/[0.06] flex items-center justify-center group-hover:bg-brand-blue group-hover:scale-105 transition-all duration-400">
                                    <svg class="size-5 text-brand-blue group-hover:text-white transition-colors duration-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-[0.15em] text-steel-grey font-semibold mb-1">
                                        {{ locale === 'en' ? 'Email' : 'Correo Electrónico' }}
                                    </p>
                                    <p class="text-base font-bold text-deep-onyx group-hover:text-brand-blue transition-colors duration-300 break-all">
                                        info@adti.com.mx
                                    </p>
                                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-brand-blue opacity-0 group-hover:opacity-100 transition-all duration-400 translate-x-0 group-hover:translate-x-1">
                                        {{ locale === 'en' ? 'Send email' : 'Enviar correo' }}
                                        <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Address Card -->
                        <a
                            href="https://maps.google.com/?q=Calzada+Federalismo+Norte+1971+Guadalajara+Jalisco"
                            target="_blank"
                            rel="noopener"
                            data-section-id="card-address"
                            class="reveal-section group block p-6 rounded-2xl border border-gray-100 bg-white hover:bg-brand-blue/[0.02] hover:border-brand-blue/20 transition-all duration-400 hover:shadow-[0_12px_40px_rgba(0,130,168,0.08)] hover:-translate-y-0.5"
                            :class="revealedSections['card-address'] ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
                            style="transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1); transition-delay: 300ms;"
                        >
                            <div class="flex items-start gap-5">
                                <div class="shrink-0 size-12 rounded-xl bg-brand-blue/[0.06] flex items-center justify-center group-hover:bg-brand-blue group-hover:scale-105 transition-all duration-400">
                                    <svg class="size-5 text-brand-blue group-hover:text-white transition-colors duration-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-[0.15em] text-steel-grey font-semibold mb-1">
                                        {{ locale === 'en' ? 'Address' : 'Dirección' }}
                                    </p>
                                    <p class="text-sm font-bold text-deep-onyx group-hover:text-brand-blue transition-colors duration-300 leading-relaxed">
                                        Calzada Federalismo Norte N. 1971<br />
                                        Jardines del Country, 44210<br />
                                        Guadalajara, Jalisco, México
                                    </p>
                                    <span class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-brand-blue opacity-0 group-hover:opacity-100 transition-all duration-400 translate-x-0 group-hover:translate-x-1">
                                        {{ locale === 'en' ? 'Open in Maps' : 'Abrir en Maps' }}
                                        <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- ── Right: Form ── -->
                    <div
                        data-section-id="form"
                        class="reveal-section lg:col-span-3 transition-all duration-800 ease-out"
                        :class="revealedSections['form'] ? 'translate-y-0 opacity-100' : 'translate-y-12 opacity-0'"
                        style="transition-duration: 800ms; transition-delay: 200ms; transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
                    >
                        <ContactForm />
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════ -->
        <!-- GOOGLE MAPS                                  -->
        <!-- ═══════════════════════════════════════════ -->
        <section class="relative bg-[#F5F0E8] overflow-hidden">
            <!-- Decorative top line -->
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-blue/15 to-transparent" />

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 lg:pt-24 pb-8">
                <div
                    data-section-id="map-header"
                    class="reveal-section text-center mb-10 transition-all duration-700 ease-out"
                    :class="revealedSections['map-header'] ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'"
                    style="transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
                >
                    <span class="inline-block text-xs uppercase tracking-[0.25em] text-brand-blue font-semibold mb-3 px-4 py-1.5 rounded-full border border-brand-blue/15 bg-brand-blue/[0.04]">
                        {{ locale === 'en' ? 'Visit Us' : 'Visítanos' }}
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-deep-onyx tracking-tight leading-[1.15] mt-3">
                        {{ locale === 'en' ? 'Our' : 'Nuestra' }}
                        <span class="text-brand-blue">{{ locale === 'en' ? 'Location' : 'Ubicación' }}</span>
                    </h2>
                </div>
            </div>

            <!-- Map container -->
            <div
                data-section-id="map"
                class="reveal-section relative w-full h-0 pb-[40%] lg:pb-[35%] min-h-[350px] transition-all duration-800 ease-out"
                :class="revealedSections['map'] ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'"
                style="transition-duration: 900ms; transition-timing-function: cubic-bezier(0.22, 1, 0.36, 1);"
            >
                <div class="absolute inset-0 pointer-events-none z-10 rounded-t-[2.5rem] shadow-[inset_0_1px_0_rgba(0,130,168,0.08)]" />
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3732.476323098339!2d-103.3725!3d20.6908!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8428ae3b1b3b1b1b%3A0x1b1b1b1b1b1b1b1b!2sCalzada%20Federalismo%20Nte.%201971%2C%20Jardines%20del%20Country%2C%2044210%20Guadalajara%2C%20Jal.!5e0!3m2!1sen!2smx!4v1690000000000"
                    class="absolute inset-0 w-full h-full"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                />
            </div>
        </section>

    </div>
</template>
