<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue'
import { computed, ref } from 'vue'

const props = defineProps({
  user: Object
})

const activeTab = ref('basic')

const imageUrl = computed(() => {
  return props.user.profile_image
    ? `/storage/${props.user.profile_image}`
    : 'https://ui-avatars.com/api/?name=' + props.user.name
})
</script>

<template>
<ProfileLayout>

  <div class="space-y-6">

    <!-- PROFILE HEADER -->
    <div class="bg-gradient-to-r from-[#001E3C] to-[#003366] text-white p-6 rounded-xl shadow flex flex-col md:flex-row items-center gap-6">
      <img :src="imageUrl"
        class="w-28 h-28 rounded-full border-4 border-[#D4AF37] object-cover"
      />

      <div>
        <h2 class="text-2xl font-bold">{{ user.name }}</h2>
        <p class="text-sm text-blue-200">{{ user.email }}</p>
        <p class="text-sm mt-1">Membership ID: {{ user.membership_id ?? 'N/A' }}</p>
      </div>
    </div>

    <!-- TABS -->
    <div class="bg-white rounded-xl shadow">

      <!-- TAB BUTTONS -->
      <div class="flex flex-wrap border-b text-sm font-medium">

        <button @click="activeTab = 'basic'"
          :class="activeTab === 'basic' ? activeClass : tabClass">
          Basic
        </button>

        <button @click="activeTab = 'address'"
          :class="activeTab === 'address' ? activeClass : tabClass">
          Address
        </button>

        <button @click="activeTab = 'professional'"
          :class="activeTab === 'professional' ? activeClass : tabClass">
          Professional
        </button>

        <button @click="activeTab = 'education'"
          :class="activeTab === 'education' ? activeClass : tabClass">
          Education
        </button>

        <button @click="activeTab = 'emergency'"
          :class="activeTab === 'emergency' ? activeClass : tabClass">
          Emergency
        </button>

      </div>

      <!-- TAB CONTENT -->
      <div class="p-6 text-sm">

        <!-- BASIC -->
        <div v-if="activeTab === 'basic'" class="grid md:grid-cols-2 gap-4">
          <div><b>User Name:</b> {{ user.user_name }}</div>
          <div><b>Phone:</b> {{ user.contact_no ?? '-' }}</div>
          <div><b>Father:</b> {{ user.user_fathers_name ?? '-' }}</div>
          <div><b>Mother:</b> {{ user.user_mothers_name ?? '-' }}</div>
          <div><b>DOB:</b> {{ user.dob ?? '-' }}</div>
          <div><b>Gender:</b> {{ user.gender ?? '-' }}</div>
          <div><b>NID:</b> {{ user.national_identity_number ?? '-' }}</div>
        </div>

        <!-- ADDRESS -->
        <div v-if="activeTab === 'address'" class="grid md:grid-cols-2 gap-4">
          <div>
            <b>Present Address:</b><br>
            {{ user.present_address_detail ?? '-' }}
          </div>
          <div>
            <b>Permanent Address:</b><br>
            {{ user.permanent_address_details ?? '-' }}
          </div>
        </div>

        <!-- PROFESSIONAL -->
        <div v-if="activeTab === 'professional'" class="grid md:grid-cols-2 gap-4">
          <div><b>Occupation:</b> {{ user.occupation.name ?? '-' }}</div>
          <div><b>Employer:</b> {{ user.employer_name ?? '-' }}</div>
          <div><b>Designation:</b> {{ user.designation ?? '-' }}</div>
          <div>
            <b>Office Address:</b><br>
            {{ user.office_address_details ?? '-' }}
          </div>
        </div>

        <!-- EDUCATION -->
        <div v-if="activeTab === 'education'" class="grid md:grid-cols-2 gap-4">
          <div><b>Degree:</b> {{ user.latest_degree_name ?? '-' }}</div>
          <div><b>Institute:</b> {{ user.latest_institute_name ?? '-' }}</div>
          <div><b>Technology:</b> {{ user.technology.name ?? '-' }}</div>
        </div>

        <!-- EMERGENCY -->
        <div v-if="activeTab === 'emergency'" class="grid md:grid-cols-2 gap-4">
          <div><b>Name:</b> {{ user.emergency_contact_name ?? '-' }}</div>
          <div><b>Relationship:</b> {{ user.relationship.name ?? '-' }}</div>
          <div><b>Phone:</b> {{ user.emergency_contact_no ?? '-' }}</div>
        </div>

      </div>

    </div>

  </div>

</ProfileLayout>
</template>

<script>
export default {
  computed: {
    tabClass() {
      return 'px-4 py-3 text-gray-600 hover:text-[#D4AF37] transition'
    },
    activeClass() {
      return 'px-4 py-3 border-b-2 border-[#D4AF37] text-[#D4AF37]'
    }
  }
}
</script>