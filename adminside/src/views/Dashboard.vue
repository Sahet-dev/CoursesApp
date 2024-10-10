<template>
    <div>
        <DashboardHeader @logout="handleLogout" />

        <div class="flex">
            <main class="flex-1">


                <!-- Render dashboard components based on user role -->
                <div v-if="user?.role">
                    <AdminDashboard v-if="user.role === 'admin'" />
                    <TeacherDashboard v-else-if="user.role === 'teacher'" />
                    <ModeratorDashboard v-else-if="user.role === 'moderator'" />

                    <div v-else class="text-red-500">Unauthorized access</div>
                </div>


                <!-- Error message -->
                <div class="text-red-500" v-if="errorMessage">{{ errorMessage }}</div>
            </main>
        </div>


    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../api/axios';
import DashboardHeader from "../components/DashboardHeader.vue";
import AdminDashboard from '../components/Course/admin/AdminDashboard.vue';
import ModeratorDashboard from '../components/Course/moderator/ModeratorDashboard.vue';
import TeacherDashboard from "../components/Course/teacher/TeacherDashboard.vue";
import Footer from "./components/Footer.vue";
import { useRouter } from 'vue-router';

const user = ref(null);
const errorMessage = ref('');
const router = useRouter();

const fetchUser = async () => {
    try {
        const response = await apiClient.get('/user');
        user.value = response.data.data;
        console.log('Fetched user data:', user.value);
    } catch (error) {
        if (error.response?.status === 401) {
            router.push('/login');
        } else {
            console.error('Failed to fetch user data:', error);
            errorMessage.value = 'Failed to fetch user data.';
        }
    }
};

const handleLogout = async () => {
    try {
        const response = await apiClient.post('/logout');
        localStorage.removeItem('token');
        router.push('/login');
    } catch (error) {
        console.error('Failed to logout:', error);
        errorMessage.value = error.response?.data?.message || 'Failed to logout.';
    }
};

onMounted(fetchUser);
</script>

<style>

</style>


