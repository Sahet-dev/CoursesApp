<template>
    <div  class="container mx-auto p-4 lg:p-12">
<!--        v-if="visible"-->

        <h1 class="text-2xl lg:text-4xl font-bold mb-4 lg:mb-6">{{ course.title }}</h1>

        <img :src="thumbnailUrl" :alt="course.title" class="w-full h-auto rounded-lg shadow-lg mb-4 lg:mb-6" />
        <p class="text-base lg:text-lg text-gray-700 mb-4">{{ course.description }}</p>
        <p class="text-lg lg:text-xl font-bold text-gray-900 mb-4">${{ course.price }}</p>
    </div>
</template>

<script setup>
const props = defineProps({
    course: Object,
    visible: Boolean,
});
import {computed} from "vue";

const thumbnailUrl = computed(() => `/storage/${props.course.thumbnail}`);
</script>
