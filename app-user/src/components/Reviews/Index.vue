<!-- resources/js/Pages/Reviews/Index.vue -->

<template>
    <Navbar />
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-semibold mb-4">Submit a Review</h2>
        <form @submit.prevent="submitReview" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-medium">Rating:</label>
                <div class="flex space-x-1">
                    <svg
                        v-for="n in 5"
                        :key="n"
                        @click="setRating(n)"
                        :class="{'text-yellow-500': n <= rating, 'text-gray-300': n > rating}"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 cursor-pointer"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M12 17.27L18.18 21l-1.45-6.3L22 9.24l-6.36-.54L12 2 8.36 8.7 2 9.24l5.27 5.46L5.82 21z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <label for="comment" class="block text-gray-700 font-medium">Comment:</label>
                <textarea v-model="comment" id="comment" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
            </div>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600
                focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span v-if="isSubmitting">Submitting...</span>
                <span v-else>Submit Review</span>
            </button>
        </form>
        <h3 class="text-xl font-semibold mt-8">Site Reviews SSS</h3>
        <ul class="mt-4 space-y-4">
            <li v-for="review in reviews" :key="review.id" class="border rounded-lg p-4 bg-gray-50 shadow-sm">
                <strong class="block text-lg font-medium">{{ review.user.name }}</strong>

                <div class="flex items-center space-x-1">
                    <!-- Display only the stars corresponding to the review rating -->
                    <svg
                        v-for="n in review.rating"
                        :key="n"
                        class="text-yellow-500 w-6 h-6"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M12 17.27L18.18 21l-1.45-6.3L22 9.24l-6.36-.54L12 2 8.36 8.7 2 9.24l5.27 5.46L5.82 21z"></path>
                    </svg>
                    <span class="text-gray-600">Rating: {{ review.rating }} stars</span>
                    <!-- Display empty stars if needed -->

                </div>
                <p class="mt-2 text-gray-800">{{ review.comment }}</p>
            </li>
        </ul>
    </div>
    <Footer />
</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import apiClient from "../../axios/index.js";
import Navbar from "../Navbar.vue";
import Footer from "../Footer.vue";


const user = ref({

});
const reviews = ref([]);
const rating = ref(1);
const comment = ref('');
const isSubmitting = ref(false);

const setRating = (value) => {
    rating.value = value;
};
const fetchUser = async () => {
    try {
        const response = await apiClient.get('/user');

        // Check if response.data contains the user information
        if (!response.data) {
            user.value = null; // Set user to null when unauthenticated
        } else {
            user.value = response.data.data;

        }

    } catch (error) {
        if (error.response && error.response.status === 401) {
            // Handle the case where the user is unauthenticated (401 error)
            user.value = null;
        } else {
            // Handle other errors (server error, network error, etc.)
            console.error('Error fetching user:', error);
        }
    }
};
const fetchReviews = async () => {
    try {
        const response = await apiClient.get('/reviews');

        // Assuming the reviews are nested under `reviews` key
        if (response.data && response.data.reviews) {
            reviews.value = response.data.reviews;
        } else {
            reviews.value = [];
        }

    } catch (error) {
        console.error('Error fetching reviews:', error);
        reviews.value = [];
    }
};






const submitReview = async () => {
    try {
        isSubmitting.value = true; // Start loading

        const response = await apiClient.post('/reviews', {
            user_id: user.value.id,
            rating: rating.value,
            comment: comment.value
        });

        const newReview = response.data.review;

        // Add the new review to the reviews array
        reviews.value.unshift({ // Add at the top
            id: newReview.id,
            user_id: newReview.user_id,
            rating: newReview.rating,
            comment: newReview.comment,
            created_at: newReview.created_at,
            updated_at: newReview.updated_at,
            user: {
                id: user.value.id,
                name: user.value.name,
                avatar: user.value.avatar,
            }
        });

        // Reset form fields
        rating.value = 1;
        comment.value = '';

    } catch (error) {
        console.error('Error submitting review:', error.response.data);
    } finally {
        isSubmitting.value = false; // Stop loading
    }
    fetchReviews()
};


fetchReviews()
fetchUser()
</script>

<style scoped>
/* Additional styles if necessary */
</style>
