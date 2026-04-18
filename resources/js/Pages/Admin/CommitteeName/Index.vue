<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  data: Object,
  filters: Object,
  users: Array,
  designations: Array
})

const page = usePage()

// SEARCH
const search = ref(props.filters.search || '')

watch(search, (value) => {
  router.get('/admin/committee-names', { search: value }, {
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
    router.put(`/admin/committee-names/${form.value.id}`, form.value, {
      onSuccess: () => {
        modalOpen.value = false
        toast('Updated successfully')
      }
    })
  } else {
    router.post('/admin/committee-names', form.value, {
      onSuccess: () => {
        modalOpen.value = false
        toast('Created successfully')
      }
    })
  }
}

// TOGGLE STATUS
const toggleStatus = (item) => {
  router.put(route('admin.committee-names.toggle', item.id), {
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
    confirmButtonText: 'Yes, delete it!',
    scrollbarPadding: false,
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(`/admin/committee-names/${id}`, {
        onSuccess: () => toast('Deleted successfully')
      })
    }
  })
}

const memberCanvasOpen = ref(false)
const memberForm = ref({
  committee_name_id: null,
  user_id: '',
  designation_id: ''
})
const committeeMembers = ref([])

const memberAdd = (item) => {
  memberForm.value.committee_name_id = item.id
  committeeMembers.value = item.members || []
  memberCanvasOpen.value = true
}

const filteredUsers = computed(() => {
  const usedIds = committeeMembers.value.map(m => m.user_id)
  return props.users.filter(u => !usedIds.includes(u.id))
})

const saveMember = () => {
  router.post('/admin/add-member', memberForm.value, {
    onSuccess: () => {
      toast('Member added')

      const user = props.users.find(u => u.id == memberForm.value.user_id)
      const designation = props.designations.find(d => d.id == memberForm.value.designation_id)

      committeeMembers.value.push({
        id: Date.now(),
        user_id: memberForm.value.user_id,
        designation_id: memberForm.value.designation_id,
        user,
        designation
      })

      memberForm.value.user_id = ''
      memberForm.value.designation_id = ''
    }
  })
}

const removeMember = (id) => {
  Swal.fire({
    title: 'Remove member?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    scrollbarPadding: false,
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(`/admin/remove-member/${id}`, {}, {
        onSuccess: () => {
          committeeMembers.value = committeeMembers.value.filter(m => m.id !== id)
          toast('Member removed')
        }
      })
    }
  })
}

const closeCanvas = () => {
  memberCanvasOpen.value = false
}
</script>

<template>
  <AdminLayout>

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">

      <h1 class="text-xl font-bold">Committee Names</h1>

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
          <th class="p-2 border">SL</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item, index) in data.data" :key="item.id">

          <td class="p-2 border">{{ index + 1 }}</td>

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

            <button @click="memberAdd(item)" class="text-blue-600">
                <span class="material-symbols-outlined text-lg">group_add</span>
            </button>

            <!-- DELETE ONLY ADMIN ROLE 1 -->
            <button
              v-if="$page.props.auth.user.admin_role_id == 1 && item.is_deleteAble == 1"
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
          {{ editMode ? 'Edit' : 'Add' }} Committee Name
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
    
    <!-- OFFCANVAS ROOT -->
    <transition name="slide">
      <div v-if="memberCanvasOpen" class="fixed inset-0 z-50 flex">
        <!-- PANEL -->
        <div class="ml-auto w-[400px] bg-white h-full shadow-xl p-6 relative">

          <!-- CLOSE BUTTON -->
          <button
            @click="closeCanvas"
            class="absolute top-3 right-3 text-gray-600 hover:text-black text-xl"
          >
            ✕
          </button>

          <h2 class="text-lg font-bold mb-4">Manage Members</h2>

          <!-- USER -->
          <select v-model="memberForm.user_id" class="w-full border p-2 mb-3">
            <option value="">Select User</option>
            <option v-for="u in filteredUsers" :key="u.id" :value="u.id">
              {{ u.name }}
            </option>
          </select>

          <!-- DESIGNATION -->
          <select v-model="memberForm.designation_id" class="w-full border p-2 mb-3">
            <option value="">Select Designation</option>
            <option v-for="d in designations" :key="d.id" :value="d.id">
              {{ d.name }}
            </option>
          </select>

          <!-- ADD -->
          <button
            @click="saveMember"
            class="bg-blue-600 text-white px-4 py-2 w-full mb-4"
          >
            Add Member
          </button>

          <!-- MEMBER LIST -->
          <div class="max-h-96 overflow-y-auto pr-3 scroll-area">
            <h3 class="font-semibold mb-2">Members</h3>

            <div v-if="committeeMembers.length === 0" class="text-gray-500 text-sm">
              No members added
            </div>

            <div
              v-for="m in committeeMembers"
              :key="m.id"
              class="flex justify-between items-center border-b py-2 text-sm"
            >
              <div>
                {{ m.user.name }} <br>
                <span class="text-gray-500">{{ m.designation.name }}</span>
              </div>

              <button @click="removeMember(m.id)" class="text-red-600">
                ✕
              </button>
            </div>
          </div>

        </div>
      </div>
    </transition>


  </AdminLayout>
</template>
<style>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from {
  transform: translateX(100%);
}

.slide-enter-to {
  transform: translateX(0);
}

.slide-leave-from {
  transform: translateX(0);
}

.slide-leave-to {
  transform: translateX(100%);
}
.scroll-area {
    max-height: 400px;
    overflow-y: auto;

    /* right padding so content doesn't touch scrollbar */
    padding-right: 10px;
}

/* ✅ Chrome, Edge, Safari */
.scroll-area::-webkit-scrollbar {
    width: 6px; /* thin scrollbar */
}

.scroll-area::-webkit-scrollbar-track {
    background: transparent;
}

.scroll-area::-webkit-scrollbar-thumb {
    background: #001e3c; /* your theme color */
    border-radius: 10px;
}

/* hover effect */
.scroll-area::-webkit-scrollbar-thumb:hover {
    background: #003366;
}

/* ✅ Firefox */
.scroll-area {
    scrollbar-width: thin;
    scrollbar-color: #001e3c transparent;
}
</style>