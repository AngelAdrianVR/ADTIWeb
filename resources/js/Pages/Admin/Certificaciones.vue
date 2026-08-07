<script setup>
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({ certificaciones: Array, flash: Object });

// ── Server errors ──
const serverErrors = computed(() => usePage().props.errors || {});

// ── Modal state ──
const showModal = ref(false);
const submitting = ref(false);
const editing = ref(null);
const form = ref({
    key: '', title: '',
    description_es: '', description_en: '',
    issuer: '', year_obtained: null,
    sort_order: 0, active: true,
});
const imageFile = ref(null);
const galleryFiles = ref([]);
const removeGalleryIds = ref([]);

// ── Detail modal ──
const showDetail = ref(false);
const selectedCertificacion = ref(null);

// ── Edit: existing media tracking ──
const editingCertificacion = ref(null);

function resetForm() {
    form.value = { key: '', title: '', description_es: '', description_en: '', issuer: '', year_obtained: null, sort_order: 0, active: true };
    imageFile.value = null;
    galleryFiles.value = [];
    removeGalleryIds.value = [];
    editing.value = null;
    editingCertificacion.value = null;
}

function openCreate() { resetForm(); showModal.value = true; }

function openEdit(c) {
    editing.value = c.id;
    editingCertificacion.value = c;
    form.value = {
        key: c.key, title: c.title,
        description_es: c.description_es || '', description_en: c.description_en || '',
        issuer: c.issuer || '', year_obtained: c.year_obtained,
        sort_order: c.sort_order, active: c.active,
    };
    imageFile.value = null;
    galleryFiles.value = [];
    removeGalleryIds.value = [];
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    resetForm();
    usePage().props.errors = {};
}

function openDetail(c) {
    selectedCertificacion.value = c;
    showDetail.value = true;
}

function editFromDetail() {
    const c = selectedCertificacion.value;
    closeDetail();
    openEdit(c);
}

function closeDetail() {
    showDetail.value = false;
    selectedCertificacion.value = null;
}

function fieldError(field) {
    return serverErrors.value[field]?.[0] || '';
}

function hasError(field) {
    return !!serverErrors.value[field];
}

function submitForm() {
    submitting.value = true;
    const data = new FormData();
    Object.entries(form.value).forEach(([k, v]) => {
        if (typeof v === 'boolean') data.append(k, v ? '1' : '0');
        else data.append(k, v ?? '');
    });
    if (imageFile.value) data.append('image', imageFile.value);
    galleryFiles.value.forEach(f => data.append('gallery[]', f));
    removeGalleryIds.value.forEach(id => data.append('remove_gallery_ids[]', id));

    if (editing.value) {
        data.append('_method', 'PUT');
        router.post(route('admin.certificaciones.update', editing.value), data, {
            onSuccess: () => { submitting.value = false; closeModal(); },
            onError: () => { submitting.value = false; },
            onFinish: () => { submitting.value = false; },
        });
    } else {
        router.post(route('admin.certificaciones.store'), data, {
            onSuccess: () => { submitting.value = false; closeModal(); },
            onError: () => { submitting.value = false; },
            onFinish: () => { submitting.value = false; },
        });
    }
}

function deleteItem(id) {
    if (!confirm('¿Eliminar esta certificación?')) return;
    router.delete(route('admin.certificaciones.destroy', id));
}

async function deleteMedia(mediaId, type = 'gallery') {
    if (!confirm('¿Eliminar esta imagen?')) return;
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(route('media.delete-file', mediaId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
        });
        if (!res.ok) return;
        if (type === 'image' && editingCertificacion.value) {
            editingCertificacion.value.image_url = null;
            editingCertificacion.value.image_id = null;
        } else if (type === 'gallery' && editingCertificacion.value?.gallery) {
            editingCertificacion.value.gallery = editingCertificacion.value.gallery.filter(g => g.id !== mediaId);
        }
    } catch (e) {
        console.error('Error deleting media:', e);
    }
}

const modalTitle = computed(() => editing.value ? 'Editar Certificación' : 'Nueva Certificación');
</script>

