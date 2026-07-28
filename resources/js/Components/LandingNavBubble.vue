<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const menuOpen = ref(false);
const lang = ref('ES');

const page = usePage();

const routeLabels = {
    'Landing/Splash': 'Inicio',
    'Landing/Inicio': 'Inicio',
    'Landing/Servicios': 'Servicios',
    'Landing/Proyectos': 'Proyectos',
    'Landing/Nosotros': 'Nosotros',
    'Landing/Contacto': 'Contacto',
};

const currentPageLabel = computed(() => {
    const component = page.component;
    return routeLabels[component] || component || 'Menú';
});

const menuLinks = [
    { label: 'Inicio', route: 'inicio' },
    { label: 'Servicios', route: 'servicios' },
    { label: 'Proyectos', route: 'proyectos' },
    { label: 'Nosotros', route: 'nosotros' },
    { label: 'Contacto', route: 'contacto' },
];

// Separamos los enlaces principales del botón de acción final (Contacto)
const mainLinks = computed(() => menuLinks.slice(0, -1));
const contactLink = computed(() => menuLinks[menuLinks.length - 1]);

function toggleMenu() {
    menuOpen.value = !menuOpen.value;
}

function closeMenu() {
    menuOpen.value = false;
}

function toggleLang() {
    lang.value = lang.value === 'ES' ? 'EN' : 'ES';
}
</script>

<template>
    <div class="z-50 flex flex-col items-end gap-3">
        
        <!-- Menú Abierto -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-4 scale-95"
        >
            <div
                v-if="menuOpen"
                class="-bottom-52 bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.1)] w-64 md:w-80 relative overflow-hidden flex flex-col"
            >
                <!-- Botón Cerrar -->
                <button
                    @click="closeMenu"
                    class="absolute top-5 right-5 size-10 rounded-full border border-gray-200 hover:border-red-300 flex items-center justify-center text-gray-400 hover:text-red-700 hover:bg-red-200 hover:scale-110 transition-all ease-linear duration-200 z-10"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Lista de Enlaces -->
                <nav class="px-6 pt-12 pb-4 flex-1">
                    <div
                        v-for="(link, i) in mainLinks"
                        :key="link.route"
                        class="border-b border-dashed border-gray-200 last:border-0"
                    >
                        <Link
                            :href="route(link.route)"
                            @click="closeMenu"
                            class="flex items-center gap-4 py-4 group"
                        >
                            <span class="size-10 rounded-full bg-gray-50 flex items-center justify-center text-xs font-bold text-gray-500 group-hover:bg-gray-100 group-hover:translate-x-4 transition-all ease-linear duration-200">
                                {{ i + 1 }}
                            </span>
                            <span class="text-[#7d8d97] font-semibold text-lg group-hover:text-brand-blue group-hover:translate-x-4 transition-all ease-linear duration-200">
                                {{ link.label }}
                            </span>
                        </Link>
                    </div>
                </nav>

                <!-- Selector de Idioma y Botón de Contacto -->
                <div class="px-3 pb-3">
                    <div class="px-4 py-3 flex items-center justify-between border-t border-gray-100 mb-2">
                        <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">Idioma</span>
                        <button
                            type="button"
                            @click="toggleLang"
                            class="text-sm font-bold tracking-wide"
                        >
                            <span :class="lang === 'ES' ? 'text-[#13495e]' : 'text-gray-300'">ES</span>
                            <span class="text-gray-200 mx-1">/</span>
                            <span :class="lang === 'EN' ? 'text-[#13495e]' : 'text-gray-300'">EN</span>
                        </button>
                    </div>

                    <Link
                        :href="route(contactLink.route)"
                        @click="closeMenu"
                        class="flex items-center justify-center gap-3 w-full bg-[#3c6185] hover:bg-brand-blue  text-[1a334b] font-bold text-lg py-4 rounded-2xl transition-colors"
                    >
                        <div class="bg-[#0d273f] rounded-full p-1.5 flex items-center justify-center text-brand-blue">
                             <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                 <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                             </svg>
                        </div>
                        {{ contactLink.label }}
                    </Link>
                </div>
            </div>
        </Transition>

        <!-- Botón Cerrado (FAB) -->
        <button
            v-if="!menuOpen"
            type="button"
            @click="toggleMenu"
            class="group flex items-center justify-between gap-4 bg-white pl-7 pr-2 py-2 rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.08)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.12)] transition-all duration-300 ease-out"
        >
            <span class="text-brand-blue font-bold text-xl tracking-tight">{{ currentPageLabel }}</span>
            <div class="size-10 rounded-full border text-black border-gray-200 group-hover:bg-brand-blue group-hover:scale-125 group-hover:text-white flex flex-col items-center justify-center gap-1.5 transition-all ease-linear duration-200">
                <span class="w-4 h-[1px] bg-current"></span>
                <span class="w-4 h-[1px] bg-current"></span>
            </div>
        </button>
    </div>
</template>