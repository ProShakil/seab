<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { router,usePage } from '@inertiajs/vue3'
const page = usePage()
import Swal from 'sweetalert2'
const authUser = page.props.auth.user

const props = defineProps({
    users: Object,
    filters: Object,
    membershipTypes: Array
})

const search = ref(props.filters.search || '')

/* SEARCH */
const doSearch = () => {
    router.get(route('users.list'), {
        search: search.value
    }, {
        preserveState: true,
        replace: true
    })
}

/* DELETE USER */
const deleteUser = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('users.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'User has been deleted.', 'success')
                }
            })
        }
    })
}
const showModal = ref(false)
const selectedUser = ref(null)
const form = ref({
    membership_type_id: '',
    status: '',
    is_admin: false,
    admin_role_id: ''
})

const openEdit = (user) => {
    selectedUser.value = user
    form.value = {
        membership_type_id: user.membership_type_id,
        status: user.data_status,
        is_admin: Number(user.is_admin) === 1,
        admin_role_id: user.admin_role_id
    }
    showModal.value = true
}

const updateUser = () => {
    router.put(route('users.update', selectedUser.value.id), form.value, {
        onSuccess: () => {
            showModal.value = false
        }
    })
}

</script>

<template>
<AdminLayout>

    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">User List</h2>
            </div>
    
            <!-- SEARCH -->
            <div class="mb-4 flex gap-2">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search by name or contact..."
                    class="border p-2 rounded w-72"
                />
    
                <button
                    @click="doSearch"
                    class="bg-blue-600 text-white px-4 rounded"
                >
                    Search
                </button>
            </div>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="w-full text-sm text-left">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">SL</th>
                        <th class="p-3">Membership ID</th>
                        <th class="p-3">Image</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Contact No</th>
                        <th class="p-3">Technology</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(user, index) in users.data"
                        :key="user.id"
                        class="border-b hover:bg-gray-50"
                    >

                        <!-- SL -->
                        <td class="p-3">{{ index + 1 }}</td>

                        <!-- Membership ID -->
                        <td class="p-3">{{ user.membership_id }}</td>

                        <!-- IMAGE -->
                        <td class="p-3">
                           <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center bg-gray-200">
                                <!-- IF IMAGE EXISTS -->
                                <img
                                    v-if="user.profile_image"
                                    :src="'/storage/' + user.profile_image"
                                    class="w-full h-full object-cover"
                                />

                                <!-- IF NO IMAGE -->
                                <span v-else class="material-symbols-outlined text-lg text-gray-600">
                                    account_circle
                                </span>

                            </div>
                        </td>

                        <!-- NAME -->
                        <td class="p-3">{{ user.name }}</td>

                        <!-- CONTACT -->
                        <td class="p-3">{{ user.contact_no }}</td>

                        <!-- TECHNOLOGY -->
                        <td class="p-3">
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded mr-1">
                                {{ user.technology.name }}
                            </span>
                        </td>

                        <!-- VIEW -->
                        <td class="p-3 text-center">
                            <a :href="route('users.show', user.id)" class="text-blue-600">
                                <span class="material-symbols-outlined text-lg">
                                    visibility
                                </span>
                            </a>
                            <button @click="openEdit(user)" class="text-green-600">
                                <span class="material-symbols-outlined text-lg">
                                    edit_note
                                </span>
                            </button>
                            <button v-if="authUser.id !== user.id" @click="deleteUser(user.id)" class="text-red-600">
                                <span class="material-symbols-outlined text-lg">
                                    delete
                                </span>
                            </button>
                        </td>

                    </tr>

                    <tr v-if="users.data.length === 0">
                        <td colspan="9" class="p-4 text-center text-gray-500">
                            No users found
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-4 flex gap-2 flex-wrap">
            <button
                v-for="link in users.links"
                :key="link.label"
                v-html="link.label"
                @click="link.url && router.visit(link.url)"
                :class="[
                    'px-3 py-1 border rounded text-sm',
                    link.active ? 'bg-blue-600 text-white' : 'bg-white'
                ]"
            />
        </div>
        <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center">

            <div class="bg-white w-[500px] p-5 rounded-lg">

                <h2 class="text-lg font-bold mb-4">Edit User</h2>

                <!-- Membership Type -->
                <select v-model="form.membership_type_id" class="w-full border p-2 mb-3">
                    <option :value="null">Select Type</option>
                    <option v-for="type in props.membershipTypes" :key="type.id" :value="type.id">
                        {{ type.name }}
                    </option>
                </select>

                <!-- Status -->
                <select v-model="form.status" class="w-full border p-2 mb-3">
                    <option :value="null">Select Status</option>
                    <option :value="1">Approved</option>
                    <option :value="2">Pending</option>
                    <option :value="0">Reject</option>
                </select>

                <!-- Admin Access -->
                <label class="flex items-center gap-2 mb-3">
                    <input type="checkbox" v-model="form.is_admin" />
                    Admin Access
                </label>

                <!-- Role Dropdown -->
                <select
                    v-if="form.is_admin"
                    v-model="form.admin_role_id"
                    class="w-full border p-2 mb-3"
                >
                    <option :value="1">Super Admin</option>
                    <option :value="2">Admin</option>
                </select>

                <!-- Buttons -->
                <div class="flex justify-end gap-2">
                    <button @click="showModal = false" class="px-4 py-2 border">Cancel</button>
                    <button @click="updateUser" class="px-4 py-2 bg-blue-600 text-white">
                        Update
                    </button>
                </div>

            </div>

        </div>
    </div>

</AdminLayout>
</template>