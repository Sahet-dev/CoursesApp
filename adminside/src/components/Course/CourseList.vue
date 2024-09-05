<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Course Catalog</h1>
        <Notifications />
        <div v-if="courses.length">
            <ul class="space-y-4">
                <li v-for="course in courses" :key="course.id" class="p-4 border rounded shadow-sm">
                    <router-link :to="`/courses/${course.id}`" class="flex items-start space-x-4">
                        <img :src="`/storage/${course.thumbnail}`" alt="Thumbnail" class="w-32 h-32 object-cover rounded mb-2"/>
                        <div>
                            <h2 class="text-xl font-semibold">{{ course.title }}</h2>
                            <p>{{ course.description }}</p>
                            <p class="font-bold">${{ course.price }}</p>
                        </div>
                    </router-link>
                </li>
            </ul>
        </div>
        <div v-else>
            <p>No courses available.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useNotifications } from './useNotifications.js';

const { addNotification } = useNotifications();

const courses = ref([]);

const fetchCourses = async () => {
    try {
        const response = await axios.get('/api/courses');
        courses.value = response.data.courses;
        addNotification('Courses loaded successfully', 'success');
    } catch (error) {
        console.error('Failed to fetch courses:', error);
        addNotification('Failed to load courses', 'error');
    }
};

onMounted(fetchCourses);
</script>

<style scoped>
/* Tailwind classes are used directly in the template, no additional styles needed */
</style>
