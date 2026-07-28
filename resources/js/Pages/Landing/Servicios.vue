<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';
import ServicesPage from '@/Components/ServicesPage.vue';

const { t } = useI18n();

defineOptions({ layout: LandingAppLayout });
</script>

<template>
    <Head :title="t('pages.services.title')" />

    <ServicesPage />
</template>
