<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Comments</h2>

        <ul v-if="comments.data.length" class="space-y-6">
            <li
                v-for="comment in comments.data"
                :key="comment.id"
                class="border border-gray-200 p-4 rounded-lg shadow-sm"
            >
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <strong class="text-lg text-gray-900">{{ comment.user.name }}</strong>
                        <span class="text-sm text-gray-500">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <!-- Edit Button -->
                        <button
                            @click="startEditingComment(comment)"
                            class="text-blue-500 hover:underline"
                        >
                            Edit
                        </button>
                        <!-- Delete Button -->
                        <button
                            @click="deleteComment(comment.id)"
                            class="text-red-500 hover:underline"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Display edit form if editing -->
                <div v-if="editingComment && editingComment.id === comment.id" class="mt-2">
          <textarea
              v-model="editingComment.comment"
              class="w-full border rounded p-2"
              rows="3"
          ></textarea>
                    <button @click="updateComment(editingComment)" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">
                        Save
                    </button>
                    <button @click="cancelEdit" class="mt-2 ml-2 px-4 py-2 bg-gray-300 text-black rounded">
                        Cancel
                    </button>
                </div>

                <!-- Display likes and replies if needed -->
                <p class="text-gray-700 mt-2 break-words" v-else>{{ comment.comment }}</p>

                <div class="mt-3">
                    <span class="font-medium text-gray-700">Likes: {{ comment.likes.length }}</span>
                    <span class="ml-4 font-medium text-gray-700">Replies: {{ comment.replies.length }}</span>
                </div>
            </li>
        </ul>
        <div v-else class="text-center text-gray-600">No comments available.</div>

        <!-- Pagination Controls -->

                <div v-if="comments.data && (comments.prev_page_url || comments.next_page_url)"
                     class="flex justify-center items-center mt-6 break-words m-2 p-2">
                    <!-- Pagination Information -->
                    <div class="mr-4">
                        Page {{ comments.current_page }} of {{ comments.last_page }}
                    </div>

                    <!-- Pagination Buttons -->
                    <div class="flex space-x-2">
                        <!-- Previous Button -->
                        <button
                            @click="fetchComments(comments.prev_page_url)"
                            :disabled="!comments.prev_page_url"
                            class="px-4 py-2 mr-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
                        >
                            Previous
                        </button>

                        <!-- Page Numbers -->
                        <button
                            v-for="page in comments.last_page"
                            :key="page"
                            @click="fetchComments(getUserPageUrl(page))"
                            :class="[
                'px-4 py-2 rounded hover:bg-gray-400 transition-all duration-300',
                comments.current_page === page ? 'bg-blue-500 text-white' : 'bg-gray-300'
            ]"
                        >
                            {{ page }}
                        </button>

                        <!-- Next Button -->
                        <button
                            @click="fetchComments(comments.next_page_url)"
                            :disabled="!comments.next_page_url"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
                        >
                            Next
                        </button>
                    </div>
                </div>


    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../../api/axios.js'; // Import your axios instance

const comments = ref({
    data: [],
    prev_page_url: null,
    next_page_url: null,
});

const editingComment = ref(null); // To track the comment being edited

// Fetch comments with pagination
const fetchComments = async (url = '/comments') => {
    try {
        const response = await apiClient.get(url);
        comments.value = response.data; // Set the paginated response
    } catch (error) {
        console.error('Failed to fetch comments', error);
    }
};

// Start editing a comment
const startEditingComment = (comment) => {
    editingComment.value = { ...comment }; // Make a copy of the comment for editing
};

// Update the comment
const updateComment = async (comment) => {
    try {
        const response = await apiClient.put(`/comments/${comment.id}`, { comment: comment.comment });
        alert(response.data.message);
        editingComment.value = null; // Clear the editing comment
        fetchComments(); // Refresh the comments list
    } catch (error) {
        console.error('Failed to update comment', error);
    }
};

// Cancel editing
const cancelEdit = () => {
    editingComment.value = null;
};

// Delete a comment
const deleteComment = async (id) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        try {
            const response = await apiClient.delete(`/comments/${id}`);
            alert(response.data.message);
            fetchComments(); // Refresh the comments list
        } catch (error) {
            console.error('Failed to delete comment', error);
        }
    }
};

// Fetch comments on component mount
onMounted(() => fetchComments());
</script>

<style scoped>
/* Add custom styles here if needed */
</style>
