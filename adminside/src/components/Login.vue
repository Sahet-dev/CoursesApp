<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

            <form @submit.prevent="login" class="space-y-4">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        v-model="form.remember"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>

                <!-- Error Message -->
                <div v-if="errorMsg" class="text-red-500 text-sm">
                    {{ errorMsg }}
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                >
                    <span v-if="loading" class="animate-spin inline-block mr-2">🔄</span>
                    {{ loading ? 'Logging in...' : 'Login' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from "../api/axios.js";

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
        const response = await apiClient.post('/login', form.value); // Send form data
        localStorage.setItem('token', response.data.token); // Save token to localStorage
        const userRole = response.data.user.role;
        console.log('Token saved:', userRole);
        if (userRole === 'admin') {
            router.push('/admin-dashboard');
        } else if (userRole === 'teacher') {
            router.push('/teacher-dashboard');
        } else if (userRole === 'moderator') {
            router.push('/moderator-dashboard');
        } else {
            router.push('/'); // Default redirect for other roles
        }
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
