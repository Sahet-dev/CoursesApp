
<template>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="relative shrink-0 flex items-center mr-2">
                        <Link :href="route('main-page')" class="flex items-center"> <!-- Flex container for logo and text -->
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                            <span class="hidden md:block ml-2">TmCourses</span> <!-- Text beside the logo -->
                        </Link>
                    </div>



                    <!-- Search Input -->
                    <div class="flex-1 sm:ml-6 flex items-center justify-center">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search courses..."
                            class="p-2 border rounded w-full sm:w-1/2"
                        />
                        <button
                            @click="searchCourses"
                            :disabled="!searchQuery.trim()"
                            class="ml-2 p-2 border rounded bg-blue-500 text-white hover:bg-blue-600"
                        >
                            Search
                        </button>
                    </div>
                </div>






                <div class="hidden sm:flex sm:items-center sm:ml-6 ">
                    <!-- Notifications Button -->

                    <!-- Updates Button -->
                    <div  class="relative  hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Trigger Button -->
                        <button
                            @click="redirectToNotifications"
                            class="relative inline-flex items-center  border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            <!-- Replace with any SVG or icon you want -->
                            Updates

                        </button>

                    </div>


                    <!-- Prices Button -->
                    <div  class="relative  hidden sm:flex sm:items-center sm:ml-6 mr-2">
                        <!-- Trigger Button -->
                        <button
                            @click="redirectToNotifications"
                            class="relative inline-flex items-center   border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            <!-- Replace with any SVG or icon you want -->
                            Prices

                        </button>

                    </div>




<!--                        <div v-if="!$page.props.auth" class="flex space-x-4 items-center justify-center">-->
                            <Link :href="route('login')" class="text-gray-200  border-b border-gray-200 bg-blue-500 p-2 rounded hover:text-gray-100 hover:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                                Login
                            </Link>

                            <Link :href="route('register')" class="ml-4 text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                Register
                            </Link>
<!--                        </div>-->
<!--                        <div v-else>-->

<!--                            <div   class="text-gray-700   focus:outline-none transition ease-in-out duration-150">-->
<!--                                {{$page.props.auth.user.name}}-->

<!--                            </div>-->
<!--                        </div>-->

                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    >
                        <svg class="h-6 w-6 transition-all" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>



        <div
            :class="{ 'dropdown-menu open': showingNavigationDropdown, 'dropdown-menu': !showingNavigationDropdown }"
            class="sm:hidden"
        >
            <div class="pt-2 pb-3 space-y-1">
                <!-- Conditional rendering for login/register or user dropdown -->



            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                 <ResponsiveNavLink :href="route('login')"> Login </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('register')"> Register </ResponsiveNavLink>

            </div>
        </div>
    </nav>
</template>

<script setup>
import { onMounted, ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link, usePage} from '@inertiajs/vue3';
import {   lenis } from '@/utils/animations';
import {Inertia} from "@inertiajs/inertia";











const showingNavigationDropdown = ref(false);



const searchQuery = ref('');

const searchCourses = () => {
    Inertia.get(route('courses.search'), { search: searchQuery.value }, {
        preserveState: true,
        replace: true,
    });
};



onMounted(() => {


    lenis.on('scroll', (e) => {
        // console.log('Scrolled:', e);
    });
});
</script>

<style>
.dropdown-menu {
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    max-height: 0;
}

.dropdown-menu.open {
    max-height: 500px;
}

.hamburger-line {
    width: 24px;
    height: 3px;
    background-color: #4a5568; /* Tailwind's 'gray-600' color */
    transition: all 0.5s ease-in-out;
}
</style>
