<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <div v-if="activities.length" class="bg-white rounded-lg p-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Activities</h2>
            <ul class="space-y-4">
                <li v-for="activity in activities" :key="activity.id" class="flex items-start space-x-4 border-b pb-4 last:border-b-0">
                    <div class="w-14 h-14 flex items-center justify-center bg-blue-50 rounded-full shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-lg font-medium">{{ activity.action }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ activity.date }}</p>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="bg-white shadow-lg rounded-lg p-6 text-center">
            <p class="text-gray-500 text-lg">No activities found</p>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import apiClient from "../../axios/index.js";

const activities = ref([]);

// Fetch user activities
const fetchActivities = async (userId) => {
    try {
        const response = await apiClient.get(`/user/${userId}/activities`);
        activities.value = response.data;
        console.log(activities.value);
    } catch (error) {
        console.error('Error fetching activities:', error);
    }
};

onMounted(() => {
    const userId = 1;
    fetchActivities(userId);
});

</script>

<style scoped>
</style>
