<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  setting: Object
})

const form = ref({
  site_title: props.setting?.site_title || '',
  headline: props.setting?.headline || '',
  subtitle: props.setting?.subtitle || '',
  logo: null,
  favicon: null
})

const logoPreview = ref(props.setting?.logo ? `/storage/${props.setting.logo}` : null)
const faviconPreview = ref(props.setting?.favicon ? `/storage/${props.setting.favicon}` : null)

function handleLogo(e) {
  const file = e.target.files[0]
  form.value.logo = file
  logoPreview.value = URL.createObjectURL(file)
}

function handleFavicon(e) {
  const file = e.target.files[0]
  form.value.favicon = file
  faviconPreview.value = URL.createObjectURL(file)
}

function submit() {

  Swal.fire({
    title: 'Are you sure?',
    text: "Do you want to update site settings?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, update it!',
    scrollbarPadding: false,
  }).then((result) => {

    if (result.isConfirmed) {

      const data = new FormData()

      data.append('site_title', form.value.site_title)
      data.append('headline', form.value.headline || '')
      data.append('subtitle', form.value.subtitle || '')

      if (form.value.logo) {
        data.append('logo', form.value.logo)
      }

      if (form.value.favicon) {
        data.append('favicon', form.value.favicon)
      }

      router.post(route('site.settings.update'), data, {
        forceFormData: true,
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Site settings updated successfully',
            timer: 1500,
            showConfirmButton: false,
            scrollbarPadding: false,
          })
        }
      })
    }
  })
}
</script>

<template>
  <AdminLayout>

    <div class="w-full mx-auto bg-white">

      <h1 class="text-xl font-bold mb-4">Site Settings</h1>

      <!-- TITLE -->
      <input
        v-model="form.site_title"
        class="w-full border p-2 mb-3 rounded"
        placeholder="Site Title"
      />

      <!-- HEADLINE -->
      <input
        v-model="form.headline"
        class="w-full border p-2 mb-3 rounded"
        placeholder="Headline"
      />

      <!-- SUBTITLE -->
      <input
        v-model="form.subtitle"
        class="w-full border p-2 mb-3 rounded"
        placeholder="Subtitle"
      />

      <!-- LOGO -->
      <div class="mb-4">
        <label class="block mb-2">Logo</label>
        <input type="file" @change="handleLogo" />

        <img v-if="logoPreview" :src="logoPreview" class="h-16 mt-2" />
      </div>

      <!-- FAVICON -->
      <div class="mb-4">
        <label class="block mb-2">Favicon</label>
        <input type="file" @change="handleFavicon" />

        <img v-if="faviconPreview" :src="faviconPreview" class="h-10 mt-2" />
      </div>

      <!-- BUTTON -->
      <button
        @click="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded"
      >
        Update Settings
      </button>

    </div>

  </AdminLayout>
</template>