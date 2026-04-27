<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import ProfileLayout from '@/Layouts/ProfileLayout.vue'
import Swal from 'sweetalert2'

const props = defineProps({
  payments: Array,
  methods: Array,
  siteSettings: Object,
})

const showForm = ref(false)

const form = ref({
  payment_date: '',
  trx_id: '',
  reference: '',
  payment_method: '',
})

const submit = () => {
  Swal.fire({
    title: 'Submit Payment?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, submit',
  }).then((result) => {
    if (result.isConfirmed) {
      router.post('/user/reunion/register', form.value, {
        onSuccess: () => {
          Swal.fire('Success', 'Payment submitted', 'success')
          showForm.value = false
        }
      })
    }
  })
}

const downloadReceipt = (id) => {
  window.open(`/user/reunion/payment/${id}/download`, '_blank')
}

const reunionId = props.siteSettings?.reunion_id

// ✅ FIXED COMPUTED (NO .value)
const hasCurrentReunionPayment = computed(() => {
  return (props.payments.data || []).some(
    p => p.reunion_period_id === reunionId
  )
})

const formatDate = (date) => {
  const now = new Date();
  
  // Parse YYYY-MM-DD safely
  const past = new Date(date + "T00:00:00");

  if (isNaN(past)) return "Invalid date";

  let diff = Math.floor((now - past) / 1000);

  if (diff < 0) return "Just now";

  if (diff < 60) return `${diff} sec ago`;
  if (diff < 3600) return `${Math.floor(diff / 60)} min ago`;
  if (diff < 86400) {
    const hours = Math.floor(diff / 3600);
    return `${hours} hour${hours > 1 ? "s" : ""} ago`;
  }
  if (diff < 2592000) {
    const days = Math.floor(diff / 86400);
    return `${days} day${days > 1 ? "s" : ""} ago`;
  }

  return past.toLocaleDateString();
};
</script>

<template>
  <ProfileLayout>

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Reunion Payments</h2>

      <button
        v-if="!hasCurrentReunionPayment"
        @click="showForm = true"
        class="bg-red-600 text-white px-3 py-1.5 rounded text-sm hover:bg-red-700"
      >
        + Add Payment
      </button>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
      <table class="w-full text-xs border">
        <thead class="bg-gray-100 text-gray-600">
          <tr>
            <th class="border p-2">SL</th>
            <th class="border p-2">Reunion</th>
            <th class="border p-2">TRX ID</th>
            <th class="border p-2">Reference</th>
            <th class="border p-2">Method</th>
            <th class="border p-2">Payment Date</th>
            <th class="border p-2">Payment Status</th>
            <th>D</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(p,index) in props.payments.data" :key="p.id" class="hover:bg-gray-50">
            <td class="border p-2">{{ index+1 }}</td>
            <td class="border p-2">{{ p.reunion_period?.title }}</td>
            <td class="border p-2">{{ p.trx_id }}</td>
            <td class="border p-2">{{ p.reference }}</td>
            <td class="border p-2">{{ p.payment_method?.name }} ({{ p.payment_method?.account_number }})</td>
            <td class="border p-2">{{ formatDate(p.payment_date) }}</td>
            <td class="border p-2">
                <span
                    :class="[
                    'px-2 py-1 rounded-full text-xs font-semibold',
                    p.payment_status == 1
                        ? 'bg-green-100 text-green-700 border border-green-300'
                        : p.payment_status == 0
                        ? 'bg-yellow-100 text-yellow-700 border border-yellow-300'
                        : 'bg-red-100 text-red-700 border border-red-300'
                    ]"
                >
                    {{
                    p.payment_status == 1
                        ? 'Confirmed'
                        : p.payment_status == 0
                        ? 'Under Processing'
                        : 'Rejected'
                    }}
                </span>
            </td>
            <td class="border p-2">
                <button
                    v-if="p.payment_status == 1"
                    @click="downloadReceipt(p.id)"
                    class="text-blue-600 hover:text-blue-800 transition"
                    title="Download Receipt"
                >
                    <span class="material-symbols-outlined text-lg">
                    download
                    </span>
                </button>

                <span
                    v-else
                    class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300"
                >
                    Waiting
                </span>
            </td>
          </tr>

          <tr v-if="props.payments.data.length === 0">
            <td colspan="7" class="text-center p-3 text-gray-400">
              No payments found
            </td>
          </tr>
        </tbody>
      </table>

      <div class="flex gap-2 mt-4 justify-center">
        <button
            v-for="link in props.payments.links"
            :key="link.label"
            @click="link.url && router.visit(link.url)"
            v-html="link.label"
            class="px-3 py-1 border rounded text-xs"
            :class="link.active ? 'bg-red-600 text-white' : 'hover:bg-gray-100'"
        />
        </div>
    </div>

    <!-- MODAL -->
    <div
      v-if="showForm"
      class="fixed inset-0 bg-black/40 flex justify-end z-50"
    >
      <div class="w-[420px] bg-white h-full p-4 shadow-lg">

        <!-- HEADER -->
        <div class="flex justify-between mb-4">
          <h2 class="font-semibold">Add Payment</h2>
          <button @click="showForm = false">✕</button>
        </div>

        <!-- FORM -->
        <input
          v-model="form.payment_date"
          type="date"
          class="w-full border p-2 mb-2"
        />

        <input
          v-model="form.trx_id"
          placeholder="TRX ID"
          class="w-full border p-2 mb-2"
        />

        <input
          v-model="form.reference"
          placeholder="Reference (optional)"
          class="w-full border p-2 mb-2"
        />

        <!-- PAYMENT METHOD -->
        <select
          v-model="form.payment_method"
          class="w-full border p-2 mb-3"
        >
          <option disabled value="">Select Payment Method</option>

          <option
            v-for="m in methods"
            :key="m.id"
            :value="m.id"
          >
            {{ m.name }} ({{ m.account_number }})
          </option>
        </select>

        <!-- SUBMIT -->
        <button
          @click="submit"
          class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700"
        >
          Save Payment
        </button>

      </div>
    </div>

  </ProfileLayout>
</template>