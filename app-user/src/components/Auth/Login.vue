<template>
    <Navbar />
    <div class="min-h-screen flex items-center justify-center  bg-gradient-to-r from-cyan-500 to-blue-500 ">
        <div class="w-full max-w-md bg-white bg-opacity-95 p-10 rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300">
            <h2 class="text-3xl font-extrabold mb-8 text-center text-gray-800 tracking-tight">Welcome Back</h2>

            <form @submit.prevent="login" class="space-y-6">
                <!-- Email Field -->
                <div class="relative">
                    <label for="email" class="absolute -top-2 left-2 inline-block bg-white px-1 text-xs font-medium text-gray-600">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        class="mt-1 block w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        placeholder="you@example.com"
                    />
                </div>

                <!-- Password Field -->
                <div class="relative">
                    <label for="password" class="absolute -top-2 left-2 inline-block bg-white px-1 text-xs font-medium text-gray-600">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        class="mt-1 block w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        placeholder="••••••••"
                    />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            v-model="form.remember"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                </div>

                <!-- Error Message -->
                <div v-if="errorMsg" class="text-red-500 text-sm bg-red-100 border border-red-400 rounded-md p-3">
                    {{ errorMsg }}
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition duration-300"
                >
                    <span v-if="loading" class="animate-spin inline-block mr-2">🔄</span>
                    {{ loading ? 'Logging in...' : 'Sign In' }}
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-600">
<!--                Not a member?-->
<!--                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">Start a 14 day free trial</a>-->
            </p>
        </div>
    </div>
</template>
<script setup>
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import apiClient from "../../axios/index.js";
import Navbar from "../Navbar.vue";

const router = useRouter();
const loading = ref(false);
const errorMsg = ref('');

const form = ref({
    email: '',
    password: '',
    remember: false
});

const login = async () => {
    try {
        loading.value = true;
        const response = await apiClient.post('/login', form.value);
        localStorage.setItem('token', response.data.token);
        console.log('Token saved:', response.data.token);
        router.push('/'); // Redirect after login
    } catch (error) {
        console.error('Login failed:', error);
        if (error.response && error.response.status === 422) {
            errorMsg.value = error.response.data.message || 'Validation failed.';
        } else {
            errorMsg.value = 'Login failed.';
        }
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
</style>
