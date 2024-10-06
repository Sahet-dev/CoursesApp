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
                    <!-- Notifications Button -->
                    <div class="relative hidden sm:flex sm:items-center sm:ml-6">
                        <button
                            @click="goToFeedback"
                            class="relative inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            Feedback
                        </button>
                    </div>

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
                        <div class="text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{ user.name }}
                        </div>
                        <div class="relative hidden sm:flex sm:items-center sm:ml-6 mr-2">
                            <button
                                @click="$emit('logout')"
                                class="relative inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                            >
                                Logout
                            </button>
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
import ApplicationLogo from "./icon/ApplicationLogo.vue";
import image from "../assets/IconTm.png";

const router = useRouter();
const showingNavigationDropdown = ref(false);
const searchQuery = ref('');
const user = ref(null);

// Fetch user info on mount
const fetchUser = async () => {
    try {
        const response = await apiClient.get('/user');

        // Check if response.data contains the user information
        if (!response.data) {
            console.log('User is unauthenticated');
            user.value = null; // Set user to null when unauthenticated
        } else {
            user.value = response.data; // Set the user value if data exists
        }

    } catch (error) {
        if (error.response && error.response.status === 401) {
            // Handle the case where the user is unauthenticated (401 error)
            console.log('User is unauthenticated');
            user.value = null;
        } else {
            // Handle other errors (server error, network error, etc.)
            console.error('Error fetching user:', error);
        }
    }
};
function goToProfile(userId){
    router.push({ name: 'UserProfile', params: {id: userId}});
}

function goToFeedback(){
    router.push({ name: 'Feedback'});
}

function goToReviews(){
    router.push({ name: 'Reviews' });
}




// Search function
const searchCourses = async () => {
    if (searchQuery.value.trim()) {
        router.push({ name: 'CourseCatalog', query: { search: searchQuery.value } });
    }
};


const handleLogout = async () => {
    try {
        const response = await apiClient.post('/logout');
        console.log('Logout response:', response.data);
        localStorage.removeItem('token'); // Clear token
        await router.push('/');
    } catch (error) {
        console.error('Failed to logout:', error);
        errorMessage.value = error.response?.data?.message || 'Failed to logout.';
    }
};


// Redirect function
const openPrices = () => {
    router.push({ name: 'Prices' });
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
