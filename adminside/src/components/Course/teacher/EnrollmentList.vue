<template>
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Course Purchases</h1>
        <ul class="list-disc pl-5 mb-8">
            <li v-for="user in purchasedUsers" :key="user.id" class="mb-2">
                <div class="flex items-center justify-between p-2 border border-gray-200 rounded-lg">
                    <div class="text-lg font-medium">{{ user.name }}</div>
                    <div class="text-sm text-gray-600">{{ user.email }}</div>
                </div>
            </li>
        </ul>

        <h1 class="text-2xl font-bold mb-4">Subscriptions</h1>
        <ul class="list-disc pl-5">
            <li v-for="subscription in subscriptions" :key="subscription.id" class="mb-2">
                <div class="flex items-center justify-between p-2 border border-gray-200 rounded-lg">
                    <div class="text-lg font-medium">{{ subscription.user.name }}</div>
                    <div class="text-sm text-gray-600">
                        {{ subscription.plan }} ({{ formatDate(subscription.starts_at) }} - {{ formatDate(subscription.ends_at) }})
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js";

const purchasedUsers = ref([]);
const subscriptions = ref([]);

const fetchPurchases = async (courseId) => {
    try {
        const response = await apiClient.get(`/course-enrollments/${courseId}`);
        purchasedUsers.value = response.data;
    } catch (error) {
        console.error('Error fetching purchases:', error);
    }
};

const fetchSubscriptions = async () => {
    try {
        const response = await apiClient.get('/subscriptions');
        subscriptions.value = response.data;
    } catch (error) {
        console.error('Error fetching subscriptions:', error);
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US');
};

// Replace with the actual course ID or parameter
const courseId = 1;

onMounted(() => {
    fetchPurchases(courseId);
    fetchSubscriptions();
});
</script>
