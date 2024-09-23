<template>
    <div class="bg-gradient-to-r from-gray-100 via-pink-50 to-blue-50">

        <Navbar/>
        <div class="container mx-auto   ">
            <!-- Course Header -->
            <div v-if="visible" class="container mx-auto p-4 lg:p-12">
                <h1 class="text-2xl lg:text-4xl font-bold mb-4 lg:mb-6">{{ course.title }}</h1>
                <div class="aspect-ratio-box">
                    <img :src="`http://localhost:8000/storage/${course.thumbnail}`" :alt="course.title" class="thumbnail" />
                </div>

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
                    <h2 class="text-lg font-bold mb-4">Lessons </h2>
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



                <!--                    Mobile  -->

                <div>
                    <!-- Mobile Lessons Dropdown (Visible only on mobile) -->
                    <div class="block md:hidden mb-4">
                        <!-- Course Info Indicator -->
                        <div class="mb-4">
                            <p class="text-gray-600 text-sm">
                                {{ totalLessons }} Lessons | {{ formattedTotalDuration }} | {{ completionPercentage }}% Completed
                            </p>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="mobile-lesson-select" class="text-gray-700 font-bold">
                                Select a Lesson:
                            </label>

                            <!-- Bookmark Button on the right -->
                            <button
                                @click="toggleBookmark(course)"
                                class="ml-2 text-sky-800 bg-transparent hover:bg-gray-200 rounded-full p-2"
                            >
                                <BookmarkIcon v-if="!isBookmarked(course)" class="w-5 h-5" />
                                <BookmarkIconSolid v-else class="w-5 h-5" />
                            </button>
                        </div>
                        <select
                            id="mobile-lesson-select"
                            v-model="selectedLesson"
                            @change="selectLesson(selectedLesson)"
                            class="block w-full p-2 border border-gray-300 rounded"
                        >
                            <option v-for="lesson in course.lessons" :key="lesson.id" :value="lesson">
                                {{ lesson.title }}
                            </option>
                        </select>
                    </div>



                </div>





                <!-- Content Area -->
                <div v-if="visible===false" class="bg-white p-4 rounded  w-full shadow    ">
                    <div v-if="selectedLesson" class=" ">
                        <h4 class="text-lg font-semibold mb-2">{{ selectedLesson.title }}</h4>

                        <div class="video-container">
                            <video v-if="selectedLesson.video_url" :src="lessonVideoUrl(selectedLesson.video_url)"
                                   controls class="video"
                                   @play="handlePlay"
                                   @pause="handlePause"
                                   @ended="handleEnd"
                                   @loadedmetadata="resetTimer"
                            >
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



                        <!--                            Navigation Buttons-->
                        <div v-if="selectedLesson" class="flex justify-between items-center    p-2 max-w-screen-xl mx-auto">
                            <button
                                @click="goToPreviousLesson"
                                :disabled="isFirstLesson"
                                class="flex items-center justify-center bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 transition duration-200"
                            >
                                <ChevronDoubleLeftIcon class="w-5 h-5"/>
                                <span class="hidden lg:inline-block ml-3 text-sm lg:text-base">Previous</span>
                            </button>
                            <button
                                @click="goToNextLesson"
                                :disabled="isLastLesson"
                                class="flex items-center justify-center bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 transition duration-200"
                            >
                                <span class="hidden lg:inline-block mr-3 text-sm lg:text-base">Next  </span>
                                <ChevronDoubleRightIcon class="w-5 h-5"/>
                            </button>
                        </div>


                        <TabGroup>
                            <TabList class="flex items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-r outline-none font-bold
                                    from-blue-200 via-purple-100 to-pink-200 shadow-md orange">
                                <Tab  v-slot="{ selected }" as="button"  class="flex items-center space-x-2 outline-none focus:outline-none
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
                                                    <div class="flex items-center mb-2 cursor-pointer hover:underline" @click="goToUser(comment.user.id)">
                                                        <div class="flex items-center space-x-4"  >
                                                            <img
                                                                :src="(comment.user.avatar ? `/storage/${comment.user.avatar}` : defaultAvatar)"
                                                                alt="User Avatar"
                                                                class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover shadow-lg"
                                                            >
                                                            <div class="font-bold text-base sm:text-lg">{{ comment.user.name }}</div>
                                                        </div>
                                                    </div>
                                                    <p class="text-gray-800 mb-2 break-words text-sm sm:text-base">{{ comment.comment }}</p>
                                                    <div class="text-xs sm:text-sm text-gray-500">Likes: {{ comment.likes_count }}</div>
                                                    <div v-if="currentUser" class="mt-2 flex space-x-2 sm:space-x-4">
                                                        <button @click="likeComment(course.id, selectedLesson.id, comment)"
                                                                class="text-blue-500 text-xs sm:text-sm hover:underline">
                                                            <div v-if="comment.liked_by_user" class="flex items-center">
                                                                <HandThumbUpIconSolid class="w-4 h-4"/>
                                                                <span class="ml-2">Unlike</span>
                                                            </div>
                                                            <div v-else class="flex items-center">
                                                                <HandThumbUpIcon class="w-4 h-4"/>
                                                                <span class="ml-2">Like</span>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- If there are no comments -->
                                            <div v-else>
                                                <p class="text-gray-500 text-sm sm:text-base">No comments available for this lesson.</p>
                                            </div>

                                            <div class="mt-6" v-if="currentUser">
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

        <Footer />
    </div>
