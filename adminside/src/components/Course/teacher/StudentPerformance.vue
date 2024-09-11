<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Student Performance</h1>

        <div v-if="performance" class="space-y-6">

            <!-- Achievements Section -->
            <section class="bg-white shadow rounded-lg p-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Achievements</h2>
                <ul class="space-y-4">
                    <li v-for="achievement in performance.achievements" :key="achievement.id" class="p-4 border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-700">{{ achievement.course.title }}</h3>
                        <p class="text-sm text-gray-600">Rating: {{ achievement.rating }} stars</p>
                        <p class="mt-2 text-gray-500">{{ achievement.feedback_text }}</p>
                    </li>
                </ul>
            </section>

            <!-- Engagement Section -->
            <section class="bg-white shadow rounded-lg p-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Engagement</h2>
                <p class="text-gray-600">Total Time Spent: <span class="font-semibold">{{ performance.totalTimeSpent }} minutes</span></p>
                <p class="text-gray-600">Total Interactions: <span class="font-semibold">{{ performance.totalInteractions }}</span></p>
                <p class="text-gray-600">Assignments Completed: <span class="font-semibold">{{ performance.totalAssignmentsCompleted }}</span></p>
            </section>

            <!-- Comments Section -->
            <section class="bg-white shadow rounded-lg p-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Comments</h2>
                <ul class="space-y-4">
                    <li v-for="comment in performance.comments" :key="comment.id" class="p-4 border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-700">Lesson: {{ comment.lesson.title }}</h3>
                        <p class="mt-2 text-gray-500">{{ comment.comment }}</p>
                    </li>
                </ul>
            </section>

            <!-- Questions Section -->
            <section class="bg-white shadow rounded-lg p-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Questions</h2>
                <ul class="space-y-4">
                    <li v-for="question in performance.questions" :key="question.id" class="p-4 border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-700">{{ question.question_text }}</h3>
                        <ul class="mt-2 space-y-2 pl-4">
                            <li v-for="response in question.responses" :key="response.id" class="text-gray-600">
                                - {{ response.response_text }}
                            </li>
                        </ul>
                    </li>
                </ul>
            </section>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js"; // Adjust the import based on your actual setup

const performance = ref(null);

const fetchPerformance = async () => {
    try {
        const response = await apiClient.get('/student-performance');
        performance.value = response.data;
    } catch (error) {
        console.error('Error fetching performance data:', error);
    }
};

onMounted(fetchPerformance);
</script>

<style scoped>
/* You can add additional scoped styles here if needed */
</style>
