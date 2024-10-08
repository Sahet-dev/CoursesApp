<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <div v-if="!isLoading && activities">
            <Line :data="data" :options="options" />
        </div>
        <div v-else-if="isLoading">
            <Loader  />
        </div>
        <div v-else-if="error">
            <p>{{ error }}</p>
        </div>
    </div>

</template>

<script setup>
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'
import * as chartConfig from './chartConfig.js'
import { onMounted, ref } from "vue";
import apiClient from "../../axios";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

// Destructure data and options from chartConfig
const { data, options } = chartConfig
const activities = ref([]);
const isLoading = ref(true);
const error = ref(null);

// Update chart data with activities data
const updateChartData = (activitiesData) => {
    // Convert the activities object into an array of values sorted by months
    const sortedData = [
        activitiesData['2024-01'] || 0,
        activitiesData['2024-02'] || 0,
        activitiesData['2024-03'] || 0,
        activitiesData['2024-04'] || 0,
        activitiesData['2024-05'] || 0,
        activitiesData['2024-06'] || 0,
        activitiesData['2024-07'] || 0,
        activitiesData['2024-08'] || 0,
        activitiesData['2024-09'] || 0,
        activitiesData['2024-10'] || 0,
        activitiesData['2024-11'] || 0,
        activitiesData['2024-12'] || 0
    ];

    // Update the chart dataset
    data.datasets[0].data = sortedData;
};

const fetchActivities = async () => {
    try {
        const response = await apiClient.get('/user/activities');
        activities.value = response.data;
        updateChartData(activities.value);  // Update chart with fetched data
    } catch (err) {
        console.error('Error fetching activities:', err);
        if (err.response && err.response.status === 401) {
            error.value = 'Unauthorized. Please log in.';
        } else {
            error.value = 'Failed to load activities.';
        }
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchActivities();
});
</script>
