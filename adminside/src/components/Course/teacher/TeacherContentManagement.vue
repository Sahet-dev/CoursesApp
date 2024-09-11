<template>
    <div>
        <div class="p-4">
            <h1 class="text-2xl font-bold mb-4">My Courses</h1>

            <!-- Course List Table -->
            <div v-if="courses.length" class="w-full border-collapse border border-gray-200">
                <table class="w-full">
                    <thead>
                    <tr>
                        <th class="border border-gray-300 p-2 text-left">Title</th>
                        <th class="border border-gray-300 p-2 text-left">Price</th>
                        <th class="border border-gray-300 p-2 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="course in courses" :key="course.id">
                        <td class="border border-gray-300 p-2">{{ course.title }}</td>
                        <td class="border border-gray-300 p-2">${{ course.price }}</td>
                        <td class="border border-gray-300 p-2 flex space-x-2">
                            <button
                                @click="previewCourse(course.id)"
                                class="bg-teal-500 text-white py-1 px-3 rounded-md hover:bg-teal-600 transition-all duration-300"
                            >
                                Preview
                            </button>
                            <button
                                @click="editCourse(course.id)"
                                class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 transition-all duration-300"
                            >
                                Edit
                            </button>
                            <button
                                @click="deleteCourse(course.id)"
                                class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 transition-all duration-300"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <p>No courses found.</p>
            </div>

            <!-- Lessons List -->
            <div v-if="selectedCourse" class="mt-6">
                <h2 class="text-xl font-semibold mb-2">Lessons for {{ selectedCourse.title }}</h2>
                <ul class="list-disc pl-5">
                    <li v-for="lesson in selectedCourse.lessons" :key="lesson.id" class="mb-2">
                        <h3 class="font-medium">{{ lesson.title }}</h3>
                        <p v-if="lesson.video_url" class="text-gray-700">Video: <a :href="lesson.video_url" class="text-blue-500 hover:underline">Watch</a></p>
                        <div v-if="lesson.markdown_text" class="mt-2">
                            <p class="text-gray-700" v-html="lesson.markdown_text"></p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js";
import {useRouter} from "vue-router";


const router = useRouter();
const courses = ref([]);
const selectedCourse = ref(null);

const fetchCourses = async () => {
    try {
        const response = await apiClient.get('/teacher-courses');
        // Assuming the response includes lessons for each course
        courses.value = response.data;
    } catch (error) {
        console.error('Failed to fetch courses:', error);
    }
};

const selectCourse = (course) => {
    selectedCourse.value = course;
};

const previewCourse = async (courseId) => {
    try {
        const response = await apiClient.get(`/courses/${courseId}/preview`);
        console.log('Course details:', response.data);
        // You can show course details in a modal or redirect as needed
    } catch (error) {
        console.error('Failed to preview course:', error);
    }
};

const editCourse = (courseId) => {
    router.push({ name: 'CourseUpdate', params: { id: courseId } });
};

const deleteCourse = async (id) => {
    const confirmed = confirm("Are you sure you want to delete this course? This action cannot be undone.");
    if (confirmed) {
        try {
            await apiClient.delete(`/courses/${id}`);
            await fetchCourses();
        } catch (error) {
            console.error('Failed to delete course:', error);
        }
    }
};





// Fetch courses on component mount
onMounted(fetchCourses);
</script>

<style scoped>
/* Add any additional styles here */
</style>
