<template>
    <div class="p-8 space-y-8">
        <h1 class="text-3xl font-bold text-gray-800">Course Analytics</h1>

        <!-- Popular Courses Section -->
        <section class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Popular Courses</h2>
            <ul class="space-y-3">
                <li v-for="course in popularCourses" :key="course.id" class="flex items-center justify-between p-4 bg-gray-50 rounded-md border">
                    <div class="flex items-center space-x-4">
                        <img :src="course.thumbnail" alt="Course Thumbnail" class="w-16 h-16 object-cover rounded-md shadow-md">
                        <span class="text-lg font-medium text-gray-800">{{ course.title }}</span>
                    </div>
                    <span class="text-sm text-gray-600">Enrollments: <span class="font-medium">{{ course.users_count }}</span></span>
                </li>
            </ul>
        </section>

        <!-- Completion Rates Section -->
        <section class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Course Completion Rates</h2>
            <ul class="space-y-3">
                <li v-for="rate in completionRates" :key="rate.course" class="flex justify-between p-4 bg-gray-50 rounded-md border">
                    <span class="text-lg font-medium text-gray-800">{{ rate.course }}</span>
                    <span class="text-sm text-gray-600">Completion Rate: <span class="font-medium">{{ rate.completion_rate.toFixed(2) }}%</span></span>
                </li>
            </ul>
        </section>

        <!-- Engagement Metrics Section -->
        <section class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Engagement Metrics</h2>
            <ul class="space-y-3">
                <li v-for="metric in engagementMetrics" :key="metric.course" class="p-4 bg-gray-50 rounded-md border">
                    <span class="text-lg font-medium text-gray-800">{{ metric.course }}</span>
                    <div class="grid grid-cols-3 gap-4 mt-2 text-sm text-gray-600">
                        <div>Avg Time Spent: <span class="font-medium">{{ metric.avg_time_spent.toFixed(2) }} minutes</span></div>
                        <div>Avg Interactions: <span class="font-medium">{{ metric.avg_interactions.toFixed(2) }}</span></div>
                        <div>Avg Assignments Completed: <span class="font-medium">{{ metric.avg_assignments_completed.toFixed(2) }}</span></div>
                    </div>
                </li>
            </ul>
        </section>
    </div>

    <div class="container mx-auto p-6">
        <!-- Popular Courses Section -->
        <div>
            <h2 class="text-2xl font-semibold mb-4">Popular Courses</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="course in popularCourses" :key="course.id" class="bg-white rounded-lg shadow p-4">
                    <img :src="`/storage/${course.thumbnail}`" alt="Course Thumbnail" class="w-full h-32 object-cover rounded-md mb-4">
                    <h3 class="text-lg font-semibold mb-2">{{ course.title }}</h3>
                    <p class="text-gray-600">{{ course.description }}</p>
                    <p class="mt-2 text-sm font-semibold">Price: <span v-if="course.price">{{ course.price }}</span><span v-else>Free</span></p>
                    <p class="text-sm font-semibold">Users Enrolled: {{ course.users_count }}</p>
                </div>
            </div>
        </div>

        <!-- Completion Rates Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold mb-4">Completion Rates</h2>
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm">
                    <th class="py-3 px-4">Course</th>
                    <th class="py-3 px-4">Completion Rate</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="rate in completionRates" :key="rate.course" class="border-t">
                    <td class="py-3 px-4">{{ rate.course }}</td>
                    <td class="py-3 px-4">{{ rate.completion_rate }}%</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Engagement Metrics Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold mb-4">Engagement Metrics</h2>
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm">
                    <th class="py-3 px-4">Course</th>
                    <th class="py-3 px-4">Avg. Time Spent (mins)</th>
                    <th class="py-3 px-4">Avg. Interactions</th>
                    <th class="py-3 px-4">Avg. Assignments Completed</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="metric in engagementMetrics" :key="metric.course" class="border-t">
                    <td class="py-3 px-4">{{ metric.course }}</td>
                    <td class="py-3 px-4">{{ metric.avg_time_spent }}</td>
                    <td class="py-3 px-4">{{ metric.avg_interactions }}</td>
                    <td class="py-3 px-4">{{ metric.avg_assignments_completed }}</td>
                </tr>
                </tbody>
            </table>
        </div>
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
        engagementMetrics.value = response.data.engagement_metrics.map(metric => ({
            ...metric,
            avg_time_spent: Number(metric.avg_time_spent),
            avg_assignments_completed: Number(metric.avg_assignments_completed)
        }));
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
.container {
    max-width: 1200px;
}

h2 {
    color: #1f2937;
}
</style>

