<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    name: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar sesión" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-6 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-sm font-medium text-emerald-700">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <!-- Username -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-[0.15em] text-[#6D6E71] mb-2">
                    Usuario
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="block w-full px-4 py-3 rounded-xl border border-[#0082A8]/15 bg-white text-[#0F172A] placeholder-[#6D6E71]/50 shadow-sm transition-all duration-200 focus:border-[#0082A8] focus:ring-2 focus:ring-[#0082A8]/20 focus:outline-none"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Tu nombre de usuario"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Password -->
            <div class="mt-5">
                <label for="password" class="block text-xs font-bold uppercase tracking-[0.15em] text-[#6D6E71] mb-2">
                    Contraseña
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="block w-full px-4 py-3 rounded-xl border border-[#0082A8]/15 bg-white text-[#0F172A] placeholder-[#6D6E71]/50 shadow-sm transition-all duration-200 focus:border-[#0082A8] focus:ring-2 focus:ring-[#0082A8]/20 focus:outline-none"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between mt-5">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="text-sm text-[#6D6E71]">Recordarme</span>
                </label>

                <Link v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-[#0082A8] hover:text-[#00D4FF] transition-colors duration-200">
                    ¿Olvidaste tu contraseña?
                </Link>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="mt-6 w-full py-3 px-4 rounded-xl font-semibold text-sm uppercase tracking-[0.1em] text-white bg-[#0082A8] hover:bg-[#00D4FF] hover:text-[#0F172A] shadow-lg shadow-[#0082A8]/20 hover:shadow-[#00D4FF]/25 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing">
                {{ form.processing ? 'Ingresando...' : 'Ingresar' }}
            </button>
        </form>

        <!-- Back to home -->
        <div class="mt-6 text-center">
            <Link :href="route('inicio')"
                class="inline-flex items-center gap-2 text-sm text-[#6D6E71] hover:text-[#0082A8] transition-colors duration-200">
                <svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Volver al inicio
            </Link>
        </div>
    </AuthenticationCard>
</template>
