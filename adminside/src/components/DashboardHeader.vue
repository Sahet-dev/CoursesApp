<template>
    <header class="bg-gray-800 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">

            <router-link
                to="/where-am-i"
                 >
                <h1 class="text-lg font-semibold">Dashboard</h1>

            </router-link>
            <span class="text-red-500 text-center">You have {{ user.role}} role</span>

            <button @click="$emit('logout')" class="bg-red-500 px-4 py-2 rounded">
                Logout
            </button>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../api/axios';
import { useRouter } from 'vue-router';
import Lenis from "lenis";

const user = ref({});
const errorMessage = ref('');

const router = useRouter();

const fetchUser = async () => {
    try {
        const response = await apiClient.get('/user');
        user.value = response.data.data;
    } catch (error) {
        if (error.response?.status === 401) {
            // User is unauthorized, redirect to login page
            router.push('/login');
        } else {
            console.error('Failed to fetch user data:', error);
            errorMessage.value = 'Failed to fetch user data.';
        }
    }
};

const lenis = new Lenis()

lenis.on('scroll', (e) => {
    // console.log(e)
})

function raf(time) {
    lenis.raf(time)
    requestAnimationFrame(raf)
}

requestAnimationFrame(raf)

onMounted(() => {

    fetchUser()

});


</script>

<style scoped>
/* Header styles */
</style>
