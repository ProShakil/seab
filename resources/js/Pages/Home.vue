<template>
  <MainLayout>

    <!-- HERO -->
    <section class="relative min-h-[calc(100dvh-70px)] md:min-h-[calc(100vh-70px)] flex items-center pt-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img v-for="(img, i) in images" :key="i" v-show="current === i" :src="img" class="absolute inset-0 w-full h-full object-cover brightness-[0.75] grayscale-[15%] transition-opacity duration-1000" />
        </div>
        <div class="absolute inset-0 bg-[#001E3C]/60 z-10"></div>
        <div class="relative z-20 max-w-7xl mx-auto px-2 w-full">
            <div class="max-w-3xl">

                <!-- BADGE -->
                <div class="inline-block py-1 px-3 mb-6 rounded-full border border-[#D4AF37]/30 bg-[#D4AF37]/10 text-[#D4AF37] text-xs font-bold tracking-widest uppercase transition-all duration-700" :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                    Established 2024
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold text-white leading-tight tracking-tighter mb-6 transition-all duration-700 delay-150" :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                    Building a <span class="text-[#D4AF37]">Sustainable</span> Future Together
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-10 leading-relaxed transition-all duration-700 delay-300" :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                    Sarishabari Engineers Association of Bangladesh (SEAB) unites precision, innovation, and heritage to advance the engineering landscape of our beloved region.
                </p>

                <!-- BUTTONS -->
                <div class="flex flex-col sm:flex-row gap-4 transition-all duration-700 delay-500" :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
                    <button class="bg-[#D4AF37] text-black px-8 py-4 rounded-xl font-bold text-lg hover:scale-[1.02] transition">
                        Join Association
                    </button>

                    <button class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/20 transition">
                        Explore Projects
                    </button>

                </div>

            </div>
        </div>
        <div class="absolute bottom-12 right-12 hidden lg:block z-20">
            <div class="bg-white/5 backdrop-blur-xl p-6 rounded-2xl border border-white/10 max-w-xs">
                <p class="text-[#D4AF37] font-bold text-3xl mb-1">500+</p>
                <p class="text-white text-sm font-medium opacity-80">Certified engineering professionals in our community</p>
            </div>
        </div>
    </section>

    <section class="py-24 bg-surface-container-low overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl relative z-10">
                        <img class="w-full h-full object-cover" data-alt="Professional engineer in safety gear examining a technical blueprint in a modern office environment, high-end corporate style" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4LjmvdxyI9tYeYObl6Mjd7AvhFSQkhbHwNRrVVjQhHsWSdmvwgX2wAtMdGOMDlkZ-AXHDI2_aXzs1UsDiytd1ikYRyIqLRK02MQzBVxdSK7mHzdL57ecYTgJyiD0dZRo4V_kklmOKwNQJ5iLZHPHvfJDmMHY9N0NTzCnsHhy0TxRele796Zpjl1MuPBmDtph5KdUYlBAsH_Ea8aGnFsN1mzF-edxRb9KwyJn_TZYJgKRc_Y5xbZEXBjfZZ1YfUjQyEXzq34MTlgY"/>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-2/3 aspect-square bg-primary-container rounded-2xl -z-0 opacity-10"></div>
                    <div class="absolute top-1/2 -left-8 -translate-y-1/2 p-6 bg-white shadow-xl rounded-xl hidden md:block z-10">
                        <span class="material-symbols-outlined text-tertiary text-4xl block mb-2">engineering</span>
                        <p class="font-headline font-bold text-primary">Technical Excellence</p>
                        <p class="text-xs text-outline">Rooted in Sarishabari</p>
                    </div>
                </div>
                <div>
                    <h2 class="text-primary font-headline text-4xl font-extrabold mb-8 leading-tight">Elevating Engineering Standards Across the Nation</h2>
                    <p class="text-on-surface-variant font-body leading-loose mb-8 text-lg">
                    SEAB is more than an organization; it is a collaborative ecosystem designed to empower engineers from Sarishabari. We bridge the gap between academic knowledge and industrial expertise, ensuring our members remain at the forefront of global engineering trends while contributing to local development.
                    </p>
                    <div class="space-y-6">
                    <div class="flex gap-4 items-start">
                        <div class="bg-tertiary-fixed p-3 rounded-lg text-on-tertiary-fixed">
                            <span class="material-symbols-outlined">diversity_3</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-primary">Professional Networking</h4>
                            <p class="text-sm text-on-surface-variant">Connect with industry leaders and fellow alumni from major engineering institutions.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="bg-secondary-fixed p-3 rounded-lg text-on-secondary-fixed">
                            <span class="material-symbols-outlined">model_training</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-primary">Continuous Learning</h4>
                            <p class="text-sm text-on-surface-variant">Workshops and seminars focused on the latest technological advancements and sustainable practices.</p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-primary font-headline text-4xl font-extrabold mb-4">Our Core Directives</h2>
                <div class="w-20 h-1 bg-tertiary mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-surface-container-low p-10 rounded-2xl group hover:bg-primary transition-all duration-500">
                    <div class="mb-8 inline-flex items-center justify-center w-16 h-16 rounded-xl bg-primary-container text-white group-hover:bg-tertiary group-hover:text-on-tertiary transition-colors">
                        <span class="material-symbols-outlined text-3xl">visibility</span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4 group-hover:text-white">Our Vision</h3>
                    <p class="text-on-surface-variant group-hover:text-slate-300 leading-relaxed text-lg">
                        {{ frontMessages?.vision }}
                    </p>
                </div>
                <div class="bg-surface-container-low p-10 rounded-2xl group hover:bg-primary transition-all duration-500">
                    <div class="mb-8 inline-flex items-center justify-center w-16 h-16 rounded-xl bg-primary-container text-white group-hover:bg-tertiary group-hover:text-on-tertiary transition-colors">
                        <span class="material-symbols-outlined text-3xl">track_changes</span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-4 group-hover:text-white">Our Mission</h3>
                    <p class="text-on-surface-variant group-hover:text-slate-300 leading-relaxed text-lg">
                        {{ frontMessages?.mission }}
                    </p>
                </div>
            </div>
        </div>
    </section>



    <section v-if="blogs && blogs.length > 0" class="py-24 bg-surface-container-highest">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-primary font-headline text-4xl font-extrabold">Industry Insights</h2>
                    <p class="text-on-surface-variant mt-2">The latest from the engineering frontier in Bangladesh.</p>
                </div>
                <Link :href="route('blogs.index')"  class="text-primary font-bold border-b-2 border-primary pb-1 hover:text-tertiary hover:border-tertiary transition-all">View All Stories</Link>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article v-for="blog in blogs" :key="blog.id" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow group">
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
        </div>
    </section>
    
    <section v-if="committees && committees.length >= 2" class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-primary font-headline text-4xl font-extrabold mb-4">Executive Leadership</h2>
                <p class="text-on-surface-variant max-w-2xl mx-auto">Guided by experience and a passion for engineering excellence.</p>
            </div>
            <div class="flex flex-col md:flex-row justify-center gap-12">
                <div class="flex flex-col items-center text-center">
                    <div class="w-48 h-48 rounded-full overflow-hidden mb-6 border-4 border-surface-container-highest shadow-lg"> 
                        <img class="w-full h-full object-cover" :src="committees[0]?.profile_image ? '/storage/' + committees[0].profile_image : '/assets/default.jpg'" :alt="committees[0]?.user_name" />
                    </div>
                    <h4 class="text-2xl font-bold text-primary">{{ committees[0]?.user_name }}</h4>
                    <p class="text-tertiary font-bold uppercase tracking-widest text-xs mt-1">Chief-Advisor, SEAB</p>
                    <p class="text-on-surface-variant mt-4 text-sm max-w-xs italic">
                        {{
                            (frontMessages?.president_message || '').length > 90
                                ? (frontMessages?.president_message || '').substring(0, 90) + '...'
                                : (frontMessages?.president_message || '')
                        }}
                    </p>
                    <Link preserve-scroll
                        v-if="(frontMessages?.president_message || '').length > 90"
                        :href="route('about', 'president_message')"
                        class="text-primary font-semibold"
                    >
                        Read More
                    </Link>
                </div>
                <div class="hidden md:block w-px bg-outline-variant/30 self-stretch"></div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-48 h-48 rounded-full overflow-hidden mb-6 border-4 border-surface-container-highest shadow-lg">
                        <img class="w-full h-full object-cover" :src="committees[1]?.profile_image ? '/storage/' + committees[1].profile_image : '/assets/default.jpg'" :alt="committees[1]?.user_name" />
                    </div>
                    <h4 class="text-2xl font-bold text-primary">{{ committees[1]?.user_name }}</h4>
                    <p class="text-tertiary font-bold uppercase tracking-widest text-xs mt-1">Advisor, SEAB</p>
                    <p class="text-on-surface-variant mt-4 text-sm max-w-xs italic">
                        {{
                            (frontMessages?.vice_president_message || '').length > 90
                                ? (frontMessages?.vice_president_message || '').substring(0, 90) + '...'
                                : (frontMessages?.vice_president_message || '')
                        }}
                    </p>
                    <Link preserve-scroll
                        v-if="(frontMessages?.vice_president_message || '').length > 90"
                        :href="route('about', 'vice_president_message')"
                        class="text-primary font-semibold"
                    >
                        Read More
                    </Link>                
                </div>
            </div>
        </div>
    </section>

    <section class="relative py-20 bg-gradient-to-r from-[#003366] to-[#001E3C] text-white overflow-hidden">
        <div class="absolute -top-10 -right-10 w-72 h-72 bg-[#D4AF37]/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-[#D4AF37]/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-6xl mx-auto px-6 text-center">
            <!-- TITLE -->
            <h2 class="text-3xl md:text-4xl font-extrabold leading-tight">
            Join the <span class="text-[#D4AF37]">SEAB Community</span>
            </h2>

            <!-- MESSAGE -->
            <p class="mt-4 text-slate-300 max-w-2xl mx-auto text-lg">
                Every diploma engineer of Sarishabari is invited to join the 
                Sarishabari Engineer's Association, Bangladesh (SEAB). 
                Let's build a stronger engineering future together.
            </p>

            <!-- BUTTONS -->
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">              
                <Link preserve-scroll :href="route('register')" class="bg-[#D4AF37] text-black px-8 py-3 rounded-xl font-bold text-lg hover:scale-[1.05] transition shadow-lg">
                    Become a Member
                </Link>             
                <Link preserve-scroll class="bg-white/10 border border-white/20 px-8 py-3 rounded-xl font-bold text-lg hover:bg-white/20 transition" :href="route('about', 'membership_process')">Learn More</Link>
            </div>

        </div>
    </section>

    <section id="gallery" class="max-w-7xl mx-auto py-16 px-6">
        <div class="text-center mb-16">
            <h2 class="text-primary font-headline text-4xl font-extrabold mb-4">Gallery</h2>
            <div class="w-20 h-1 bg-tertiary mx-auto rounded-full"></div>
        </div>
        <div class="flex justify-center items-center mb-8">
            <div class="flex gap-3 mt-4 md:mt-0">
                <button @click="filter = 'all'" :class="btnClass('all')">All</button>
                <button @click="filter = 'photo'" :class="btnClass('photo')">Photos</button>
                <button @click="filter = 'video'" :class="btnClass('video')">Videos</button>
            </div>
        </div>
        <div class="grid md:grid-cols-4 gap-4">
            <div v-for="item in filteredGalleries" :key="item.id" class="relative group overflow-hidden rounded-xl border cursor-pointer" @click="openModal(item)">               
                <img 
                    v-if="item.type === 'photo'"
                    :src="getImage(item.image)"
                    class="w-full h-40 object-cover group-hover:scale-110 transition duration-500"
                />
                <!-- VIDEO -->
                <div v-else class="relative">
                    <iframe
                        :src="item.embed_url"
                        class="w-full h-40"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>

                <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition">
                    <span class="material-symbols-outlined text-white text-4xl">
                        search
                    </span>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-10">
            <Link 
                :href="route('gallery.list')" 
                class="bg-[#003366] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#002244] transition"
            >
                View Gallery
            </Link>
        </div>

        <div v-if="showModal"
            class="fixed inset-0 bg-black/90 flex items-center justify-center z-50"
            @click="closeModal"
        >

            <!-- PHOTO -->
            <img 
                v-if="activeItem?.type === 'photo'"
                :src="getImage(activeItem.image)"
                class="max-h-[90vh] max-w-[90vw] rounded-lg"
            />

            <!-- VIDEO -->
            <iframe
                v-else
                :src="activeItem?.embed_url"
                class="w-[90vw] h-[80vh]"
                frameborder="0"
                allowfullscreen
            ></iframe>

        </div>
    </section>

  </MainLayout>
</template>

<script setup>
import { ref, onMounted,computed  } from 'vue'
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue'

const props = defineProps({
    frontMessages: Object,
    committees: Array,
    galleries: Array,
    blogs: Array,
});
const images = [
  'https://images.unsplash.com/photo-1503387762-592deb58ef4e',
  'https://plus.unsplash.com/premium_photo-1661499003600-c24ac7ae42bd',
  'https://images.unsplash.com/photo-1451187580459-43490279c0fa'
]

const current = ref(0)
const show = ref(false)

onMounted(() => {
  show.value = true

  setInterval(() => {
    current.value = (current.value + 1) % images.length
  }, 5000)
})

const filter = ref('all')

// filtered data
const filteredGalleries = computed(() => {
    if (filter.value === 'photo') {
        return props.galleries.filter(item => item.type === 'photo')
    }

    if (filter.value === 'video') {
        return props.galleries.filter(item => item.type === 'video')
    }

    return props.galleries
})

// button style
const btnClass = (type) => [
    'px-4 py-2 rounded-lg text-sm font-semibold transition',
    filter.value === type
        ? 'bg-[#003366] text-white'
        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
]

// image helper
const getImage = (path) => `/storage/${path}`

const showModal = ref(false)
const activeItem = ref(null)

const openModal = (item) => {
    activeItem.value = item
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    activeItem.value = null
}
</script>