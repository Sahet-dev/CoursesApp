<template>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Create a New Lesson</h2>
        <form @submit.prevent="submitLessonForm">
            <div class="mb-4">
                <label for="lessonTitle" class="block text-sm font-medium text-gray-700">Lesson Title</label>
                <input type="text" id="lessonTitle" v-model="lesson.title" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="lessonContent" class="block text-sm font-medium text-gray-700">Lesson Content</label>
                <textarea id="lessonContent" v-model="lesson.content" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="mb-4">
                <label for="lessonVideo" class="block text-sm font-medium text-gray-700">Lesson Video</label>
                <input type="file" id="lessonVideo" @change="handleVideoUpload" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700">Create Lesson</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const lesson = ref({
    title: '',
    content: '',
})

const submitLessonForm = () => {
    // Submit lesson form logic
}

const handleVideoUpload = (event) => {
    const file = event.target.files[0]
    // Handle video upload logic
}
</script>

<style scoped>
/* Add any additional styles here */
</style>
