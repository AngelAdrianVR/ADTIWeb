<script setup>
import { Link } from '@inertiajs/vue3';
import BarChart from '@/Components/Charts/BarChart.vue';

const props = defineProps({
    mensajesUnread: Array,
    mensajesCount: Number,
    visitas: Object,
    visitasPorHora: Object,
    visitasPorDia: Object,
    visitasPorSemana: Object,
    visitasPorMes: Object,
});

const initials = (name) => (name || '?').split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();

function formatDate(date) {
    if (!date) return '—';
    return new Date(date).toLocaleString('es-MX', { dateStyle: 'medium', timeStyle: 'short' });
}

const stats = [
    { label: 'Visitas totales', value: props.visitas?.total ?? 0, color: '#0082A8', bg: 'rgba(0,130,168,0.10)', icon: 'M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418' },
    { label: 'Visitas hoy', value: props.visitas?.hoy ?? 0, color: '#00D4FF', bg: 'rgba(0,212,255,0.12)', icon: 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' },
    { label: 'Visitas semana', value: props.visitas?.semana ?? 0, color: '#6D6E71', bg: 'rgba(109,110,113,0.12)', icon: 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5' },
    { label: 'Visitas mes', value: props.visitas?.mes ?? 0, color: '#0F172A', bg: 'rgba(15,23,42,0.10)', icon: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z' },
];

const charts = [
    { title: 'Visitas por hora', subtitle: 'Últimas 24 horas', data: props.visitasPorHora, color: '#0082A8' },
    { title: 'Visitas por día', subtitle: 'Últimos 14 días', data: props.visitasPorDia, color: '#00D4FF' },
    { title: 'Visitas por semana', subtitle: 'Últimas 12 semanas', data: props.visitasPorSemana, color: '#6D6E71' },
    { title: 'Visitas por mes', subtitle: 'Últimos 12 meses', data: props.visitasPorMes, color: '#0F172A' },
];

function totalOf(chart) {
    return (chart.data?.data || []).reduce((a, b) => a + (Number(b) || 0), 0);
}
</script>

<template>
    <div class="space-y-8">
        <!-- ── Header ── -->
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-black text-deep-onyx">Dashboard</h1>
                <p class="text-sm text-steel-grey mt-1">Resumen de actividad de tu sitio web</p>
            </div>
            <Link
                :href="route('inicio')"
                target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-deep-onyx hover:bg-brand-blue text-white text-xs font-bold transition-all duration-200 shadow-sm"
            >
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                </svg>
                Ver sitio web
            </Link>
        </div>

        <!-- ── Quick access: mensajes sin leer ── -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-slate-100 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl bg-brand-blue/10 text-brand-blue flex items-center justify-center shrink-0">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-deep-onyx">Mensajes sin leer</h2>
                        <p class="text-xs text-steel-grey">
                            Tienes
                            <span class="font-black" :class="mensajesCount > 0 ? 'text-red-500' : 'text-emerald-600'">{{ mensajesCount }}</span>
                            mensajes pendientes de revisar
                        </p>
                    </div>
                </div>
                <Link
                    :href="route('admin.mensajes.index')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-blue hover:bg-brand-blue/90 text-white text-xs font-bold transition-all duration-200 shadow-md hover:shadow-lg"
                >
                    Ver mensajes
                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </Link>
            </div>

            <div v-if="mensajesUnread.length">
                <div
                    v-for="m in mensajesUnread"
                    :key="m.id"
                    class="flex items-center gap-3 px-6 py-3 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors"
                >
                    <div class="size-9 rounded-lg bg-brand-blue/10 text-brand-blue font-black text-xs flex items-center justify-center shrink-0">{{ initials(m.nombre) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-deep-onyx truncate">{{ m.nombre }}</p>
                        <p class="text-xs text-steel-grey truncate">{{ m.descripcion }}</p>
                    </div>
                    <div class="text-right shrink-0 hidden sm:block">
                        <p class="text-[10px] text-steel-grey">{{ formatDate(m.created_at) }}</p>
                    </div>
                    <span class="shrink-0 size-2 rounded-full bg-red-500 animate-pulse" title="Sin leer" />
                </div>
            </div>
            <div v-else class="px-6 py-8 text-center">
                <p class="text-sm text-steel-grey">No tienes mensajes sin leer 🎉</p>
            </div>
        </div>

        <!-- ── Stats de visitas ── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                v-for="s in stats"
                :key="s.label"
                class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5"
            >
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] uppercase tracking-wider text-steel-grey font-bold">{{ s.label }}</span>
                    <div class="size-8 rounded-lg flex items-center justify-center shrink-0" :style="{ backgroundColor: s.bg, color: s.color }">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="s.icon" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-deep-onyx tabular-nums">{{ Number(s.value).toLocaleString('es-MX') }}</p>
            </div>
        </div>

        <!-- ── Charts ── -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div v-for="c in charts" :key="c.title" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between gap-2 mb-1">
                    <h3 class="text-sm font-black text-deep-onyx">{{ c.title }}</h3>
                    <span class="text-[10px] font-bold text-steel-grey bg-slate-100 rounded-full px-2.5 py-1 tabular-nums shrink-0">{{ totalOf(c) }} visitas</span>
                </div>
                <p class="text-[11px] text-steel-grey mb-4">{{ c.subtitle }}</p>
                <BarChart :labels="c.data?.labels || []" :data="c.data?.data || []" :color="c.color" />
            </div>
        </div>
    </div>
</template>
