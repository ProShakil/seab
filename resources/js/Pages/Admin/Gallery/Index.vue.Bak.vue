<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  galleries: Object,
  filters: Object
})

/* ---------------- DATA ---------------- */
const items = ref(props.galleries)
const filter = ref('all')

/* ---------------- OFFCANVAS ---------------- */
const showCanvas = ref(false)

/* ---------------- VIEW MODALS ---------------- */
const imageView = ref(null)
const videoView = ref(null)

/* ---------------- UPLOAD DATA ---------------- */
const files = ref([])
const videoUrl = ref('')

/* ---------------- FILTER ---------------- */
const filtered = computed(() => {
  if (filter.value === 'all') return items.value
  return items.value.filter(i => i.type === filter.value)
})

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

const getImageUrl = (path) => {
  if (!path) return ''

  if (path.startsWith('http')) return path

  return `/storage/${path}`
}

/* ---------------- UPLOAD PHOTOS ---------------- */
const uploadPhotos = () => {
  const fd = new FormData()

  if (!files.value.length) return

  files.value.forEach((file) => {
    fd.append('images[]', file)
  })

  router.post('/admin/gallery/photos', fd, {
    forceFormData: true,
    preserveScroll: true,
    onStart: () => {
      // optional: loading state
    },
    onSuccess: () => {
      showCanvas.value = false
      files.value = []
      toast('Photos uploaded successfully')
    },
    onError: (errors) => {
      console.log(errors)
      toast('Upload failed', 'error')
    },
    onFinish: () => {
      // optional: stop loader
    }
  })
}

/* ---------------- ADD VIDEO ---------------- */
const addVideo = () => {
  router.post('/admin/gallery/video', {
    video_url: videoUrl.value
  }, {
    onSuccess: () => {
      showCanvas.value = false
      videoUrl.value = ''
      toast('Video added successfully')
    },
    onError: () => {
      toast('Video upload failed', 'error')
    }
  })
}

/* ---------------- DELETE ---------------- */
const destroy = (id) => {
  Swal.fire({
    title: 'Delete this item?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33'
  }).then(res => {
    if (res.isConfirmed) {
      router.delete(`/admin/gallery/${id}`, {
        onSuccess: () => {
          items.value = items.value.filter(i => i.id !== id)
        }
      })
    }
  })
}
</script>

<template>
  <AdminLayout>

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">

      <h1 class="text-xl font-bold">Gallery</h1>

      <!-- UPLOAD BUTTON (TOP RIGHT) -->
      <button
        @click="showCanvas = true"
        class="bg-blue-600 text-white px-4 py-2 rounded  shadow-lg z-40"
      >
        + Upload
      </button>

    </div>

    <!-- FILTERS -->
    <div class="flex gap-2 mb-4">

      <button @click="filter='all'" class="px-3 py-1 border rounded">All</button>
      <button @click="filter='photo'" class="px-3 py-1 border rounded">Photo</button>
      <button @click="filter='video'" class="px-3 py-1 border rounded">Video</button>

    </div>

    <!-- GRID -->
    <div class="grid grid-cols-3 gap-4">

      <div
        v-for="item in filtered"
        :key="item.id"
        class="relative border rounded overflow-hidden group"
      >

        <!-- DELETE -->
        <button
          @click="destroy(item.id)"
          class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 text-xs rounded z-10"
        >
          ✕
        </button>

        <!-- VIEW -->
        <button
          @click="item.type==='photo' ? imageView=item : videoView=item"
          class="absolute bottom-2 right-2 bg-black/70 text-white px-2 py-1 text-xs rounded z-10"
        >
          View
        </button>

        <!-- PHOTO -->
        <img
          v-if="item.type==='photo'"
          :src="getImageUrl(item.image)"
          class="w-full h-48 object-cover"
        />

        <!-- VIDEO -->
        <iframe
          v-if="item.type==='video'"
          :src="item.embed_url"
          class="w-full h-48"
          frameborder="0"
        />

      </div>

    </div>

    <!-- ================= LIGHTBOX PHOTO ================= -->
    <div v-if="imageView" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50">

      <img :src="getImageUrl(imageView.image)" class="max-h-[90vh]" />

      <button @click="imageView=null" class="absolute top-5 right-5 text-white text-2xl">✕</button>

    </div>

    <!-- ================= VIDEO FULLSCREEN ================= -->
    <div v-if="videoView" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50">

      <iframe
        :src="videoView.embed_url"
        class="w-[85%] h-[75vh]"
        allowfullscreen
      />

      <button @click="videoView=null" class="absolute top-5 right-5 text-white text-2xl">✕</button>

    </div>

    <!-- ================= OFFCANVAS UPLOAD ================= -->
    <div v-if="showCanvas" class="fixed inset-0 z-50 flex">

      <!-- BACKDROP -->
      <div class="absolute inset-0 bg-black/40" @click="showCanvas=false"></div>

      <!-- PANEL -->
      <div class="ml-auto w-[420px] bg-white h-full shadow-lg p-5 relative animate-slide">

        <!-- CLOSE BUTTON -->
        <button @click="showCanvas=false" class="absolute top-3 right-3 text-xl">
          ✕
        </button>

        <h2 class="text-lg font-bold mb-4">Upload Media</h2>

        <!-- PHOTOS -->
        <div class="mb-5">
          <label class="font-semibold">Upload Photos</label>
          <input
            type="file"
            multiple
            @change="files = Array.from($event.target.files)"
            class="w-full border p-2"
          />

          <button
            @click="uploadPhotos"
            class="bg-blue-600 text-white w-full py-2 mt-2"
          >
            Upload Photos
          </button>
        </div>

        <hr class="my-3"/>

        <!-- VIDEO -->
        <div>
          <label class="font-semibold">YouTube Video</label>

          <input
            v-model="videoUrl"
            placeholder="Paste YouTube URL"
            class="border p-2 w-full mb-2"
          />

          <button
            @click="addVideo"
            class="bg-green-600 text-white w-full py-2"
          >
            Add Video
          </button>
        </div>

      </div>
    </div>

  </AdminLayout>
</template>

<style scoped>
@keyframes slide {
  from { transform: translateX(100%); }
  to { transform: translateX(0); }
}
.animate-slide {
  animation: slide 0.3s ease;
}
</style>