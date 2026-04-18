<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
  contacts: Object
})

const selectedContact = ref(null)
const showCanvas = ref(false)

// 👁 View (mark as read + open panel)
function viewMessage(contact) {
  selectedContact.value = contact
  showCanvas.value = true

  if (contact.view_status == 0) {
    router.post(route('admin.contact.read'), { id: contact.id }, {
      preserveScroll: true,
      onSuccess: () => {
        contact.view_status = 1
      }
    })
  }
}

// 🔄 toggle read/unread
function toggleStatus(contact) {
  const isUnread = contact.view_status == 0

  Swal.fire({
    title: isUnread ? 'Mark as read?' : 'Mark as unread?',
    text: isUnread
      ? 'This message will be marked as read.'
      : 'This message will be marked as unread.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, confirm',
    scrollbarPadding: false,
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(route('admin.contact.toggle'), {
        id: contact.id
      }, {
        preserveScroll: true,
        onSuccess: () => {
          // update UI instantly
          contact.view_status = isUnread ? 1 : 0

          // use YOUR toast function
          toast(isUnread ? 'Marked as read' : 'Marked as unread')
        }
      })
    }
  })
}

// 🗑 delete

const deleteContact = (id) => {
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
      router.delete(route('admin.contact.delete', id), {
        onSuccess: () => toast('Deleted successfully')
      })
    }
  })
}

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
</script>
<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-4"><h1 class="text-xl font-bold">Message</h1></div>
      
    <table class="w-full border border-gray-300">
    <thead>
        <tr class="bg-gray-100">
        <th class="p-2 border">SL</th>
        <th class="p-2 border text-left">Name</th>
        <th class="p-2 border text-left">Email</th>
        <th class="p-2 border text-left">Phone</th>
        <th class="p-2 border text-left">Subject</th>
        <th class="p-2 border text-center">Action</th>
        </tr>
    </thead>

    <tbody>
        <tr v-for="(contact,index) in contacts.data" :key="contact.id" :class="contact.view_status == 0 ? 'bg-gray-100 font-semibold' : ''">
        <td class="p-2 border text-center">{{ index+1 }}</td>
        <td class="p-2 border">{{ contact.name }}</td>
        <td class="p-2 border">{{ contact.email }}</td>
        <td class="p-2 border">{{ contact.phone }}</td>
        <td class="p-2 border">{{ contact.subject }}</td>
        <td class="p-2 border">
            <button @click="viewMessage(contact)">
                <span class="material-symbols-outlined text-blue-600">visibility</span>
            </button>

            <button @click="toggleStatus(contact)">
                <span class="material-symbols-outlined text-green-600">
                {{ contact.view_status == 0 ? 'mark_email_read' : 'mark_email_unread' }}
                </span>
            </button>

            <button @click="deleteContact(contact.id)">
                <span class="material-symbols-outlined text-red-600">delete</span>
            </button>
        </td>
        </tr>
    </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex gap-2">
    <button
        v-for="link in contacts.links"
        :key="link.label"
        v-html="link.label"
        :disabled="!link.url"
        @click="$inertia.visit(link.url)"
        class="px-3 py-1 border rounded"
        :class="{ 'bg-gray-300': link.active }"
    />
    </div>
    <div v-if="showCanvas" class="fixed top-0 right-0 h-full w-[400px] bg-white shadow-lg z-50 transform translate-x-0 transition-transform duration-300">
    <div class="p-4 border-b flex justify-between">
        <h2 class="font-bold">Message</h2>
        <button @click="showCanvas = false">✕</button>
    </div>

    <div class="p-4 space-y-2" v-if="selectedContact">
        <div class="border p-2 bg-gray-50 mt-2">
            {{ selectedContact.message }}
        </div>
    </div>
    </div>
  </AdminLayout>
</template>