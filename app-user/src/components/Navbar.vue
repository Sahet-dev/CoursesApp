<template>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="relative shrink-0 flex items-center mr-2">
                        <router-link to="/" class="flex items-center">
<!--                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />-->
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
                    <!-- Notifications Button -->
                    <div class="relative hidden sm:flex sm:items-center sm:ml-6">
                        <button
                            @click="redirectTo('notifications')"
                            class="relative inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            Updates
                        </button>
                    </div>

                    <!-- Prices Button -->
                    <div class="relative hidden sm:flex sm:items-center sm:ml-6 mr-2">
                        <button
                            @click="redirectTo('prices')"
                            class="relative inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            Prices
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
                        <div class="text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{ user.name }}
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
            <div class="pt-2 pb-3 space-y-1">
                <!-- Authentication Links -->
                <router-link to="/login" class="block px-4 py-2 text-sm">Login</router-link>
                <router-link to="/register" class="block px-4 py-2 text-sm">Register</router-link>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const showingNavigationDropdown = ref(false);
const searchQuery = ref('');
const user = ref(null);

// Fetch user info on mount
const fetchUser = async () => {
    try {
        const response = await axios.get('/api/user');
        user.value = response.data;
    } catch (error) {
        console.error('Error fetching user:', error);
    }
};

// Search function
const searchCourses = async () => {
    try {
        await axios.get('/api/courses/search', { params: { search: searchQuery.value } });
        // Handle search result, e.g., redirect or update state
    } catch (error) {
        console.error('Error searching courses:', error);
    }
};

// Redirect function
const redirectTo = (path) => {
    router.push({ name: path });
};

// Handle mounting
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
