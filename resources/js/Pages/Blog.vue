<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue'
    import { Link } from '@inertiajs/vue3'
    defineProps({
        blogs: Object
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
                    Blog
                </h1>       
            </div>
        </section>
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article v-for="blog in blogs.data" :key="blog.id" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow group">
                    <div class="h-56 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="Modern geometric office building facade with glass reflections and architectural lines, minimalist engineering design" :src="`/storage/${blog.thumbnail}`"/>
                    </div>
                    <div class="p-8">
                        <Link :href="route('blogs.show', blog.slug)">
                            <h3 class="text-xl font-bold text-primary mb-4 leading-tight">{{ blog.title }}</h3>
                        </Link>
                        <p class="text-on-surface-variant text-sm mb-6 line-clamp-2">{{ blog.excerpt }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-outline">{{ new Date(blog.created_at).toLocaleDateString('en-US', { month:'short', day:'2-digit', year:'numeric' }) }}</span>
                            <Link :href="route('blogs.show', blog.slug)">
                                <span class="material-symbols-outlined text-primary group-hover:translate-x-2 transition-transform">arrow_forward</span>
                            </Link>
                        </div>
                    </div>
                </article>
            </div>

            <div class="mt-12 flex justify-center gap-2">
                <Link
                    v-for="link in blogs.links"
                    :key="link.label"
                    :href="link.url || ''"
                    v-html="link.label"
                    class="px-3 py-2 border rounded text-sm"
                    :class="{
                        'bg-primary text-white': link.active,
                        'opacity-50 pointer-events-none': !link.url
                    }"
                />
            </div>
        </div>
    </MainLayout>
</template>