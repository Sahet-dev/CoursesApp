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
        // Make a single API call to fetch all analytics data
        const response = await apiClient.get('/analytics/dashboard-metrics', { params });
        const data = response.data;

        // Update all relevant state values
        totalRevenue.value = data.financial_metrics.totalRevenue;
        revenueByCourse.value = data.financial_metrics.revenueByCourse;
        arpu.value = data.financial_metrics.arpu;
        ltv.value = data.financial_metrics.ltv;

        activeUsers.value = data.active_users;
        newSubscriptions.value = data.new_subscriptions;
        churnRate.value = data.churn_rate;
        retentionRate.value = data.retention_rate;

        popularCourses.value = data.popular_courses;
        completionRates.value = data.completion_rates;
        engagementMetrics.value = data.engagement_metrics;

    } catch (error) {
        errorMessage.value = 'Error fetching analytics data.';
        console.error(error);
    }
};

onMounted(() => {
    fetchAnalyticsData();
});
</script>

