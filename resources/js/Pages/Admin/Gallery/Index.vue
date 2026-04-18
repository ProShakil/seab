<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  galleries: Object
})

/* ---------------- SAFE DATA ---------------- */
const items = ref(props.galleries?.data || [])
const nextPageUrl = ref(props.galleries?.next_page_url || null)
const loadingMore = ref(false)

const filter = ref('all')

const showCanvas = ref(false)
const imageView = ref(null)
const videoView = ref(null)

const files = ref([])
const videoUrl = ref('')

/* ---------------- FILTER ---------------- */
const filtered = computed(() => {
  if (filter.value === 'all') return items.value || []
  return (items.value || []).filter(i => i.type === filter.value)
})

/* ---------------- TOAST ---------------- */
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

/* ---------------- IMAGE URL ---------------- */
const getImageUrl = (path) => {
  if (!path) return ''
  if (path.startsWith('http')) return path
  return `/storage/${path}`
}

/* ---------------- INFINITE SCROLL ---------------- */
const loadMore = async () => {
  if (!nextPageUrl.value || loadingMore.value) return

  loadingMore.value = true

  try {
    const res = await fetch(nextPageUrl.value, {
      headers: {
        Accept: 'application/json'
      }
    })

    const data = await res.json()

    items.value.push(...data.data)
    nextPageUrl.value = data.next_page_url

  } catch (e) {
    console.log('Load more error:', e)
  }

  loadingMore.value = false
}

const handleScroll = () => {
  const scrollBottom =
    window.innerHeight + window.scrollY >= document.body.offsetHeight - 300

  if (scrollBottom) loadMore()
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll)
})

/* ---------------- UPLOAD ---------------- */
const uploadPhotos = () => {
  const fd = new FormData()

  if (!files.value.length) return

  files.value.forEach(f => fd.append('images[]', f))

  router.post('/admin/gallery/photos', fd, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      showCanvas.value = false
      files.value = []
      toast('Photos uploaded')
    }
  })
}

const addVideo = () => {
  router.post('/admin/gallery/video', {
    video_url: videoUrl.value
  }, {
    onSuccess: () => {
      showCanvas.value = false
      videoUrl.value = ''
      toast('Video added')
    }
  })
}

/* ---------------- DELETE ---------------- */
const destroy = (id) => {
  Swal.fire({
    title: 'Delete?',
    icon: 'warning',
    showCancelButton: true,
    scrollbarPadding: false,
  }).then(res => {
    if (res.isConfirmed) {
      router.delete(`/admin/gallery/${id}`, {
        onSuccess: () => {
          items.value = items.value.filter(i => i.id !== id)
          toast('Deleted')
        }
      })
    }
  })
}
</script>

<template>
<AdminLayout>

<div class="h-[70vh] flex flex-col">

  <!-- HEADER -->
  <div class="p-3 border-b flex justify-between">
    <h1 class="font-bold text-xl">Gallery</h1>

    <button @click="showCanvas=true"
      class="bg-blue-600 text-white px-4 py-2 rounded">
      Upload
    </button>
  </div>

  <!-- FILTER -->
  <div class="p-3 flex gap-2 border-b">

    <button @click="filter='all'"
      class="px-3 py-1 border rounded"
      :class="filter==='all' && 'bg-blue-600 text-white'">
      All
    </button>

    <button @click="filter='photo'"
      class="px-3 py-1 border rounded"
      :class="filter==='photo' && 'bg-blue-600 text-white'">
      Photos
    </button>

    <button @click="filter='video'"
      class="px-3 py-1 border rounded"
      :class="filter==='video' && 'bg-blue-600 text-white'">
      Videos
    </button>

  </div>

  <!-- SCROLL AREA -->
  <div class="flex-1 overflow-y-auto p-3">

    <!-- MASONRY -->
    <div class="columns-2 md:columns-3 gap-4">

      <div v-for="item in filtered"
        :key="item.id"
        class="mb-4 break-inside-avoid relative group rounded overflow-hidden shadow">

        <!-- ACTIONS -->
        <div class="absolute top-2 right-2 z-10 flex gap-2 opacity-0 group-hover:opacity-100">

          <button @click="destroy(item.id)"
            class="bg-red-600 text-white px-2 py-1 text-xs rounded">
            ✕
          </button>

        </div>

        <button
          @click="item.type==='photo' ? imageView=item : videoView=item"
          class="absolute bottom-2 right-2 bg-black text-white px-2 py-1 text-xs rounded z-10">
          View
        </button>

        <!-- PHOTO -->
        <img
          v-if="item.type==='photo'"
          :src="getImageUrl(item.image)"
          class="w-full object-cover"
        />

        <!-- VIDEO -->
        <iframe
          v-if="item.type==='video'"
          :src="item.embed_url"
          class="w-full h-56"
        />

      </div>

    </div>

    <div v-if="loadingMore" class="text-center py-4 text-gray-500">
      Loading...
    </div>

  </div>

</div>

<!-- LIGHTBOX -->
<div v-if="imageView"
  class="fixed inset-0 bg-black/80 flex items-center justify-center z-50">

  <img :src="getImageUrl(imageView.image)" class="max-h-[90vh]" />
  <button @click="imageView=null"
    class="absolute top-5 right-5 text-white text-2xl">✕</button>
</div>

<!-- VIDEO -->
<div v-if="videoView"
  class="fixed inset-0 bg-black/90 flex items-center justify-center z-50">

  <iframe :src="videoView.embed_url"
    class="w-[85%] h-[75vh]" />

  <button @click="videoView=null"
    class="absolute top-5 right-5 text-white text-2xl">✕</button>
</div>

<!-- OFFCANVAS -->
<div v-if="showCanvas" class="fixed inset-0 z-50 flex">

  <button @click="showCanvas=false" class="absolute top-3 right-3 text-xl">
          ✕
  </button>

  <div class="ml-auto w-[420px] bg-white h-full p-5">

    <h2 class="font-bold mb-4">Upload</h2>

    <input type="file" multiple
      @change="files = Array.from($event.target.files)"
      class="w-full border p-2 mb-3"/>

    <button @click="uploadPhotos"
      class="w-full bg-blue-600 text-white py-2 mb-4">
      Upload Photos
    </button>

    <input v-model="videoUrl"
      class="w-full border p-2 mb-2"
      placeholder="YouTube URL"/>

    <button @click="addVideo"
      class="w-full bg-green-600 text-white py-2">
      Add Video
    </button>

  </div>
</div>

</AdminLayout>
</template>