<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-bold mb-4">Teacher Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Total Revenue -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Total Revenue</h2>
                <p class="text-gray-700">${{ totalRevenue.toFixed(2) }}</p>
            </div>

            <!-- Teacher Earnings -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Teacher Earnings</h2>
                <p class="text-gray-700">${{ teacherEarnings.toFixed(2) }}</p>
            </div>

            <!-- New Subscriptions -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">New Subscriptions</h2>
                <p class="text-gray-700">{{ newSubscriptions }}</p>
            </div>

            <!-- Churn Rate -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Churn Rate</h2>
                <p class="text-gray-700">{{ churnRate.toFixed(2) }}%</p>
            </div>
        </div>

        <!-- Recent Transactions -->
        <h2 class="text-2xl font-semibold mt-6 mb-4">Recent Transactions</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
            <tr>
                <th class="px-4 py-2">Transaction ID</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Payment Method</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="purchase in recentPurchases" :key="purchase.transaction_id">
                <td class="px-4 py-2">{{ purchase.transaction_id }}</td>
                <td class="px-4 py-2">${{ purchase.amount.toFixed(2) }}</td>
                <td class="px-4 py-2">{{ purchase.payment_method }}</td>
                <td class="px-4 py-2">{{ purchase.status.charAt(0).toUpperCase() + purchase.status.slice(1) }}</td>
                <td class="px-4 py-2">{{ formatDate(purchase.purchase_date) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js";

const totalRevenue = ref(0);
const teacherEarnings = ref(0);
const newSubscriptions = ref(0);
const churnRate = ref(0);
const recentPurchases = ref([]);

const fetchDashboardData = async () => {
    try {
        const response = await apiClient.get('/teacher/dashboard-data'); // Ensure this matches your backend route
        const data = response.data;
        totalRevenue.value = data.totalRevenue;
        teacherEarnings.value = data.teacherEarnings;
        newSubscriptions.value = data.newSubscriptions;
        churnRate.value = data.churnRate;
        recentPurchases.value = data.recentPurchases;
    } catch (error) {
        console.error('Failed to fetch dashboard data:', error);
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

onMounted(fetchDashboardData);
</script>

<style scoped>
</style>
