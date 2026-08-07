<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    title: String,
});

const sidebarOpen = ref(false);

// ── Notificaciones (extensible por tipo) ──
const notificaciones = ref(usePage().props.notificaciones || []);
const unreadCount = ref(usePage().props.unreadNotificaciones || 0);
const notifOpen = ref(false);
const notifRef = ref(null);

// Icono y color por tipo de notificación (agrega más tipos aquí)
const typeStyles = {
    mensaje: { icon: 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75', cls: 'bg-brand-blue/10 text-brand-blue' },
};

function notifStyle(type) {
    return typeStyles[type] || typeStyles.mensaje;
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function timeAgo(dateStr) {
    if (!dateStr) return '';
    const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 1000);
    if (diff < 60) return 'ahora';
    if (diff < 3600) return `hace ${Math.floor(diff / 60)} min`;
    if (diff < 86400) return `hace ${Math.floor(diff / 3600)} h`;
    return `hace ${Math.floor(diff / 86400)} d`;
}

async function refreshNotifications() {
    try {
        const res = await fetch(route('admin.notificaciones.index'));
        const data = await res.json();
        notificaciones.value = data.notificaciones || [];
        unreadCount.value = data.count ?? 0;
    } catch (e) {
        // ignore
    }
}

let pollTimer = null;
let notifRefreshTimer = null;

onMounted(() => {
    pollTimer = setInterval(refreshNotifications, 30000);
    document.addEventListener('click', onDocClick);
    document.addEventListener('keydown', onDocKey);
});

onUnmounted(() => {
    if (pollTimer) clearInterval(pollTimer);
    if (notifRefreshTimer) clearTimeout(notifRefreshTimer);
    document.removeEventListener('click', onDocClick);
    document.removeEventListener('keydown', onDocKey);
});

function onDocClick(e) {
    if (notifOpen.value && notifRef.value && !notifRef.value.contains(e.target)) {
        notifOpen.value = false;
    }
}

function onDocKey(e) {
    if (e.key === 'Escape') notifOpen.value = false;
}

function toggleNotif() {
    notifOpen.value = !notifOpen.value;
    if (notifOpen.value) {
        notifRefreshTimer = setTimeout(refreshNotifications, 300);
    }
}

async function markRead(id) {
    const n = notificaciones.value.find(x => x.id === id);
    if (n && n.is_unread) {
        n.is_unread = false;
        unreadCount.value = Math.max(0, unreadCount.value - 1);
    }
    try {
        await fetch(route('admin.notificaciones.read', id), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
        });
    } catch (e) {
        // ignore
    }
}

async function markAllRead() {
    notificaciones.value.forEach(n => n.is_unread = false);
    unreadCount.value = 0;
    try {
        await fetch(route('admin.notificaciones.read-all'), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
        });
    } catch (e) {
        // ignore
    }
}

function openNotification(n) {
    notifOpen.value = false;
    if (n.is_unread) markRead(n.id);
    if (n.route_name) {
        router.visit(route(n.route_name, n.route_params || {}));
    }
}

async function deleteNotification(id) {
    const n = notificaciones.value.find(x => x.id === id);
    if (n && n.is_unread) unreadCount.value = Math.max(0, unreadCount.value - 1);
    notificaciones.value = notificaciones.value.filter(x => x.id !== id);
    try {
        await fetch(route('admin.notificaciones.destroy', id), {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'Accept': 'application/json' },
        });
    } catch (e) {
        // ignore
    }
}

const logout = () => {
    router.post(route('logout'));
};

