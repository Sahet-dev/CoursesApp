<template>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="relative shrink-0 flex items-center mr-2">
                        <router-link to="/" class="flex items-center">
                            <img :src="image" alt="Application Logo" class="w-16 h-16 items-center">
                            <span class="hidden md:block ml-2">TmCourses</span>
                        </router-link>
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

                <div class="hidden sm:flex sm:items-center sm:ml-6">



                    <!-- Prices Button -->
                    <div class="relative hidden sm:flex sm:items-center sm:ml-6 mr-2">
                        <button
                            @click="openPrices"
                            class="relative inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            Prices
                        </button>
                    </div>
                    <div class="relative hidden sm:flex sm:items-center sm:ml-6 mr-2">
                        <button
                            @click="goToReviews"
                            class="relative inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            Reviews
                        </button>
                    </div>


                    <!-- Authentication Links -->
                    <template v-if="!user">
                        <router-link to="/login" class="text-gray-200 border-b border-gray-200 bg-blue-500 p-2 rounded hover:text-gray-100 hover:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                            Login
                        </router-link>
                        <router-link to="/register" class="ml-4 text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            Register
                        </router-link>
                    </template>
                    <template v-else>
                        <div class=" ">
                            <Menu as="div" class="relative inline-block text-left">
                                <div>
                                    <MenuButton
                                        class="inline-flex w-full justify-center rounded-md  px-4 py-2 text-sm font-medium  hover:bg-black/10 focus:outline-none"
                                    >
                                        <UserIconSolid class="w-5 h-5 mr-2" />
                                        {{user.name}}

                                        <ChevronDownIcon
                                            class="-mr-1 ml-2 h-5 w-5 text-violet-200 hover:text-violet-100"
                                            aria-hidden="true"
                                        />
                                    </MenuButton>
                                </div>

                                <transition
                                    enter-active-class="transition duration-100 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-75 ease-in"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <MenuItems
                                        class="absolute right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                                    >
                                        <div class="px-1 py-1">
                                            <MenuItem v-slot="{ active }">
                                                <button @click="goToProfile(user.id)"
                                                    :class="[
                                                      active ? 'bg-blue-500 text-white' : 'text-gray-900',
                                                      'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                >
                                                    <Bars3Icon
                                                        :active="active"
                                                        class="mr-2 h-5 w-5 text-blue-400"
                                                        aria-hidden="true"
                                                    />
                                                    Profile
                                                </button>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                                <button  @click="handleLogout"
                                                    :class="[
                                                      active ? 'bg-blue-500 text-white' : 'text-gray-900',
                                                      'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                                    ]"
                                                >
                                                    <ArrowLeftStartOnRectangleIcon
                                                    :active="active"
                                                        class="mr-2 h-5 w-5 text-blue-400"
                                                        aria-hidden="true"
                                                    />
                                                    Logout
                                                </button>
                                            </MenuItem>
                                        </div>

                                    </MenuItems>
                                </transition>
                            </Menu>

                        </div>
                    </template>
                </div>

                <!-- Hamburger Menu -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    >
                        <svg class="h-6 w-6 transition-all" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
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

        <!-- Mobile Dropdown Menu -->
        <div
            :class="{ 'dropdown-menu open': showingNavigationDropdown, 'dropdown-menu': !showingNavigationDropdown }"
            class="sm:hidden"
        >
            <div class="pt-2 pb-3 space-y-1" v-if= "!user">
                <!-- Authentication Links -->
                <router-link to="/login" class="block px-4 py-2 text-sm">Login ssss</router-link>
                <router-link to="/register" class="block px-4 py-2 text-sm">Register</router-link>
            </div>

            <div class="pt-2 pb-3 space-y-1" v-else>
                <!-- Authentication Links -->
                <button class="block px-4 py-2 text-sm" @click="goToProfile(user.id)">Profile</button>
                <router-link to="/login" class="block px-4 py-2 text-sm">Profisssssle</router-link>
                <router-link to="/profile/:id" class="block px-4 py-2 text-sm">Profile</router-link>
                <router-link to="/register" class="block px-4 py-2 text-sm">Register</router-link>
                <router-link to="/register" class="block px-4 py-2 text-sm">Register</router-link>
            </div>
        </div>
    </nav>
</template>
<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from "../axios/index.js";
import { UserCircleIcon as UserIconSolid } from "@heroicons/vue/24/solid";
import { ArrowLeftStartOnRectangleIcon, Bars3Icon } from "@heroicons/vue/24/outline";
import image from "../assets/IconTm.png";
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';

const router = useRouter();
const showingNavigationDropdown = ref(false);
const searchQuery = ref('');
const user = ref(null);
const isOpen = ref(false);

const fetchUser = async () => {
    try {
        const response = await apiClient.get('/user');
        user.value = response.data ? response.data.data : null;
    } catch (error) {
        if (error.response && error.response.status === 401) {
            user.value = null;
        } else {
            console.error('Error fetching user:', error);
        }
    }
};

const goToProfile = (userId) => {
    router.push({ name: 'UserProfile', params: { id: userId } });
};

const goToFeedback = () => {
    router.push({ name: 'Feedback' });
};

const goToReviews = () => {
    router.push({ name: 'Reviews' });
};

const openDropdown = () => {
    isOpen.value = true;
};

const closeDropdown = () => {
    isOpen.value = false;
};

const searchCourses = async () => {
    if (searchQuery.value.trim()) {
        await router.push({ name: 'CourseCatalog', query: { search: searchQuery.value } });
    }
};

const handleLogout = async () => {
    try {
        const response = await apiClient.post('/logout');
        console.log('Logout response:', response.data);
        localStorage.removeItem('token');
        window.location.reload();
    } catch (error) {
        console.error('Failed to logout:', error);
    }
};

const openPrices = () => {
    router.push({ name: 'Prices' });
};

onMounted(() => {
    fetchUser();
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
</style>
