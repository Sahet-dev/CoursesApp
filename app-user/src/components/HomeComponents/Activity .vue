<template>
    <div class="max-w-7xl mx-auto p-6 bg-gray-50 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Activity Dashboard</h1>

        <div v-if="!isLoading && latestActivities" class="space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Chart Section -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Activity Overview</h2>
                    <div class="h-80">
                        <Line :data="data" :options="options" />
                    </div>
                </div>

                <!-- Courses Section -->
                <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md">

                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Most Interacted Courses</h2>
                    <ul class="space-y-4">
                        <li v-for="course in latestActivities" :key="course.course_id"
                            class="bg-gray-50 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center">
                                <img :src="imageUrl(course.thumbnail)" :alt="course.title"
                                     class="w-full sm:w-24 h-48 sm:h-24 object-cover" />
                                <div class="flex-1 p-4" @click="openCourse(course.id)">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ course.title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ truncateDescription(course.description) }}</p>
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-2 space-y-2 sm:space-y-0">
                                        <span class="text-lg font-bold text-blue-600">${{ course.price }}</span>
                                        <span class="text-sm text-gray-500">Interactions: {{ course.interaction_count }}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

<!--             Featured Courses Section -->
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold mb-6 text-gray-700">Featured Courses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="course in popularCourses" :key="course.id"
                         class="bg-gray-50 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg">

                        <img :src="imageUrl(course.thumbnail)" :alt="course.title" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ course.title }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ truncateDescription(course.description) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-blue-600">${{ course.price }}</span>
                                <button @click="openCourse(course.id)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 mt-8" @click="goToCatalog">Browse Catalog</button>
        </div>

        <div v-else-if="isLoading" class="flex justify-center items-center h-64">
            <Loader />
        </div>

        <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <p class="font-bold">Error</p>
            <p>{{ error }}</p>
        </div>
    </div>
</template>
<script setup>
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'
import * as chartConfig from './chartConfig.js'
import apiClient from "../../axios";
import {onMounted, ref} from 'vue';
import Loader from "../CourseDetail/Loader.vue";
import {imageUrl} from "../../imageUtil.js";
import {useRouter} from "vue-router";
import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";
import Lenis from "@studio-freight/lenis";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

const {data, options} = chartConfig;
const activities = ref([]);
const isLoading = ref(false);
const error = ref(null);
const latestActivities = ref([]);
const popularCourses = ref([]);
const router = useRouter();
const monthlyData = new Array(12).fill(0);

const truncateDescription = (description, maxLength = 100) => {
    // Check if description is undefined or null
    if (!description) return '';

    return description.length > maxLength
        ? description.substring(0, maxLength) + '...'
        : description;
};

const openCourse = async (courseId) => {
    router.push({ name: 'CoursePage', params: { id: courseId } });
};

const goToCatalog = () => {
    router.push({ name: 'CoursesList' });
};


const fetchActivities = async () => {
    isLoading.value = true;
    try {
        const response = await apiClient.get('/user/activities');
        activities.value = response.data;

        activities.value.forEach(activity => {
            const month = parseInt(activity.month.split('-')[1], 10) - 1;
            monthlyData[month] = activity.interaction_count;
        });

        data.datasets[0].data = monthlyData;
    } catch (err) {
        console.error('Error fetching activities:', err);
        if (err.response && err.response.status === 401) {
            error.value = 'Unauthorized. Please log in.';
        } else {
            error.value = 'Failed to load activities.';
        }
    } finally {
        isLoading.value = false;
    }
};

const fetchLatestActivities = async () => {
    try {
        const response = await apiClient.get('/user/latest-activities');
        latestActivities.value = response.data.latestCourses;
        popularCourses.value = response.data.popularCourses;
        console.log(popularCourses.value);
    } catch (err) {
        console.error('Error fetching latest activities:', err);
        error.value = err.response && err.response.status === 401
            ? 'Unauthorized. Please log in.'
            : 'Failed to load latest activities.';
    } finally {
        isLoading.value = false;
    }
};

gsap.registerPlugin(ScrollTrigger);

const lenis = new Lenis()

lenis.on('scroll', (e) => {
    // console.log(e)
})
function raf(time) {
    lenis.raf(time)
    requestAnimationFrame(raf)
}

requestAnimationFrame(raf)



onMounted(() => {
    fetchActivities();
    fetchLatestActivities()
});
</script>