</template>

<script setup>
import { ref, computed, watchEffect, onMounted, onUnmounted } from 'vue';
import apiClient from "../../axios/index.js";
import {useRoute, useRouter} from "vue-router";
import {BookmarkIcon, BookOpenIcon, ChatBubbleLeftRightIcon, HandThumbUpIcon} from "@heroicons/vue/24/outline/index.js";
import {BookOpenIcon as BookOpenIconSolid,
    ChatBubbleLeftRightIcon as ChatBubbleLeftRightIconSolid,
    ChevronDoubleRightIcon, ChevronDoubleLeftIcon, HandThumbUpIcon as HandThumbUpIconSolid , BookmarkIcon as BookmarkIconSolid
} from "@heroicons/vue/24/solid";
import defaultAvatar   from "../../assets/avatar_default.png";
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import Navbar from "../Navbar.vue";
import Footer from "../Footer.vue";

const courseData = ref({});
const comments = ref([]);
const likes = ref(null);
const lessonStartTime = ref(null);
const timeSpent = ref(0);
const timerInterval = ref(null);
const courseId = ref(null);
const lessonId = ref(null);
const user = ref(null);
const currentUser = ref(null);
const lessons = ref([]);
const course = ref({ lessons: [] });
const userHasAccess = ref(false);
const authenticated = ref(false);
const route = useRoute();
const visible = ref(true);
const detailedLessons = ref(  []);
const bookmarks = ref([]);
const titleOnlyLessons = ref(  []);
const selectedLesson = ref( null);
const totalLessons = computed(() => course.value.lessons?.length || 0);
const router = useRouter();
const fetchUserData = async () => {
    try {
        const response = await apiClient.get('/user'); // Fetch user info
        const userData = response.data;
        currentUser.value = userData; // Assign user data
        return userData;
    } catch (error) {
        console.error('Failed to fetch user data:', error);
        return null; // Return null if there's an error (user not authenticated)
    }
};

// Fetch data on component mount
const fetchCoursesData = async () => {
    const courseId = route.params.id;
    try {
        let response;
        const userData = await fetchUserData();

        if (userData) {
            response = await apiClient.get(`/api-private-courses/${courseId}`);
        } else {
            response = await apiClient.get(`/api-courses/${courseId}`);
        }
        const data = response.data;

        course.value = data.course;
        lessons.value = data.lessons;
        userHasAccess.value = data.userHasAccess;
        authenticated.value = !!userData;
    } catch (error) {
        console.error('Failed to fetch course data:', error);
    }
};


