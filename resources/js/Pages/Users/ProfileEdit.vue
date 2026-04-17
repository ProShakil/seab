<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  user: Object,
  relationships: Array,
  technologies: Array,
  occupations: Array,
})

const activeTab = ref('basic')

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  contact_no: props.user.contact_no,

  user_fathers_name: props.user.user_fathers_name,
  user_mothers_name: props.user.user_mothers_name,

  dob: props.user.dob,
  gender: props.user.gender,

  id_type: props.user.id_type,
  national_identity_number: props.user.national_identity_number,

  present_address_detail: props.user.present_address_detail,
  permanent_address_details: props.user.permanent_address_details,

  tech_id: props.user.tech_id,
  occupation_id: props.user.occupation_id,

  employer_name: props.user.employer_name,
  designation: props.user.designation,
  office_address_details: props.user.office_address_details,

  latest_degree_name: props.user.latest_degree_name,
  latest_institute_name: props.user.latest_institute_name,

  emergency_contact_name: props.user.emergency_contact_name,
  relationship_id: props.user.relationship_id,
  emergency_contact_no: props.user.emergency_contact_no,
  profile_image: null,
  signature_image: null,
})
const profilePreview = ref(props.user.profile_image ? `/storage/${props.user.profile_image}` : null)
const signaturePreview = ref(props.user.signature ? `/storage/${props.user.signature}` : null)

