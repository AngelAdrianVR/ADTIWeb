<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

defineProps({
    mensajesUnread: Array,
    mensajesCount: Number,
    visitas: Object,
    visitasPorHora: Object,
    visitasPorDia: Object,
    visitasPorSemana: Object,
    visitasPorMes: Object,
});
</script>

<template>
    <AppLayout title="Dashboard">
        <Welcome
            :mensajes-unread="mensajesUnread"
            :mensajes-count="mensajesCount"
            :visitas="visitas"
            :visitas-por-hora="visitasPorHora"
            :visitas-por-dia="visitasPorDia"
            :visitas-por-semana="visitasPorSemana"
            :visitas-por-mes="visitasPorMes"
        />
    </AppLayout>
</template>