function startLessonTimer() {
    if (!lessonStartTime.value) {
        lessonStartTime.value = new Date();


    }

    // Start an interval that increments timeSpent every second
    timerInterval.value = setInterval(() => {
        const now = new Date();
        const elapsedTime = Math.floor((now - lessonStartTime.value) / 1000); // Calculate elapsed time in seconds
        timeSpent.value = elapsedTime;
    }, 1000);
}


const fetchBookmarks = async () => {
    const { data } = await apiClient.get('/bookmarks');
    bookmarks.value = data;
};

// Check if a course is bookmarked
const isBookmarked = (course) => {
    return bookmarks.value.some((bookmark) => bookmark.id === course.id);
};

// Add or remove a bookmark
const toggleBookmark = async (course) => {
    if (isBookmarked(course)) {
        await apiClient.delete(`/bookmarks/${course.id}`);
        bookmarks.value = bookmarks.value.filter((bookmark) => bookmark.id !== course.id);
    } else {
        await apiClient.post(`/bookmarks/${course.id}`);
        bookmarks.value.push(course);
    }
};

// Remove a bookmark
const removeBookmark = async (course) => {
    await apiClient.delete(`/bookmarks/${course.id}`);
    bookmarks.value = bookmarks.value.filter((bookmark) => bookmark.id !== course.id);
};


// Stop the timer and accumulate time when the lesson is paused or the user leaves the lesson
function stopLessonTimer() {

    if (timerInterval.value) {

        clearInterval(timerInterval.value); // Stop the timer
        timerInterval.value = null;

        saveTimeSpent(courseId.value, lessonId.value);
    }

    lessonStartTime.value = null; // Reset start time
}

// Reset the timer when the user switches lessons
function resetTimer() {
    timeSpent.value = 0;          // Reset the time spent
    lessonStartTime.value = null; // Reset the start time
    if (timerInterval.value) {
        clearInterval(timerInterval.value); // Clear any existing interval
        timerInterval.value = null;
    }
}

// Function to save the accumulated time spent on the lesson
const saveTimeSpent = async () => {
    const timeInSeconds = timeSpent.value;

    try {
        // Make an API request to the backend
        const response = await apiClient.post(`/courses/${course.value.id}/lessons/${selectedLesson.value.id}/save-time`, {
            time_spent: timeInSeconds,
        });
    } catch (error) {
        // Handle errors
        console.error('Failed to save time spent:', error.response ? error.response.data : error);
    }
};
const isFirstLesson = computed(() => {
    const currentIndex = course.value.lessons.indexOf(selectedLesson.value);
    return currentIndex === 0;
});

const isLastLesson = computed(() => {
    const currentIndex = course.value.lessons.indexOf(selectedLesson.value);
    return currentIndex === course.value.lessons.length - 1;
});



async function trackInteraction(interactionType) {
    try {
        const response = await apiClient.post(`/store-interactions/${course.value.id}`, {  // Pass courseId in the URL
            interaction_type: interactionType,
            course_id: course.value.id,
            lesson_id: selectedLesson.value?.id || null,
        });

        if (response.status === 200) {
        } else {
            console.error('Failed to track interaction:', response);
        }
    } catch (error) {
        console.error('Failed to track interaction:', error.response ? error.response.data : error.message);
    }
}




function s(id) {
    console.log('id: ', id);
    if (id) {
        router.push({ path: `/user/${id}` });
    } else {
        console.error("User ID is undefined");
    }
}

const goToUser = (userId) => {
    router.push({name: 'Profile', params: {id: userId}});
};


const goToNextLesson = () => {
    const currentIndex = course.value.lessons.indexOf(selectedLesson.value);

    if (currentIndex !== -1) {
        const nextIndex = currentIndex + 1;

        if (nextIndex < course.value.lessons.length) {
            selectedLesson.value = course.value.lessons[nextIndex];
            trackInteraction('next_lesson');
        } else {
            console.error('No more lessons.');
        }
    } else {
        console.error('Current lesson not found.');

    }
};

