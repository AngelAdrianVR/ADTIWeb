<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';
import ServicesCards from '@/Components/ServicesCards.vue';

const { t } = useI18n();

defineOptions({ layout: LandingAppLayout });
</script>

<template>
    <Head :title="t('pages.services.title')" />

    <ServicesCards />
</template>
