<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({ mensajes: Array });

const showDetail = ref(false);
const selected = ref(null);

function openDetail(m) {
    selected.value = m;
    showDetail.value = true;
}

function closeDetail() {
    showDetail.value = false;
    selected.value = null;
}

function deleteItem(id) {
    if (!confirm('¿Eliminar este mensaje?')) return;
    router.delete(route('admin.mensajes.destroy', id));
}

function formatDate(date) {
    if (!date) return '—';
    return new Date(date).toLocaleString('es-MX', { dateStyle: 'medium', timeStyle: 'short' });
}

const initials = (name) => (name || '?').split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
</script>

<template>
    <Head title="Mensajes" />

    <div>
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-deep-onyx">Mensajes</h1>
                <p class="text-sm text-steel-grey mt-1">Mensajes recibidos desde el formulario de contacto</p>
            </div>
            <span class="px-3 py-1.5 rounded-full bg-brand-blue/10 text-brand-blue text-xs font-bold">{{ mensajes.length }} mensajes</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Nombre</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Correo</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Teléfono</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Fecha</th>
                        <th class="text-right px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="m in mensajes" :key="m.id" class="hover:bg-slate-50/30 transition-colors cursor-pointer" @click="openDetail(m)">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-lg bg-brand-blue/10 text-brand-blue font-black text-xs flex items-center justify-center shrink-0">{{ initials(m.nombre) }}</div>
                                <span class="font-medium text-deep-onyx">{{ m.nombre }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-steel-grey">{{ m.correo }}</td>
                        <td class="px-6 py-3 text-steel-grey">{{ m.telefono }}</td>
                        <td class="px-6 py-3 text-steel-grey text-xs">{{ formatDate(m.created_at) }}</td>
                        <td class="px-6 py-3 text-right" @click.stop>
                            <button @click="deleteItem(m.id)" class="px-3 py-1.5 text-xs font-bold rounded-lg text-red-600 hover:bg-red-50 transition-colors">Eliminar</button>
                        </td>
                    </tr>
                    <tr v-if="!mensajes.length">
                        <td colspan="5" class="px-6 py-12 text-center">
                            <p class="text-steel-grey text-sm">Aún no hay mensajes recibidos</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ── Detail Modal ── -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showDetail" class="fixed inset-0 z-[9999] flex items-start justify-center pt-12 pb-12 px-4 overflow-y-auto" @click.self="closeDetail">
                    <div class="fixed inset-0 bg-black/60" />
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-black text-deep-onyx">Detalle del Mensaje</h2>
                            <button @click="closeDetail" class="size-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-steel-grey transition-colors">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div v-if="selected" class="space-y-6">
                            <div class="flex items-center gap-4">
                                <div class="size-14 rounded-xl bg-brand-blue/10 text-brand-blue font-black text-lg flex items-center justify-center shrink-0">{{ initials(selected.nombre) }}</div>
                                <div class="min-w-0">
                                    <h3 class="text-lg font-black text-deep-onyx">{{ selected.nombre }}</h3>
                                    <p class="text-sm text-steel-grey">{{ formatDate(selected.created_at) }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Correo</h4>
                                    <a :href="'mailto:' + selected.correo" class="text-sm text-brand-blue font-semibold hover:underline break-all">{{ selected.correo }}</a>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Teléfono</h4>
                                    <a :href="'tel:' + selected.telefono" class="text-sm text-deep-onyx font-semibold hover:text-brand-blue">{{ selected.telefono }}</a>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-2">Mensaje</h4>
                                <p class="text-sm text-deep-onyx leading-relaxed bg-slate-50 rounded-xl p-4 border border-slate-100 whitespace-pre-wrap">{{ selected.descripcion }}</p>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                <button @click="deleteItem(selected.id); closeDetail();" class="px-5 py-2.5 rounded-xl text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">Eliminar</button>
                                <button @click="closeDetail" class="px-5 py-2.5 rounded-xl text-sm font-bold text-steel-grey hover:bg-slate-100 transition-colors">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s ease; will-change: opacity; }
.modal-enter-active > div:last-child, .modal-leave-active > div:last-child { transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease; will-change: transform, opacity; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from > div:last-child { transform: scale(0.97) translateY(8px); opacity: 0; }
.modal-leave-to > div:last-child { transform: scale(0.97) translateY(8px); opacity: 0; }
</style>
