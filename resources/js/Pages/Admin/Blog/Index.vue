<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  blogs: Object,
})

// search state
const search = ref('')

// auto search (optional)
watch(search, (value) => {
  router.get(route('admin.blog.index'), {
    search: value
  }, {
    preserveState: true,
    replace: true
  })
})
// TOAST
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

// TOGGLE STATUS
const toggleStatus = (item) => {
  router.post(route('admin.blog.toggle', item.id), {
    status: item.status === 1 ? 0 : 1,
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      item.status = item.status === 1 ? 0 : 1
      toast('Status updated')
    }
  })
}

const destroy = (id) => {
  Swal.fire({
    title: 'Are you sure?',
    text: "This action cannot be undone!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    scrollbarPadding: false,
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(`/admin/blog/delete/${id}`, {
        onSuccess: () => toast('Deleted successfully')
      })
    }
  })
}
</script>

<template>
  <AdminLayout>

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">

      <h1 class="text-xl font-bold">Blog List</h1>

      <div class="flex gap-2">

        <!-- SEARCH -->
        <input
          v-model="search"
          placeholder="Search..."
          class="border px-3 py-2 rounded"
        />

        <!-- ADD BUTTON -->
        <Link
          :href="route('admin.blog.create')"
          class="bg-blue-600 text-white px-4 py-2 rounded"
        >
          + Add
        </Link>

      </div>

    </div>
    
    <!-- TABLE -->
    <table class="w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">#</th>
          <th class="p-2 border">Thumbnail</th>
          <th class="p-2 border text-left">Title</th>
          <th class="p-2 border">Author</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(blog, index) in blogs.data" :key="blog.id">

          <!-- SL -->
          <td class="p-2 border text-center">
            {{ index + 1 }}
          </td>

          <!-- THUMBNAIL -->
          <td class="p-2 border text-center">
            <img
              v-if="blog.thumbnail"
              :src="`/storage/${blog.thumbnail}`"
              class="w-12 h-12 object-cover rounded mx-auto"
            />
            <span v-else>-</span>
          </td>

          <!-- TITLE -->
          <td class="p-2 border">
            {{ blog.title }}
          </td>

          <!-- AUTHOR -->
          <td class="p-2 border text-center">
            {{ blog.user?.name }}
          </td>

          <!-- STATUS -->
          <td class="p-2 border text-center">
            <span
              class="px-2 py-1 text-xs rounded text-white"
              :class="blog.status ? 'bg-green-600' : 'bg-gray-500'"
            >
              {{ blog.status ? 'Published' : 'Draft' }}
            </span>
          </td>

          <!-- ACTION -->
          <td class="p-2 border text-center space-x-2">
            <Link
                :href="route('admin.blog.create', blog.id)"
                class="text-blue-600 hover:text-blue-800"
            >
                <span class="material-symbols-outlined">edit_note</span>
            </Link>

            <button
                @click="toggleStatus(blog)" class="relative inline-flex h-6 items-center rounded-full">
                <span class="text-sm" :class="blog.status == 1 ? 'text-green-600' : 'text-gray-400'">
                    <span class="material-symbols-outlined">
                        {{ blog.status == 1 ? 'visibility' : 'visibility_off' }}
                    </span>
                </span>
            </button>

            <button
              v-if="$page.props.auth.user.admin_role_id == 1"
              @click="destroy(blog.id)"
              class="text-red-600"
            >
            <span class="material-symbols-outlined text-lg">delete</span>
            </button>

          </td>

        </tr>
      </tbody>
    </table>

    <!-- PAGINATION -->
    <div class="mt-4 flex gap-2">
      <button
        v-for="link in blogs.links"
        :key="link.label"
        v-html="link.label"
        :disabled="!link.url"
        @click="$inertia.visit(link.url)"
        class="px-3 py-1 border rounded"
        :class="{ 'bg-gray-300': link.active }"
      />
    </div>

  </AdminLayout>
</template>