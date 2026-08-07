<script setup>
import { ref, onMounted } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();
const page = usePage();

const form = useForm({
    nombre: '',
    telefono: '',
    correo: '',
    descripcion: '',
});

const submitted = ref(false);
const sectionRevealed = ref(false);
const fieldRevealed = ref([false, false, false, false]);

onMounted(() => {
    // Check for flash success
    if (page.props.flash?.contact_success) {
        submitted.value = true;
        setTimeout(() => { submitted.value = false; }, 6000);
    }

    const observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                sectionRevealed.value = true;
                // Staggered field reveals
                [400, 600, 800, 1000].forEach((delay, i) => {
                    setTimeout(() => { fieldRevealed.value[i] = true; }, delay);
                });
            }
        },
        { threshold: 0.1 }
    );
    const el = document.getElementById('contact-form-section');
    if (el) observer.observe(el);
});

function submit() {
    form.post(route('contacto.store'), {
        preserveScroll: true,
        onSuccess: () => {
            submitted.value = true;
            form.reset();
            setTimeout(() => { submitted.value = false; }, 6000);
        },
    });
}
</script>

<template>
    <section
        id="contact-form-section"
        class="relative py-24 sm:py-32 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-[#F5F0E8] to-white overflow-hidden"
    >
        <!-- Decorative background elements -->
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-blue/20 to-transparent" />
        <div class="absolute top-20 left-[10%] size-64 rounded-full bg-brand-blue/[0.03] blur-3xl pointer-events-none" />
        <div class="absolute bottom-20 right-[5%] size-80 rounded-full bg-brand-blue/[0.04] blur-3xl pointer-events-none" />
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 size-[600px] rounded-full bg-gradient-to-br from-brand-blue/[0.02] to-transparent blur-3xl pointer-events-none" />

        <!-- Decorative dots grid -->
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
            style="background-image: radial-gradient(circle, #0082A8 1px, transparent 1px); background-size: 40px 40px;"
        />
        <div
            class="max-w-2xl mx-auto transition-all duration-800 ease-out"
            :class="sectionRevealed ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12'"
        >
            <!-- Header -->
            <div class="text-center mb-12">
                <span class="inline-block text-xs uppercase tracking-[0.3em] text-brand-blue font-semibold mb-4 px-4 py-1.5 rounded-full border border-brand-blue/20 bg-brand-blue/[0.04]">
                    {{ locale === 'en' ? 'Get in Touch' : 'Contáctanos' }}
                </span>
                <h2 class="text-4xl sm:text-5xl font-black text-deep-onyx tracking-tight leading-[1.1]">
                    {{ locale === 'en' ? 'Start Your' : 'Inicia tu' }}
                    <span class="text-brand-blue">{{ locale === 'en' ? 'Project' : 'Proyecto' }}</span>
                </h2>
                <p class="mt-4 text-steel-grey max-w-md mx-auto">
                    {{ locale === 'en' ? 'Tell us about your needs and we\'ll get back to you within 24 hours.' : 'Cuéntanos sobre tus necesidades y te contactaremos en menos de 24 horas.' }}
                </p>
            </div>

            <!-- Success message -->
            <Transition
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="opacity-0 -translate-y-4 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition duration-400 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 -translate-y-4 scale-95"
            >
                <div
                    v-if="submitted"
                    class="mb-8 px-6 py-4 rounded-2xl bg-brand-blue/10 border border-brand-blue/20 flex items-center gap-3"
                >
                    <div class="size-8 rounded-full bg-brand-blue flex items-center justify-center shrink-0">
                        <svg class="size-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-brand-blue">
                        {{ locale === 'en' ? 'Message sent successfully! We\'ll contact you soon.' : '¡Mensaje enviado con éxito! Te contactaremos pronto.' }}
                    </p>
                </div>
            </Transition>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Nombre -->
                <div
                    :class="fieldRevealed[0] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);"
                >
                    <label for="nombre" class="block text-xs uppercase tracking-[0.15em] text-steel-grey font-semibold mb-2">
                        {{ locale === 'en' ? 'Full Name' : 'Nombre Completo' }}
                    </label>
                    <div class="relative">
                        <input
                            id="nombre"
                            v-model="form.nombre"
                            type="text"
                            :placeholder="locale === 'en' ? 'John Doe' : 'Juan Pérez'"
                            class="w-full px-5 py-4 bg-white rounded-2xl border text-deep-onyx placeholder:text-steel-grey/30 outline-none transition-all duration-300"
                            :class="form.errors.nombre
                                ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
                                : 'border-gray-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/15'"
                        />
                    </div>
                    <p v-if="form.errors.nombre" class="mt-1.5 text-xs text-red-500 font-medium">{{ form.errors.nombre }}</p>
                </div>

                <!-- Teléfono + Correo -->
                <div
                    class="grid sm:grid-cols-2 gap-6"
                    :class="fieldRevealed[1] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);"
                >
                    <div>
                        <label for="telefono" class="block text-xs uppercase tracking-[0.15em] text-steel-grey font-semibold mb-2">
                            {{ locale === 'en' ? 'Phone' : 'Teléfono' }}
                        </label>
                        <input
                            id="telefono"
                            v-model="form.telefono"
                            type="tel"
                            :placeholder="locale === 'en' ? '+1 555 000 0000' : '+52 55 0000 0000'"
                            class="w-full px-5 py-4 bg-white rounded-2xl border text-deep-onyx placeholder:text-steel-grey/30 outline-none transition-all duration-300"
                            :class="form.errors.telefono
                                ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
                                : 'border-gray-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/15'"
                        />
                        <p v-if="form.errors.telefono" class="mt-1.5 text-xs text-red-500 font-medium">{{ form.errors.telefono }}</p>
                    </div>
                    <div>
                        <label for="correo" class="block text-xs uppercase tracking-[0.15em] text-steel-grey font-semibold mb-2">
                            {{ locale === 'en' ? 'Email' : 'Correo Electrónico' }}
                        </label>
                        <input
                            id="correo"
                            v-model="form.correo"
                            type="email"
                            :placeholder="locale === 'en' ? 'john@company.com' : 'juan@empresa.com'"
                            class="w-full px-5 py-4 bg-white rounded-2xl border text-deep-onyx placeholder:text-steel-grey/30 outline-none transition-all duration-300"
                            :class="form.errors.correo
                                ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
                                : 'border-gray-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/15'"
                        />
                        <p v-if="form.errors.correo" class="mt-1.5 text-xs text-red-500 font-medium">{{ form.errors.correo }}</p>
                    </div>
                </div>

                <!-- Descripción -->
                <div
                    :class="fieldRevealed[2] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);"
                >
                    <label for="descripcion" class="block text-xs uppercase tracking-[0.15em] text-steel-grey font-semibold mb-2">
                        {{ locale === 'en' ? 'Description' : 'Descripción' }}
                    </label>
                    <textarea
                        id="descripcion"
                        v-model="form.descripcion"
                        rows="5"
                        :placeholder="locale === 'en' ? 'Describe your project or requirements...' : 'Describe tu proyecto o requerimientos...'"
                        class="w-full px-5 py-4 bg-white rounded-2xl border text-deep-onyx placeholder:text-steel-grey/30 outline-none resize-none transition-all duration-300"
                        :class="form.errors.descripcion
                            ? 'border-red-400 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
                            : 'border-gray-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/15'"
                    />
                    <p v-if="form.errors.descripcion" class="mt-1.5 text-xs text-red-500 font-medium">{{ form.errors.descripcion }}</p>
                </div>

                <!-- Submit -->
                <div
                    :class="fieldRevealed[3] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                    style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);"
                >
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full flex items-center justify-center gap-3 bg-brand-blue hover:bg-brand-blue/90 text-white font-bold text-base py-5 rounded-2xl transition-all duration-300 hover:shadow-[0_8px_30px_rgba(0,130,168,0.35)] hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                >
                    <span v-if="form.processing" class="size-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    <span v-else>
                        {{ locale === 'en' ? 'Send Message' : 'Enviar Mensaje' }}
                    </span>
                    <svg v-if="!form.processing" class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
                </div>
            </form>
        </div>
    </section>
</template>
