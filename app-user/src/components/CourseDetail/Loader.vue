<template>
    <div class="flex text-center items-center justify-center">
        <div v-if="loading" class="loader-wrapper">
            <div class="loader"></div>
        </div>
        <div v-else class="message">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Oops! Something went wrong.</h2>
            <p class="text-md text-gray-600 mb-4">We are having trouble loading the content. Please try again later.</p>
            <button @click="retry" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">
                Retry
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const loading = ref(true);
const timeout = 60000; // 60 seconds

const retry = () => {
    // Logic to retry fetching the data
    loading.value = true;
    setTimeout(() => {
        loading.value = false;
    }, 1000); // Simulate retry
};

// Timeout to switch from loading to error state
onMounted(() => {
    setTimeout(() => {
        if (loading.value) {
            loading.value = false; // Switch to error message after timeout
        }
    }, timeout);
});
</script>

<style scoped>
.loader {
    width: 50px;
    aspect-ratio: 1;
    display: grid;
    border: 4px solid #0000;
    border-radius: 50%;
    border-right-color: #25b09b;
    animation: l15 1s infinite linear;
}
.loader::before,
.loader::after {
    content: "";
    grid-area: 1/1;
    margin: 2px;
    border: inherit;
    border-radius: 50%;
    animation: l15 2s infinite;
}
.loader::after {
    margin: 8px;
    animation-duration: 3s;
}
@keyframes l15{
    100%{transform: rotate(1turn)}
}
</style>
