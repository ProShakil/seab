<script setup>
    import MainLayout from '@/Layouts/MainLayout.vue'
    import { Link } from '@inertiajs/vue3'
    defineProps({
        blog: Object,
        previous: Object,
        next: Object,
        relatedBlogs: Array
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
                    Blog Details
                </h1>       
            </div>
        </section>
        <div class="max-w-6xl mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-3 gap-10">

            <!-- LEFT SIDE -->
            <div class="lg:col-span-2">
                <h1 class="text-3xl font-bold text-primary mb-3">
                    {{ blog.title }}
                </h1>

                <p class="text-sm text-gray-500 mb-6">
                    {{ new Date(blog.created_at).toLocaleDateString('en-US', { month:'short',day:'2-digit', year:'numeric' }) }}
                </p>

                <!-- CONTENT (Quill HTML) -->
                <div class="prose max-w-none" v-html="blog.content"></div>

                <!-- PREV / NEXT -->
                <div class="flex justify-between mt-10">

                    <Link
                        v-if="previous"
                        :href="route('blogs.show', previous.slug)"
                        class="px-4 py-2 border rounded hover:bg-gray-100"
                    >
                        ← Previous
                    </Link>

                    <div v-else></div>

                    <Link
                        v-if="next"
                        :href="route('blogs.show', next.slug)"
                        class="px-4 py-2 border rounded hover:bg-gray-100"
                    >
                        Next →
                    </Link>

                </div>

            </div>

            <!-- RIGHT SIDE (SIDEBAR) -->
            <div class="space-y-4">

                <h3 class="text-lg font-bold text-primary mb-4">Latest Posts</h3>

                <div v-for="item in relatedBlogs" :key="item.id"
                    class="flex gap-3 items-start border-b pb-3">

                    <img
                        :src="`/storage/${item.thumbnail}`"
                        class="w-16 h-16 object-cover rounded"
                    />

                    <div>
                        <Link :href="route('blogs.show', item.slug)">
                            <h4 class="text-sm font-semibold hover:text-primary line-clamp-2">
                                {{ item.title }}
                            </h4>
                        </Link>

                        <p class="text-xs text-gray-400">
                            {{ new Date(item.created_at).toLocaleDateString('en-US', {
                                month:'short',
                                day:'2-digit'
                            }) }}
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </MainLayout>
</template>