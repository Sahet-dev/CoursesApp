
<template>
    <div class="max-w-md mx-auto mt-16 p-8 bg-white shadow-lg rounded-lg">
        <h2 class="text-3xl font-extrabold mb-8 text-center text-indigo-600">Login</h2>
        <form @submit.prevent="handleLogin">
            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                <input
                    v-model="email"
                    type="email"
                    id="email"
                    class="mt-2 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                    placeholder="Enter your email"
                    required
                />
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                <input
                    v-model="password"
                    type="password"
                    id="password"
                    class="mt-2 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2"
                    placeholder="Enter your password"
                    required
                />
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out"
            >
                Login
            </button>

            <p v-if="errorMessage" class="mt-4 text-center text-red-500">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import router from "../../router/index.js";
import apiClient from "../../axios/index.js";

const email = ref('');
const password = ref('');
const errorMessage = ref('');

console.log('router')
const handleLogin = async () => {
    try {
        const response = await apiClient.post('/login-user', {
            email: email.value,
            password: password.value,
        });
        console.log(response.data.message)
        // Check if login is successful before redirecting
        if (response.data.message === 'Logged in successfully') {
            console.log('router might be wrong')
            router.push({ name: 'MainPage'});  // Ensure the route exists
        } else {
            errorMessage.value = 'Something went wrong. Please try again.';
        }

    } catch (error) {
        if (error.response && error.response.status === 401) {
            errorMessage.value = 'Invalid email or password';
        } else {
            errorMessage.value = 'Something went wrong. Please try again.';
        }
    }
};

</script>

<style scoped>
/* Optional: Add custom styles here */
</style>
