<template>
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">Course Management</h1>
        <div class="mb-4">
            <button  class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-all duration-300">
                <router-link
                    to="/admin-dashboard/new-course"
                    >
                    Create Course
                </router-link>
            </button>
        </div>
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
                        <button @click="previewCourse(course.id)" class="bg-teal-500 text-white py-1 px-3 rounded-md hover:bg-teal-600 transition-all duration-300">
                            Preview
                        </button>
                        <button @click="editCourse(course.id)" class="bg-yellow-500 text-white py-1 px-3 rounded-md hover:bg-yellow-600 transition-all duration-300">
                            Edit
                        </button>
                        <button @click="deleteCourse(course.id)" class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 transition-all duration-300">
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <Loader />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../../api/axios.js";
import { useRouter } from 'vue-router';
import Loader from "../Loader.vue";

const courses = ref([]);
const router = useRouter();
const fetchCourses = async () => {
    try {
        const response = await apiClient.get('/teacher/courses');
        courses.value = response.data.data;
        console.log(courses.value)
    } catch (error) {
        console.error('Failed to fetch courses:', error);
    }
};






const previewCourse = (courseId) => {
    //
}


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

fetchCourses();
</script>

<style scoped>
</style>
