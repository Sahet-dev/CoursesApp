<template>
    <div>
        <Navbar @logout="handleLogout"/>

        <div  v-if="loading" class="h-screen flex justify-center items-center min-h-screen bg-gradient-to-r from-gray-100 via-pink-50 to-blue-50">
            <Loader />
        </div>
        <div v-else>
            <div v-if="user === null">
                <UnAuthenticatedMainPage />

            </div>
            <div v-else>
                <Home />
            </div>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import '../Main/css/mainPage.css'
import {onMounted, ref} from 'vue';
import apiClient from "../../axios/index.js";
import Footer from "../Footer.vue";
import Navbar from "../Navbar.vue";
import Home from "../Home.vue";
import Loader from "../CourseDetail/Loader.vue";
import UnAuthenticatedMainPage from "./UnAuthenticatedMainPage.vue";
import {useRouter} from "vue-router";

const user = ref(null);
const loading = ref(true);
const errorMessage = ref('');
const router = useRouter();
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
            console.log('User is unauthenticated', error);
            // user.value = null;
        } else {
            // Handle other errors (server error, network error, etc.)
            console.error('Error fetching user:', error);
        }
    }finally {
        loading.value = false;
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
        errorMessage.value = error.response?.data?.message || 'Failed to logout.';
    }
};


onMounted(() => {
    fetchUser()
});


</script>
