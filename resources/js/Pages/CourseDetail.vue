<template>
    <div>
        <AuthenticatedLayout>
            <div class="container mx-auto   overflow-y-auto">
                <!-- Course Header -->
                <div v-if="visible" class="container mx-auto p-4 lg:p-12">
                    <h1 class="text-2xl lg:text-4xl font-bold mb-4 lg:mb-6">{{ course.title }}</h1>
                    <img :src="thumbnailUrl" :alt="course.title" class="w-full h-auto rounded-lg shadow-lg mb-4 lg:mb-6" />
                    <p class="text-base lg:text-lg text-gray-700 mb-4">{{ course.description }}</p>
                    <p class="text-lg lg:text-xl font-bold text-gray-900 mb-4">${{ course.price }}</p>
                </div>

                <!-- Sidebar and Content Area -->
                <div class="flex flex-col md:flex-row p-4">
                    <!-- Sidebar -->
                    <div class="w-full md:w-1/4 bg-gray-50 p-4 rounded shadow hidden md:block mb-4 md:mb-0">
                        <!-- Course Info Indicator -->
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

                    <!-- Content Area -->
                    <div class="bg-white p-4 rounded  w-full shadow md:flex-1 ml-2">
                        <div v-if="selectedLesson" class=" ">
                            <h4 class="text-lg font-semibold mb-2">{{ selectedLesson.title }}</h4>
                            <div class="video-container">
                                <video v-if="selectedLesson.video_url" :src="lessonVideoUrl(selectedLesson.video_url)" controls class="video">
                                </video>
                                <div
                                    v-if="userHasAccess === false && !selectedLesson.video_url"
                                    class="video w-full h-64 flex flex-col items-center justify-center bg-gray-300 text-gray-600 rounded mb-4 p-4"
                                >
                                    <h2 class="text-xl font-semibold mb-4 text-center">Access Restricted</h2>
                                    <p class="mb-6 text-center">Please subscribe or purchase to access this course's lessons.</p>
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        Subscribe or Purchase
                                    </button>
                                </div>
                            </div>

                            <TabGroup>
                                <TabList class="flex items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-r outline-none font-bold
                from-blue-200 via-purple-100 to-pink-200 shadow-md orange">
                                    <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none
                    focus:ring-2 px-3">
                                        <template v-if="selected">
                                            <BookOpenIconSolid class="w-5 h-5 selected-tab outline-none" />
                                        </template>
                                        <template v-else>
                                            <BookOpenIcon class="w-5 h-5 orange" />
                                        </template>
                                        <span   :class="[selected ? 'selected-tab' : 'orange']">Guides</span>
                                    </Tab>
                                    <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none
                    focus:ring-2 px-3">
                                        <template v-if="selected">
                                            <ChatBubbleLeftRightIconSolid class="w-5 h-5 selected-tab outline-none" />

                                        </template>
                                        <template v-else>
                                            <ChatBubbleLeftRightIcon class="w-5 h-5 orange" />
                                        </template>
                                        <span class="  ml-2" :class="[selected ? 'selected-tab' : 'orange']">Comments</span>
                                    </Tab>

                                </TabList>

                                <TabPanels class="mt-2">

                                    <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                                        <TabPanel key="activity" class="p-3  ">
                                            <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                                                <div v-html="selectedLesson.markdown_text"></div>

                                            </div>
                                        </TabPanel>
                                    </transition>

                                    <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                                        <TabPanel key="account" class="p-3  ">
                                            <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                                                <!-- Check if there are any comments -->
                                                <div v-if="comments.length > 0">
                                                    <h2 class="text-xl sm:text-2xl font-semibold mb-4">Comments</h2>
                                                    <div v-for="comment in comments" :key="comment.id" class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gray-100 rounded-md">
                                                        <div class="flex items-center mb-2">
                                                            <div class="font-bold text-base sm:text-lg">{{ comment.user.name }}</div>
                                                        </div>
                                                        <p class="text-gray-800 mb-2 break-words text-sm sm:text-base">{{ comment.comment }}</p>
                                                        <div class="text-xs sm:text-sm text-gray-500">Likes: {{ comment.likes_count }}</div>
                                                        <div v-if="user" class="mt-2 flex space-x-2 sm:space-x-4">

                                                            <pre>{{course.id}}!</pre>
                                                            <button @click="likeComment(course.id, selectedLesson.id, comment.id)" class="text-blue-500 text-xs sm:text-sm hover:underline">
                                                                {{ comment.liked_by_user ? 'Unlike' : 'Like' }}
                                                            </button>

                                                            </div>
                                                    </div>
                                                </div>

                                                <!-- If there are no comments -->
                                                <div v-else>
                                                    <p class="text-gray-500 text-sm sm:text-base">No comments available for this lesson.</p>
                                                </div>

                                                <div class="mt-6">
                                                    <h2 class="text-xl sm:text-2xl font-semibold mb-4">Add a Comment</h2>
                                                    <form @submit.prevent="submitComment" class="flex flex-col space-y-4">
                                                        <textarea v-model="newComment" rows="3" placeholder="Type your comment here..." class="p-2 border border-gray-300 rounded-md"></textarea>
                                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                                            Submit Comment
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                        </TabPanel>

                                    </transition>


                                </TabPanels>
                            </TabGroup>

                        </div>

                        <!-- Show titles and access message for remaining lessons -->
                        <div v-else>
                            <div v-for="lesson in titleOnlyLessons" :key="lesson.id">
                                <h4 class="text-lg font-semibold mb-2">{{ lesson.title }}</h4>
                                <div v-if="lesson.id > 4 && !userHasAccess" class="text-center mt-4">
                                    <p class="text-red-600">Please subscribe or purchase to access this course's lessons.</p>
                                    <button @click="redirectToPurchase" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
                                        Subscribe or Purchase
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