const adminNav = [
    { label: 'Dashboard', route: 'dashboard', icon: 'M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5' },
    { label: 'Servicios', route: 'admin.servicios.index', icon: 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z' },
    { label: 'Proyectos', route: 'admin.proyectos.index', icon: 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z' },
    { label: 'Certificaciones', route: 'admin.certificaciones.index', icon: 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z' },
    { label: 'Mensajes', route: 'admin.mensajes.index', icon: 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75' },
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
                    <span class="flex-1 truncate">{{ item.label }}</span>
                    <span
                        v-if="item.route === 'admin.mensajes.index' && unreadCount > 0"
                        class="shrink-0 min-w-5 h-5 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center"
                    >{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
                </Link>
            </nav>

            <!-- Ver landing page -->
            <div class="px-4 pb-4">
                <Link
                    :href="route('inicio')"
                    target="_blank"
                    class="flex items-center justify-center gap-2 w-full px-3 py-2.5 rounded-xl text-sm font-bold text-brand-blue bg-brand-blue/10 hover:bg-brand-blue/20 border border-brand-blue/20 transition-all duration-200"
                >
                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                    </svg>
                    Ver sitio web
                </Link>
            </div>

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
            <!-- Top bar -->
            <div class="flex items-center gap-3 px-4 sm:px-6 py-3 border-b border-slate-200 bg-white sticky top-0 z-30">
                <button @click="sidebarOpen = true" class="lg:hidden size-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-deep-onyx transition-colors">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <span class="font-bold text-deep-onyx text-sm">{{ title || 'Dashboard' }}</span>
                <div class="flex-1" />

                <!-- Notificaciones dropdown -->
                <div ref="notifRef" class="relative">
                    <button
                        @click="toggleNotif"
                        class="relative size-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-deep-onyx transition-colors"
                        :title="unreadCount > 0 ? unreadCount + ' notificaciones nuevas' : 'Sin notificaciones'"
                    >
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span
                            v-if="unreadCount > 0"
                            class="absolute -top-0.5 -right-0.5 min-w-4 h-4 px-1 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center border-2 border-white"
                        >{{ unreadCount > 9 ? '9+' : unreadCount }}</span>
                    </button>

                    <Transition name="dropdown">
                        <div
                            v-if="notifOpen"
                            class="absolute right-0 top-12 w-[22rem] max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50"
                        >
                            <!-- Header -->
                            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                                <div>
                                    <p class="text-sm font-black text-deep-onyx">Notificaciones</p>
                                    <p v-if="unreadCount > 0" class="text-[10px] text-steel-grey">{{ unreadCount }} sin leer</p>
                                    <p v-else class="text-[10px] text-steel-grey">Sin notificaciones nuevas</p>
                                </div>
                                <button
                                    v-if="unreadCount > 0"
                                    @click="markAllRead"
                                    class="text-[10px] font-bold text-brand-blue hover:underline"
                                >Marcar leídas</button>
                            </div>

                            <!-- List -->
                            <div class="max-h-80 overflow-y-auto">
                                <div v-if="!notificaciones.length" class="px-4 py-10 text-center">
                                    <svg class="size-8 mx-auto text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                    <p class="text-sm text-steel-grey">No hay notificaciones</p>
                                </div>

                                <div
                                    v-for="n in notificaciones"
                                    :key="n.id"
                                    class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50/70 transition-colors border-b border-slate-50 last:border-0 cursor-pointer"
                                    :class="n.is_unread ? 'bg-brand-blue/[0.03]' : ''"
                                    @click="openNotification(n)"
                                >
                                    <div class="shrink-0 size-9 rounded-xl flex items-center justify-center" :class="notifStyle(n.type).cls">
                                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" :d="notifStyle(n.type).icon" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold leading-snug" :class="n.is_unread ? 'text-deep-onyx' : 'text-steel-grey'">
                                            {{ n.title }}
                                        </p>
                                        <p class="text-[11px] text-steel-grey mt-0.5 truncate">{{ n.description }}</p>
                                        <p class="text-[10px] text-steel-grey/50 mt-1">{{ timeAgo(n.created_at) }}</p>
                                    </div>
                                    <button
                                        @click.stop="deleteNotification(n.id)"
                                        class="shrink-0 size-6 rounded-md hover:bg-red-50 text-slate-300 hover:text-red-500 flex items-center justify-center transition-colors"
                                        title="Eliminar notificación"
                                    >
                                        <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-4 py-2.5 border-t border-slate-100 text-center">
                                <Link :href="route('admin.mensajes.index')" @click="notifOpen = false" class="text-[11px] font-bold text-brand-blue hover:underline">Ver todos los mensajes</Link>
                            </div>
                        </div>
                    </Transition>
                </div>
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

.dropdown-enter-active, .dropdown-leave-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px) scale(0.98); }
</style>
