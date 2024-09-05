<template>

    <div>
        <DashboardHeader />

        <transition
            name="fade"

        >
            <div
                v-if="notification.visible"
                class="fixed top-4 right-4 bg-green-500 text-white p-3 rounded"
            >
                {{ notification.message }}
            </div>
        </transition>
        <div class="p-6 bg-white rounded-md shadow-md">
            <img :src="thumbnailUrl" :alt="course.title" class="w-full h-48 object-cover rounded mb-4" />

            <div v-if="course.title">





                <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Course Data: {{ course.title }}</h2>

                <form @submit.prevent="updateCourseData" enctype="multipart/form-data">
                    <!-- Course Form -->
                    <div class="mb-4">
                        <label for="title" class="block text-lg font-medium text-gray-700">Course Title</label>
                        <input v-model="course.title" id="title" type="text" class="w-full px-3 py-2 border rounded-md" required />
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-lg font-medium text-gray-700">Course Description</label>
                        <textarea v-model="course.description" id="description" class="w-full px-3 py-2 border rounded-md" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="thumbnail" class="block text-lg font-medium text-gray-700">Course Thumbnail</label>
                        <input @change="handleFileUpload" id="thumbnail" type="file" class="w-full px-3 py-2 border rounded-md" />
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-lg font-medium text-gray-700">Price</label>
                        <input v-model="course.price" id="price" type="number" step="0.01" class="w-full px-3 py-2 border rounded-md" required />
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600">Update Course</button>
                    <div v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</div>
                </form>
            </div>

            <div v-else >
                <Loader  />
            </div>


        </div>
    </div>

</template>

<script setup>
import { ref, onMounted, computed} from 'vue';
import { useRoute, useRouter } from 'vue-router';
import apiClient from "../../api/axios.js";
import DashboardHeader from "../DashboardHeader.vue";
import Loader from "./Loader.vue";
import ButtonSpinner from "../ButtonSpinner.vue";

const route = useRoute();
const router = useRouter();
const course = ref({
    title: '',
    description: '',
    thumbnail: '',
    price: 0
});



const errorMessage = ref('');
const baseUrl = import.meta.env.VITE_APP_URL || 'http://localhost:8000';
const thumbnailUrl = computed(() => `${baseUrl}/storage/${course.value.thumbnail}`);
const notification = ref({
    message: '',
    visible: false
});



const fetchCourse = async () => {
    const courseId = route.params.id;
    try {
        const response = await apiClient.get(`/courses/${courseId}`);
        course.value = response.data.course;

    } catch (error) {
        errorMessage.value = 'Failed to load course data.';
    }
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        course.value.thumbnail = file;
    }
};
function showNotification(message) {
    notification.value.message = message;
    notification.value.visible = true;

    setTimeout(() => {
        notification.value.visible = false;
    }, 3000);
}
const updateCourseData = async () => {
    try {
        const formData = new FormData();
        formData.append('title', course.value.title);
        formData.append('description', course.value.description);
        formData.append('price', course.value.price);

        // Only append the thumbnail if it's a file
        if (course.value.thumbnail instanceof File) {
            formData.append('thumbnail', course.value.thumbnail);
        }





        formData.append('_method', 'PUT');
        const response = await apiClient.post(`/courses/${course.value.id}`, formData);
        showNotification('Course data updated successfully!');

    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errorMessage.value = Object.values(error.response.data.errors).flat().join(', ');
        } else {
            errorMessage.value = 'Failed to update course data.';
        }
    }
};


onMounted(() => {
    fetchCourse();
});
</script>
<style>

.fade-enter-from{
    opacity: 0;
    transform: translateY(-60px);
}
.fade-enter-to{
    opacity: 1;
    transform: translateY(0);
}

.fade-enter-active {
    transition: all 0.3s ease;
}
.fade-leave-from{
    opacity: 1;
    transform: translateY(0);
}
.fade-leave-to{
    opacity: 0;
    transform: translateY(-60px);
}
.fade-leave-active  {
    transition: all 0.3s ease;
}

</style>
