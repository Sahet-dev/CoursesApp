<script setup>
import { ref } from 'vue';
import axios from 'axios';

const selectedLesson = ref(null);
const comments = ref([]);

const selectLesson = async (lesson) => {
    selectedLesson.value = lesson;

    // Fetch comments for the selected lesson
    try {
        const response = await axios.get(`/api/lessons/${lesson.id}/comments`);
        comments.value = response.data;
    } catch (error) {
        console.error("Failed to fetch comments:", error);
    }
};

// Function to like a comment (simplified example)
const likeComment = async (courseId, lessonId, commentId) => {
    // Your existing logic to like a comment
};
</script>

<template>
    <div>
        <AuthenticatedLayout>
            <!-- Content and Sidebar Area -->
            <div class="flex flex-col md:flex-row p-4">
                <!-- Content Area -->
                <div class="bg-white p-4 rounded w-full shadow md:flex-1 ml-2">
                    <div v-if="selectedLesson">
                        <h4 class="text-lg font-semibold mb-2">{{ selectedLesson.title }}</h4>
                        <!-- Video Container and Guides -->
                        <TabGroup>
                            <!-- Guides and Comments Tabs -->
                            <TabPanels class="mt-2">
                                <!-- Lesson Content -->
                                <TabPanel key="activity" class="p-3">
                                    <!-- Lesson content goes here -->
                                </TabPanel>

                                <!-- Comments Tab -->
                                <TabPanel key="account" class="p-3">
                                    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                                        <div v-if="comments.length > 0">
                                            <h2 class="text-xl sm:text-2xl font-semibold mb-4">Comments</h2>
                                            <div v-for="comment in comments" :key="comment.id" class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gray-100 rounded-md">
                                                <div class="flex items-center mb-2">
                                                    <div class="font-bold text-base sm:text-lg">{{ comment.user.name }}</div>
                                                </div>
                                                <p class="text-gray-800 mb-2 break-words text-sm sm:text-base">{{ comment.comment }}</p>
                                                <div class="text-xs sm:text-sm text-gray-500">Likes: {{ comment.likes_count }}</div>
                                                <div v-if="user" class="mt-2 flex space-x-2 sm:space-x-4">
                                                    <button @click="likeComment(course.id, selectedLesson.id, comment.id)" class="text-blue-500 text-xs sm:text-sm hover:underline">
                                                        {{ comment.liked_by_user ? 'Unlike' : 'Like' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </TabGroup>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>
