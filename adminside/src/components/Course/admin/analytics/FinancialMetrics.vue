<template>
    <div class="p-4 space-y-6">
        <h1 class="text-2xl font-bold mb-6">Financial Metrics</h1>

        <button
            @click="fetchData"
            class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600"
        >
            Fetch Financial Metrics
        </button>

        <section v-if="metrics" class="bg-white p-4 rounded shadow-md space-y-4">
            <div class="space-y-2">
                <h2 class="text-xl font-semibold">Total Revenue</h2>
                <p class="text-lg font-medium">${{ metrics.totalRevenue.toFixed(2) }}</p>
            </div>

            <div class="space-y-2">
                <h2 class="text-xl font-semibold">Revenue by Course</h2>
                <ul>
                    <li v-for="(revenue, course) in metrics.revenueByCourse" :key="course" class="border-b py-2">
                        <span class="font-medium">{{ course }}:</span> ${{ revenue.toFixed(2) }}
                    </li>
                </ul>
            </div>

            <div class="space-y-2">
                <h2 class="text-xl font-semibold">ARPU (Average Revenue Per User)</h2>
                <p class="text-lg font-medium">${{ metrics.arpu.toFixed(2) }}</p>
            </div>

            <div class="space-y-2">
                <h2 class="text-xl font-semibold">LTV (Lifetime Value)</h2>
                <p class="text-lg font-medium">${{ metrics.ltv.toFixed(2) }}</p>
            </div>
        </section>

        <section v-else class="bg-white p-4 rounded shadow-md">
            <p class="text-gray-600">No financial metrics data available. Click the button above to fetch data.</p>
        </section>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from "../../../../api/axios.js";

const metrics = ref(null);

const fetchData = async () => {
    try {
        const response = await apiClient.get('/analytics/financial-metrics');
        metrics.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
</script>

<style scoped>
/* Additional scoped styles can be added here if needed */
</style>
