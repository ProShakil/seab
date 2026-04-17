<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { router, Link } from '@inertiajs/vue3'
import { reactive, watch } from 'vue'

const { memberlist, membershipTypes, technologies, filters } = defineProps({
    memberlist: Object,
    membershipTypes: Array,
    technologies: Array,
    filters: Object
})

const form = reactive({
    search: filters?.search ?? '',
    technology_id: filters?.technology_id ?? '',
    membership_type_id: filters?.membership_type_id ?? '',
})

watch(form, (value) => {
    router.get(route('member.list'), value, {
        preserveState: true,
        replace: true,
    })
}, { deep: true })
</script>

<template>
<MainLayout>
    <div class="max-w-6xl mx-auto px-6 py-16">
        <section class="mb-12">
            <h1 class="font-headline text-5xl font-extrabold tracking-tight text-primary mb-4">Members Directory</h1>
            <p class="text-on-surface-variant max-w-2xl text-lg leading-relaxed">
                Connect with distinguished engineers across Sarishabari. Our professional network facilitates collaboration, technical excellence, and heritage preservation.
            </p>
        </section>
        <section class="mb-12">
            <div class="bg-surface-container-low p-6 rounded-xl space-y-6">
               <div class="flex flex-col md:flex-row gap-4 items-end">
                  <div class="w-full md:flex-1 space-y-2">
                     <label class="block text-xs font-bold uppercase tracking-wider text-outline">Search Professionals</label>
                     <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
                        <input v-model="form.search" class="w-full pl-12 pr-4 py-3 bg-surface-container-highest border-none rounded-lg focus:ring-2 focus:ring-primary/20 text-sm" placeholder="Search by name, company, or specialization..." type="text"/>
                     </div>
                  </div>
                  <div class="w-full md:w-64 space-y-2">
                     <label class="block text-xs font-bold uppercase tracking-wider text-outline">Technology</label>
                     <select v-model="form.technology_id" class="w-full px-4 py-3 ...">
                        <option value="">All Technologies</option>

                        <option
                            v-for="tech in technologies"
                            :key="tech.id"
                            :value="tech.id"
                        >
                            {{ tech.name }}
                        </option>
                    </select>
                  </div>
                  <div class="w-full md:w-64 space-y-2">
                     <label class="block text-xs font-bold uppercase tracking-wider text-outline">Membership Type</label>
                     <select v-model="form.membership_type_id" class="w-full px-4 py-3 ...">
                        <option value="">All Membership Type</option>

                        <option
                            v-for="type in membershipTypes"
                            :key="type.id"
                            :value="type.id"
                        >
                            {{ type.name }}
                        </option>
                    </select>
                  </div>
               </div>
            </div>
         </section>
         
         <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- CARD -->
            <div v-for="user in memberlist.data" :key="user.id"  class="group relative bg-white rounded-2xl p-6 shadow-sm border hover:shadow-xl transition">

                <!-- icon -->
                <div class="absolute top-3 right-3 opacity-10 pointer-events-none">
                    <span class="material-symbols-outlined text-7xl">engineering</span>
                </div>

                <!-- profile -->
                <div class="flex items-center gap-4">
                    <div class="shrink-0">
                     <img
                        class="w-20 h-20 rounded-full object-cover ring-4 ring-surface-container"
                        :src="user.profile_image
                            ? '/storage/' + user.profile_image
                            : '/assets/default.jpg'"
                        :alt="user.name"
                    />
                  </div>
                  <div class="space-y-1">
                     <div class="flex items-center gap-2">
                        <h3 class="font-semibold font-bold text-text-base text-primary">{{ user.name }}</h3>
                     </div>
                     
                     <p class="text-sm  font-medium text-tertiary uppercase tracking-wide">{{ user.membership_type?.name }} #{{user.membership_id}}</p>
                     <p class="text-sm text-on-surface-variant font-medium">{{ user.designation }}</p>
                     <p class="text-xs text-outline">{{ user.employer_name }}</p>
                  </div>
                </div>
               <div class="mt-6 pt-6 border-t border-outline-variant/30 flex justify-between items-center">
                  <span
                        class="inline-flex items-center px-3 py-1 rounded-full
                            bg-indigo-500/10 text-indigo-600 text-xs font-semibold
                            border border-indigo-500/20"
                    >
                        {{ user.technology?.name }}
                    </span>
                    <Link
                        :href="route('member.profile', user.id)"
                        class="text-primary hover:text-primary-container font-bold text-xs uppercase tracking-widest flex items-center gap-1 transition-colors">
                        View Profile
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </Link>
               </div>

            </div>            
        </section>
        <div class="mt-8 flex justify-center gap-2 flex-wrap">
            <Link
                v-for="(link, index) in memberlist.links"
                :key="index"
                :href="link.url ?? ''"
                v-html="link.label"
                class="px-3 py-2 text-sm rounded-lg border"
                :class="[
                    link.active
                        ? 'bg-blue-600 text-white border-blue-600 shadow-lg'
                        : 'text-gray-700 border-gray-300 hover:bg-gray-100'
                ]"
            />
            <!-- bg-indigo-500 text-indigo-950 border-indigo-600 shadow-lg ring-2 ring-indigo-400 -->
        </div>
        
    </div>
</MainLayout>
</template>