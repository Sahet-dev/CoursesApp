<template>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">User Management</h1>



        <!-- User List -->
        <table v-if="users.length" class="w-full border-collapse border border-gray-200">
            <thead>
            <tr>
                <th class="border border-gray-300 p-2 text-left">Name</th>
                <th class="border border-gray-300 p-2 text-left">Email</th>
                <th class="border border-gray-300 p-2 text-left">Role</th>
                <th class="border border-gray-300 p-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="users.length === 0">
                <td colspan="3" class="border border-gray-300 p-2 text-center">No users available</td>
            </tr>
            <tr v-for="user in users" :key="user.id">
                <td class="border border-gray-300 p-2">{{ user.name }}</td>
                <td class="border border-gray-300 p-2">{{ user.email }} : {{user.id}}</td>
                <td class="border border-gray-300 p-2">
                    <select v-model="user.role" @change="updateUserRole(user)" class="mt-1 block w-full">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="moderator">Moderator</option>
                        <option value="admin">Admin</option>
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
        <div v-else  >
            <Loader />
        </div>


    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../../api/axios.js';
import router from '../../../router'; // Import router if using Vue Router
import axios from 'axios';
import Loader from "../Loader.vue";

const users = ref([]);

const isEditing = ref(false);
const form = ref({
    id: null,
    name: '',
    email: '',
    role: 'student',
});

const errorMessage = ref('');

const fetchUsers = async () => {
    try {
        const response = await apiClient.get('/users');
        users.value = response.data.data; // Access the `data` property here
        console.log('Fetched user data:', users.value);
    } catch (error) {
        if (error.response?.status === 401) {
            // User is unauthorized, redirect to login page
            router.push('/login');
        } else {
            console.error('Failed to fetch user data:', error);
            errorMessage.value = 'Failed to fetch user data.';
        }
    }
};







const editUser = (user) => {
    form.value = { ...user };
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
    console.log('Updating user role:', user);
    if (!user.id) {
        console.error('User ID is missing:', user);
        return;
    }
    try {
        await apiClient.put(`/users/rol/${user.id}`, { role: user.role });
        await fetchUsers();
    } catch (error) {
        console.error('Failed to update user role:', error);
    }
};




const deleteUser = async (id) => {
    try {
        await axios.delete(`/api/users/${id}`);
        await fetchUsers();
    } catch (error) {
        console.error('Failed to delete user:', error);
    }
};


onMounted(fetchUsers);
</script>
