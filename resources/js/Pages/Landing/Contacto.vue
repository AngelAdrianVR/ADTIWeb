<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';

const { t } = useI18n();

defineOptions({ layout: LandingAppLayout });
</script>

<template>
    <Head :title="t('pages.contact.title')" />
    <section class="min-h-[calc(100vh-5rem)] flex items-center justify-center px-4">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-deep-onyx tracking-tight">{{ t('pages.contact.title') }}</h1>
            <p class="mt-4 text-steel-grey">{{ t('pages.contact.description') }}</p>
        </div>
    </section>
</template>
