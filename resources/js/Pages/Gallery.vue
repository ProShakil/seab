<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    galleries: Object,
    activeFilter: String
})

const items = computed(() => props.galleries.data)
const filter = ref(props.activeFilter || 'all')

// change filter (no reload)
const setFilter = (type) => {
    router.get(route('gallery.list'), { type }, {
        preserveState: true,
        replace: true
    })
}

// button style
const btnClass = (type) => [
    'px-4 py-2 rounded-lg text-sm font-semibold transition',
    filter.value === type
        ? 'bg-[#003366] text-white'
        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
]

// image helper
const getImage = (path) => `/storage/${path}`

// ===== LIGHTBOX =====
const showModal = ref(false)
const activeItem = ref(null)

const openModal = (item) => {
    activeItem.value = item
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}
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
                    Gallery
                </h1>       
            </div>
        </section>

        <section class="max-w-5xl mx-auto py-16 px-6">

            <!-- FILTER -->
            <div class="flex justify-center mb-8 gap-3">
                <button @click="setFilter('all')" :class="btnClass('all')">All</button>
                <button @click="setFilter('photo')" :class="btnClass('photo')">Photos</button>
                <button @click="setFilter('video')" :class="btnClass('video')">Videos</button>
            </div>

            <!-- GRID -->
            <div class="grid md:grid-cols-4 gap-4">

                <div v-for="item in items"
                    :key="item.id"
                    class="relative border rounded-xl overflow-hidden cursor-pointer group"
                    @click="openModal(item)"
                >

                    <!-- PHOTO -->
                    <img 
                        v-if="item.type === 'photo'"
                        :src="getImage(item.image)"
                        loading="lazy"
                        class="w-full h-40 object-cover group-hover:scale-110 transition duration-500"
                    />

                    <!-- VIDEO -->
                    <div v-else class="relative">
                        <iframe
                            :src="item.embed_url"
                            class="w-full h-40 pointer-events-none"
                            loading="lazy"
                        ></iframe>
                    </div>

                    <!-- ICON -->
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition">
                        <span class="material-symbols-outlined text-white text-4xl">search</span>
                    </div>

                </div>

            </div>

            <!-- PAGINATION -->
            <div class="flex justify-center mt-10 gap-2 flex-wrap">
                <Link
                    v-for="link in props.galleries.links"
                    :key="link.label"
                    :href="link.url || ''"
                    v-html="link.label"
                    class="px-3 py-1 border rounded"
                    :class="{ 'bg-[#003366] text-white': link.active }"
                />
            </div>
        </section>

        <div v-if="showModal"
            class="fixed inset-0 bg-black/90 flex items-center justify-center z-50"
            @click="closeModal"
        >
            <img 
                v-if="activeItem?.type === 'photo'"
                :src="getImage(activeItem.image)"
                class="max-w-[90vw] max-h-[90vh]"
            />

            <iframe
                v-else
                :src="activeItem?.embed_url"
                class="w-[90vw] h-[80vh]"
                allowfullscreen
            ></iframe>
        </div>

    </MainLayout>
</template>