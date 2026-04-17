<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    committees: Array,
    committee_name: String
})

const search = ref('')
const selectedMember = ref(null)

// 🔍 filtered members
const filteredMembers = computed(() => {
    if (!search.value) return props.committees

    return props.committees.filter(m =>
        m.user?.name?.toLowerCase().includes(search.value.toLowerCase()) ||
        m.designation?.name?.toLowerCase().includes(search.value.toLowerCase())
    )
})
</script>

<style>
main {
    background-color: #f7f7f7;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>

<template>
<MainLayout>

    <!-- HEADER -->
    <section class="relative h-[35vh] flex items-center justify-center overflow-hidden bg-[#001e3c]">

        <div class="absolute inset-0 bg-gradient-to-br from-[#001e3c] via-[#002b55] to-[#001e3c]"></div>

        <!-- Waves -->
        <svg class="absolute bottom-0 w-full h-[120px]" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#D4AF37" fill-opacity="0.18"
                d="M0,160L80,165.3C160,171,320,181,480,165.3C640,149,800,107,960,112C1120,117,1280,171,1360,197.3L1440,224L1440,320L0,320Z" />
            <path fill="#D4AF37" fill-opacity="0.10"
                d="M0,96L60,90C120,85,240,75,360,90C480,105,600,149,720,154.7C840,160,960,128,1080,122.7C1200,117,1320,139,1380,149.3L1440,160L1440,320L0,320Z" />
        </svg>

        <div class="relative z-10 text-center px-6">
            <h1 class="text-3xl md:text-5xl font-bold text-white">
                {{ committee_name }}
            </h1>
            <p class="mt-4 text-gray-300 max-w-2xl mx-auto text-lg">
                Committee Members
            </p>
        </div>
    </section>


    <!-- CONTENT -->
    <div class="max-w-6xl mx-auto px-6 py-16">
        <!-- SEARCH -->
        <div class="max-w-2xl mx-auto mb-10">
            <div class="relative">

                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    search
                </span>

                <input
                    v-model="search"
                    type="text"
                    placeholder="Search member by name or designation..."
                    class="w-full h-12 pl-12 pr-4 rounded-xl border border-gray-200
                        focus:outline-none focus:ring-2 focus:ring-blue-200"
                />
            </div>
        </div>
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- EMPTY STATE -->
            <div v-if="filteredMembers.length === 0"
                class="col-span-full text-center py-20">

                <div class="text-gray-400">
                    <span class="material-symbols-outlined text-6xl">search_off</span>
                </div>

                <h2 class="text-xl font-semibold text-gray-700 mt-3">
                    No Members Found
                </h2>

                <p class="text-gray-500 text-sm">
                    Try adjusting your search keyword
                </p>
            </div>

            <!-- CARD -->
            <div
                v-for="member in filteredMembers"
                :key="member.id"
                class="group relative bg-white rounded-2xl p-6 shadow-sm border hover:shadow-xl transition">

                <!-- icon -->
                <div class="absolute top-3 right-3 opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-7xl">engineering</span>
                </div>

                <!-- profile -->
                <div class="flex items-center gap-4">

                    <img
                        class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-100"
                        :src="member.user?.profile_image
                            ? '/storage/' + member.user.profile_image
                            : '/assets/default.jpg'"
                        :alt="member.user?.name"
                    />

                    <div>
                        <h3 class="font-semibold text-lg">
                            {{ member.user?.name }}
                        </h3>

                        <p class="text-sm text-gray-600">
                            {{ member.designation?.name }}
                        </p>
                    </div>

                </div>

                <!-- button -->
                <div class="mt-5 pt-4 border-t">
                    <Link
                        :href="route('member.profile', member.user.id)"
                        class="text-blue-600 text-xs font-bold uppercase flex items-center gap-1">
                        View Profile
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </Link>
                </div>

            </div>

        </section>

    </div>

</MainLayout>
</template>