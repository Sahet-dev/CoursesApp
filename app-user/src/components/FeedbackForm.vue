<template>
    <Navbar />
    <div>
        <div class="p-6 bg-white shadow-md rounded-lg mx-5">
            <h1 class="text-2xl font-semibold mb-4">Submit Feedback</h1>

            <form @submit.prevent="submitFeedback" class="space-y-4">
                <div>
                    <label for="type" class="block text-gray-700">Type</label>
                    <select v-model="type" id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="support">Support</option>
                        <option value="feedback">Feedback</option>
                    </select>
                </div>

                <div>
                    <label for="message" class="block text-gray-700">Message</label>
                    <textarea v-model="message" id="message" name="message" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required></textarea>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-all duration-300">
                    Submit
                </button>

                <div v-if="successMessage" class="text-green-500 mt-4">{{ successMessage }}</div>
            </form>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import axios from 'axios';
import apiClient from "../axios/index.js";
import Navbar from "./Navbar.vue";

const type = ref('support');
const message = ref('');
const successMessage = ref('');

const submitFeedback = async () => {
    try {
        const response = await apiClient.post('/feedback', {type: type.value, message: message.value});
        successMessage.value = response.data.success;
        type.value = '';
        message.value = '';
    } catch (error) {
        console.error(error);
    }
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
