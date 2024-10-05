<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <!-- Loader when data is being fetched -->
        <Loader v-if="isLoading" />

        <!-- Check if data is loaded and there are bookmarked items -->
        <div v-if="!isLoading && bookmarkedItems.length" class="space-y-4">
            <ul class="space-y-2">
                <li v-for="item in bookmarkedItems" :key="item.id"
                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition duration-200">
                    <!-- Bookmark Details -->
                    <div class="flex items-start sm:items-center space-x-4 w-full sm:w-auto">
                        <!-- Bookmark Icon -->
                        <svg class="w-8 h-8 text-blue-500 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 3a2 2 0 00-2 2v16l7-4 7 4V5a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <!-- Bookmark Details -->
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-lg">{{ item.title }}</p>
                            <p class="text-gray-600 text-sm">{{ item.type }} - Bookmarked At: {{ item.bookmarked_at }}</p>
                        </div>
                    </div>
                    <!-- Remove Button -->
                    <button
                        @click="removeBookmark(item.id)"
                        class="mt-3 sm:mt-0 px-4 py-2 bg-amber-400 text-white rounded-md hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200"
                    >
                        Remove
                    </button>
                </li>
            </ul>
        </div>

        <!-- Display message when no bookmarks are found and data is loaded -->
        <div v-else-if="!isLoading && !bookmarkedItems.length" class="text-center text-gray-500">
            No bookmarks found
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../axios/index.js";
import Loader from "../CourseDetail/Loader.vue";

const bookmarkedItems = ref([]);
const isLoading = ref(true);

async function fetchBookmarks() {
    try {
        const response = await apiClient.get('/bookmarks');
        bookmarkedItems.value = response.data;
    } catch (error) {
        console.error('Error fetching bookmarks:', error);
    } finally {
        isLoading.value = false; // Hide loader once data is loaded or error occurs
    }
}

async function removeBookmark(id) {
    try {
        await apiClient.delete(`/bookmarks/${id}`);
        bookmarkedItems.value = bookmarkedItems.value.filter(item => item.id !== id);
    } catch (error) {
        console.error('Error removing bookmark:', error);
    }
}

onMounted(() => {
    fetchBookmarks();
});
</script>

<style scoped>
</style>
