<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

const props = defineProps({
  blog: Object
})

const isEdit = !!props.blog
const blogId = props.blog?.id || null

const form = ref({
  title: props.blog?.title || '',
  content: props.blog?.content || '',
  slug: props.blog?.slug || '',
  thumbnail: null
})

const errors = ref({
  title: '',
  content: '',
  thumbnail: ''
})

const slug = ref(props.blog?.slug || '')
const preview = ref(
  props.blog?.thumbnail
    ? `/storage/${props.blog.thumbnail}`
    : null
)

// SLUG
watch(() => form.value.title, async (val) => {
  if (!val) return

  try {
    const res = await fetch(
      `https://translate.googleapis.com/translate_a/single?client=gtx&sl=bn&tl=en&dt=t&q=${encodeURIComponent(val)}`
    )

    const data = await res.json()
    let translated = data[0][0][0]

    let slugified = translated
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')

    slug.value = slugified || 'blog-' + Date.now()
    form.value.slug = slug.value

  } catch (e) {
    slug.value = 'blog-' + Date.now()
    form.value.slug = slug.value
  }
})

// FILE
function handleFile(e) {
  const file = e.target.files[0]
  if (file) {
    form.value.thumbnail = file
    preview.value = URL.createObjectURL(file)
  }
}

// VALIDATION
function validate() {
  let valid = true

  errors.value.title = ''
  errors.value.content = ''
  errors.value.thumbnail = ''

  if (!form.value.title.trim()) {
    errors.value.title = 'Title is required'
    valid = false
  }

  if (!form.value.content || form.value.content === '<p><br></p>') {
    errors.value.content = 'Content is required'
    valid = false
  }

  // 🚨 thumbnail required ONLY for create
  if (!isEdit && !form.value.thumbnail) {
    errors.value.thumbnail = 'Thumbnail is required'
    valid = false
  }

  return valid
}

// SUBMIT
function submit() {

  if (!validate()) return

  const data = new FormData()

  data.append('title', form.value.title)
  data.append('slug', form.value.slug)
  data.append('content', form.value.content)

  if (form.value.thumbnail) {
    data.append('thumbnail', form.value.thumbnail)
  }

  router.post(route('admin.blog.store', blogId), data, {
    forceFormData: true
  })
}
</script>

<template>
  <AdminLayout>

    <div class="w-full mx-auto bg-white ">

      <h1 class="text-xl font-bold mb-4">
        {{ isEdit ? 'Edit Blog' : 'Create Blog' }}
      </h1>

      <!-- TITLE -->
      <div class="mb-3">
        <input
          v-model="form.title"
          class="w-full border p-2 rounded"
          placeholder="Blog Title *"
        />

        <p v-if="errors.title" class="text-red-500 text-sm mt-1">
          {{ errors.title }}
        </p>
      </div>

      <!-- SLUG -->
      <div class="mb-4 text-sm text-gray-600">
        Slug: <span class="font-semibold">{{ slug }}</span>
        <input type="hidden" v-model="form.slug" />
      </div>

      <!-- THUMBNAIL -->
      <div class="mb-4">
        <input type="file" @change="handleFile" />
        <p v-if="errors.thumbnail" class="text-red-500 text-sm mt-1">
            {{ errors.thumbnail }}
        </p>
        <div v-if="preview" class="mt-3">
          <img :src="preview" class="w-40 h-40 object-cover rounded border" />
        </div>
      </div>

      <!-- CONTENT -->
      <div class="mb-4">
        <QuillEditor
          v-model:content="form.content"
          contentType="html"
          theme="snow"
        />

        <p v-if="errors.content" class="text-red-500 text-sm mt-1">
          {{ errors.content }}
        </p>
      </div>

      <!-- BUTTON -->
      <button
        @click="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded"
      >
        {{ isEdit ? 'Update' : 'Create' }}
      </button>

    </div>

  </AdminLayout>
</template>