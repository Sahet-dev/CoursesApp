<template>
    <DashboardHeader />
    <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
        <h1 class="text-9xl font-bold text-blue-500 mb-8">404</h1>
        <h2 class="text-2xl md:text-4xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
        <p class="text-md md:text-lg text-gray-600 mb-4">
            Sorry, the page you are looking for does not exist.
        </p>
        <p class="text-md md:text-lg text-gray-600 mb-8">
            Redirecting to the home page in <span class="font-bold">{{ countdown }}</span> seconds...
        </p>
        <router-link to="/where-am-i" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-md transition">
            Go Back Home
        </router-link>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import DashboardHeader from "./DashboardHeader.vue";

const router = useRouter();
const countdown = ref(40);

onMounted(() => {
    const interval = setInterval(() => {
        countdown.value--;
        if (countdown.value === 0) {
            clearInterval(interval);
            router.push('/admin-dashboard');
        }
    }, 1000);
});
</script>

<style scoped>
</style>
