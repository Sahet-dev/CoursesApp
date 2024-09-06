<template>
    <div class="w-1/4 bg-gray-50 p-4 rounded shadow">
        <!-- Course Info Indicator -->
        <div class="mb-4">
            <p class="text-gray-600 text-sm">
                {{ totalLessons }} Lessons | {{ formattedTotalDuration }} | {{ completionPercentage }}% Completed
            </p>
        </div>

        <h2 class="text-lg font-bold mb-4">Lessons</h2>
        <ul>
            <li v-for="lesson in lessons" :key="lesson.id">
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

<script>
export default {
    props: {
        lessons: Array,
        selectedLesson: Object,
        totalLessons: Number,
        formattedTotalDuration: String,
        completionPercentage: Number,
    },
    methods: {
        selectLesson(lesson) {
            this.$emit('update:selectedLesson', lesson);
        },
    },
};
</script>
