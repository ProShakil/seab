<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'

defineProps({
    frontMessages: Object,
    meta: Object
})
</script>

<template>
    <MainLayout>
        <section class="relative h-[35vh] flex items-center justify-center overflow-hidden bg-[#001e3c]">

            <!-- Animated Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#001e3c] via-[#002b55] to-[#001e3c] animate-bg"></div>

            <!-- Wave SVG -->
            <svg class="absolute bottom-0 w-full h-[160px]" viewBox="0 0 1440 320" preserveAspectRatio="none">

                <path class="wave wave1"
                    fill="#D4AF37" fill-opacity="0.18"
                    d="M0,160L80,165.3C160,171,320,181,480,165.3C640,149,800,107,960,112C1120,117,1280,171,1360,197.3L1440,224L1440,320L0,320Z" />

                <path class="wave wave2"
                    fill="#D4AF37" fill-opacity="0.10"
                    d="M0,96L60,90C120,85,240,75,360,90C480,105,600,149,720,154.7C840,160,960,128,1080,122.7C1200,117,1320,139,1380,149.3L1440,160L1440,320L0,320Z" />
            </svg>

            <!-- Content -->
            <div class="relative z-10 text-center px-6">

                <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight">
                    {{ meta?.title }}
                </h1>

                <p class="mt-4 text-gray-300 max-w-2xl mx-auto text-lg">
                    {{ meta?.subtitle }}
                </p>

                <!-- small SaaS badge -->
                <div class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-sm text-gray-200">
                    ✦ Engineering Community Platform
                </div>

            </div>
        </section>
        <div class="max-w-5xl mx-auto px-6 py-16">
            <!-- Card -->
            <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-100" v-html="frontMessages">
            </div>

        </div>
    </MainLayout>
</template>