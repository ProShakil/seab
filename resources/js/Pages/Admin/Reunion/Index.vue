<script setup>
import { ref,onMounted, watch  } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { router, usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'
const page = usePage()


const activeTab = ref('reunion')
const reunionEnabled = ref(true)
const selectedReunionId = ref('')
const showReunionForm = ref(false)
const isReunionEdit = ref(false)
const selectedPaymentReunion = ref('')
const paymentPagination = ref({
  current_page: 1,
  last_page: 1
})

/* Payment Method Modal */
const isMethodEdit = ref(false)
const showMethodForm = ref(false)

/* Reunion form */
const reunionForm = ref({
  id: null,
  title: '',
  fee: '',
  start_date: '',
  end_date: '',
  receipt_model: '',
})

const methodForm = ref({
  id: null,
  name: '',
  type: '',
  account_number: '',
  account_name: '',
  description: ''
})


const loading = ref(false)

// data storage per tab
const reunionData = ref([])
const paymentData = ref([])
const methodData = ref([])

// search per tab
const reunionSearch = ref('')
const paymentSearch = ref('')
const methodSearch = ref('')

// tabs
const tabs = [
  { key: 'reunion', label: 'Reunion Periods' },
  { key: 'methods', label: 'Payment Methods' },
  { key: 'payments', label: 'Payments' }
]

// cache (avoid repeated API calls)
const loaded = {
  reunion: false,
  payments: false,
  methods: false
}

// API loader
const loadTab = async (tab) => {
  activeTab.value = tab

  // already loaded → no API call
  if (loaded[tab]) return

  loading.value = true

  try {
    if (tab === 'reunion') {
      const res = await axios.get('/admin/tab/reunion', {
        params: { search: reunionSearch.value }
      })
      reunionData.value = res.data.data  
    }
    if (tab === 'payments') {
      const res = await axios.get('/admin/tab/payments', {
        params: { 
          search: paymentSearch.value,
          reunion_period_id: selectedPaymentReunion.value,
          page: paymentPagination.value.current_page
        }
      })
      paymentData.value = res.data.data      
      paymentPagination.value.current_page = res.data.current_page
      paymentPagination.value.last_page = res.data.last_page

      const paymentPageChange = (page) => {
        paymentPagination.value.current_page = page
        loaded.payments = false
        loadTab('payments')
      }

    }

    if (tab === 'methods') {
      const res = await axios.get('/admin/tab/payment-method', {
        params: { search: methodSearch.value }
      })
      methodData.value = res.data.data
    }
    loaded[tab] = true
  } finally {
    loading.value = false
  }
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

const updateReunionSetting = () => {
  router.post('/admin/reunion/settings', {
    reunion: reunionEnabled.value,
    reunion_id: selectedReunionId.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      toast('Reunion settings updated')
    }
  })
}
// Reunion
const openReunionAdd = () => {
  isReunionEdit.value = false

  reunionForm.value = {
    id: null,
    title: '',
    fee: '',
    start_date: '',
    end_date: '',
    receipt_model: '',
    data_status: 1
  }

  showReunionForm.value = true
}

const openReunionEdit = (item) => {
  isReunionEdit.value = true

  reunionForm.value = { ...item }

  showReunionForm.value = true
}
// SAVE
const reeunionSave = () => {
  const payload = reunionForm.value
    
  if (isReunionEdit.value) {
    router.put(`/admin/tab/reunion/${payload.id}`, payload, {
      preserveScroll: true,
      onSuccess: () => {
        loaded.reunion = false
        loadTab('reunion')
        showReunionForm.value = false
        toast('Updated successfully')
      }
    })
  } else {
    router.post('/admin/tab/reunion', payload, {
      preserveScroll: true,
      onSuccess: () => {
        loaded.reunion = false
        loadTab('reunion')
        showReunionForm.value = false
        toast('Created successfully')
      }
    })
  }
}

// TOGGLE STATUS
const reunionToggleStatus = (item) => {
  router.put(route('admin.reunion.toggle', item.id), {
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
const reunionDestroy = (id) => {
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
      router.delete(`/admin/tab/reunion/${id}`, {
        onSuccess: () => {
          toast('Deleted successfully')
          loadTab('reunion')
        }
      })
    }
  })
}

// Method Save

const openPaymentMethodAdd = () => {
  isMethodEdit.value = false

  methodForm.value = {
    id: null,
    name: '',
    type: '',
    account_number: '',
    account_name: '',
    description: '',
    data_status: 1
  }

  showMethodForm.value = true
}

const openPaymentMethodEdit = (item) => {
  isMethodEdit.value = true

  methodForm.value = { ...item }

  showMethodForm.value = true
}
const saveMethod = () => {
  const payload = methodForm.value

  if (isMethodEdit.value) {
    router.put(`/admin/tab/payment-method/${payload.id}`, payload, {
      onSuccess: () => {
        showMethodForm.value = false
        toast('Updated successfully')

        loaded.methods = false
        loadTab('methods')
      }
    })
  } else {
    router.post('/admin/tab/payment-method', payload, {
      onSuccess: () => {
        showMethodForm.value = false
        toast('Created successfully')

        loaded.methods = false
        loadTab('methods')
      }
    })
  }
}

const toggleMethod = (item) => {
  router.put(`/admin/tab/payment-method/${item.id}/toggle`, {}, {
    onSuccess: () => {
      item.data_status = item.data_status === 1 ? 0 : 1
      toast('Status updated')
    }
  })
}

const methoddestroy = (id) => {
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
      router.delete(`/admin/tab/payment-method/${id}`, {
        onSuccess: () => {
          toast('Deleted successfully')
          loadTab('methods')
        }
      })
    }
  })
}

