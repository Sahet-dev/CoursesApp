<template>

    <Navbar />
    <div class="min-h-screen bg-gradient-to-r from-gray-100 via-pink-50 to-blue-50 ">
        <div class="container mx-auto px-4 py-8 pb-2 mb-2   ">

            <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Course Catalog</h1>

            <div v-if="isLoading" class="flex justify-center items-center min-h-[calc(100vh-200px)] ">
                <!--            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500"></div>-->
                <Loader  />
            </div>

            <div v-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ error }}</p>
            </div>

            <div v-if="courses.length && !isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="course in courses" :key="course.id" class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:scale-105">
                    <img :src="imageUrl(course.thumbnail)" :alt="course.title" class="w-full h-48 object-cover" />
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">{{ course.title }}</h2>
                        <p class="text-gray-600 mb-4">{{ course.description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-blue-600">${{ course.price }}</span>
                            <button @click="openCourse(course.id)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Enroll Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="courses.length && !isLoading" class="mt-8 flex justify-center items-center space-x-4 pb-4" >
                <button
                    :disabled="!pagination.prev_page_url"
                    @click="fetchCourses(pagination.prev_page_url)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py- px-4 rounded-l transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Previous
                </button>
                <span class="text-gray-700">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
                <button
                    :disabled="!pagination.next_page_url"
                    @click="fetchCourses(pagination.next_page_url)"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-r transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Next
                </button>
            </div>
        </div>

    </div>
    <Footer />
</template>

<script setup>
import {ref, onMounted} from "vue";
import apiClient from "../axios/index.js";
import Navbar from "./Navbar.vue";
import Footer from "./Footer.vue";
import Loader from "./CourseDetail/Loader.vue";
import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";
import Lenis from "@studio-freight/lenis";
import {imageUrl} from "../imageUtil.js";
import {useRouter} from "vue-router";

const courses = ref([]);
const pagination = ref({});
const isLoading = ref(true);
const error = ref(null);
const router = useRouter();

const fetchCourses = async (url = "/courses-list") => {
    isLoading.value = true;
    error.value = null;

    try {
        const response = await apiClient.get(url);
        courses.value = response.data.data;
        pagination.value = response.data;
    } catch (err) {
        console.error("Error fetching courses:", err);
        error.value = "Failed to load courses. Please try again later.";
    } finally {
        isLoading.value = false;
    }
};

const openCourse = async (courseId) => {
    router.push({ name: 'CoursePage', params: { id: courseId } });
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
    fetchCourses();
});
</script>
