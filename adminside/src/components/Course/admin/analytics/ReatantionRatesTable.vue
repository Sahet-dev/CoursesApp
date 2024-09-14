<template>
    <div class="p-4 space-y-4">
        <h1 class="text-2xl font-bold mb-4">Retention Rate</h1>

        <form @submit.prevent="fetchData" class="mb-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Initial Period -->
                <div>
                    <label for="initialPeriodStart" class="block text-sm font-medium text-gray-700">Initial Period Start</label>
                    <input
                        type="date"
                        id="initialPeriodStart"
                        v-model="initialPeriodStart"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>
                <div>
                    <label for="initialPeriodEnd" class="block text-sm font-medium text-gray-700">Initial Period End</label>
                    <input
                        type="date"
                        id="initialPeriodEnd"
                        v-model="initialPeriodEnd"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>

                <!-- Retention Period -->
                <div>
                    <label for="retentionPeriodStart" class="block text-sm font-medium text-gray-700">Retention Period Start</label>
                    <input
                        type="date"
                        id="retentionPeriodStart"
                        v-model="retentionPeriodStart"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>
                <div>
                    <label for="retentionPeriodEnd" class="block text-sm font-medium text-gray-700">Retention Period End</label>
                    <input
                        type="date"
                        id="retentionPeriodEnd"
                        v-model="retentionPeriodEnd"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2">
                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600"
                    >
                        Fetch Data
                    </button>
                </div>
            </div>
        </form>

        <section v-if="retentionRate !== null" class="bg-white p-4 rounded shadow-md">
            <h2 class="text-xl font-semibold mb-2">Retention Rate</h2>
            <p class="text-lg font-medium">Retention Rate: {{ retentionRate.toFixed(2) }}%</p>
        </section>

        <section v-else class="bg-white p-4 rounded shadow-md">
            <p class="text-gray-600">No retention rate data available. Please select the date ranges and fetch the data.</p>
        </section>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from "../../../../api/axios.js"; // Adjust the path according to your project structure

const initialPeriodStart = ref('');
const initialPeriodEnd = ref('');
const retentionPeriodStart = ref('');
const retentionPeriodEnd = ref('');
const retentionRate = ref(null);

const fetchData = async () => {
    try {
        const response = await apiClient.get('/analytics/retention-rate', {
            params: {
                initial_period_start: initialPeriodStart.value,
                initial_period_end: initialPeriodEnd.value,
                retention_period_start: retentionPeriodStart.value,
                retention_period_end: retentionPeriodEnd.value,
            }
        });
        retentionRate.value = response.data.retention_rate;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};
</script>

<style scoped>
/* Additional scoped styles can be added here if needed */
</style>
