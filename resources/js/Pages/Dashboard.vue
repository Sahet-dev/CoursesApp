<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// Props coming from Inertia
const props = defineProps({
    enrolledCourses: Array,
    recentActivity: Array,
    currentPlan: Object,
    notifications: Array,
    achievements: Array,
    badges: Array,
    feedbacks: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-6">
                <!-- Enrolled Courses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800">Enrolled Courses</h3>
                    <ul class="mt-4">
                        <li v-for="course in enrolledCourses" :key="course.id" class="border-b border-gray-200 py-2">
                            <strong>{{ course.name }}</strong> - {{ course.lessons.length }} lessons
                        </li>
                    </ul>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800">Recent Activity</h3>
                    <ul class="mt-4">
                        <li v-for="activity in recentActivity" :key="activity.id" class="border-b border-gray-200 py-2">
                            {{ activity.description }} ({{ activity.created_at }})
                        </li>
                    </ul>
                </div>

                <!-- Current Plan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800">Current Plan</h3>
                    <p class="mt-4">
                        <strong>Plan:</strong> {{ currentPlan?.name || 'No active plan' }}<br>
                        <strong>Status:</strong> {{ currentPlan?.status || 'N/A' }}
                    </p>
                </div>

                <!-- Notifications -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800">Notifications</h3>
                    <ul class="mt-4">
                        <li v-for="notification in notifications" :key="notification.id" class="border-b border-gray-200 py-2">
                            {{ notification.data.message }} ({{ notification.created_at }})
                        </li>
                    </ul>
                </div>

                <!-- Achievements -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800">Achievements</h3>
                    <ul class="mt-4">
                        <li v-for="achievement in achievements" :key="achievement.id" class="border-b border-gray-200 py-2">
                            <strong>{{ achievement.title }}</strong> - {{ achievement.course.name }}
                        </li>
                    </ul>
                </div>

                <!-- Badges -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800">Badges</h3>
                    <ul class="mt-4">
                        <li v-for="badge in badges" :key="badge.id" class="border-b border-gray-200 py-2">
                            {{ badge.badge.name }} - {{ badge.created_at }}
                        </li>
                    </ul>
                </div>

                <!-- Feedbacks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800">Feedbacks</h3>
                    <ul class="mt-4">
                        <li v-for="feedback in feedbacks" :key="feedback.id" class="border-b border-gray-200 py-2">
                            {{ feedback.message }} - <strong>{{ feedback.user.name }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