<script setup>
import {ref, computed, watchEffect, onMounted} from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {BookOpenIcon, ChatBubbleLeftRightIcon, HandThumbUpIcon} from "@heroicons/vue/24/outline/index.js";
import { HandThumbUpIcon as HandThumbUpIconSolid, BookOpenIcon as BookOpenIconSolid,
    ChatBubbleLeftRightIcon as ChatBubbleLeftRightIconSolid} from "@heroicons/vue/24/solid";
import axios from 'axios';
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {Inertia} from "@inertiajs/inertia";
import axiosService from "@/axiosService.js";
import axiosInstance from "@/axiosService.js";

const props = defineProps({
    course: Object,
    user: Object,
    detailedLessons: Array,
    titleOnlyLessons: Array,
    userHasAccess: Boolean,
});

const course = ref(props.course);
const visible = ref(true);
const detailedLessons = ref(props.detailedLessons || []);
const titleOnlyLessons = ref(props.titleOnlyLessons || []);
const userHasAccess = ref(props.userHasAccess);
const selectedLesson = ref(detailedLessons.value[0] || null);
const additionalData = ref(null);
const comments = ref([]);


watchEffect(() => {
    if (detailedLessons.value.length > 0) {
        selectedLesson.value = detailedLessons.value[0];
    }
});

const totalLessons = computed(() => course.value.lessons?.length || 0);
const totalDuration = computed(() => {
    return course.value.lessons?.reduce((total, lesson) => {
        if (!lesson.duration) return total;
        const [hours, minutes, seconds] = lesson.duration.split(':').map(Number);
        return total + hours * 3600 + minutes * 60 + seconds;
    }, 0) || 0;
});

const formattedTotalDuration = computed(() => {
    const hours = Math.floor(totalDuration.value / 3600);
    const minutes = Math.floor((totalDuration.value % 3600) / 60);
    const seconds = totalDuration.value % 60;
    return `${hours}:${minutes}:${seconds}`;
});

const completionPercentage = computed(() => {
    const completedLessons = course.value.lessons?.filter(lesson => lesson.completed).length || 0;
    return totalLessons.value > 0 ? Math.round((completedLessons / totalLessons.value) * 100) : 0;
});


const likeComment = (courseId, lessonId, commentId) => {
    Inertia.post(`/courses/${courseId}/lessons/${lessonId}/comments/${commentId}/toggle-like`, {}, {
        onSuccess: (page) => {
            // Update the local state with the new lesson data if necessary
            console.log(page.props.message); // "Liked" or "Unliked"
            // Optionally update component state here if you need
        },
        onError: (errors) => {
            // Handle errors
            console.error('Error toggling like:', errors);
        },
        preserveState: true, // Keep current page state
    });
};

async function selectLesson(lesson) {
    selectedLesson.value = lesson;
    visible.value = false;


    try {
        const response = await axios.get(`/api/lessons/${lesson.id}/comments`);
        comments.value = response.data;
    } catch (error) {
        console.error("Failed to fetch comments:", error);
    }

}

const lessonVideoUrl = videoUrl => `/storage/${videoUrl}`;
const thumbnailUrl = computed(() => `/storage/${course.value.thumbnail}`);

function redirectToPurchase() {
    window.location.href = '/subscribe-or-purchase';
}




const newComment = ref('');

const submitComment = () => {
    if (!course.value.id || !selectedLesson.value.id || !newComment.value.trim()) {
        console.error('Course ID, Lesson ID or Comment is missing');
        return;
    }

    Inertia.post(`/courses/${course.value.id}/lessons/${selectedLesson.value.id}/comments`, {
        comment: newComment.value.trim()
    }, {
        onSuccess: (page) => {
            // Handle success (e.g., update the comments list)
            console.log('Comment submitted successfully');
            newComment.value = ''; // Clear the input field
            // Optionally fetch updated comments
            selectLesson(selectedLesson.value);
        },
        onError: (errors) => {
            // Handle errors
            console.error('Error submitting comment:', errors);
        },
        preserveState: true, // Keep current page state
    });
};










const beforeEnter = (el) => {
    el.style.opacity = 0;
    el.style.transform = 'translateX(100px)';
};

const enter = (el, done) => {
    el.offsetHeight; // trigger reflow
    el.style.transition = 'all 0.3s ease-out';
    el.style.opacity = 1;
    el.style.transform = 'translateX(0)';
    done();
};

const leave = (el, done) => {
    el.style.transition = 'all 0.3s ease-out';
    el.style.opacity = 0;
    el.style.transform = 'translateX(-100px)';
    done();
};



</script>

<style>
.orange {
    font-weight: bold;
    color: #e49e58;
}
.selected-tab {
    color: #5daeec;
    text-decoration: none; /* Remove text underline */
    padding-bottom: 2px;
}



.video-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    max-width: 100%;
    background: #000;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.video-container .video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}


.tab-enter-from {
    opacity: 0;
    transform: translateX(100px);
}
.tab-enter-active {
    transition: all 0.3s ease-out;
}
.tab-leave-to {
    opacity: 0;
    transform: translateX(-100px);
}
.tab-leave-active {
    transition: all 0.3s ease-out;
}
</style>
