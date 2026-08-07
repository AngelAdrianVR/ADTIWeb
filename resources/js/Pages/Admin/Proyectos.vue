<script setup>
import { ref, computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({ proyectos: Array, flash: Object });

// ── Server errors ──
const serverErrors = computed(() => usePage().props.errors || {});

// ── Modal state ──
const showModal = ref(false);
const submitting = ref(false);
const editing = ref(null);
const form = ref({
    key: '', title_es: '', title_en: '',
    description_es: '', description_en: '',
    client_es: '', client_en: '',
    location_es: '', location_en: '',
    year: null, category: '', video_url: '',
    features_es: [], features_en: [],
    sort_order: 0, active: true,
});
const imageFile = ref(null);
const videoFile = ref(null);
const galleryFiles = ref([]);
const removeGalleryIds = ref([]);

// ── Detail modal ──
const showDetail = ref(false);
const selectedProyecto = ref(null);

// ── Edit: existing media tracking ──
const editingProyecto = ref(null);

function resetForm() {
    form.value = {
        key: '', title_es: '', title_en: '',
        description_es: '', description_en: '',
        client_es: '', client_en: '',
        location_es: '', location_en: '',
        year: null, category: '', video_url: '',
        features_es: [], features_en: [],
        sort_order: 0, active: true,
    };
    imageFile.value = null; videoFile.value = null; galleryFiles.value = []; removeGalleryIds.value = [];
    editing.value = null; editingProyecto.value = null;
}

function openCreate() { resetForm(); showModal.value = true; }

function openEdit(p) {
    editing.value = p.id;
    editingProyecto.value = p;
    form.value = {
        key: p.key, title_es: p.title_es, title_en: p.title_en,
        description_es: p.description_es, description_en: p.description_en,
        client_es: p.client_es || '', client_en: p.client_en || '',
        location_es: p.location_es || '', location_en: p.location_en || '',
        year: p.year, category: p.category || '', video_url: p.video_url || '',
        features_es: [...(p.features_es || [])], features_en: [...(p.features_en || [])],
        sort_order: p.sort_order, active: p.active,
    };
    imageFile.value = null; videoFile.value = null; galleryFiles.value = []; removeGalleryIds.value = [];
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    resetForm();
    usePage().props.errors = {};
}

function openDetail(p) {
    selectedProyecto.value = p;
    showDetail.value = true;
}

function editFromDetail() {
    const p = selectedProyecto.value;
    closeDetail();
    openEdit(p);
}

function closeDetail() {
    showDetail.value = false;
    selectedProyecto.value = null;
}

function fieldError(field) {
    return serverErrors.value[field]?.[0] || '';
}

function hasError(field) {
    return !!serverErrors.value[field];
}

function addFeature(lang) { form.value['features_' + lang].push(''); }
function removeFeature(lang, idx) { form.value['features_' + lang].splice(idx, 1); }

function submitForm() {
    submitting.value = true;
    const data = new FormData();
    Object.entries(form.value).forEach(([k, v]) => {
        if (Array.isArray(v)) v.forEach((item, i) => data.append(`${k}[${i}]`, item));
        else if (typeof v === 'boolean') data.append(k, v ? '1' : '0');
        else data.append(k, v ?? '');
    });
    if (imageFile.value) data.append('image', imageFile.value);
    if (videoFile.value) data.append('video', videoFile.value);
    galleryFiles.value.forEach(f => data.append('gallery[]', f));
    removeGalleryIds.value.forEach(id => data.append('remove_gallery_ids[]', id));

    if (editing.value) {
        data.append('_method', 'PUT');
        router.post(route('admin.proyectos.update', editing.value), data, {
            onSuccess: () => { submitting.value = false; closeModal(); },
            onError: () => { submitting.value = false; },
            onFinish: () => { submitting.value = false; },
        });
    } else {
        router.post(route('admin.proyectos.store'), data, {
            onSuccess: () => { submitting.value = false; closeModal(); },
            onError: () => { submitting.value = false; },
            onFinish: () => { submitting.value = false; },
        });
    }
}

function deleteItem(id) {
    if (!confirm('¿Eliminar este proyecto?')) return;
    router.delete(route('admin.proyectos.destroy', id));
}

async function deleteGalleryMedia(mediaId) {
    if (!confirm('¿Eliminar esta imagen de la galería?')) return;
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(route('media.delete-file', mediaId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
        });
        if (res.ok && editingProyecto.value?.gallery) {
            editingProyecto.value.gallery = editingProyecto.value.gallery.filter(g => g.id !== mediaId);
        }
    } catch (e) {
        console.error('Error deleting media:', e);
    }
}

