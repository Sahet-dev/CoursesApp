<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 text-center">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
            <h1 class="text-2xl font-semibold text-gray-800">{{ message }}</h1>
            <p class="text-gray-600 mt-2">You will be redirected shortly...</p>
            <div class="mt-4 w-full h-1 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 transition-all duration-[4000ms] w-full" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, onMounted } from 'vue';

const props = defineProps({
    message: String,
});

onMounted(() => {
    setTimeout(() => {
        window.location.href = 'http://localhost:4000';
    }, 40000);
});
</script>
