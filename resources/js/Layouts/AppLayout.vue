<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    title: String,
});

const sidebarOpen = ref(false);

const logout = () => {
    router.post(route('logout'));
};

const adminNav = [
    { label: 'Dashboard', route: 'dashboard', icon: 'M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5' },
    { label: 'Servicios', route: 'admin.servicios.index', icon: 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z' },
    { label: 'Proyectos', route: 'admin.proyectos.index', icon: 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z' },
    { label: 'Certificaciones', route: 'admin.certificaciones.index', icon: 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z' },
];
</script>

<template>
    <div class="min-h-screen flex bg-[#F4F8FA]">
        <Head :title="title" />
        <Banner />

        <!-- ═══════ Mobile overlay ═══════ -->
        <Transition name="overlay">
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
                @click="sidebarOpen = false"
            />
        </Transition>

        <!-- ═══════ Sidebar ═══════ -->
        <aside
            class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-[#031120] text-white flex flex-col shrink-0 transition-transform duration-300 ease-[cubic-bezier(0.23,1,0.32,1)]"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="px-6 py-6 border-b border-white/[0.06] flex items-center justify-between">
                <Link :href="route('dashboard')" class="block">
                    <img draggable="false" class="h-8" src="/images/logo_3d.png" alt="ADTI">
                </Link>
                <button @click="sidebarOpen = false" class="lg:hidden size-8 rounded-lg hover:bg-white/10 flex items-center justify-center text-white/60 transition-colors">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <Link
                    v-for="item in adminNav"
                    :key="item.route"
                    :href="route(item.route)"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200"
                    :class="route().current(item.route + '*') || route().current(item.route)
                        ? 'bg-brand-blue/15 text-brand-blue'
                        : 'text-white/40 hover:text-white hover:bg-white/[0.04]'"
                >
                    <svg class="size-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                    </svg>
                    {{ item.label }}
                </Link>
            </nav>

            <div class="px-4 py-4 border-t border-white/[0.06]">
                <Dropdown align="left" width="48" position="top">
                    <template #trigger>
                        <button class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-white/50 hover:text-white hover:bg-white/[0.04] transition-all duration-200">
                            <div class="size-8 rounded-lg bg-brand-blue/20 flex items-center justify-center text-brand-blue font-bold text-xs uppercase shrink-0">
                                {{ $page.props.auth.user.name?.charAt(0) || 'A' }}
                            </div>
                            <span class="truncate text-left">{{ $page.props.auth.user.name }}</span>
                        </button>
                    </template>
                    <template #content>
                        <div class="py-1">
                            <DropdownLink :href="route('profile.show')">Perfil</DropdownLink>
                            <form @submit.prevent="logout">
                                <DropdownLink as="button">Cerrar sesión</DropdownLink>
                            </form>
                        </div>
                    </template>
                </Dropdown>
            </div>
        </aside>

        <!-- ═══════ Main Content ═══════ -->
        <main class="flex-1 overflow-auto min-w-0">
            <!-- Mobile hamburger -->
            <div class="lg:hidden flex items-center gap-3 px-4 py-3 border-b border-slate-200 bg-white sticky top-0 z-30">
                <button @click="sidebarOpen = true" class="size-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-deep-onyx transition-colors">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <span class="font-bold text-deep-onyx text-sm">{{ title || 'Dashboard' }}</span>
            </div>

            <div class="p-6 lg:p-8">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
.overlay-enter-active,
.overlay-leave-active { transition: opacity 0.25s ease; }
.overlay-enter-from,
.overlay-leave-to { opacity: 0; }
</style>
