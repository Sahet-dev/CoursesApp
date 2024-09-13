<template>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">User Management</h1>

        <!-- User List -->
        <table v-if="users.data && users.data.length" class="w-full border-collapse border border-gray-200">
            <thead>
            <tr>
                <th class="border border-gray-300 p-2 text-left">ID</th>
                <th class="border border-gray-300 p-2 text-left">Name</th>
                <th class="border border-gray-300 p-2 text-left">Email</th>
                <th class="border border-gray-300 p-2 text-left">Role</th>
                <th class="border border-gray-300 p-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in users.data" :key="user.id">
                <td class="border border-gray-300 p-2">{{ user.id }}</td>
                <td class="border border-gray-300 p-2">{{ user.name }}</td>
                <td class="border border-gray-300 p-2">{{ user.email }}</td>
                <td class="border border-gray-300 p-2">
                    <select v-model="user.role" @change="updateUserRole(user)" class="mt-1 block w-full">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="moderator">Moderator</option>
                    </select>
                </td>
                <td class="border border-gray-300 p-2 flex space-x-2">
                    <button @click="editUser(user)" class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 transition-all duration-300">
                        Edit
                    </button>
                    <button @click="deleteUser(user.id)" class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 transition-all duration-300">
                        Delete
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
        <div v-else>
            <Loader />
        </div>

        <!-- Pagination Controls -->
        <div v-if="users.data && (users.prev_page_url || users.next_page_url)"
             class="flex justify-center items-center mt-6">
            <!-- Pagination Information -->
            <div class="mr-4">
                Page {{ users.current_page }} of {{ users.last_page }}
            </div>

            <!-- Pagination Buttons -->
            <div class="flex space-x-2">
                <!-- Previous Button -->
                <button
                    @click="fetchUsers(users.prev_page_url)"
                    :disabled="!users.prev_page_url"
                    class="px-4 py-2 mr-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
                >
                    Previous
                </button>

                <!-- Page Numbers -->
                <button
                    v-for="page in users.last_page"
                    :key="page"
                    @click="fetchUsers(getUserPageUrl(page))"
                    :class="[
                'px-4 py-2 rounded hover:bg-gray-400 transition-all duration-300',
                users.current_page === page ? 'bg-blue-500 text-white' : 'bg-gray-300'
            ]"
                >
                    {{ page }}
                </button>

                <!-- Next Button -->
                <button
                    @click="fetchUsers(users.next_page_url)"
                    :disabled="!users.next_page_url"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
                >
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import apiClient from '../../../api/axios.js';
import router from '../../../router';
import axios from 'axios';
import Loader from "../Loader.vue";

const users = ref({
    data: [],
    prev_page_url: null,
    next_page_url: null,
});

const isEditing = ref(false);
const form = ref({
    id: null,
    name: '',
    email: '',
    role: 'student',
});

const errorMessage = ref('');

// Fetch users with pagination
const fetchUsers = async (url = '/users') => {
    try {
        const response = await apiClient.get(url);
        users.value = response.data; // Set the paginated response
        console.log('Fetched user data:', users.value);
    } catch (error) {
        if (error.response?.status === 401) {
            router.push('/login');
        } else {
            console.error('Failed to fetch user data:', error);
            errorMessage.value = 'Failed to fetch user data.';
        }
    }
};

const editUser = (user) => {
    form.value = {...user};
    isEditing.value = true;
    showModal.value = true;
};

const updateUser = async () => {
    try {
        await axios.put(`/api/users/${form.value.id}`, form.value);
        await fetchUsers();
        closeModal();
    } catch (error) {
        console.error('Failed to update user:', error);
    }
};

const updateUserRole = async (user) => {
    if (!user.id) {
        console.error('User ID is missing:', user);
        return;
    }
    try {
        await apiClient.put(`/users/change-role/${user.id}`, {role: user.role});
        await fetchUsers();
    } catch (error) {
        console.error('Failed to update user role:', error);
        const errorMessage = error.response?.data?.message || 'An unexpected error occurred';
        alert(errorMessage);
    }
};

const deleteUser = async (id) => {
    try {
        await axios.delete(`/api/users/${id}`);
        await fetchUsers();
    } catch (error) {
        console.error('Failed to delete user:', error);
        const errorMessage = error.response?.data?.message || 'An unexpected error occurred';
        console.log(errorMessage);
        alert(errorMessage);
    }
};

onMounted(fetchUsers);
</script>