async function deleteVideo() {
    const id = editingProyecto.value?.video_id;
    if (!id) return;
    if (!confirm('¿Eliminar este video?')) return;
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(route('media.delete-file', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
        });
        if (res.ok && editingProyecto.value) {
            editingProyecto.value.video_file_url = null;
            editingProyecto.value.video_id = null;
        }
    } catch (e) {
        console.error('Error deleting video:', e);
    }
}

const modalTitle = computed(() => editing.value ? 'Editar Proyecto' : 'Nuevo Proyecto');
</script>

<template>
    <Head title="Proyectos" />

    <div>
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-deep-onyx">Proyectos</h1>
                <p class="text-sm text-steel-grey mt-1">Gestiona los proyectos destacados</p>
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
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Img</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Key</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Título ES</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Categoría</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Año</th>
                        <th class="text-left px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Activo</th>
                        <th class="text-right px-6 py-3 text-xs uppercase tracking-wider text-steel-grey font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr v-for="p in proyectos" :key="p.id" class="hover:bg-slate-50/30 transition-colors cursor-pointer" @click="openDetail(p)">
                        <td class="px-6 py-3">
                            <img v-if="p.image_url" :src="p.image_url" class="size-10 rounded-lg object-cover" />
                            <div v-else class="size-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg></div>
                        </td>
                        <td class="px-6 py-3 font-mono text-xs text-brand-blue">{{ p.key }}</td>
                        <td class="px-6 py-3 font-medium text-deep-onyx">{{ p.title_es }}</td>
                        <td class="px-6 py-3 text-steel-grey text-xs">{{ p.category }}</td>
                        <td class="px-6 py-3 text-steel-grey text-xs">{{ p.year }}</td>
                        <td class="px-6 py-3"><span :class="p.active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'" class="px-2 py-0.5 rounded-full text-[10px] font-bold border">{{ p.active ? 'Sí' : 'No' }}</span></td>
                        <td class="px-6 py-3 text-right" @click.stop>
                            <button @click="openEdit(p)" class="px-3 py-1.5 text-xs font-bold rounded-lg text-brand-blue hover:bg-brand-blue/10 transition-colors mr-1">Editar</button>
                            <button @click="deleteItem(p.id)" class="px-3 py-1.5 text-xs font-bold rounded-lg text-red-600 hover:bg-red-50 transition-colors">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-[9999] flex items-start justify-center pt-12 pb-12 px-4 overflow-y-auto" @click.self="closeModal">
                    <div class="fixed inset-0 bg-black/60" />
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-black text-deep-onyx">{{ modalTitle }}</h2>
                            <button @click="closeModal" class="size-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-steel-grey"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-5">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Key *</label>
                                    <input v-model="form.key" required :class="hasError('key') ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : 'border-slate-200 focus:border-brand-blue focus:ring-brand-blue/10'" class="w-full px-3 py-2.5 rounded-xl border text-sm focus:ring-2 outline-none transition-colors" />
                                    <p v-if="fieldError('key')" class="text-xs text-red-500 mt-1 font-medium">{{ fieldError('key') }}</p>
                                </div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Categoría</label><input v-model="form.category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Año</label><input v-model.number="form.year" type="number" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Título ES *</label>
                                    <input v-model="form.title_es" required :class="hasError('title_es') ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : 'border-slate-200 focus:border-brand-blue focus:ring-brand-blue/10'" class="w-full px-3 py-2.5 rounded-xl border text-sm focus:ring-2 outline-none transition-colors" />
                                    <p v-if="fieldError('title_es')" class="text-xs text-red-500 mt-1 font-medium">{{ fieldError('title_es') }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Título EN *</label>
                                    <input v-model="form.title_en" required :class="hasError('title_en') ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : 'border-slate-200 focus:border-brand-blue focus:ring-brand-blue/10'" class="w-full px-3 py-2.5 rounded-xl border text-sm focus:ring-2 outline-none transition-colors" />
                                    <p v-if="fieldError('title_en')" class="text-xs text-red-500 mt-1 font-medium">{{ fieldError('title_en') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Descripción ES *</label>
                                    <textarea v-model="form.description_es" rows="4" required :class="hasError('description_es') ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : 'border-slate-200 focus:border-brand-blue focus:ring-brand-blue/10'" class="w-full px-3 py-2.5 rounded-xl border text-sm focus:ring-2 outline-none resize-y min-h-[120px] leading-relaxed transition-colors" />
                                    <p v-if="fieldError('description_es')" class="text-xs text-red-500 mt-1 font-medium">{{ fieldError('description_es') }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Descripción EN *</label>
                                    <textarea v-model="form.description_en" rows="4" required :class="hasError('description_en') ? 'border-red-300 focus:border-red-400 focus:ring-red-200' : 'border-slate-200 focus:border-brand-blue focus:ring-brand-blue/10'" class="w-full px-3 py-2.5 rounded-xl border text-sm focus:ring-2 outline-none resize-y min-h-[120px] leading-relaxed transition-colors" />
                                    <p v-if="fieldError('description_en')" class="text-xs text-red-500 mt-1 font-medium">{{ fieldError('description_en') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Cliente ES</label><input v-model="form.client_es" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Cliente EN</label><input v-model="form.client_en" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Ubicación ES</label><input v-model="form.location_es" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Ubicación EN</label><input v-model="form.location_en" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-2"><label class="text-xs font-bold uppercase tracking-wider text-steel-grey">Features ES</label><button type="button" @click="addFeature('es')" class="text-[10px] font-bold text-brand-blue hover:underline">+</button></div>
                                    <div class="space-y-2"><div v-for="(f,i) in form.features_es" :key="'es-'+i" class="flex gap-2"><input v-model="form.features_es[i]" class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /><button type="button" @click="removeFeature('es',i)" class="shrink-0 size-8 rounded-lg hover:bg-red-50 text-red-400 flex items-center justify-center"><svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></div></div>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2"><label class="text-xs font-bold uppercase tracking-wider text-steel-grey">Features EN</label><button type="button" @click="addFeature('en')" class="text-[10px] font-bold text-brand-blue hover:underline">+</button></div>
                                    <div class="space-y-2"><div v-for="(f,i) in form.features_en" :key="'en-'+i" class="flex gap-2"><input v-model="form.features_en[i]" class="flex-1 px-3 py-2 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /><button type="button" @click="removeFeature('en',i)" class="shrink-0 size-8 rounded-lg hover:bg-red-50 text-red-400 flex items-center justify-center"><svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button></div></div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Imagen principal</label>
                                <!-- Existing image preview -->
                                <div v-if="editing && editingProyecto?.image_url" class="mb-3">
                                    <div class="relative inline-block">
                                        <img :src="editingProyecto.image_url" class="size-24 rounded-xl object-cover shadow-sm border border-slate-200" />
                                        <span class="absolute -top-1.5 -right-1.5 size-5 rounded-full bg-brand-blue text-white flex items-center justify-center text-[10px] font-bold shadow">1</span>
                                    </div>
                                    <p class="text-[10px] text-steel-grey mt-1">Se reemplazará si seleccionas una nueva imagen</p>
                                </div>
                                <input type="file" accept="image/*" @change="e => imageFile = e.target.files[0]" class="w-full text-sm text-steel-grey file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-blue/10 file:text-brand-blue hover:file:bg-brand-blue/20" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Galería</label>
                                <!-- Existing gallery -->
                                <div v-if="editing && editingProyecto?.gallery?.length" class="mb-3">
                                    <p class="text-[10px] text-steel-grey font-medium mb-2">Imágenes actuales ({{ editingProyecto.gallery.length }})</p>
                                    <div class="flex flex-wrap gap-2">
                                        <div v-for="img in editingProyecto.gallery" :key="img.id" class="relative group">
                                            <img :src="img.url" class="size-20 rounded-xl object-cover shadow-sm border border-slate-200" />
                                            <button type="button" @click="deleteGalleryMedia(img.id)" class="absolute -top-1.5 -right-1.5 size-5 rounded-full bg-red-500 text-white hover:bg-red-600 flex items-center justify-center shadow transition-colors">
                                                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" accept="image/*" multiple @change="e => galleryFiles = [...e.target.files]" class="w-full text-sm text-steel-grey file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-blue/10 file:text-brand-blue hover:file:bg-brand-blue/20" />
                                <p v-if="editing" class="text-[10px] text-steel-grey mt-1">Las nuevas imágenes se agregarán a las existentes</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">URL de YouTube (opcional)</label>
                                    <input v-model="form.video_url" type="url" placeholder="https://www.youtube.com/watch?v=..." class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Subir video (opcional)</label>
                                    <!-- Existing video -->
                                    <div v-if="editing && editingProyecto?.video_file_url" class="mb-2 flex items-center gap-2">
                                        <a :href="editingProyecto.video_file_url" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-blue hover:underline">
                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                            Ver video actual
                                        </a>
                                        <button type="button" @click="deleteVideo" class="size-5 rounded-full bg-red-500 text-white hover:bg-red-600 flex items-center justify-center shadow transition-colors">
                                            <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <input type="file" accept="video/mp4,video/webm,video/quicktime" @change="e => videoFile = e.target.files[0]" class="w-full text-sm text-steel-grey file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-blue/10 file:text-brand-blue hover:file:bg-brand-blue/20" />
                                    <p v-if="editing && editingProyecto?.video_file_url" class="text-[10px] text-steel-grey mt-1">Se reemplazará si seleccionas un nuevo video</p>
                                </div>
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
                            <h2 class="text-xl font-black text-deep-onyx">Detalle del Proyecto</h2>
                            <button @click="closeDetail" class="size-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-steel-grey transition-colors">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div v-if="selectedProyecto" class="space-y-6">
                            <!-- Image -->
                            <div class="flex items-start gap-6">
                                <img v-if="selectedProyecto.image_url" :src="selectedProyecto.image_url" class="size-28 rounded-xl object-cover shadow-md" @error="$event.target.style.display='none'" />
                                <div v-else class="size-28 rounded-xl bg-slate-100 flex items-center justify-center text-slate-300 shadow-md">
                                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span :class="selectedProyecto.active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'" class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold border mb-2">{{ selectedProyecto.active ? 'Activo' : 'Inactivo' }}</span>
                                    <h3 class="text-lg font-black text-deep-onyx">{{ selectedProyecto.title_es }}</h3>
                                    <p class="text-sm text-steel-grey mt-0.5">{{ selectedProyecto.title_en }}</p>
                                    <p class="text-xs font-mono text-brand-blue mt-1">Key: {{ selectedProyecto.key }} &middot; Categoría: {{ selectedProyecto.category || '—' }} &middot; Año: {{ selectedProyecto.year || '—' }}</p>
                                </div>
                            </div>

                            <!-- Client & Location -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Cliente</h4>
                                    <p class="text-sm text-deep-onyx leading-relaxed">{{ selectedProyecto.client_es || '—' }}</p>
                                    <p class="text-xs text-steel-grey">{{ selectedProyecto.client_en || '' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Ubicación</h4>
                                    <p class="text-sm text-deep-onyx leading-relaxed">{{ selectedProyecto.location_es || '—' }}</p>
                                    <p class="text-xs text-steel-grey">{{ selectedProyecto.location_en || '' }}</p>
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Descripción ES</h4>
                                    <p class="text-sm text-deep-onyx leading-relaxed">{{ selectedProyecto.description_es }}</p>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-1">Descripción EN</h4>
                                    <p class="text-sm text-deep-onyx leading-relaxed">{{ selectedProyecto.description_en }}</p>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-2">Features ES</h4>
                                    <ul class="space-y-1">
                                        <li v-for="(f, i) in (selectedProyecto.features_es || [])" :key="'pdes-'+i" class="text-sm text-deep-onyx flex items-start gap-2">
                                            <svg class="size-3.5 text-brand-blue shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/></svg>
                                            {{ f }}
                                        </li>
                                        <li v-if="!selectedProyecto.features_es?.length" class="text-sm text-steel-grey italic">Sin features</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-2">Features EN</h4>
                                    <ul class="space-y-1">
                                        <li v-for="(f, i) in (selectedProyecto.features_en || [])" :key="'pden-'+i" class="text-sm text-deep-onyx flex items-start gap-2">
                                            <svg class="size-3.5 text-brand-blue shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/></svg>
                                            {{ f }}
                                        </li>
                                        <li v-if="!selectedProyecto.features_en?.length" class="text-sm text-steel-grey italic">Sin features</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Gallery -->
                            <div v-if="selectedProyecto.gallery?.length">
                                <h4 class="text-[10px] font-bold uppercase tracking-wider text-steel-grey mb-2">Galería ({{ selectedProyecto.gallery.length }})</h4>
                                <div class="grid grid-cols-4 gap-3">
                                    <img v-for="img in selectedProyecto.gallery" :key="img.id" :src="img.url" class="w-full aspect-square rounded-xl object-cover shadow-sm" @error="$event.target.style.display='none'" />
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
