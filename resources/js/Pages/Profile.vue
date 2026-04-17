<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    user: Object
})

function goBack() {
    if (window.history.length > 1) {
        window.history.back()
    } else {
        window.location.href = '/'
    }
}
</script>

<template>
<MainLayout>

    <!-- HEADER -->
    <section class="bg-[#001e3c] text-white py-10">
        <div class="max-w-5xl mx-auto px-6">

            <!-- BACK BUTTON -->
            <button
                @click="goBack"
                class="inline-flex items-center gap-2 text-sm text-gray-300 hover:text-white mb-6">

                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Back
            </button>

            <!-- PROFILE HEADER -->
            <div class="flex items-center gap-5">

                <img
                    class="w-24 h-24 rounded-full object-cover ring-4 ring-white/20"
                    :src="user.profile_image
                        ? '/storage/' + user.profile_image
                        : '/assets/default.jpg'"
                />

                <div>
                    <h1 class="text-2xl font-bold">
                        {{ user.name }}
                    </h1>

                    <p class="text-gray-300">
                        {{ user.designation || 'Member' }}
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- CONTENT -->
    <section class="max-w-5xl mx-auto px-6 py-10">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

            <!-- PERSONAL -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="font-semibold mb-4">Personal Info</h2>

                <p><strong>Father:</strong> {{ user.user_fathers_name }}</p>
                <p><strong>Mother:</strong> {{ user.user_mothers_name }}</p>
                <p><strong>DOB:</strong> {{ user.dob }}</p>
                <p><strong>Gender:</strong> {{ user.gender }}</p>
                <p><strong>NID:</strong> {{ user.national_identity_number }}</p>
            </div>

            <!-- CONTACT -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="font-semibold mb-4">Contact</h2>

                <p><strong>Email:</strong> {{ user.email }}</p>
                <p><strong>Phone:</strong> {{ user.contact_no }}</p>
                <p><strong>Emergency:</strong> {{ user.emergency_contact_name }}</p>
                <p><strong>Emergency No:</strong> {{ user.emergency_contact_no }}</p>
            </div>

            <!-- WORK -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="font-semibold mb-4">Work</h2>

                <p><strong>Employer:</strong> {{ user.employer_name }}</p>
                <p><strong>Designation:</strong> {{ user.designation }}</p>
                <p><strong>Office:</strong> {{ user.office_address_details }}</p>
            </div>

            <!-- EDUCATION -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h2 class="font-semibold mb-4">Education</h2>

                <p><strong>Degree:</strong> {{ user.latest_degree_name }}</p>
                <p><strong>Institute:</strong> {{ user.latest_institute_name }}</p>
            </div>

        </div>

    </section>

</MainLayout>
</template>