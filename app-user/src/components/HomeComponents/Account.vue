<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <Loader v-if="loading" />

        <div v-else class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
            <!-- Avatar Section -->
            <div class="flex-shrink-0">
                <img :src="defaultAvatar" alt="User Avatar" class="w-24 h-24 rounded-full object-cover" />
            </div>

            <!-- User Information Section -->
            <div class="flex-1">
                <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-800">User Profile</h2>
                <div class="space-y-4">
                    <!-- Name -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Name:</span>
                        <p>{{ user?.name || 'N/A' }}</p>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Email:</span>
                        <p>{{ user?.email || 'N/A' }}</p>
                    </div>

                    <!-- Plan -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Plan:</span>
                        <p>{{ user?.plan || 'N/A' }}</p>
                    </div>

                    <!-- Expires At -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Expires At:</span>
                        <p>{{ user?.expires_at || 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import apiClient from "../../axios/index.js";
import defaultAvatar from "../../assets/avatar_default.png";
import Loader from "../CourseDetail/Loader.vue";

const user = ref(null);
const loading = ref(true);
const route = useRoute();

const fetchUser = async () => {
    try {
        const response = await apiClient.get('/user');

        if (!response.data) {
            user.value = null;
        } else {
            user.value = response.data.data;
        }
    } catch (error) {
        if (error.response && error.response.status === 401) {
            user.value = null;
        } else {
            console.error('Error fetching user:', error);
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchUser();
});
</script>

<style scoped>
</style>
