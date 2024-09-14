<template>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">Feedback List</h1>

        <!-- Feedback Table -->
        <div v-if="feedbacks.data.length > 0">
            <table class="min-w-full divide-y divide-gray-200 mb-4">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="feedback in feedbacks.data" :key="feedback.id">
                    <td class="px-6 py-4 whitespace-nowrap">{{ feedback.type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ feedback.message }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ feedback.user_id }}</td>
                </tr>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div class="flex justify-between items-center">
                <button
                    :disabled="!feedbacks.prev_page_url"
                    @click="fetchFeedbacks(feedbacks.prev_page_url)"
                    class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all duration-300 disabled:opacity-50"
                >
                    Previous
                </button>
                <div class="text-gray-700">
                    Page {{ feedbacks.current_page }} of {{ feedbacks.last_page }}
                </div>
                <button
                    :disabled="!feedbacks.next_page_url"
                    @click="fetchFeedbacks(feedbacks.next_page_url)"
                    class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all duration-300 disabled:opacity-50"
                >
                    Next
                </button>
            </div>
        </div>

        <!-- No Feedbacks Message -->
        <div v-else>
            <p class="text-gray-700">No feedbacks available.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js"; // Import axios for HTTP requests

const feedbacks = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    prev_page_url: null,
    next_page_url: null,
});

const fetchFeedbacks = async (url = '/admin/feedback') => {
    try {
        const response = await apiClient.get(url); // Use axios to fetch data from the backend API
        feedbacks.value = response.data.data; // Set the feedback data from API response
    } catch (error) {
        console.error('Error fetching feedbacks:', error);
    }
};





onMounted(() => {
    fetchFeedbacks(); // Fetch feedbacks when the component is mounted
});
</script>

<style scoped>
/* Add any additional styles here */
</style>
