<template>
    <div class="w-64 bg-gray-200 p-4">
        <h3 class="text-xl font-bold mb-4">Lessons</h3>
        <ul>
            <li v-for="(lesson, index) in lessons" :key="index" class="mb-2">
                <button
                    @click="$emit('update:selectedLesson', index)"
                    class="w-full text-left px-2 py-1 rounded hover:bg-gray-300 border border-transparent hover:border-blue-700"
                    :class="{'bg-gray-300': selectedLesson === index}"
                >
                    {{ lesson.title || `Lesson ${index + 1}` }}
                </button>
            </li>
        </ul>
    </div>
</template>

<script setup>
import {defineProps, defineEmits, } from 'vue';
const props = defineProps({

    lessons: {
        type: Array,
        required: true
    },
    selectedLesson: {
        type: Number,
        required: true
    }
});
const emit = defineEmits(['update:selectedLesson']);
</script>
