<template>
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
                                 <Activity  />
                            </TabPanel>
                        </transition>

                        <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                            <TabPanel key="account" class="p-3 ">
                                <Account />
                            </TabPanel>
                        </transition>

                        <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                            <TabPanel key="bookmarks" class="p-3 ">
                               <Bookmarks />
                            </TabPanel>
                        </transition>

                        <transition name="tab" @before-enter="beforeEnter" @enter="enter" @leave="leave">
                            <TabPanel key="completed" class="p-3 ">
                                <Completed />
                            </TabPanel>
                        </transition>

                    </TabPanels>
        </TabGroup>
    </div>
</template>

<script setup>
import {defineProps, ref} from 'vue';
import { UserIcon, BookmarkIcon, CheckBadgeIcon, PresentationChartLineIcon } from "@heroicons/vue/24/outline";
import { UserIcon as UserIconSolid, BookmarkIcon as BookmarkIconSolid, CheckBadgeIcon as CheckBadgeIconSolid, PresentationChartLineIcon as PresentationChartLineIconSolid } from "@heroicons/vue/24/solid";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';

import {Inertia} from "@inertiajs/inertia";
import Bookmarks from "./HomeComponents/Bookmarks.vue";
import Account from "./HomeComponents/Account.vue";
import Activity from "./HomeComponents/Activity .vue";
import Completed from "./HomeComponents/Completed.vue";


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
