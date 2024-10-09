<template>
    <div class="bg-gradient-to-r from-gray-200 via-pink-200 to-blue-200">
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg grid">
            <!-- Profile Section -->
            <div class="group relative bg-white">
                <img :src="imageSrc" class="w-full h-[200px] object-cover">
                <div class="absolute top-2 right-2">
                    <button class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0">
                        <!-- Camera Icon for Changing Avatar -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                        <input type="file" class="absolute left-0 bottom-0 right-0 opacity-0 cursor-pointer">
                    </button>
                </div>
                <!-- Avatar and User Information -->
                <div class="flex">
                    <div class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full overflow-hidden">
                        <img :src="imageUrl(user.avatar ? `/storage/${user.avatar}` : defaultAvatar)" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
            <h1 class="text-2xl font-bold mb-4">{{ user.name }}'s Profile</h1>

            <!-- Basic Information -->
            <div class="basic-info mb-8">
                <h2 class="text-xl font-semibold mb-2">Basic Information</h2>
                <div class="grid grid-cols-2 gap-4 text-gray-700">
                    <p><strong>Email:</strong> {{ user.email }}</p>
                    <p><strong>Age:</strong> {{ user.age }}</p>
                    <p><strong>Gender:</strong> {{ user.gender }}</p>
                    <p><strong>Last Login:</strong> {{ user.last_login_at }}</p>

                </div>
            </div>

            <!-- Statistics -->
            <div class="statistics mb-8">
                <h2 class="text-xl font-semibold mb-4">Statistics</h2>
                <ul class="space-y-4">
                    <li class="p-4 bg-gray-50 border rounded-lg">
                        <p><strong>Comments Count:</strong> {{ statistics.commentCount }}</p>
                        <p><strong>Completed Lessons Count:</strong> {{ statistics.completedLessonsCount }}</p>
                        <p><strong>Purchased Courses Count:</strong> {{ statistics.purchasedCoursesCount }}</p>
                        <p><strong>Days Since Registration:</strong> {{ Math.round(statistics.daysSinceRegistration) }} days</p>
                        <p><strong>Created Courses Count:</strong> {{ statistics.createdCoursesCount }}</p>
                    </li>
                </ul>
            </div>

            <div class="achievements mb-8">
                <h2 class="text-xl font-semibold mb-4">Achievements</h2>
                <ul class="space-y-4">
                    <li v-for="achievement in user.achievements" :key="achievement.id" class="p-4 bg-gray-50 border rounded-lg">
                        <p><strong>Course:</strong> {{ achievement.course.title }}</p>
                        <p><strong>Rating:</strong> {{ achievement.rating }}</p>
                        <p><strong>Feedback:</strong> {{ achievement.feedback_text }}</p>
                        <p><strong>Earned At:</strong> {{ achievement.pivot.earned_at }}</p>
                    </li>
                </ul>
            </div>

            <!-- Purchased Courses -->
            <div class="purchased-courses mb-8">
                <h2 class="text-xl font-semibold mb-4">Purchased Courses</h2>
                <ul class="space-y-4">
                    <li v-for="course in user.purchased_courses" :key="course.id" class="p-4 bg-gray-50 border rounded-lg">
                        <p><strong>Title:</strong> {{ course.title }}</p>
                        <p><strong>Description:</strong> {{ course.description }}</p>
                        <p><strong>Teacher:</strong> {{ course.teacher.name }}</p>
                        <p><strong>Completed:</strong> {{ course.pivot.completed ? 'Yes' : 'No' }}</p>
                    </li>
                </ul>
            </div>
            <!-- Comments -->
            <div class="comments mb-8 overflow-hidden">
                <h2 class="text-xl font-semibold mb-4">Comments</h2>
                <ul class="space-y-4">
                    <li v-for="comment in user.comments" :key="comment.id" class="p-4 bg-gray-50 border rounded-lg">

                        <p><strong>Lesson:</strong> {{ comment.lesson.title }}</p>
                        <p class="break-words"><strong>Comment:</strong> {{ comment.comment }}</p>
                        <p><strong>Likes:</strong> {{ comment.likes.length }}</p>
                    </li>
                </ul>
            </div>


            <!-- Engagements -->
            <div class="engagements">
                <h2 class="text-xl font-semibold mb-4">Engagements</h2>
                <ul class="space-y-4">
                    <li v-for="engagement in user.engagements" :key="engagement.id" class="p-4 bg-gray-50 border rounded-lg">
                        <p><strong>Course:</strong> {{ engagement.course.title  }}</p>
                        <p><strong>Time Spent:</strong> {{ Math.round(engagement.time_spent / 60) }} minutes</p>

<!--                        <ul class="space-y-4">-->
<!--                            <li v-for="interaction in engagement.interactions" :key="interaction.timestamp" class="p-4 bg-gray-50 border rounded-lg">-->
<!--                                <p><strong>Interaction Type:</strong> {{ interaction.interaction_type }}</p>-->
<!--                                <p><strong>Lesson ID:</strong> {{ interaction.lesson_id }}</p>-->
<!--                                <p><strong>Timestamp:</strong> {{ new Date(interaction.timestamp).toLocaleString() }}</p>-->
<!--                            </li>-->
<!--                        </ul>-->
                        <p><strong>Assignments Completed:</strong> {{ engagement.assignments_completed }}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../axios/index.js";
import {useRoute} from "vue-router";
import imageSrc from '../../assets/pngwing.com.png'
import defaultAvatar from '../../assets/avatar_default.png'
import {imageUrl} from "../../imageUtil.js";
const route = useRoute();
// Reactive properties to store user data and statistics
const user = ref({});
const statistics = ref({
    commentCount: 0,
    completedLessonsCount: 0,
    purchasedCoursesCount: 0,
    createdCoursesCount: 0,
    daysSinceRegistration: 0,
    registrationDate: 0
});
const userId = ref(route.params.id);
// Ref to hold the default image for the user


const fetchUserProfile = async () => {
    const userId = route.params.id; // Retrieve the ID from route params


        try {
            const response = await apiClient.get(`/user/profile/${userId}`);
            user.value = response.data.user;
            statistics.value = response.data.statistics;
            // Handle response
        } catch (error) {
            console.error('Error fetching user profile:', error);
        }

};

// Fetch the data when the component is mounted
onMounted(() => {
    fetchUserProfile();
});
</script>

<style scoped>
</style>
