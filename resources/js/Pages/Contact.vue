<script setup>
import { ref, reactive, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import MainLayout from '@/Layouts/MainLayout.vue'
import Swal from 'sweetalert2'

const form = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
})

// errors
const errors = reactive({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
})

/* =========================
   LIVE VALIDATION
========================= */

watch(() => form.name, (val) => {
    const clean = val.replace(/[^a-zA-Z\s]/g, '')
    form.name = clean

    if (!clean) errors.name = 'Name is required'
    else if (clean.length < 3) errors.name = 'Name must be at least 3 characters'
    else errors.name = ''
})

watch(() => form.email, (val) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

    if (!val) errors.email = 'Email is required'
    else if (!emailRegex.test(val)) errors.email = 'Invalid email address'
    else errors.email = ''
})

watch(() => form.phone, (val) => {
    const clean = val.replace(/[^0-9+]/g, '')
    form.phone = clean

    if (!clean) errors.phone = 'Phone is required'
    else if (!/^\+?[0-9]{7,15}$/.test(clean)) errors.phone = 'Invalid phone number'
    else errors.phone = ''
})

watch(() => form.subject, (val) => {
    if (!val) errors.subject = 'Subject is required'
    else if (val.length < 3) errors.subject = 'Subject too short'
    else errors.subject = ''
})

watch(() => form.message, (val) => {
    if (!val) errors.message = 'Message is required'
    else if (val.length < 5) errors.message = 'Message too short'
    else errors.message = ''
})

/* =========================
   VALIDATE BEFORE SUBMIT
========================= */

const validateForm = () => {

    let valid = true

    if (!form.name) { errors.name = 'Name is required'; valid = false }
    if (!form.email) { errors.email = 'Email is required'; valid = false }
    if (!form.phone) { errors.phone = 'Phone is required'; valid = false }
    if (!form.subject) { errors.subject = 'Subject is required'; valid = false }
    if (!form.message) { errors.message = 'Message is required'; valid = false }

    return valid
}

/* =========================
   TOAST (FIXED)
========================= */

const toast = (msg, icon = 'success') => {
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon,
    title: msg,
    showConfirmButton: false,
    timer: 2000
  })
}

/* =========================
   SUBMIT
========================= */

const submit = () => {

    const isValid = validateForm()

    if (!isValid) {
        toast('Please fill all required fields correctly', 'error')
        return
    }

    form.post('/contact', {
        onSuccess: () => {

            toast('Message sent successfully!', 'success')
            form.reset()
        },
        onError: () => {
            toast('Something went wrong!', 'error')
        }
    })
}
</script>
<style>
    .input-floating {
        @apply w-full rounded-lg px-3 pt-5 pb-2
        focus:outline-none focus:ring-2 focus:ring-[#003366] transition;
    }

    .label-floating {
        @apply absolute left-3 top-2 text-gray-500 text-sm 
        transition-all duration-200 
        pointer-events-none bg-white px-1;
    }

    /* floating effect */
    .peer:placeholder-shown + .label-floating {
        @apply top-3 text-base text-gray-400;
    }

    .peer:focus + .label-floating,
    .peer:not(:placeholder-shown) + .label-floating {
        @apply top-1 text-xs text-[#003366];
    }

    .error {
        @apply text-red-500 text-sm mt-1;
    }
</style>
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
                    Contact Us
                </h1>       
            </div>
        </section>
        <section class="max-w-5xl mx-auto py-16 px-6">

            <div class="bg-white shadow-xl rounded-2xl p-8 space-y-6">

                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Name -->
                    <div class="relative">
                        <input v-model="form.name" placeholder=" " class="peer input-floating border" :class="errors.name ? 'border-red-500 ring-red-200' : 'border-gray-300'"/>
                        <label class="label-floating">Full Name</label>
                        <p v-if="errors.name" class="error">{{ errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <input v-model="form.email" placeholder=" " class="peer input-floating border" :class="{ 'border-red-500 ring-red-200': errors.email }"/>
                        <label class="label-floating">Email Address</label>
                        <p v-if="errors.email" class="error">{{ errors.email }}</p>
                    </div>

                    <!-- Phone -->
                    <div class="relative">
                        <input v-model="form.phone" placeholder=" " class="peer input-floating border" :class="{ 'border-red-500 ring-red-200': errors.phone }"/>
                        <label class="label-floating">Phone Number</label>
                        <p v-if="errors.phone" class="error">{{ errors.phone }}</p>
                    </div>

                    <!-- Subject -->
                    <div class="relative">
                        <input v-model="form.subject" placeholder=" " class="peer input-floating border" :class="{ 'border-red-500 ring-red-200': errors.subject }" />
                        <label class="label-floating">Subject</label>
                        <p v-if="errors.subject" class="error">{{ errors.subject }}</p>
                    </div>

                    <!-- Message -->
                    <div class="relative">
                        <textarea v-model="form.message" rows="5" placeholder=" "
                                class="peer input-floating resize-none border" :class="{ 'border-red-500 ring-red-200': errors.message }"></textarea>
                        <label class="label-floating">Your Message</label>
                        <p v-if="errors.message" class="error">{{ errors.message }}</p>
                    </div>

                    <!-- Button -->
                    <button @click="submit"
                        :disabled="form.processing"
                        class="w-full bg-[#003366] text-white py-3 rounded-lg font-semibold 
                            hover:bg-[#002244] transition disabled:opacity-50">

                        <span v-if="form.processing">Sending...</span>
                        <span v-else>Send Message</span>
                    </button>

                </form>
            </div>

        </section>
    </MainLayout>
</template>