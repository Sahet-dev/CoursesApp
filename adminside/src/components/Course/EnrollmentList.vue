<template>
    <div>
        <h1>Course Enrollments</h1>
        <ul>
            <li v-for="user in enrolledUsers" :key="user.id">
                {{ user.name }} ({{ user.email }})
            </li>
        </ul>

        <h1>Subscriptions</h1>
        <ul>
            <li v-for="subscription in subscriptions" :key="subscription.id">
                {{ subscription.user.name }} - {{ subscription.plan }} ({{ subscription.starts_at }} - {{ subscription.ends_at }})
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../api/axios.js';



const enrolledUsers = ref([]);
const subscriptions = ref([]);

const fetchEnrollments = async (courseId) => {
    try {
        const response = await apiClient.get(`/course-enrollments/${courseId}`);
        enrolledUsers.value = response.data;
    } catch (error) {
        console.error('Error fetching enrollments:', error);
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

// Replace with the actual course ID or parameter
const courseId = 1;

onMounted(() => {
    fetchEnrollments(courseId);
    fetchSubscriptions();
});
</script>
