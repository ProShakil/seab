<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

const props = defineProps({
  data: Object
})

/* -----------------------------
   TABS
------------------------------*/
const tabs = ref([
  { key: 'president', label: 'President' },
  { key: 'vice_president', label: 'Vice President' },
  { key: 'mission', label: 'Mission' },
  { key: 'vision', label: 'Vision' },
  { key: 'about_seab', label: 'About SEAB' },
  { key: 'membership_process', label: 'Membership Process' },
])

const activeTab = ref('president')

/* -----------------------------
   FORM
------------------------------*/
const form = useForm({
  president_message: props.data?.president_message ?? '',
  vice_president_message: props.data?.vice_president_message ?? '',
  mission: props.data?.mission ?? '',
  vision: props.data?.vision ?? '',
  about_seab: props.data?.about_seab ?? '',
  membership_process: props.data?.membership_process ?? ''
})

/* -----------------------------
   SAVE STATES
------------------------------*/
const saveState = reactive({
  president_message: 'saved',
  vice_president_message: 'saved',
  mission: 'saved',
  vision: 'saved',
  about_seab: 'saved',
  membership_process: 'saved',
})

/* -----------------------------
   DIRTY CHECK
------------------------------*/
const isDirty = ref(false)

/* -----------------------------
   DEBOUNCE TIMERS
------------------------------*/
const timers = {}

/* -----------------------------
   AUTO SAVE (DEBOUNCE)
------------------------------*/
const autoSave = (key, value) => {

  isDirty.value = true
  saveState[key] = 'saving'

  clearTimeout(timers[key])

  timers[key] = setTimeout(async () => {
    try {
      await axios.post('/admin/front-message', {
        [key]: value
      })

      saveState[key] = 'saved'
      isDirty.value = false

    } catch (e) {
      saveState[key] = 'error'
    }
  }, 1000)
}

/* -----------------------------
   UNSAVED WARNING
------------------------------*/
const beforeUnloadHandler = (e) => {
  if (!isDirty.value) return
  e.preventDefault()
  e.returnValue = ''
}

onMounted(() => {
  window.addEventListener('beforeunload', beforeUnloadHandler)
})

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', beforeUnloadHandler)
})

/* -----------------------------
   SAVE ALL
------------------------------*/
const saveAll = () => {
  form.post('/admin/front-message', {
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: 'Saved successfully',
        timer: 1200,
        showConfirmButton: false,
        scrollbarPadding: false,
      })
      isDirty.value = false
    }
  })
}
</script>

<template>
  <AdminLayout>

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">

      <div>
        <h1 class="text-xl font-bold">Front Messages</h1>

        <p v-if="isDirty" class="text-orange-500 text-sm">
          You have unsaved changes...
        </p>
      </div>

      <button
        @click="saveAll"
        class="bg-blue-600 text-white px-5 py-2 rounded"
      >
        Save All
      </button>

    </div>

    <!-- STICKY TABS -->
    <div class="sticky top-0 z-20 bg-white border-b mb-4">
      <div class="flex gap-6 overflow-x-auto relative">

        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="activeTab = tab.key"
          class="relative px-3 py-3 whitespace-nowrap text-sm font-medium"
        >
          {{ tab.label }}

          <!-- UNDERLINE -->
          <span
            class="absolute left-0 bottom-0 h-[3px] bg-blue-600 transition-all duration-300"
            :class="activeTab === tab.key ? 'w-full' : 'w-0'"
          ></span>
        </button>

      </div>
    </div>

    <!-- CONTENT -->
    <div class="bg-white border p-4 rounded">

      <!-- PRESIDENT -->
      <div v-if="activeTab === 'president'">
        <textarea
          v-model="form.president_message"
          @input="autoSave('president_message', form.president_message)"
          class="w-full border p-2 h-60"
        />
        <Status :state="saveState.president_message" />
      </div>

      <!-- VICE PRESIDENT -->
      <div v-if="activeTab === 'vice_president'">
        <textarea
          v-model="form.vice_president_message"
          @input="autoSave('vice_president_message', form.vice_president_message)"
          class="w-full border p-2 h-60"
        />
        <Status :state="saveState.vice_president_message" />
      </div>

      <!-- MISSION -->
      <div v-if="activeTab === 'mission'">
        <textarea
          v-model="form.mission"
          @input="autoSave('mission', form.mission)"
          class="w-full border p-2 h-60"
        />
        <Status :state="saveState.mission" />
      </div>

      <!-- VISION -->
      <div v-if="activeTab === 'vision'">
        <textarea
          v-model="form.vision"
          @input="autoSave('vision', form.vision)"
          class="w-full border p-2 h-60"
        />
        <Status :state="saveState.vision" />
      </div>

      <!-- ABOUT SEAB (HTML EDITOR) -->
      <div v-if="activeTab === 'about_seab'">
        <label class="font-semibold mb-2 block">About SEAB</label>

        <QuillEditor
          v-model:content="form.about_seab"
          contentType="html"
          theme="snow"
          @update:content="autoSave('about_seab', form.about_seab)"
        />

        <Status :state="saveState.about_seab" />
      </div>

      <!-- MEMBERSHIP PROCESS (HTML EDITOR) -->
      <div v-if="activeTab === 'membership_process'">
        <label class="font-semibold mb-2 block">Membership Process</label>

        <QuillEditor
          v-model:content="form.membership_process"
          contentType="html"
          theme="snow"
          @update:content="autoSave('membership_process', form.membership_process)"
        />

        <Status :state="saveState.membership_process" />
      </div>

    </div>

  </AdminLayout>
</template>

<!-- STATUS COMPONENT -->
<script>
export default {
  components: {
    Status: {
      props: ['state'],
      template: `
        <div class="mt-2 text-sm">
          <span v-if="state === 'saving'" class="text-blue-500">Saving...</span>
          <span v-else-if="state === 'saved'" class="text-green-600">Saved ✓</span>
          <span v-else-if="state === 'error'" class="text-red-600">Error ❌</span>
        </div>
      `
    }
  }
}
</script>

<style scoped>
button span:last-child {
  transition: width 0.3s ease;
}
</style>