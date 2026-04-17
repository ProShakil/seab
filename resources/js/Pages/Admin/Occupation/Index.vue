<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  data: Object,
  filters: Object
})

const page = usePage()

// SEARCH
const search = ref(props.filters.search || '')

watch(search, (value) => {
  router.get('/admin/occupation', { search: value }, {
    preserveState: true,
    replace: true
  })
})

// MODAL
const modalOpen = ref(false)
const editMode = ref(false)

const form = ref({
  id: null,
  name: ''
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

// OPEN ADD
const openAdd = () => {
  editMode.value = false
  form.value = { id: null, name: '' }
  modalOpen.value = true
}

// OPEN EDIT
const openEdit = (item) => {
  editMode.value = true
  form.value = { id: item.id, name: item.name }
  modalOpen.value = true
}

// SAVE
const save = () => {
  if (editMode.value) {
    router.put(`/admin/occupation/${form.value.id}`, form.value, {
      onSuccess: () => {
        modalOpen.value = false
        toast('Updated successfully')
      }
    })
  } else {
    router.post('/admin/occupation', form.value, {
      onSuccess: () => {
        modalOpen.value = false
        toast('Created successfully')
      }
    })
  }
}

// TOGGLE STATUS
const toggleStatus = (item) => {
  router.put(route('admin.occupation.toggle', item.id), {
    data_status: item.data_status === 1 ? 0 : 1,
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      item.data_status = item.data_status === 1 ? 0 : 1
      toast('Status updated')
    }
  })
}

// DELETE (SWEETALERT)
const destroy = (id) => {
  Swal.fire({
    title: 'Are you sure?',
    text: "This action cannot be undone!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/occupation/${id}`, {
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

      <h1 class="text-xl font-bold">Occupation</h1>

      <div class="flex gap-2">

        <input
          v-model="search"
          placeholder="Search..."
          class="border px-3 py-2 rounded"
        />

        <button
          @click="openAdd"
          class="bg-blue-600 text-white px-4 py-2 rounded"
        >
          + Add
        </button>

      </div>

    </div>

    <!-- TABLE -->
    <table class="w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="item in data.data" :key="item.id">

          <td class="p-2 border">{{ item.id }}</td>

          <td class="p-2 border">{{ item.name }}</td>

          <!-- TOGGLE -->
          <td class="p-2 border">
            <button
                @click="toggleStatus(item)"
                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200"
                :class="item.data_status == 1 ? 'bg-green-500' : 'bg-gray-300'"
                >
                <span
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
                    :class="item.data_status == 1 ? 'translate-x-6' : 'translate-x-1'"
                />
            </button>
          </td>

          <!-- ACTION ICONS -->
          <td class="p-2 border flex gap-3">

            <!-- EDIT -->
            <button @click="openEdit(item)" class="text-blue-600">
                <span class="material-symbols-outlined text-lg">edit_note</span>
            </button>

            <!-- DELETE ONLY ADMIN ROLE 1 -->
            <button
              v-if="$page.props.auth.user.admin_role_id == 1"
              @click="destroy(item.id)"
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
        v-for="link in data.links"
        :key="link.label"
        v-html="link.label"
        @click="link.url && router.visit(link.url)"
        class="px-3 py-1 border"
        :class="{ 'bg-blue-600 text-white': link.active }"
      />
    </div>

    <!-- MODAL -->
    <div v-if="modalOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center">

      <div class="bg-white p-6 rounded w-[400px]">

        <h2 class="text-lg font-bold mb-4">
          {{ editMode ? 'Edit' : 'Add' }} Occupation
        </h2>

        <input
          v-model="form.name"
          class="w-full border p-2 mb-4"
          placeholder="Name"
        />

        <div class="flex justify-end gap-2">

          <button @click="modalOpen = false" class="px-3 py-1 border">
            Cancel
          </button>

          <button @click="save" class="bg-green-600 text-white px-4 py-1">
            Save
          </button>

        </div>

      </div>

    </div>

  </AdminLayout>
</template>