<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Reviews</h2>
        <ul v-if="reviews.data.length" class="space-y-6">
            <li
                v-for="review in reviews.data"
                :key="review.id"
                class="border border-gray-200 p-4 rounded-lg shadow-sm"
            >
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <strong class="text-lg text-gray-900">{{ review.user.name }}</strong>
                        <span class="text-sm text-gray-500">{{ new Date(review.created_at).toLocaleDateString() }}</span>
                    </div>
                    <!-- Update and Delete Buttons -->
                    <div>
                        <button @click="startEditing(review)" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                        <button @click="deleteReview(review.id)" class="text-red-500 hover:text-red-700">Delete</button>
                    </div>
                </div>
                <div class="flex items-center mb-3">
                    <span class="font-medium text-gray-700">Rating: </span>
                    <span class="ml-2 text-yellow-500">
                        <template v-for="n in 5">
                            <svg
                                v-if="n <= review.rating"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 inline-block"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                :key="'filled' + n"
                            >
                                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.749 1.449 8.285L12 18.896l-7.385 4.444 1.449-8.285L.587 9.306l8.332-1.151z" />
                            </svg>
                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 inline-block"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                                :key="'empty' + n"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.429 4.406a1 1 0 00.95.69h4.631c.969 0 1.371 1.24.588 1.81l-3.75 2.731a1 1 0 00-.364 1.118l1.43 4.406c.3.922-.755 1.688-1.539 1.118L12 17.011l-3.75 2.73c-.784.57-1.838-.196-1.539-1.118l1.43-4.406a1 1 0 00-.364-1.118L3.027 9.833c-.783-.57-.38-1.81.588-1.81h4.631a1 1 0 00.95-.69l1.429-4.406z"
                                />
                            </svg>
                        </template>
                    </span>
                </div>
                <!-- Show either edit form or review text -->
                <div v-if="editingReviewId === review.id">
                    <textarea v-model="editingReview.comment" class="w-full border rounded-md p-2 mb-2"></textarea>
                    <button @click="updateReview(review.id)" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2">Update</button>
                    <button @click="cancelEditing" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                </div>
                <p v-else class="text-gray-700">{{ review.comment }}</p>
            </li>
        </ul>
        <div v-else class="text-center text-gray-600">No reviews available.</div>

        <!-- Pagination Controls -->
        <div class="flex justify-center mt-6" v-if="reviews.data.length">
            <button
                @click="fetchReviews(reviews.prev_page_url)"
                :disabled="!reviews.prev_page_url"
                class="px-4 py-2 mr-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
            >
                Previous
            </button>
            <button
                @click="fetchReviews(reviews.next_page_url)"
                :disabled="!reviews.next_page_url"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
            >
                Next
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../../api/axios.js'; // Import axios client

const reviews = ref({
    data: [],
    prev_page_url: null,
    next_page_url: null,
});

const editingReviewId = ref(null); // Track which review is being edited
const editingReview = ref({}); // Hold the current review being edited

// Fetch reviews with pagination
const fetchReviews = async (url = '/reviews') => {
    try {
        const response = await apiClient.get(url);
        reviews.value = response.data; // Set the paginated response
    } catch (error) {
        console.error('Failed to fetch reviews', error);
    }
};

// Start editing a review
const startEditing = (review) => {
    editingReviewId.value = review.id;
    editingReview.value = { ...review };
};

// Cancel editing
const cancelEditing = () => {
    editingReviewId.value = null;
    editingReview.value = {};
};

// Update a review
const updateReview = async (reviewId) => {
    try {
        await apiClient.put(`/reviews/${reviewId}`, editingReview.value);
        fetchReviews(); // Refresh the reviews
        cancelEditing();
    } catch (error) {
        console.error('Failed to update review', error);
    }
};

// Delete a review
const deleteReview = async (reviewId) => {
    try {
        await apiClient.delete(`/reviews/${reviewId}`);
        fetchReviews(); // Refresh the reviews
    } catch (error) {
        console.error('Failed to delete review', error);
    }
};

// Fetch reviews on component mount
onMounted(() => fetchReviews());
</script>
