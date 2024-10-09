<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <!-- Check if there are completed courses -->
        <div v-if="completedCourses.length" class="space-y-4">
            <ul class="space-y-2">
                <li v-for="course in completedCourses" :key="course.id" class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition duration-200">
                    <!-- Course Details -->
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-lg">{{ course.title }}</p>
                        <p class="text-gray-600 text-sm">Completed At: {{ course.completed_at }}</p>
                        <p class="text-gray-600 text-sm">Rating:
                            <span class="font-medium text-yellow-500">
                                {{ course.rating }}
                                <svg class="inline-block w-4 h-4 text-yellow-500 ml-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M12 17.27L18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27Z"></path>
                                </svg>
                              </span>
                        </p>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Display message when no completed courses are found -->
        <div v-else class="text-center text-gray-500">
            No completed courses found
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import apiClient from "../../axios/index.js";

const completedCourses = ref([]);
const fetchCompletedCourses = async () => {
    try {
        const response = await apiClient.get('/user/completed-courses');
        completedCourses.value = response.data;
    } catch (error) {
        console.error('Error fetching completed courses:', error);
    }
};

onMounted(fetchCompletedCourses);
</script>

<style scoped>
</style>
