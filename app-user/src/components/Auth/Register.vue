<template>
    <Navbar />
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-cyan-500 to-blue-500 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white bg-opacity-95 p-10 rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 tracking-tight">Create your account</h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Sign in</a>
                </p>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="name" class="sr-only">Name</label>
                        <input
                            v-model="name"
                            id="name"
                            name="name"
                            type="text"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Full name"
                        />
                    </div>
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input
                            v-model="email"
                            id="email-address"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Email address"
                        />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input
                            v-model="password"
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Password"
                        />
                    </div>
                    <div>
                        <label for="password_confirmation" class="sr-only">Confirm Password</label>
                        <input
                            v-model="passwordConfirmation"
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Confirm password"
                        />
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300"
                    >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <!-- Heroicon name: solid/user-add -->
              <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
              </svg>
            </span>
                        Register
                    </button>
                </div>
            </form>
            <div v-if="errorMessage" class="mt-4 text-sm text-red-600 bg-red-100 border border-red-400 rounded-md p-3">
                {{ errorMessage }}
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref } from 'vue';
import axios from 'axios';
import {useRouter} from "vue-router";
import apiClient from "../../axios/index.js";
import Navbar from "../Navbar.vue";

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


        await router.push({name: 'Login'});

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
</style>
