<script setup>
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LandingAppLayout from '@/Layouts/LandingAppLayout.vue';
import HeroSection from '@/Components/HeroSection.vue';
import ServicesCards from '@/Components/ServicesCards.vue';
import ClientsRibbon from '@/Components/ClientsRibbon.vue';
import CompanyMap3D from '@/Components/CompanyMap3D.vue';
import CertificationsShowcase from '@/Components/CertificationsShowcase.vue';
import FeaturedProjects from '@/Components/FeaturedProjects.vue';
import VideoShowcase from '@/Components/VideoShowcase.vue';
import ContactForm from '@/Components/ContactForm.vue';
import StatsShowcase from '@/Components/StatsShowcase.vue';

const { t } = useI18n();

const props = defineProps({
    servicios: {
        type: Array,
        default: () => [],
    },
    proyectos: {
        type: Array,
        default: () => [],
    },
    certificaciones: {
        type: Array,
        default: () => [],
    },
});

defineOptions({
    layout: LandingAppLayout,
});
</script>

<template>
    <Head :title="t('nav.home')" />

    <HeroSection />

    <ServicesCards :servicios="servicios" />

    <FeaturedProjects :proyectos="proyectos" />

    <CompanyMap3D />

    <ClientsRibbon />

    <CertificationsShowcase :certificaciones="certificaciones" />

    <StatsShowcase />

    <VideoShowcase />

    <ContactForm />
</template>
