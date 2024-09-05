<template>
    <div class="loader-container">
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
.loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 40vh;
    background-color: rgba(255, 255, 255, 0.12);
    flex-direction: column;
}

.loader-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.loader {
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1.5s linear infinite;
}

.message {
    text-align: center;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