<template>
    <Head title="Certificaciones" />

    <div>
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-deep-onyx">Certificaciones</h1>
                <p class="text-sm text-steel-grey mt-1">Gestiona las certificaciones de la empresa</p>
            </div>
            <button @click="openCreate" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-blue hover:bg-brand-blue/90 text-white font-bold text-sm transition-all duration-200 shadow-md hover:shadow-lg">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Agregar
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Logo</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Key</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Título</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Emisor</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Año</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Activo</th>
                        <th class="text-right px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="c in certificaciones" :key="c.id" class="hover:bg-slate-50/30 transition-colors cursor-pointer" @click="openDetail(c)">
                        <td class="px-6 py-3">
                            <img v-if="c.image_url" :src="c.image_url" class="size-10 rounded-lg object-contain bg-slate-50" />
                            <div v-else class="size-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
                        </td>
                        <td class="px-6 py-3 font-mono text-xs text-brand-blue">{{ c.key }}</td>
                        <td class="px-6 py-3 font-medium text-deep-onyx">{{ c.title }}</td>
                        <td class="px-6 py-3 text-steel-grey text-xs">{{ c.issuer }}</td>
                        <td class="px-6 py-3 text-steel-grey text-xs">{{ c.year_obtained }}</td>
                        <td class="px-6 py-3"><span :class="c.active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'" class="px-2 py-0.5 rounded-full text-[10px] font-bold border">{{ c.active ? 'Sí' : 'No' }}</span></td>
                        <td class="px-6 py-3 text-right" @click.stop>
                            <button @click="openEdit(c)" class="px-3 py-1.5 text-xs font-bold rounded-lg text-brand-blue hover:bg-brand-blue/10 transition-colors mr-1">Editar</button>
                            <button @click="deleteItem(c.id)" class="px-3 py-1.5 text-xs font-bold rounded-lg text-red-600 hover:bg-red-50 transition-colors">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-[9999] flex items-start justify-center pt-12 pb-12 px-4 overflow-y-auto" @click.self="closeModal">
                    <div class="fixed inset-0 bg-black/60" />
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-black text-deep-onyx">{{ modalTitle }}</h2>
                            <button @click="closeModal" class="size-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-steel-grey"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Key *</label>
                                    <input v-model="form.key" required :class="hasError('key') ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : 'border-slate-200 focus:border-brand-blue focus:ring-brand-blue/10'" class="w-full px-3 py-2.5 rounded-xl border text-sm focus:ring-2 outline-none transition-colors" />
                                    <p v-if="fieldError('key')" class="text-xs text-red-500 mt-1 font-medium">{{ fieldError('key') }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Título *</label>
                                    <input v-model="form.title" required :class="hasError('title') ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : 'border-slate-200 focus:border-brand-blue focus:ring-brand-blue/10'" class="w-full px-3 py-2.5 rounded-xl border text-sm focus:ring-2 outline-none transition-colors" />
                                    <p v-if="fieldError('title')" class="text-xs text-red-500 mt-1 font-medium">{{ fieldError('title') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Emisor</label><input v-model="form.issuer" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Año obtenido</label><input v-model.number="form.year_obtained" type="number" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Descripción ES</label><textarea v-model="form.description_es" rows="2" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none resize-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Descripción EN</label><textarea v-model="form.description_en" rows="2" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none resize-none" /></div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Logo / Imagen</label>
                                <!-- Existing image preview -->
                                <div v-if="editing && editingCertificacion?.image_url" class="mb-3">
                                    <div class="relative inline-block">
                                        <img :src="editingCertificacion.image_url" class="size-24 rounded-xl object-contain bg-slate-50 shadow-sm border border-slate-200" />
                                        <button type="button" @click="deleteMedia(editingCertificacion.image_id, 'image')" class="absolute -top-1.5 -right-1.5 size-5 rounded-full bg-red-500 text-white hover:bg-red-600 flex items-center justify-center shadow transition-colors">
                                            <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-steel-grey mt-1">Se reemplazará si seleccionas una nueva imagen</p>
                                </div>
                                <input type="file" accept="image/*" @change="e => imageFile = e.target.files[0]" class="w-full text-sm text-steel-grey file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-blue/10 file:text-brand-blue hover:file:bg-brand-blue/20" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Galería</label>
                                <!-- Existing gallery -->
                                <div v-if="editing && editingCertificacion?.gallery?.length" class="mb-3">
                                    <p class="text-[10px] text-steel-grey font-medium mb-2">Imágenes actuales ({{ editingCertificacion.gallery.length }})</p>
                                    <div class="flex flex-wrap gap-2">
                                        <div v-for="img in editingCertificacion.gallery" :key="img.id" class="relative group">
                                            <img :src="img.url" class="size-20 rounded-xl object-cover shadow-sm border border-slate-200" />
                                            <button type="button" @click="deleteMedia(img.id)" class="absolute -top-1.5 -right-1.5 size-5 rounded-full bg-red-500 text-white hover:bg-red-600 flex items-center justify-center shadow transition-colors">
                                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" accept="image/*" multiple @change="e => galleryFiles = [...e.target.files]" class="w-full text-sm text-steel-grey file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-blue/10 file:text-brand-blue hover:file:bg-brand-blue/20" />
                                <p v-if="editing" class="text-[10px] text-steel-grey mt-1">Las nuevas imágenes se agregarán a las existentes</p>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-3 cursor-pointer select-none">
                                    <input v-model="form.active" type="checkbox" class="rounded border-slate-300 text-brand-blue focus:ring-brand-blue/30" />
                                    <span class="text-sm text-steel-grey font-medium">Activo</span>
                                </label>
                                <div>
                                    <label class="text-xs font-bold uppercase tracking-wider text-steel-grey mr-2">Orden</label>
                                    <input v-model.number="form.sort_order" type="number" class="w-20 px-3 py-2 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" />
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="closeModal" :disabled="submitting" class="px-5 py-2.5 rounded-xl text-sm font-bold text-steel-grey hover:bg-slate-100 transition-colors disabled:opacity-50">Cancelar</button>
                                <button type="submit" :disabled="submitting" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-blue hover:bg-brand-blue/90 shadow-md transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                                    <svg v-if="submitting" class="size-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ submitting ? 'Guardando...' : (editing ? 'Actualizar' : 'Crear') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- ── Detail Modal ── -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showDetail" class="fixed inset-0 z-[9999] flex items-start justify-center pt-12 pb-12 px-4 overflow-y-auto" @click.self="closeDetail">
                    <div class="fixed inset-0 bg-black/60" />
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-black text-deep-onyx">Detalle de Certificación</h2>
                            <button @click="closeDetail" class="size-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-steel-grey transition-colors">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div v-if="selectedCertificacion" class="space-y-6">
                            <!-- Image -->
                            <div class="flex items-start gap-6">
                                <img v-if="selectedCertificacion.image_url" :src="selectedCertificacion.image_url" class="size-28 rounded-xl object-contain bg-slate-50 shadow-md" @error="$event.target.style.display='none'" />
                                <div v-else class="size-28 rounded-xl bg-slate-100 flex items-center justify-center text-slate-300 shadow-md">
                                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span :class="selectedCertificacion.active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'" class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border mb-2">{{ selectedCertificacion.active ? 'Activo' : 'Inactivo' }}</span>
                                    <h3 class="text-lg font-black text-deep-onyx">{{ selectedCertificacion.title }}</h3>
                                    <p class="text-xs font-mono text-brand-blue mt-1">Key: {{ selectedCertificacion.key }} &middot; Emisor: {{ selectedCertificacion.issuer || '—' }} &middot; Año: {{ selectedCertificacion.year_obtained || '—' }}</p>
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Descripción ES</h4>
                                    <p class="text-sm text-deep-onyx leading-relaxed">{{ selectedCertificacion.description_es || '—' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Descripción EN</h4>
                                    <p class="text-sm text-deep-onyx leading-relaxed">{{ selectedCertificacion.description_en || '—' }}</p>
                                </div>
                            </div>

                            <!-- Gallery -->
                            <div v-if="selectedCertificacion.gallery?.length">
                                <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-2">Galería ({{ selectedCertificacion.gallery.length }})</h4>
                                <div class="grid grid-cols-4 gap-3">
                                    <img v-for="img in selectedCertificacion.gallery" :key="img.id" :src="img.url" class="w-full aspect-square rounded-xl object-cover shadow-sm" @error="$event.target.style.display='none'" />
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                <button @click="editFromDetail()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-brand-blue hover:bg-brand-blue/10 transition-colors">Editar</button>
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
