<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <h1 class="text-3xl font-bold mb-4">Your Courses</h1>

        <div v-if="courses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="course in courses"
                :key="course.id"
                class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
            >
                <h2 class="text-xl font-semibold mb-2">{{ course.title }}</h2>
                <p class="text-gray-700 mb-2">{{ course.description }}</p>
                <p class="text-sm text-gray-500 mb-4">Created on: {{ formatDate(course.createdAt) }}</p>
                <router-link
                    :to="`/teacher-dashboard/edit-course/${course.id}`"
                    class="text-blue-500 hover:underline"
                >
                    Edit Course
                </router-link>
            </div>
        </div>

        <div v-else class="text-center">
            <p class="text-gray-700">You have no courses yet.</p>
            <router-link to="/teacher-dashboard/create-new-course" class="text-blue-500 hover:underline">
                Create your first course
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js";

const courses = ref([]);

const fetchCourses = async () => {
    try {
        const response = await apiClient.get('/teacher/courses'); // Ensure this matches your backend route
        courses.value = response.data;
    } catch (error) {
        console.error('Failed to fetch courses:', error);
    }
};

const formatDate = (date) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString(undefined, options);
};

onMounted(fetchCourses);
</script>

<style scoped>
</style>
