<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <!-- Flex container with responsive adjustments -->
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
            <!-- Avatar Section -->
            <div class="flex-shrink-0">
                <img
                    :src="user.avatarUrl"
                    alt="User Avatar"
                    class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg"
                />
            </div>

            <!-- User Information Section -->
            <div class="flex-1">
                <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-800">User Profile</h2>
                <div class="space-y-4">
                    <!-- Name -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Name:</span>
                        <p class="text-gray-600">{{ user.name }}</p>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Email:</span>
                        <p class="text-gray-600">{{ user.email }}</p>
                    </div>

                    <!-- Plan -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Plan:</span>
                        <p class="text-gray-600">
                            {{ user.subscriptions?.length > 0 ? user.subscriptions[0].plan : 'No active subscription' }}
                        </p>
                    </div>

                    <!-- Expires At -->
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                        <span class="font-semibold text-gray-700 md:w-32">Expires At:</span>
                        <p class="text-gray-600">
                            {{ user.subscriptions?.length > 0 ? user.subscriptions[0].ends_at : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import apiClient from "../../axios/index.js";

const user = ref(null);
const route = useRoute(); // Get the route to access route params

onMounted(() => {
    fetchUserProfile(route.params.id);
});

const fetchUserProfile = async (userId) => {
    try {
        const response = await apiClient.get(`user/profile/{id}`);
        user.value = response.data.user;
    } catch (error) {
        console.error('Failed to fetch user profile:', error);
    }
};
</script>


<style scoped>
</style>
