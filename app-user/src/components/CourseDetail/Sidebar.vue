<template>
    <div class="w-full md:w-1/4 bg-gray-50 p-4 rounded shadow hidden md:block mb-4 md:mb-0">
        <div class="mb-4">
            <p class="text-gray-600 text-sm">
                {{ totalLessons }} Lessons | {{ formattedTotalDuration }} | {{ completionPercentage }}% Completed
            </p>
        </div>
        <h2 class="text-lg font-bold mb-4">Lessons</h2>
        <ul>
            <li v-for="lesson in course.lessons" :key="lesson.id">
                <button
                    @click="selectLesson(lesson)"
                    :class="{ 'text-blue-500': selectedLesson === lesson }"
                    class="w-full text-left p-2 hover:bg-gray-200 rounded"
                >
                    {{ lesson.title }}
                </button>
            </li>
        </ul>
    </div>
</template>

<script setup>
const props = defineProps({
    course: Object,
    selectedLesson: Object,
    totalLessons: Number,
    formattedTotalDuration: String,
    completionPercentage: Number,
});

const emit = defineEmits(["select-lesson"]);

const selectLesson = (lesson) => {
    emit("select-lesson", lesson);
};
</script>
