<template>
    <div class="p-4 space-y-4">
        <h1 class="text-2xl font-bold mb-4">Churn Rate</h1>

        <form @submit.prevent="fetchData" class="mb-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input
                        type="date"
                        id="startDate"
                        v-model="startDate"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>
                <div class="flex-1">
                    <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input
                        type="date"
                        id="endDate"
                        v-model="endDate"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>
                <button
                    type="submit"
                    class="flex-shrink-0 bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600"
                >
                    Fetch Data
                </button>
            </div>
        </form>

        <section v-if="churnRate !== null" class="bg-white p-4 rounded shadow-md">
            <h2 class="text-xl font-semibold mb-2">Churn Rate</h2>
            <p class="text-lg font-medium">Churn Rate: {{ churnRate.toFixed(2) }}%</p>
        </section>

        <section v-else class="bg-white p-4 rounded shadow-md">
            <p class="text-gray-600">No churn rate data available. Please select a date range and fetch the data.</p>
        </section>
    </div>
</template>

<script setup>
import { ref } from 'vue';



import apiClient from "../../../../api/axios.js";

const startDate = ref('');
const endDate = ref('');
const churnRate = ref(null);

const fetchData = async () => {
    try {
        const response = await apiClient.get('/analytics/churn-rate', {
            params: { start_date: startDate.value, end_date: endDate.value }
        });
        churnRate.value = response.data.churn_rate;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
</script>

<style scoped>
/* Additional scoped styles can be added here if needed */
</style>
