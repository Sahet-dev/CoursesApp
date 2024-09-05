<template>
    <div class="fixed top-4 right-4 z-50 space-y-4">
        <div v-for="(notification, index) in notifications" :key="index" class="flex items-center p-4 border-l-4 shadow-md" :class="notificationClasses(notification.type)">
            <p class="flex-1">{{ notification.message }}</p>
            <button @click="removeNotification(index)" class="ml-4 text-xl font-bold">&times;</button>
        </div>
    </div>
</template>

<script setup>
import {useNotifications} from './useNotifications.js';

const {notifications, addNotification, removeNotification, notificationClasses} = useNotifications();
</script>

<style scoped>
/* Tailwind classes are used directly in the template, no additional styles needed */
</style>
