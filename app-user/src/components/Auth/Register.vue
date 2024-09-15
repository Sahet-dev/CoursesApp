<template>
    <div class="max-w-md mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
        <form @submit.prevent="handleRegister">
            <!-- Registration Fields -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    v-model="name"
                    type="text"
                    id="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    v-model="email"
                    type="email"
                    id="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                    v-model="password"
                    type="password"
                    id="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input
                    v-model="passwordConfirmation"
                    type="password"
                    id="password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </div>

            <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700"
            >
                Register
            </button>

            <p v-if="errorMessage" class="mt-4 text-red-500">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import {useRouter} from "vue-router";
import apiClient from "../../axios/index.js";

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const errorMessage = ref('');
const router = useRouter();

const handleRegister = async () => {
    try {
        const response = await apiClient.post('/user-api/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value,
        });

        // On successful registration, redirect to dashboard
        if (response.status === 201) {
            router.push({name: 'dashboard'}); // Redirect to the dashboard
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errorMessage.value = Object.values(error.response.data.errors).flat().join(' ');
        } else {
            errorMessage.value = 'Something went wrong. Please try again.';
        }
    }
};
</script>

<style scoped>
/* Optional: Add custom styles here */
</style>
