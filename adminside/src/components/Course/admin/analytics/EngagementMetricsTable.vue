<template>
    <div class="p-4 space-y-4">
        <h1 class="text-2xl font-bold mb-4">Course Analytics</h1>

        <section class="bg-white p-4 rounded shadow-md">
            <h2 class="text-xl font-semibold mb-2">Popular Courses</h2>
            <ul>
                <li v-for="course in popularCourses" :key="course.id" class="border-b py-2">
                    <span class="font-medium">{{ course.title }}</span> - Enrollments: {{ course.users_count }}
                </li>
            </ul>
        </section>

        <section class="bg-white p-4 rounded shadow-md">
            <h2 class="text-xl font-semibold mb-2">Course Completion Rates</h2>
            <ul>
                <li v-for="rate in completionRates" :key="rate.course" class="border-b py-2">
                    <span class="font-medium">{{ rate.course }}</span> - Completion Rate: {{ rate.completion_rate.toFixed(2) }}%
                </li>
            </ul>
        </section>

        <section class="bg-white p-4 rounded shadow-md">
            <h2 class="text-xl font-semibold mb-2">Engagement Metrics</h2>
            <ul>
                <li v-for="metric in engagementMetrics" :key="metric.course" class="border-b py-2">
                    <span class="font-medium">{{ metric.course }}</span>
                    <div>Avg Time Spent: {{ metric.avg_time_spent.toFixed(2) }} minutes</div>
                    <div>Avg Interactions: {{ metric.avg_interactions.toFixed(2) }}</div>
                    <div>Avg Assignments Completed: {{ metric.avg_assignments_completed.toFixed(2) }}</div>
                </li>
            </ul>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../../api/axios.js";

const popularCourses = ref([]);
const completionRates = ref([]);
const engagementMetrics = ref([]);

const fetchData = async () => {
    try {
        const response = await apiClient.get('/analytics/course-engagement');
        popularCourses.value = response.data.popular_courses;
        completionRates.value = response.data.completion_rates;
        engagementMetrics.value = response.data.engagement_metrics;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

onMounted(() => {
    fetchData();
});
</script>


<style scoped>
/* Additional scoped styles can be added here if needed */
</style>
