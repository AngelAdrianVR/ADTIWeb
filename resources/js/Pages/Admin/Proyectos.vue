<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({ proyectos: Array, flash: Object });

const showModal = ref(false);
const editing = ref(null);
const form = ref({
    key: '', title_es: '', title_en: '',
    description_es: '', description_en: '',
    client_es: '', client_en: '',
    location_es: '', location_en: '',
    year: null, category: '',
    features_es: [], features_en: [],
    sort_order: 0, active: true,
});
const imageFile = ref(null);
const galleryFiles = ref([]);
const removeGalleryIds = ref([]);

function resetForm() {
    form.value = {
        key: '', title_es: '', title_en: '',
        description_es: '', description_en: '',
        client_es: '', client_en: '',
        location_es: '', location_en: '',
        year: null, category: '',
        features_es: [], features_en: [],
        sort_order: 0, active: true,
    };
    imageFile.value = null; galleryFiles.value = []; removeGalleryIds.value = [];
    editing.value = null;
}

function openCreate() { resetForm(); showModal.value = true; }

function openEdit(p) {
    editing.value = p.id;
    form.value = {
        key: p.key, title_es: p.title_es, title_en: p.title_en,
        description_es: p.description_es, description_en: p.description_en,
        client_es: p.client_es || '', client_en: p.client_en || '',
        location_es: p.location_es || '', location_en: p.location_en || '',
        year: p.year, category: p.category || '',
        features_es: [...(p.features_es || [])], features_en: [...(p.features_en || [])],
        sort_order: p.sort_order, active: p.active,
    };
    imageFile.value = null; galleryFiles.value = []; removeGalleryIds.value = [];
    showModal.value = true;
}

function closeModal() { showModal.value = false; resetForm(); }

function addFeature(lang) { form.value['features_' + lang].push(''); }
function removeFeature(lang, idx) { form.value['features_' + lang].splice(idx, 1); }

function submitForm() {
    const data = new FormData();
    Object.entries(form.value).forEach(([k, v]) => {
        if (Array.isArray(v)) v.forEach((item, i) => data.append(`${k}[${i}]`, item));
        else if (typeof v === 'boolean') data.append(k, v ? '1' : '0');
        else data.append(k, v ?? '');
    });
    if (imageFile.value) data.append('image', imageFile.value);
    galleryFiles.value.forEach(f => data.append('gallery[]', f));
    removeGalleryIds.value.forEach(id => data.append('remove_gallery_ids[]', id));

    if (editing.value) {
        data.append('_method', 'PUT');
        router.post(route('admin.proyectos.update', editing.value), data, { onSuccess: () => closeModal() });
    } else {
        router.post(route('admin.proyectos.store'), data, { onSuccess: () => closeModal() });
    }
}

function deleteItem(id) {
    if (!confirm('¿Eliminar este proyecto?')) return;
    router.delete(route('admin.proyectos.destroy', id));
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
                    <tr v-for="p in proyectos" :key="p.id" class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-3">
                            <img v-if="p.image_url" :src="p.image_url" class="size-10 rounded-lg object-cover" />
                            <div v-else class="size-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300"><svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg></div>
                        </td>
                        <td class="px-6 py-3 font-mono text-xs text-brand-blue">{{ p.key }}</td>
                        <td class="px-6 py-3 font-medium text-deep-onyx">{{ p.title_es }}</td>
                        <td class="px-6 py-3 text-steel-grey text-xs">{{ p.category }}</td>
                        <td class="px-6 py-3 text-steel-grey text-xs">{{ p.year }}</td>
                        <td class="px-6 py-3"><span :class="p.active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'" class="px-2 py-0.5 rounded-full text-[10px] font-bold border">{{ p.active ? 'Sí' : 'No' }}</span></td>
                        <td class="px-6 py-3 text-right">
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
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" />
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-black text-deep-onyx">{{ modalTitle }}</h2>
                            <button @click="closeModal" class="size-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-steel-grey"><svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-5">
                            <div class="grid grid-cols-3 gap-4">
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Key *</label><input v-model="form.key" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Categoría</label><input v-model="form.category" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Año</label><input v-model.number="form.year" type="number" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Título ES *</label><input v-model="form.title_es" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Título EN *</label><input v-model="form.title_en" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none" /></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Descripción ES *</label><textarea v-model="form.description_es" rows="3" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none resize-none" /></div>
                                <div><label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Descripción EN *</label><textarea v-model="form.description_en" rows="3" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 outline-none resize-none" /></div>
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
                                <input type="file" accept="image/*" @change="e => imageFile = e.target.files[0]" class="w-full text-sm text-steel-grey file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-blue/10 file:text-brand-blue" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-steel-grey mb-1.5">Galería</label>
                                <input type="file" accept="image/*" multiple @change="e => galleryFiles = [...e.target.files]" class="w-full text-sm text-steel-grey file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-brand-blue/10 file:text-brand-blue" />
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
                                <button type="button" @click="closeModal" class="px-5 py-2.5 rounded-xl text-sm font-bold text-steel-grey hover:bg-slate-100 transition-colors">Cancelar</button>
                                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-brand-blue hover:bg-brand-blue/90 shadow-md transition-all">{{ editing ? 'Actualizar' : 'Crear' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.25s ease; }
.modal-enter-active > div:last-child, .modal-leave-active > div:last-child { transition: transform 0.25s ease, opacity 0.25s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from > div:last-child { transform: scale(0.95) translateY(10px); opacity: 0; }
.modal-leave-to > div:last-child { transform: scale(0.95) translateY(10px); opacity: 0; }
</style>