const goToPreviousLesson = () => {
    const currentIndex = course.value.lessons.indexOf(selectedLesson.value);

    if (currentIndex !== -1) {
        const nextIndex = currentIndex - 1;

        if (nextIndex < course.value.lessons.length) {
            selectedLesson.value = course.value.lessons[nextIndex];
            trackInteraction('previous_lesson');
        } else {
            console.error('No more lessons.');
        }
    } else {
        console.error('Current lesson not found.');

    }
};

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

async function selectLesson(lesson)     {

    stopLessonTimer();
    selectedLesson.value = lesson;
    resetTimer();
    visible.value = false;



    const userData = await fetchUserData();
    let apiEndpoint;

    // Determine the correct API endpoint based on authentication status
    if (userData) {
        apiEndpoint = `/lessons/${lesson.id}/comments/authenticated`; // For authenticated users
    } else {
        apiEndpoint = `/lessons/${lesson.id}/comments`; // For unauthenticated users
    }

    try {
        // Fetch comments from the determined API endpoint
        const response = await apiClient.get(apiEndpoint);
        comments.value = response.data;

    } catch (error) {
        console.error("Failed to fetch comments:", error);
    }


    await trackInteraction('select_lesson');
}

const newComment = ref('');

watchEffect(() => {
    if (detailedLessons.value.length > 0) {
        selectedLesson.value = detailedLessons.value[0];
    }
});

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

onUnmounted(() => {
    stopLessonTimer();
});

const handlePlay = () => {
    startLessonTimer();
    trackInteraction('start_video');
};

const handlePause = () => {
    stopLessonTimer();
    trackInteraction('pause_video');  // Track video pause
};

const handleEnd = () => {
    stopLessonTimer();
    trackInteraction('end_video');  // Track video end
};




const likeComment = async (courseId, lessonId, comment) => {
    try {
        const response = await apiClient.post(`/courses/${courseId}/lessons/${lessonId}/comments/${comment.id}/toggle-like`);

        // Update comment based on server response
        comment.liked_by_user = response.data.liked; // Update liked status
        comment.likes_count += response.data.liked ? 1 : -1; // Update like count based on the action

        const interactionType = response.data.liked ? 'like_comment' : 'unlike_comment';
        trackInteraction(interactionType);
        selectLesson(selectedLesson.value)
    } catch (error) {
        console.error('Error toggling like:', error);
    }
};


const submitComment = () => {
    // Make sure course, lesson, and comment exist before submitting
    if (!course.value.id || !selectedLesson.value?.id || !newComment.value.trim()) {
        console.error('Course ID, Lesson ID or Comment is missing');
        return;
    }

    // Submit the comment
    apiClient.post(`/courses/${course.value.id}/lessons/${selectedLesson.value.id}/comments`, {
        comment: newComment.value.trim()
    })
        .then(() => {
            newComment.value = ''; // Clear the input field
            selectLesson(selectedLesson.value); // Refresh the lesson comments
        })
        .catch(error => {
            console.error('Error submitting comment:', error);
        });
};


const lessonVideoUrl = videoUrl => `http://localhost:8000/storage/${videoUrl}`;
// const thumbnailUrl = computed(() => `http://localhost:8000/storage/${courseData.value.thumbnail}`);
const thumbnailUrl = computed(() => `http://localhost:8000/storage/${courseData.value.thumbnail}`);
function redirectToPurchase() {
    window.location.href = '/subscribe-or-purchase';
}

onUnmounted(() => {
    stopLessonTimer();
});

onMounted(fetchCoursesData);

</script>
<style>
.aspect-ratio-box {
    position: relative;
    width: 100%;           /* Full width */
    padding-top: 56.25%;    /* 16:9 aspect ratio (9/16 * 100 = 56.25) */
    overflow: hidden;       /* Hide anything that overflows the box */
}

.thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;      /* Ensures the image covers the box without distortion */
}


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
