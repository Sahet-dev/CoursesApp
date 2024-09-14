<template>
    <div v-if="selectedLesson" class="bg-white p-4 rounded w-full shadow md:flex-1">
        <h4 class="text-lg font-semibold mb-2">{{ selectedLesson.title }}</h4>
        <div class="video-container">
            <video v-if="selectedLesson.video_url" :src="lessonVideoUrl(selectedLesson.video_url)"
                   controls class="video"
                   @play="startLessonTimer"
                   @pause="stopLessonTimer"
                   @ended="stopLessonTimer"
                   @loadedmetadata="resetTimer"
            >
            </video>
            <div v-if="!userHasAccess && !selectedLesson.video_url" class="video w-full h-64 flex flex-col items-center justify-center bg-gray-300 text-gray-600 rounded mb-4 p-4">
                <h2 class="text-xl font-semibold mb-4 text-center">Access Restricted</h2>
                <p class="mb-6 text-center">Please subscribe or purchase to access this course's lessons.</p>
                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Subscribe or Purchase
                </button>
            </div>
        </div>

        <div class="flex justify-between items-center p-2 max-w-screen-xl mx-auto">
            <button @click="goToPreviousLesson" :disabled="isFirstLesson"
                    class="flex items-center justify-center bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 transition duration-200">
                <ChevronDoubleLeftIcon class="w-5 h-5"/>
                <span class="hidden lg:inline-block ml-3 text-sm lg:text-base">Previous</span>
            </button>
            <button @click="goToNextLesson" :disabled="isLastLesson"
                    class="flex items-center justify-center bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 transition duration-200">
                <span class="hidden lg:inline-block mr-3 text-sm lg:text-base">Next</span>
                <ChevronDoubleRightIcon class="w-5 h-5"/>
            </button>
        </div>
    </div>
</template>

<script setup>
import {ChevronDoubleLeftIcon, ChevronDoubleRightIcon} from '@heroicons/vue/24/outline';
const props = defineProps({
    selectedLesson: Object,
    userHasAccess: Boolean,
    isFirstLesson: Boolean,
    isLastLesson: Boolean,
});

const emit = defineEmits(["start-lesson-timer", "stop-lesson-timer", "reset-timer", "next-lesson", "previous-lesson"]);

const startLessonTimer = () => emit("start-lesson-timer");
const stopLessonTimer = () => emit("stop-lesson-timer");
const resetTimer = () => emit("reset-timer");

const goToNextLesson = () => emit("next-lesson");
const goToPreviousLesson = () => emit("previous-lesson");

const lessonVideoUrl = (videoUrl) => `/storage/${videoUrl}`;
</script>
