<template>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Analytics Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white shadow-md rounded-lg p-4 text-center">
                <p class="text-xl font-semibold">Active Users</p>
                <p class="text-2xl font-bold">{{ activeUsers }}</p>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 text-center">
                <p class="text-xl font-semibold">New Subscriptions</p>
                <p class="text-2xl font-bold">{{ newSubscriptions }}</p>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 text-center">
                <p class="text-xl font-semibold">Churn Rate</p>
                <p class="text-2xl font-bold">{{ churnRate }}%</p>
            </div>
            <div class="bg-white shadow-md rounded-lg p-4 text-center">
                <p class="text-xl font-semibold">Retention Rate</p>
                <p class="text-2xl font-bold">{{ retentionRate }}%</p>
            </div>
        </div>


        <FinancialMetrics />


        <div v-if="errorMessage" class="mb-4 text-red-500">
            {{ errorMessage }}
        </div>




    </div>
</template>



<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js";
import FinancialMetrics from "./FinancialMetrics.vue";

const totalRevenue = ref(0);
const revenueByCourse = ref([]);
const arpu = ref(0);
const ltv = ref(0);
const activeUsers = ref(0);
const newSubscriptions = ref(0);
const churnRate = ref(0);
const retentionRate = ref(0);
const errorMessage = ref('');
const popularCourses = ref([]);
const completionRates = ref([]);

const fetchAnalyticsData = async () => {
    const params = {
        start_date: '2024-01-01',
        end_date: '2024-08-18',
        initial_period_start: '2024-01-01',
        initial_period_end: '2024-03-01',
        retention_period_start: '2024-03-01',
        retention_period_end: '2024-08-18',
    };

    try {
        const response = await apiClient.get('/analytics/dashboard-metrics', { params });
        const data = response.data;

        totalRevenue.value = data.financial_metrics.totalRevenue;
        revenueByCourse.value = data.financial_metrics.revenueByCourse;
        arpu.value = data.financial_metrics.arpu;
        ltv.value = data.financial_metrics.ltv;

        activeUsers.value = data.active_users;
        newSubscriptions.value = data.new_subscriptions;
        churnRate.value = data.churn_rate;
        retentionRate.value = data.retention_rate;


    } catch (error) {
        errorMessage.value = 'Error fetching analytics data.';
        console.error(error);
    }
};

onMounted(() => {
    fetchAnalyticsData();
});
</script>