/* FILE HANDLERS */
const handleProfileChange = (e) => {
  const file = e.target.files[0]
  form.profile_image = file

  if (file) {
    const reader = new FileReader()
    reader.onload = (ev) => {
      profilePreview.value = ev.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleSignatureChange = (e) => {
  const file = e.target.files[0]
  form.signature_image = file

  if (file) {
    const reader = new FileReader()
    reader.onload = (ev) => {
      signaturePreview.value = ev.target.result
    }
    reader.readAsDataURL(file)
  }
}
const submit = () => {
  form.post(route('profile.update'))
}
// const submit = () => {
//   form.patch(route('profile.update'), {
//     forceFormData: true,
//   })
// }

/* ID TYPE LABEL */
const idTypeLabel = computed(() => {
  if (form.id_type == 1) return 'NID Number'
  if (form.id_type == 2) return 'Smart Card Number'
  if (form.id_type == 3) return 'Birth Registration Number'
  return 'ID Number'
})

/* HIDE WORK INFO FOR SPECIFIC OCCUPATION */
const hideWorkFields = computed(() => {
  return !form.occupation_id || [1, 2, 3].includes(Number(form.occupation_id))
})

const tabClass = 'px-4 py-3 text-gray-600 hover:text-[#D4AF37]'
const activeClass = 'px-4 py-3 border-b-2 border-[#D4AF37] text-[#D4AF37]'

</script>

<template>
<ProfileLayout>

  <div class="bg-white rounded-xl shadow">

    <!-- TABS -->
    <div class="flex flex-wrap border-b text-sm font-medium">
      <button @click="activeTab='basic'" :class="activeTab==='basic'?activeClass:tabClass">Basic</button>
      <button @click="activeTab='address'" :class="activeTab==='address'?activeClass:tabClass">Address</button>
      <button @click="activeTab='professional'" :class="activeTab==='professional'?activeClass:tabClass">Professional</button>
      <button @click="activeTab='education'" :class="activeTab==='education'?activeClass:tabClass">Education</button>
      <button @click="activeTab='emergency'" :class="activeTab==='emergency'?activeClass:tabClass">Emergency</button>
    </div>

    <form @submit.prevent="submit" class="p-6 space-y-6 text-sm">

      <!-- BASIC -->
      <div v-if="activeTab === 'basic'" class="grid md:grid-cols-2 gap-4">

        <input v-model="form.name" placeholder="Name" class="input w-full border p-2 mb-4" />
        <input v-model="form.email" placeholder="Email" class="input w-full border p-2 mb-4" />
        <input v-model="form.contact_no" placeholder="Phone" class="input w-full border p-2 mb-4" />

        <input v-model="form.user_fathers_name" placeholder="Father Name" class="input w-full border p-2 mb-4" />
        <input v-model="form.user_mothers_name" placeholder="Mother Name" class="input w-full border p-2 mb-4" />

        <input v-model="form.dob" type="date" class="input w-full border p-2 mb-4" />
        <select v-model="form.gender" class="input w-full border p-2 mb-4">
            <option :value="null">Select Gender</option>
            <option :value="'Male'">Male</option>
            <option :value="'Female'">Female</option>
        </select>

        <!-- ID TYPE -->
        <div>
          <select v-model="form.id_type" class="input mr-5">
            <option :value="null">Select ID Type</option>
            <option :value="1">NID</option>
            <option :value="2">Smart Card</option>
            <option :value="3">Birth Registration</option>
          </select>
          <input v-model="form.national_identity_number"
                 :placeholder="idTypeLabel"
                 class="input" />
        </div>

        <!-- ✅ PROFILE IMAGE -->
        <div>
          <label class="block mb-1 text-gray-600">Profile Image</label>
          <input type="file" accept="image/*" class="input w-full border p-2 mb-4"
                 @change="handleProfileChange" />

          <img v-if="profilePreview"
               :src="profilePreview"
               class="mt-2 w-24 h-24 object-cover rounded-full border" />
        </div>

        <!-- ✅ SIGNATURE -->
        <div>
          <label class="block mb-1 text-gray-600">Signature</label>
          <input type="file" accept="image/*" class="input w-full border p-2 mb-4"
                 @change="handleSignatureChange" />

          <img v-if="signaturePreview"
               :src="signaturePreview"
               class="mt-2 w-32 h-16 object-contain border rounded" />
        </div>

      </div>

      <!-- ADDRESS -->
      <div v-if="activeTab === 'address'" class="grid md:grid-cols-2 gap-4">

        <textarea v-model="form.present_address_detail" class="input w-full border p-2 mb-4" placeholder="Present Address"></textarea>
        <textarea v-model="form.permanent_address_details" class="input w-full border p-2 mb-4" placeholder="Permanent Address"></textarea>

      </div>

      <!-- PROFESSIONAL -->
      <div v-if="activeTab === 'professional'" class="grid md:grid-cols-2 gap-4">

        <!-- OCCUPATION -->
        <div>
          <select v-model="form.occupation_id" class="input w-full border p-2 mb-4">
            <option :value="null">Select Occupation</option>
            <option v-for="o in occupations" :key="o.id" :value="o.id">
              {{ o.name }}
            </option>
          </select>
        </div>

        <!-- CONDITIONAL WORK FIELDS -->
        <template v-if="!hideWorkFields">

          <input v-model="form.employer_name" placeholder="Employer" class="input w-full border p-2 mb-4" />
          <input v-model="form.designation" placeholder="Designation" class="input w-full border p-2 mb-4" />
          <textarea v-model="form.office_address_details" placeholder="Office Address" class="input w-full border p-2 mb-4"></textarea>

        </template>

      </div>

      <!-- EDUCATION -->
      <div v-if="activeTab === 'education'" class="grid md:grid-cols-2 gap-4">

        <div>
          <select v-model="form.tech_id" class="input w-full border p-2 mb-4">
            <option :value="null">Select Technology</option>
            <option v-for="t in technologies" :key="t.id" :value="t.id">
              {{ t.name }}
            </option>
          </select>
        </div>

        <input v-model="form.latest_degree_name" placeholder="Degree" class="input w-full border p-2 mb-4" />
        <input v-model="form.latest_institute_name" placeholder="Institute" class="input w-full border p-2 mb-4" />

      </div>

      <!-- EMERGENCY -->
      <div v-if="activeTab === 'emergency'" class="grid md:grid-cols-2 gap-4">

        <input v-model="form.emergency_contact_name" placeholder="Contact Name" class="input w-full border p-2 mb-4" />

        <select v-model="form.relationship_id" class="input w-full border p-2 mb-4">
          <option :value="null">Select Relationship</option>
          <option v-for="r in relationships" :key="r.id" :value="r.id">
            {{ r.name }}
          </option>
        </select>

        <input v-model="form.emergency_contact_no" placeholder="Phone" class="input w-full border p-2 mb-4" />

      </div>

      <!-- SUBMIT -->
      <div class="pt-4">
        <button
          type="submit"
          class="bg-[#001E3C] text-white px-6 py-2 rounded-lg hover:bg-[#003366]"
          :disabled="form.processing"
        >
          Save Changes
        </button>
      </div>

    </form>

  </div>

</ProfileLayout>
</template>