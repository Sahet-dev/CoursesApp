<template>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">Course Management</h1>
        <div class="mb-4">
            <router-link to="/admin-dashboard/new-course">
                <button class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-all duration-300">
                    Create Course
                </button>
            </router-link>
        </div>

        <Loader v-if="loading" />

        <div v-else-if="!loading && courses.data && courses.data.length" class="w-full border-collapse border border-gray-200">
            <table class="w-full table-fixed">
                <thead>
                <tr>
                    <th class="border border-gray-300 p-2 text-left w-1/3">Title</th>
                    <th class="border border-gray-300 p-2 text-left w-1/6">Price</th>
                    <th class="border border-gray-300 p-2 text-left w-1/2">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="course in courses.data" :key="course.id">
                    <!-- Title Column with Truncate -->
                    <td class="border border-gray-300 p-2 truncate">{{ course.title }}</td>

                    <!-- Price Column with Right Alignment -->
                    <td class="border border-gray-300 p-2 text-right">${{ course.price }}</td>

                    <!-- Actions Column with Flexbox for Buttons -->
                    <td class="border border-gray-300 p-2">
                        <div class="flex space-x-2">
                            <button @click="previewCourse(course.id)" class="bg-teal-500 text-white py-1 px-3 rounded-md hover:bg-teal-600 transition-all duration-300">
                                Preview
                            </button>
                            <button @click="editCourse(course.id)" class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 transition-all duration-300">
                                Edit
                            </button>
                            <button @click="deleteCourse(course.id)" class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 transition-all duration-300">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- Pagination Buttons -->
            <div class="flex justify-between items-center mt-8">
                <!-- Pagination Information -->
                <div>
                    Page {{ courses.current_page }} of {{ courses.last_page }}
                </div>

                <!-- Pagination Buttons -->
                <div class="flex space-x-2">
                    <button
                        v-if="courses.prev_page_url"
                        @click="fetchCourses(courses.prev_page_url)"
                        class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all duration-300"
                    >
                        Previous
                    </button>

                    <!-- Page Numbers -->
                    <button
                        v-for="page in courses.last_page"
                        :key="page"
                        @click="fetchCourses(getPageUrl(page))"
                        :class="[
                'py-2 px-4 rounded-md transition-all duration-300',
                courses.current_page === page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-black hover:bg-gray-300'
            ]"
                    >
                        {{ page }}
                    </button>

                    <button
                        v-if="courses.next_page_url"
                        @click="fetchCourses(courses.next_page_url)"
                        class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all duration-300"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
        <div v-else-if="!loading && (!courses.data || !courses.data.length)" class="flex flex-col items-center justify-center pt-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">No Courses Yet</h2>
            <p class="text-gray-600 mb-4">Start by creating your first course.</p>
            <button @click="createFirstCourse" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                Create First Course
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../../api/axios.js';
import { useRouter } from 'vue-router';
import Loader from "../Loader.vue";

const loading = ref(true);
const courses = ref({
    data: [],
    prev_page_url: null,
    next_page_url: null,
});
const router = useRouter();

const fetchCourses = async (url = '/teacher/courses') => {
    try {
        const response = await apiClient.get(url);
        courses.value = response.data;
        console.log(courses.value);
    } catch (error) {
        console.error('Failed to fetch courses:', error);
    } finally {
        loading.value = false;
    }
};

const previewCourse = (courseId) => {
    // Preview course logic
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

const createFirstCourse = () => {
    router.push({ name: 'NewCourse' });
};

onMounted(fetchCourses);
</script>

<style scoped>
</style>
