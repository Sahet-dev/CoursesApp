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

        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Popular Courses</h2>
            <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Course Title</th>
                    <th class="px-4 py-2 text-right">Enrollments</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="course in popularCourses" :key="course.id" class="border-t">
                    <td class="px-4 py-2">{{ course.title }}</td>
                    <td class="px-4 py-2 text-right">{{ course.users_count }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Course Completion Rates</h2>
            <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Course Title</th>
                    <th class="px-4 py-2 text-right">Completion Rate</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="rate in completionRates" :key="rate.course" class="border-t">
                    <td class="px-4 py-2">{{ rate.course }}</td>
                    <td class="px-4 py-2 text-right">{{ rate.completion_rate.toFixed(2) }}%</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Engagement Metrics</h2>
            <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Course Title</th>
                    <th class="px-4 py-2 text-right">Avg. Time Spent (minutes)</th>
                    <th class="px-4 py-2 text-right">Avg. Interactions</th>
                    <th class="px-4 py-2 text-right">Avg. Assignments Completed</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="engagement in engagementMetrics" :key="engagement.course" class="border-t">
                    <td class="px-4 py-2">{{ engagement.course }}</td>
                    <td class="px-4 py-2 text-right">{{ engagement.avg_time_spent }}</td>
                    <td class="px-4 py-2 text-right">{{ engagement.avg_interactions }}</td>
                    <td class="px-4 py-2 text-right">{{ engagement.avg_assignments_completed }}</td>
                </tr>
                </tbody>
            </table>
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
const engagementMetrics = ref([]);


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
        const financialMetricsResponse = await apiClient.get('/analytics/financial-metrics');
        totalRevenue.value = financialMetricsResponse.data.totalRevenue;
        console.log(financialMetricsResponse.data.totalRevenue)
        revenueByCourse.value = financialMetricsResponse.data.revenueByCourse;
        arpu.value = financialMetricsResponse.data.arpu;
        ltv.value = financialMetricsResponse.data.ltv;
    } catch (error) {
        errorMessage.value = 'Error fetching financial metrics.';
        console.error(error);
    }

    try {
        const activeUsersResponse = await apiClient.get('/analytics/active-users', { params });
        activeUsers.value = activeUsersResponse.data.active_users;
    } catch (error) {
        errorMessage.value = 'Error fetching active users.';
    }

    try {
        const newSubscriptionsResponse = await apiClient.get('/analytics/new-subscriptions', { params });
        newSubscriptions.value = newSubscriptionsResponse.data.new_subscriptions;
    } catch (error) {
        errorMessage.value = 'Error fetching new subscriptions.';
    }

    try {
        const churnRateResponse = await apiClient.get('/analytics/churn-rate', { params });
        churnRate.value = churnRateResponse.data.churn_rate;
    } catch (error) {
        errorMessage.value = 'Error fetching churn rate.';
    }

    try {
        const retentionRateResponse = await apiClient.get('/analytics/retention-rate', { params });
        retentionRate.value = retentionRateResponse.data.retention_rate;
    } catch (error) {
        errorMessage.value = 'Error fetching retention rate.';
    }
    try {
        const response = await apiClient.get('/analytics/course-engagement');
        const data = response.data;

        // Populate data into Vue component state
        popularCourses.value = data.popular_courses;
        completionRates.value = data.completion_rates;
        engagementMetrics.value = data.engagement_metrics;
    } catch (error) {
        errorMessage.value = 'Error fetching course engagement data.';
        console.error(error);
    }
};

onMounted(() => {
    fetchAnalyticsData();
});
</script>

