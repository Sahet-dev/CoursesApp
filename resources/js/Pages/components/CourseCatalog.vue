<template>
    <div class="bg-gradient-to-r from-gray-100 via-pink-50 to-blue-50">
        <Navbar/>
        <div class="container mx-auto p-4  ">
            <h1 class="text-2xl font-bold mb-6">Course Catalog</h1>
                <h5 class="text-2xl mb-6 p-2">Results were found: {{ totalOccurrences }}</h5>
                <h5 class="text-2xl mb-6 p-2">For search term: "{{ props.filters.search }}"</h5>

            <div v-if="courses.length">
                <div v-for="course in courses" :key="course.id" class="bg-white rounded shadow p-6 mb-6">
                    <img
                        :src="imageUrl(course.thumbnail)"
                        :alt="course.title"
                        class="w-full h-48 object-cover rounded mb-4"
                    />
                    <h2 class="text-xl font-semibold mb-2">
            <span v-for="(part, index) in splitText(course.title, course.id)" :key="index">
              <span v-if="part.isHighlighted" class="highlight">{{ part.text }}</span>
              <span v-else>{{ part.text }}</span>
            </span>
                    </h2>
                    <p class="text-gray-700 mb-2">
            <span v-for="(part, index) in splitText(course.description, course.id)" :key="index">
              <span v-if="part.isHighlighted" class="highlight">{{ part.text }}</span>
              <span v-else>{{ part.text }}</span>
            </span>
                    </p>
                    <p class="text-sm text-gray-500">Occurrences of "{{ props.filters.search }}": {{ searchCounts[course.id] }}</p>
                    <p class="text-lg font-bold mb-2">${{ course.price }}</p>
                    <a
                        :href="route('courseDetail', course.id)"
                        class="text-blue-500 hover:underline"
                    >View Course</a>
                </div>
            </div>
            <div v-else class="text-gray-500">
                No courses found matching the search criteria.
            </div>
        </div>
    </div>
</template>









<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import Navbar from "@/Pages/components/Navbar.vue";
import { usePage } from '@inertiajs/vue3';
import {lenis} from "@/utils/animations.js";

const { props } = usePage();

// Function to generate the course image URL
const imageUrl = (thumbnail) => {
    const baseUrl = import.meta.env.VITE_APP_URL || 'http://localhost:8000';
    return `${baseUrl}/storage/${thumbnail}`;
};

// Function to split text based on search term
const splitText = (text, courseId) => {
    const search = props.filters?.search || '';
    if (!search) return [{ text, isHighlighted: false }];

    const regex = new RegExp(`(${search})`, 'gi');
    const parts = text.split(regex);

    return parts.map(part => ({
        text: part,
        isHighlighted: part.toLowerCase() === search.toLowerCase(),
    }));
};

// Computed property for courses
const courses = computed(() => props.courses);

// Ref to hold search term count for each course
const searchCounts = ref({});

// Computed property to calculate the total number of occurrences
const totalOccurrences = computed(() => {
    return Object.values(searchCounts.value).reduce((acc, count) => acc + count, 0);
});

// Watch for changes to the courses and update searchCounts
watch(courses, (newCourses) => {
    searchCounts.value = {}; // Reset the counts
    newCourses.forEach(course => {
        const search = props.filters?.search || '';
        if (search) {
            const titleOccurrences = (course.title.match(new RegExp(search, 'gi')) || []).length;
            const descriptionOccurrences = (course.description.match(new RegExp(search, 'gi')) || []).length;
            searchCounts.value[course.id] = titleOccurrences + descriptionOccurrences;
        }
    });
}, { immediate: true });




onMounted(() => {


    lenis.on('scroll', (e) => {
        // console.log('Scrolled:', e);
    });
});

</script>







<style scoped>




[data-highlight="true"] {
    background-color: #fef08a; /* Yellow background color */
    padding: 2px 4px; /* Optional padding */
    border-radius: 2px; /* Optional rounded corners */
}



.highlight {
    background-color: #fef08a; /* Yellow background color */
    padding: 2px 4px; /* Optional padding */
    border-radius: 2px; /* Optional rounded corners */
}


</style>