// Payments

const deletePayment = (id) => {
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
      router.delete(`/admin/tab/payments/${id}`, {
        onSuccess: () => {
          loaded.payments = false
          loadTab('payments')
          toast('Deleted successfully')
        }
      })
    }
  })
}

const togglePayments = (item) => {
  router.put(`/admin/tab/payments/${item.id}/toggle`, {}, {
    onSuccess: () => {
      item.payment_status = item.payment_status === 1 ? 0 : 1
      loaded.payments = false
      loadTab('payments')
      toast('Payment Confirmed')
    }
  })
}

// first load
loadTab('reunion')

onMounted(() => {
  const settings = page.props.siteSettings

  reunionEnabled.value = settings?.reunion == 1
  selectedReunionId.value = settings?.reunion_id ?? ''
})

watch(paymentSearch, () => {
  paymentPagination.value.current_page = 1
  loaded.payments = false
  loadTab('payments')
})

watch(selectedPaymentReunion, () => {
  paymentPagination.value.current_page = 1
  loaded.payments = false
  loadTab('payments')
})
</script>

<template>
  <AdminLayout>
    <div class="px-4 py-3">

      <!-- Header -->
    
      <div class="mb-3 flex items-center justify-between">

        <!-- Left -->
        <div>
          <h1 class="text-lg font-semibold text-gray-800">Management Panel</h1>
          <p class="text-sm text-gray-500">
            Manage Reunion, Payment Methods and Payments
          </p>
        </div>

        <!-- Right -->
        <div class="flex items-center gap-3">

          <!-- Switch -->
          <div class="flex items-center gap-2">
            <span class="text-xs text-gray-600">
              Reunion {{ reunionEnabled ? 'Enabled' : 'Disabled' }}
            </span>

            <button
              @click="reunionEnabled = !reunionEnabled"
              class="relative inline-flex h-6 w-11 items-center rounded-full transition"
              :class="reunionEnabled ? 'bg-green-500' : 'bg-gray-300'"
            >
              <span
                class="inline-block h-4 w-4 bg-white rounded-full transform transition"
                :class="reunionEnabled ? 'translate-x-6' : 'translate-x-1'"
              />
            </button>
          </div>
          <div v-if="reunionEnabled" class="mb-3">

              <select
                v-model="selectedReunionId"
                class="w-full border rounded px-3 py-2 text-xs"
              >
                <option value="" disabled>Select Reunion</option>

                <option
                  v-for="item in (reunionData?.data || [])"
                  :key="item.id"
                  :value="item.id"
                >
                  {{ item.title }}
                </option>

              </select>

            </div>

          <!-- UPDATE BUTTON -->
          <button
            @click="updateReunionSetting"
            class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded"
          >
            Update
          </button>

        </div>

      </div>

      <!-- Card -->
      <div class="bg-white rounded-lg border shadow-sm">

        <!-- Tabs -->
        <div class="flex border-b">
          <button
            v-for="tab in tabs"
            :key="tab.key"
            @click="loadTab(tab.key)"
            :class="[
              'px-4 py-2 text-sm font-medium transition',
              activeTab === tab.key
                ? 'border-b-2 border-blue-600 text-blue-600'
                : 'text-gray-500 hover:text-blue-600'
            ]"
          >
            {{ tab.label }}
          </button>

        </div>

        <!-- Content -->
        <div class="p-4 min-h-[420px]">
            <div v-if="loading" class="text-sm text-gray-500">
                Loading...
            </div>
            <!-- Reunion -->
            <div v-if="activeTab === 'reunion' && !loading">
                <div class="flex items-center justify-between mb-3 gap-3">
    
                    <!-- Title -->
                    <h2 class="text-sm font-semibold whitespace-nowrap">
                        Reunion Periods
                    </h2>

                    <!-- Search -->
                    <div class="flex-1 max-w-xs">
                        <input
                        v-model="reunionSearch"
                        type="text"
                        placeholder="Search..."
                        class="w-full text-xs border rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <!-- Add Button -->
                    <button @click="openReunionAdd" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded whitespace-nowrap">
                        + Add
                    </button>

                </div>

                <table class="w-full text-xs border">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                        <th class="p-2 border">Title</th>
                        <th class="p-2 border">Fee</th>
                        <th class="p-2 border">Start</th>
                        <th class="p-2 border">End</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in reunionData.data" :key="item.id">
                            <td class="border p-2">{{ item.title }}</td>
                            <td class="border p-2">{{ item.fee }}</td>
                            <td class="border p-2">{{ item.start_date }}</td>
                            <td class="border p-2">{{ item.end_date }}</td>
                            <td class="border p-2 text-center">
                                <button
                                    @click="reunionToggleStatus(item)"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200"
                                    :class="item.data_status == 1 ? 'bg-green-500' : 'bg-gray-300'"
                                    >
                                    <span
                                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200"
                                        :class="item.data_status == 1 ? 'translate-x-6' : 'translate-x-1'"
                                    />
                                </button>
                            </td>
                            <td class="text-center">
                                <button @click="openReunionEdit(item)" class="text-blue-600">
                                    <span class="material-symbols-outlined text-lg">edit_note</span>
                                </button>

                                <button @click="reunionDestroy(item.id)"
                                    v-if="$page.props.auth.user.admin_role_id == 1"
                                    
                                    class="text-red-600"
                                    >
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="reunionData.data.length === 0">
                          <td colspan="6" class="text-center p-3 text-gray-400">
                            No data found
                          </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payment Methods -->
          <div v-if="activeTab === 'methods'">
            <div class="flex items-center justify-between mb-3 gap-3">

                <h2 class="text-sm font-semibold whitespace-nowrap">
                    Payment Methods
                </h2>

                <div class="flex-1 max-w-xs">
                    <input
                    v-model="methodSearch"
                    type="text"
                    placeholder="Search..."
                    class="w-full text-xs border rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                </div>

                <button @click="openPaymentMethodAdd" class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded whitespace-nowrap">
                    + Add
                </button>

            </div>

            <table class="w-full text-xs border">
              <thead class="bg-gray-100 text-gray-600">
                <tr>
                  <th class="p-2 border">SL</th>
                  <th class="p-2 border">Name</th>
                  <th class="p-2 border">Type</th>
                  <th class="p-2 border">Account No</th>
                  <th class="p-2 border">Account Name</th>
                  <th class="p-2 border text-center">Status</th>
                  <th class="p-2 border text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                 <tr v-for="(item, index) in methodData" :key="item.id" class="hover:bg-gray-50">
                  <td class="p-2 border">{{ index+1 }}</td>
                  <td class="p-2 border">{{ item.name }}</td>
                  <td class="p-2 border">{{ item.type || '-' }}</td>
                  <td class="p-2 border">{{ item.account_number || '-' }}</td>
                  <td class="p-2 border">{{ item.account_name || '-' }}</td>
                  <td class="p-2 border text-center">
                    <button
                      @click="toggleMethod(item)"
                      class="relative inline-flex h-6 w-11 items-center rounded-full transition"
                      :class="item.data_status == 1 ? 'bg-green-500' : 'bg-gray-300'"
                    >
                      <span
                        class="inline-block h-4 w-4 bg-white rounded-full transform transition"
                        :class="item.data_status == 1 ? 'translate-x-6' : 'translate-x-1'"
                      />
                    </button>
                  </td>
                  <td>
                    <button @click="openPaymentMethodEdit(item)" class="text-blue-600">
                      <span class="material-symbols-outlined text-lg">edit_note</span>
                    </button>
                    <button
                      v-if="$page.props.auth.user.admin_role_id == 1"
                      @click="methoddestroy(item.id)"
                      class="text-red-600"
                    >
                      <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                  </td>
                </tr>

                <!-- empty -->
                <tr v-if="methodData.length === 0">
                  <td colspan="7" class="text-center p-3 text-gray-400">
                    No data found
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

            <!-- Payments -->
            <div v-if="activeTab === 'payments'">
                <div class="flex items-center justify-between mb-3 gap-3">

                    <h2 class="text-sm font-semibold whitespace-nowrap">
                        Payments
                    </h2>

                    <div class="flex-1 max-w-xs">
                        <input
                        v-model="paymentSearch"
                        type="text"
                        placeholder="Search..."
                        class="w-full text-xs border rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <div class="w-52">
                      <select
                        v-model="selectedPaymentReunion"
                        class="w-full text-xs border rounded px-3 py-1.5"
                      >
                        <option value="">All Reunion Periods</option>
                        <option
                          v-for="item in reunionData.data"
                          :key="item.id"
                          :value="item.id"
                        >
                          {{ item.title }}
                        </option>
                      </select>
                    </div>

                </div>

                <table class="w-full text-xs border">
                  <thead class="bg-gray-100 text-gray-600">
                      <tr>
                      <th class="p-2 border">#</th>
                      <th class="p-2 border">User</th>
                      <th class="p-2 border">Reunion</th>
                      <th class="p-2 border">Amount</th>
                      <th class="p-2 border">Method</th>
                      <th class="p-2 border">TRX</th>
                      <th class="p-2 border">Date</th>
                      <th class="p-2 border">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr
                        v-for="item in paymentData"
                        :key="item.id"
                        class="hover:bg-gray-50"
                      >
                        <td class="p-2 border">{{ item.receipt_number }}</td>
                        <td class="p-2 border">{{ item.user?.name }}</td>
                        <td class="p-2 border">{{ item.reunion_period?.title }}</td>
                        <td class="p-2 border">{{ item.reunion_period?.fee }}</td>
                        <td class="p-2 border">{{ item.payment_method?.name }} ({{ item.payment_method?.account_number }})</td>
                        <td class="p-2 border">{{ item.trx_id }}</td>
                        <td class="p-2 border">{{ item.payment_date }}</td>
                        <td class="p-2 border text-center">
                          <span v-if="item.payment_status != 1">
                            <span class="material-symbols-outlined text-green-600 cursor-pointer"
                              @click="togglePayments(item)"
                            >
                              check
                            </span>
                            <span class="material-symbols-outlined text-red-600 cursor-pointer"
                              @click="deletePayment(item.id)"
                            >  
                              close
                            </span>
                          </span>
                          <span v-else class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                            Payment Confirmed
                          </span>
                        </td>
                      </tr>
                      <tr v-if="paymentData.length === 0">
                        <td colspan="7" class="text-center p-3 text-gray-400">
                          No payments found
                        </td>
                      </tr>
                  </tbody>
                </table>

                <div class="flex justify-between items-center mt-4 text-xs">
                  <button
                    @click="paymentPageChange(paymentPagination.current_page - 1)"
                    :disabled="paymentPagination.current_page === 1"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                  >
                    Previous
                  </button>

                  <span>
                    Page {{ paymentPagination.current_page }} of {{ paymentPagination.last_page }}
                  </span>

                  <button
                    @click="paymentPageChange(paymentPagination.current_page + 1)"
                    :disabled="paymentPagination.current_page === paymentPagination.last_page"
                    class="px-3 py-1 border rounded disabled:opacity-50"
                  >
                    Next
                  </button>
                </div>
            </div>         

        </div>
      </div>

    </div>

    <!-- Off Canvas -->
     <div
        v-if="showReunionForm"
        class="fixed inset-0 bg-black/40 z-50 flex justify-end"
        >
        <div class="w-[420px] bg-white h-full shadow-lg p-4">

            <div class="flex justify-between items-center mb-3">
            <h2 class="text-sm font-semibold">
                {{ isReunionEdit ? 'Edit Reunion' : 'Add Reunion' }}
            </h2>

            <button @click="showReunionForm = false">✕</button>
            </div>

            <!-- Form -->
            <input v-model="reunionForm.title" placeholder="Title" class="w-full border p-2 mb-2" />
            <input v-model="reunionForm.fee" placeholder="Fee" class="w-full border p-2 mb-2" />

            <input v-model="reunionForm.start_date" type="date" class="w-full border p-2 mb-2" />
            <input v-model="reunionForm.end_date" type="date" class="w-full border p-2 mb-2" />
            <select v-model="reunionForm.receipt_model" class="w-full border p-2 mb-2">
              <option value="">Select Receipt Model</option>
              <option value="1">Global</option>
              <option value="2">Eid</option>
            </select>

            <button @click="reeunionSave" class="w-full bg-blue-600 text-white py-2 rounded mt-2">
            {{ isReunionEdit ? 'Update' : 'Save' }}
            </button>

        </div>
    </div>

    <!-- Payment Method -->

    <div v-if="showMethodForm" class="fixed inset-0 bg-black/40 flex justify-end z-50">
      <div class="w-[420px] bg-white h-full p-4">

        <div class="flex justify-between mb-3">
          <h2>{{ isMethodEdit ? 'Edit Method' : 'Add Method' }}</h2>
          <button @click="showMethodForm = false">✕</button>
        </div>

        <input v-model="methodForm.name" placeholder="Name" class="w-full border p-2 mb-2" />
        <select
          v-model="methodForm.type"
          class="w-full border p-2 mb-2"
        >
          <option value="" disabled>Select Type</option>

          <option value="MFS">Mobile Financial Service (MFS)</option>
          <option value="BANK">Bank</option>
          <option value="CARD">Card</option>
          <option value="CASH">Cash</option>
        </select>
        <input v-model="methodForm.account_number" placeholder="Account Number" class="w-full border p-2 mb-2" />
        <input v-model="methodForm.account_name" placeholder="Account Name" class="w-full border p-2 mb-2" />

        <textarea v-model="methodForm.description" placeholder="Description" class="w-full border p-2 mb-2"></textarea>

        <button @click="saveMethod" class="w-full bg-blue-600 text-white py-2 rounded">
          {{ isMethodEdit ? 'Update' : 'Save' }}
        </button>

      </div>
    </div>
  </AdminLayout>
</template>