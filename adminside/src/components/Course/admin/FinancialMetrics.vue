<template>
    <div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white shadow-md rounded-lg p-4 text-center">
                <p class="text-xl font-semibold">Total Revenue</p>
                <p class="text-2xl font-bold">{{ totalRevenue }} $</p>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 text-center">
                <p class="text-xl font-semibold">ARPU</p>
                <p class="text-2xl font-bold">{{ arpu }}</p>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Revenue by Course</h2>
            <table class="min-w-full bg-white">
                <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Course</th>
                    <th class="py-2 px-4 border-b text-left">Description</th>
                    <th class="py-2 px-4 border-b text-left">Revenue</th>
                    <th class="py-2 px-4 border-b text-left">Thumbnail</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="course in revenueByCourse" :key="course.id">
                    <td class="py-2 px-4 border-b">{{ course.title }}</td>
                    <td class="py-2 px-4 border-b">{{ course.description }}</td>
                    <td class="py-2 px-4 border-b">{{ course.revenue }}</td>
                    <td class="py-2 px-4 border-b">
                        <img :src="imageUrl(course.thumbnail)" alt="Course Thumbnail" class="w-20 h-20 object-cover" />
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>





<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js";


const totalRevenue = ref(0);
const revenueByCourse = ref([]);
const arpu = ref(0);
const ltv = ref(0);

const activeUsers = ref(0);
const retentionRate = ref(0);
const errorMessage = ref('');



const fetchAnalyticsData = async () => {


    try {
        const financialMetricsResponse = await apiClient.get('/analytics/financial-metrics');
        totalRevenue.value = financialMetricsResponse.data.totalRevenue;
        console.log(financialMetricsResponse.data.totalRevenue)
        revenueByCourse.value = financialMetricsResponse.data.revenueByCourse;
        console.log(revenueByCourse)
        arpu.value = financialMetricsResponse.data.arpu;
        ltv.value = financialMetricsResponse.data.ltv;
    } catch (error) {
        errorMessage.value = 'Error fetching financial metrics.';
        console.error(error);
    }


};

onMounted(() => {
    fetchAnalyticsData();
});
</script>

