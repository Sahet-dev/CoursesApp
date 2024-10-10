<template>
    <DashboardHeader />
    <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
        <h1 class="text-9xl font-bold text-blue-500 mb-8">404</h1>
        <h2 class="text-2xl md:text-4xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
        <p class="text-md md:text-lg text-gray-600 mb-4">
            Sorry, the page you are looking for does not exist.
        </p>

        <button @click="goBackHome" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-md transition">
            Go Back Home
        </button>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import DashboardHeader from "./DashboardHeader.vue";
import {onMounted, ref} from "vue";
import apiClient from "../api/axios.js";

const user = ref(null);
const errorMessage = ref('');
const router = useRouter();

const fetchUser = async () => {
    try {
        const response = await apiClient.get('/user');
        user.value = response.data.data;
    } catch (error) {
        if (error.response?.status === 401) {
            router.push('/login');
        } else {
            console.error('Failed to fetch user data:', error);
            errorMessage.value = 'Failed to fetch user data.';
        }
    }
};





const goBackHome = () => {
    if (user.value.role === 'admin') {
        router.push('/admin-dashboard');
    } else if (user.value.role === 'teacher') {
        router.push('/teacher-dashboard');
    } else if (user.value.role === 'moderator') {
        router.push('/moderator-dashboard');
    } else {
        router.push('/');
    }
};
onMounted(fetchUser);

</script>

<style scoped>
</style>
