<template>
    <div class="max-w-lg mx-auto p-6 bg-white shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-4">Subscribe to a Course</h1>

        <div class="mb-6">
            <label for="course" class="block text-sm font-medium text-gray-700">Select a Course</label>
            <select id="course" v-model="selectedCourse" class="mt-1 block w-full p-2 border-gray-300 rounded-md">
                <option value="" disabled>Select a course</option>
                <option v-for="course in courses" :key="course.id" :value="course.id">
                    {{ course.title }} - {{ course.price }}$
                </option>
            </select>
        </div>

        <div class="mb-6">
            <label for="card-element" class="block text-sm font-medium text-gray-700">Payment Information</label>
            <div id="card-element" class="mt-1 block p-2 border-gray-300 rounded-md bg-gray-50"></div>
        </div>

        <button
            @click="createSubscription"
            class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
        >
            Subscribe
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { loadStripe } from '@stripe/stripe-js';
import apiClient from "../axios/index.js";

const stripe = ref(null);
const paymentMethodId = ref(null);
const customerId = ref(null);
const subscriptionId = ref(null);
const cardElement = ref(null);
const selectedCourse = ref('');
const courses = ref([]);

onMounted(async () => {
    stripe.value = await loadStripe('your-stripe-public-key');

    const elements = stripe.value.elements();
    cardElement.value = elements.create('card');
    cardElement.value.mount('#card-element');

    const { data } = await apiClient.get('/courses');
    courses.value = data;
});

const createSubscription = async () => {
    if (!selectedCourse.value) {
        alert('Please select a course');
        return;
    }

    try {
        const { paymentMethod } = await stripe.value.createPaymentMethod({
            type: 'card',
            card: cardElement.value,
        });
        paymentMethodId.value = paymentMethod.id;

        const customerResponse = await apiClient.post('/stripe/create-customer', {
            payment_method: paymentMethodId.value,
        });
        customerId.value = customerResponse.data.customer_id;

        const subscriptionResponse = await apiClient.post('/stripe/create-subscription', {
            customer_id: customerId.value,
            course_id: selectedCourse.value, // Send course ID for subscription
        });
        subscriptionId.value = subscriptionResponse.data.id;

        alert('Subscription successful!');
    } catch (error) {
        console.error('Subscription error:', error);
        alert('An error occurred. Please try again.');
    }
};
</script>

<style scoped>
#card-element {
    background-color: #f9fafb;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #d1d5db;
}
</style>
