<script setup>
import {onMounted, ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import {Inertia} from "@inertiajs/inertia";
import {lenis} from "@/utils/animations.js";

const showingNavigationDropdown = ref(false);



const searchQuery = ref('');

// Search function
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

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center ">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('main-page')">
                                    <div class="flex items-center">
                                        <ApplicationLogo
                                            class="block h-9 w-auto fill-current text-gray-800"
                                        />
                                        <span class="hidden md:block ml-2">TmCourses</span>
                                    </div>

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
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                            </div>

                            <button
                                @click="redirectToNotifications"
                                class="relative inline-flex items-center ml-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                            >
                                <!-- Replace with any SVG or icon you want -->
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 64 64"
                                    class="w-6 h-6 text-gray-500"
                                    fill="currentColor"
                                >
                                    <!-- Example SVG path -->
                                    <path d="M32 2c1.104 0 2 0.896 2 2 0 0.04-0.014 0.075-0.017 0.115C33.332 4.043 32.671 4 32 4s-1.332 0.043-1.983 0.115C30.014 4.075 30 4.04 30 4 30 2.896 30.896 2 32 2z M16 22c0-8.837 7.163-16 16-16s16 7.163 16 16v12H16V22z M16 36h32v4H16V36z M32 62c-3.313 0-6-2.687-6-6h12C38 59.313 35.313 62 32 62z M56 54H8c-1.104 0-2-0.896-2-2s0.896-2 2-2c4.418 0 8-3.582 8-8h32c0 4.418 3.582 8 8 8c1.104 0 2 0.896 2 2S57.104 54 56 54z"/>
                                </svg>

                                <span class="hidden sm:inline-block ms-2">Notifications</span></button>

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
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ 'dropdown-menu open': showingNavigationDropdown, 'dropdown-menu': !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" >
                            Dashboard
                        </ResponsiveNavLink>

                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('notifications')" >
                                Notifications
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('notifications')" method="get" as="button">
                                Updates
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('notifications')" method="get" as="button">
                                Baha
                            </ResponsiveNavLink>

                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>

                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
<style>
.dropdown-menu {
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    max-height: 0;
}

.dropdown-menu.open {
    max-height: 500px;
}
</style>
