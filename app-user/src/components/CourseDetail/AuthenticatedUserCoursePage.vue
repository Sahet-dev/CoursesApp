<template>
    <div>
            <div class="min-h-screen bg-gradient-to-r from-gray-100 via-pink-50 to-blue-50 ">
                <TabGroup>
                    <TabList class="flex items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-r outline-none font-bold
                from-blue-200 via-purple-100 to-pink-200 shadow-md orange">
                        <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none
                    focus:ring-2 px-3">
                            <template v-if="selected">
                                <PresentationChartLineIconSolid class="w-5 h-5 selected-tab outline-none" />
                            </template>
                            <template v-else>
                                <PresentationChartLineIcon class="w-5 h-5 orange" />
                            </template>
                            <span class="hidden md:block ml-2" :class="[selected ? 'selected-tab' : 'orange']">Activity</span>
                        </Tab>
                        <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none
                    focus:ring-2 px-3">
                            <template v-if="selected">
                                <UserIconSolid class="w-5 h-5 selected-tab outline-none" />
                            </template>
                            <template v-else>
                                <UserIcon class="w-5 h-5 orange" />
                            </template>
                            <span class="hidden md:block ml-2" :class="[selected ? 'selected-tab' : 'orange']">Account</span>
                        </Tab>
                        <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none
                    focus:ring-2 px-3">
                            <template v-if="selected">
                                <BookmarkIconSolid class="w-5 h-5 selected-tab outline-none" />
                            </template>
                            <template v-else>
                                <BookmarkIcon class="w-5 h-5 orange" />
                            </template>
                            <span class="hidden md:block ml-2" :class="[selected ? 'selected-tab' : 'orange']">Bookmarks</span>
                        </Tab>
                        <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none
                    focus:ring-2 px-3">
                            <template v-if="selected">
                                <CheckBadgeIconSolid class="w-5 h-5 selected-tab outline-none" />
                            </template>
                            <template v-else>
                                <CheckBadgeIcon class="w-5 h-5 orange" />
                            </template>
                            <span class="hidden md:block ml-2" :class="[selected ? 'selected-tab' : 'orange']">Completed</span>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">

                        <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                            <TabPanel key="activity" class="p-3  ">
                                <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                                    <!-- Activities Section -->
                                    <div v-if="activities.length" class="bg-white   rounded-lg p-4">
                                        <h2 class="text-2xl font-bold mb-6 text-gray-800">Activities</h2>
                                        <ul class="space-y-4">
                                            <li v-for="activity in activities" :key="activity.id" class="flex items-start space-x-4 border-b pb-4 last:border-b-0">
                                                <!-- Icon Container -->
                                                <div class="w-14 h-14 flex items-center justify-center bg-blue-50 rounded-full shadow-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </div>
                                                <!-- Activity Details -->
                                                <div class="flex-1">
                                                    <p class="text-gray-800 text-lg font-medium">{{ activity.action }}</p>
                                                    <p class="text-gray-500 text-sm mt-1">{{ activity.date }}</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-else class="bg-white shadow-lg rounded-lg p-6 text-center">
                                        <p class="text-gray-500 text-lg">No activities found</p>
                                    </div>
                                </div>


                                <!-- Popular Courses Section -->
                                <section class="animated-element py-12 bg-gray-50">
                                    <div class="container mx-auto px-4">
                                        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Popular Courses</h2>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                                            <!-- Replaced Link with a div and added @click -->
                                            <div
                                                v-for="course in popularCourses"
                                                :key="course.id"
                                                @click="getCourse(course.id)"
                                                class="bg-white shadow-lg rounded-lg overflow-hidden cursor-pointer block hover:shadow-2xl transition-shadow duration-300"
                                            >
                                                <img :src="course.thumbnail" :alt="course.title" class="w-full h-48 object-cover">
                                                <div class="p-6">
                                                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ course.title }}</h3>
                                                    <p class="text-gray-600 mb-4">{{ course.description }}</p>
                                                    <p class="text-lg font-medium text-gray-900">${{ course.price }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-8">
                                            <button class="px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition duration-300">
                                                See All Courses
                                            </button>
                                        </div>
                                    </div>
                                </section>







                            </TabPanel>
                        </transition>

                        <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                            <TabPanel key="account" class="p-3 ">
                                <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                                    <!-- Flex container with responsive adjustments -->
                                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
                                        <!-- Avatar Section -->
                                        <div class="flex-shrink-0">
                                            <img
                                                :src="user.avatarUrl"
                                                alt="User Avatar"
                                                class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border-4 border-gray-200 shadow-lg"
                                            />
                                        </div>

                                        <!-- User Information Section -->
                                        <div class="flex-1">
                                            <h2 class="text-xl md:text-2xl font-bold mb-4 text-gray-800">User Profile</h2>
                                            <div class="space-y-4">
                                                <!-- Name -->
                                                <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                                                    <span class="font-semibold text-gray-700 md:w-32">Name:</span>
                                                    <p class="text-gray-600">{{ user.name }}</p>
                                                </div>

                                                <!-- Email -->
                                                <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                                                    <span class="font-semibold text-gray-700 md:w-32">Email:</span>
                                                    <p class="text-gray-600">{{ user.email }}</p>
                                                </div>

                                                <!-- Plan -->
                                                <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                                                    <span class="font-semibold text-gray-700 md:w-32">Plan:</span>
                                                    <!-- Check if subscriptions array exists and is not empty -->
                                                    <p class="text-gray-600">
                                                        {{ user.subscriptions?.length > 0 ? user.subscriptions[0].plan : 'No active subscription' }}
                                                    </p>
                                                </div>

                                                <!-- Expires At -->
                                                <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-2">
                                                    <span class="font-semibold text-gray-700 md:w-32">Expires At:</span>
                                                    <!-- Check if subscriptions array exists and is not empty -->
                                                    <p class="text-gray-600">
                                                        {{ user.subscriptions?.length > 0 ? user.subscriptions[0].ends_at : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </TabPanel>

                        </transition>
                        <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                            <TabPanel key="bookmarks" class="p-3 ">
                                <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                                    <!-- Check if there are bookmarked items -->
                                    <div v-if="bookmarkedItems.length" class="space-y-4">
                                        <ul class="space-y-2">
                                            <li v-for="item in bookmarkedItems" :key="item.id" class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition duration-200">
                                                <!-- Bookmark Details -->
                                                <div class="flex items-start sm:items-center space-x-4 w-full sm:w-auto">
                                                    <!-- Bookmark Icon -->
                                                    <svg class="w-8 h-8 text-blue-500 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3a2 2 0 00-2 2v16l7-4 7 4V5a2 2 0 00-2-2H5z"></path>
                                                    </svg>
                                                    <!-- Bookmark Details -->
                                                    <div class="flex-1">
                                                        <p class="font-semibold text-gray-800 text-lg">{{ item.title }}</p>
                                                        <p class="text-gray-600 text-sm">{{ item.type }} - Bookmarked At: {{ item.bookmarked_at }}</p>
                                                    </div>
                                                </div>
                                                <!-- Remove Button -->
                                                <button
                                                    @click="removeBookmark(item.id)"
                                                    class="mt-3 sm:mt-0 px-4 py-2 bg-amber-400 text-white rounded-md hover:bg-amber-500 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200"
                                                >
                                                    Remove
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- Display message when no bookmarks are found -->
                                    <div v-else class="text-center text-gray-500">
                                        No bookmarks found
                                    </div>
                                </div>
                            </TabPanel>

                        </transition>

                        <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                            <TabPanel key="completed" class="p-3 ">
                                <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                                    <!-- Check if there are completed courses -->
                                    <div v-if="completedCourses.length" class="space-y-4">
                                        <ul class="space-y-2">
                                            <li v-for="course in completedCourses" :key="course.id" class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition duration-200">
                                                <!-- Course Details -->
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-800 text-lg">{{ course.title }}</p>
                                                    <p class="text-gray-600 text-sm">Completed At: {{ course.completed_at }}</p>
                                                    <p class="text-gray-600 text-sm">Rating:
                                                        <span class="font-medium text-yellow-500">
                                                    {{ course.rating }}
                                                    <svg class="inline-block w-4 h-4 text-yellow-500 ml-1" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                      <path d="M12 17.27L18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27Z"></path>
                                                    </svg>
                                                  </span>
                                                    </p>
                                                </div>

                                            </li>
                                        </ul>
                                    </div>
                                    <!-- Display message when no completed courses are found -->
                                    <div v-else class="text-center text-gray-500">
                                        No completed courses found
                                    </div>
                                </div>
                            </TabPanel>
                        </transition>

                    </TabPanels>
                </TabGroup>
            </div>

    </div>
</template>

<script setup>
import {defineProps, ref} from 'vue';
import { UserIcon, BookmarkIcon, CheckBadgeIcon, PresentationChartLineIcon } from "@heroicons/vue/24/outline";
import { UserIcon as UserIconSolid, BookmarkIcon as BookmarkIconSolid, CheckBadgeIcon as CheckBadgeIconSolid, PresentationChartLineIcon as PresentationChartLineIconSolid } from "@heroicons/vue/24/solid";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from '@inertiajs/vue3';
import {Inertia} from "@inertiajs/inertia";


// Define the props
const props = defineProps({
    user: {
        type: Object,
        default: () => ({ name: '', email: '', avatarUrl: '', subscriptions: [] }), // Properly initialize with default structure
    },

    popularCourses: Array,
    latestCourses: Array,
    // accountDetails: Object,
    // completedCourses: Array,
    // bookmarkedItems: Array,
    // activities: Array,
});


const getCourse = (id) => {
    Inertia.get(route('courseDetail', { id }), {

    });
};

console.log(props.popularCourses)
console.log(props.latestCourses)
// Dummy data for accountDetails
const accountDetails = ref({
    name: 'John Doe',
    email: 'john.doe@example.com',
    avatarUrl: 'https://example.com/avatar.jpg',
    subscriptions: [
        {
            plan: 'Premium',
            ends_at: '2024-12-31',
        },
    ],
});

// Dummy data for completedCourses
const completedCourses = ref([
    {
        id: 1,
        title: 'Introduction to Vue.js',
        completed_at: '2024-08-01',
        rating: 4.5,
    },
    {
        id: 2,
        title: 'Advanced JavaScript',
        completed_at: '2024-07-15',
        rating: 4.8,
    },
]);

// Dummy data for bookmarkedItems
const bookmarkedItems = ref([
    {
        id: 1,
        title: 'Vue.js Essentials',
        type: 'Article',
        bookmarked_at: '2024-08-05',
    },
    {
        id: 2,
        title: 'Tailwind CSS Guide',
        type: 'Video',
        bookmarked_at: '2024-07-20',
    },
]);

// Dummy data for activities
const activities = ref([
    {
        id: 1,
        action: 'Completed a course',
        date: '2024-08-01',
    },
    {
        id: 2,
        action: 'Bookmarked an article',
        date: '2024-08-05',
    },
]);

console.log(props.user)
function removeBookmark(id) {
    console.log('Remove bookmark with id:', id);
}
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

<style scoped>
.orange {
    font-weight: bold;
    color: #e49e58;
}
.selected-tab {
    color: #5daeec;
    text-decoration: none; /* Remove text underline */
    padding-bottom: 2px;
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
