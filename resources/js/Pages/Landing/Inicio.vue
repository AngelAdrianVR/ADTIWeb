<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';
import HeroSection from '@/Components/HeroSection.vue';
import ServicesCards from '@/Components/ServicesCards.vue';
import ClientsRibbon from '@/Components/ClientsRibbon.vue';
import VideoShowcase from '@/Components/VideoShowcase.vue';
import ContactForm from '@/Components/ContactForm.vue';
import StatsShowcase from '@/Components/StatsShowcase.vue';

const { t } = useI18n();

defineOptions({
    layout: LandingAppLayout,
});
</script>

<template>
    <Head :title="t('nav.home')" />

    <HeroSection />

    <ServicesCards />

    <ClientsRibbon />

    <StatsShowcase />

    <VideoShowcase />

    <ContactForm />
</template>
