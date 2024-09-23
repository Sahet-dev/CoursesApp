<template>
    <Navbar />
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 to-purple-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl font-extrabold text-center text-gray-900 mb-8">
                Choose Your <span class="text-indigo-600">Subscription Plan</span>
            </h2>

            <div class="mt-12 space-y-8 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-6 lg:gap-8">
                <!-- Monthly Subscription Card -->
                <div
                    class="relative bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col overflow-hidden transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    :class="{'ring-2 ring-indigo-600': selectedPlan === 'monthly'}"
                >
                    <div class="px-6 py-8 bg-white sm:p-10 sm:pb-6">
                        <h3 class="text-2xl font-semibold text-center text-gray-900">Monthly Plan</h3>
                        <div class="mt-4 flex justify-center items-baseline text-6xl font-extrabold text-indigo-600">
                            $15
                            <span class="ml-1 text-2xl font-medium text-gray-500">/mo</span>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-gray-50 space-y-6 sm:p-10 sm:pt-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-base text-gray-700">Access all courses</p>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-base text-gray-700">Cancel anytime</p>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-base text-gray-700">Support included</p>
                            </li>
                        </ul>
                        <button
                            @click="selectPlan('monthly')"
                            class="w-full bg-indigo-600 text-white rounded-md px-4 py-3 text-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors duration-200"
                        >
                            Select Monthly Plan
                        </button>
                    </div>
                </div>

                <!-- Yearly Subscription Card -->
                <div
                    class="relative bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col overflow-hidden transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    :class="{'ring-2 ring-indigo-600': selectedPlan === 'yearly'}"
                >
                    <div class="px-6 py-8 bg-white sm:p-10 sm:pb-6">
                        <h3 class="text-2xl font-semibold text-center text-gray-900">Yearly Plan</h3>
                        <div class="mt-4 flex justify-center items-baseline text-6xl font-extrabold text-indigo-600">
                            $150
                            <span class="ml-1 text-2xl font-medium text-gray-500">/yr</span>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col justify-between px-6 pt-6 pb-8 bg-gray-50 space-y-6 sm:p-10 sm:pt-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-base text-gray-700">Access all courses</p>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-base text-gray-700">Cancel anytime</p>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-base text-gray-700">Support included</p>
                            </li>
                            <li class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-base text-gray-700">Save $30 annually</p>
                            </li>
                        </ul>
                        <button
                            @click="selectPlan('yearly')"
                            class="w-full bg-indigo-600 text-white rounded-md px-4 py-3 text-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors duration-200"
                        >
                            Select Yearly Plan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Display Selected Plan -->
            <div class="mt-10 text-center">
                <p class="text-xl font-medium text-gray-700">
                    You have selected the
                    <span class="font-bold text-indigo-600">{{ selectedPlan === 'monthly' ? 'Monthly' : 'Yearly' }}</span> plan.
                </p>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import { ref } from 'vue'
import Navbar from "./Navbar.vue";
import Footer from "./Footer.vue";

const selectedPlan = ref('monthly')

const selectPlan = (plan) => {
    selectedPlan.value = plan
}
</script>
