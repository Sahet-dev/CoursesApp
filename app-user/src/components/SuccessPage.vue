<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-8">
                <h1 class="text-3xl font-extrabold text-center text-indigo-600 mb-4">
                    Congratulations on Your Subscription!
                </h1>
                <p class="text-lg text-gray-700 text-center mb-6">
                    Your payment was successful! You now have access to all courses.
                </p>
                <div class="flex justify-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M12 6v12" />
                    </svg>
                </div>
                <p class="text-center text-gray-600 mb-6">
                    Start your learning journey now! Explore your lessons and enjoy the benefits of your subscription.
                </p>
                <div class="flex justify-center">
                    <router-link
                        to="/"
                    class="w-full inline-block text-center bg-indigo-600 text-white rounded-md px-4 py-3 text-lg font-semibold hover:bg-indigo-700 transition duration-200"
                    >
                    Start Lessons
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import apiClient from "../axios/index.js";

const handleSuccess = async () => {
    const sessionId = new URLSearchParams(window.location.search).get('session_id');

    try {
        const response = await apiClient.get(`/success?session_id=${sessionId}`);

        // Handle the success response as needed
        console.log('Payment successful:', response.data);
        window.location.href = '/payment-success'; // Redirect to a success page or handle as needed
    } catch (error) {
        console.error('Error handling success:', error);
    }
};

onMounted(() => {
    handleSuccess(); // Call the function when the component is mounted
});
</script>
