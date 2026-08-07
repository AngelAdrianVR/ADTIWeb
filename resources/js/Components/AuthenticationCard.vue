<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#F4F8FA] relative overflow-hidden">
        <!-- Subtle tech grid -->
        <div class="absolute inset-0 pointer-events-none opacity-[0.03]"
            style="background-image: linear-gradient(#0082A8 1px, transparent 1px), linear-gradient(90deg, #0082A8 1px, transparent 1px); background-size: 60px 60px;"
        />

        <!-- Subtle glow orb -->
        <div class="absolute w-[500px] h-[500px] rounded-full bg-[#0082A8]/4 blur-3xl" style="top: 50%; left: 50%; transform: translate(-50%, -50%);" />

        <!-- Content -->
        <div class="relative z-10 w-full flex flex-col items-center px-4">
            <div class="mb-8">
                <slot name="logo" />
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white/80 backdrop-blur-sm shadow-xl shadow-[#0082A8]/5 rounded-2xl border border-[#0082A8]/10">
                <slot />
            </div>
        </div>
    </div>
</template>
