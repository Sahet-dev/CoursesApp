<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-xl">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">My Bookmarks</h2>

        <Loader v-if="isLoading" />

        <div v-else-if="bookmarkedItems.length" class="space-y-6">
            <TransitionGroup name="list" tag="ul" class="space-y-4">
                <li v-for="item in bookmarkedItems" :key="item.id"
                    class="flex flex-col sm:flex-row items-center bg-gray-50 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 ease-in-out">
                    <div class="w-full sm:w-1/4 h-40 sm:h-full">
                        <img :src="imageUrl(item.thumbnail || '/placeholder-image.jpg')" :alt="item.title"
                             class="w-full h-full object-cover" />
                    </div>
                    <div class="flex-1 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                        <div class="space-y-2">
                            <h3 class="font-semibold text-xl text-gray-800">{{ item.title }}</h3>
                            <p class="text-gray-600 text-sm">{{ item.type }}</p>
                            <p class="text-gray-500 text-xs">Bookmarked: {{ formatDate(item.bookmarked_at) }}</p>
                        </div>
                        <button
                            @click="removeBookmark(item.id)"
                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition duration-300 ease-in-out transform hover:-translate-y-1"
                        >
                            Remove
                        </button>
                    </div>
                </li>
            </TransitionGroup>
        </div>

        <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookmarks</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new bookmark.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../axios/index.js";
import Loader from "../CourseDetail/Loader.vue";
import {imageUrl} from "../../imageUtil.js";

const bookmarkedItems = ref([]);
const isLoading = ref(true);

async function fetchBookmarks() {
    try {
        const response = await apiClient.get('/bookmarks');
        bookmarkedItems.value = response.data;
    } catch (error) {
        console.error('Error fetching bookmarks:', error);
    } finally {
        isLoading.value = false;
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

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

onMounted(() => {
    fetchBookmarks();
});
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>
