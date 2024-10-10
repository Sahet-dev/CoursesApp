<script setup>

import { ref } from 'vue';
import {  CameraIcon, XMarkIcon, CheckCircleIcon } from "@heroicons/vue/24/outline";
import apiClient from "../../axios/index.js";

import imageSrc from '../../assets/pngwing.com.png'
import defaultAvatar from '../../assets/avatar_default.png'
import Navbar from "../Navbar.vue";
import Footer from "../Footer.vue";

const imagesForm = ref({
    avatar: null,
    cover: null,
});
const user = ref({});
const avatarImageSrc = ref('');

const form = ref({
    name: '',

    first_name: '',
    lastname: '',
    gender: '',
    age: '',
    location: '',
    avatar: null,
});



const fetchUserData = async () => {
    try {
        const response = await apiClient.get('/get-profile-information');

        // Check if response.data contains the user information
        if (!response.data) {
            console.log('User is unauthenticated');
            user.value = null;

        } else {
            user.value = response.data;

            form.value = {
                name: user.value.name || '',
                first_name: user.value.first_name || '',
                lastname: user.value.lastname || '',
                gender: user.value.gender || '',
                age: user.value.age || '',
                location: user.value.location || '',
                avatar: user.value.avatar || null,
            };
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

fetchUserData();

function onAvatarChange(event) {
    console.log('File input changed:', event.target.files);
    imagesForm.value.avatar = event.target.files[0];
    if (imagesForm.value.avatar) {
        const reader = new FileReader();
        reader.onload = () => {
            avatarImageSrc.value = reader.result;
            console.log('Image preview URL:', avatarImageSrc.value);
        };
        reader.readAsDataURL(imagesForm.value.avatar);
    }
}


function cancelAvatarImg() {
    imagesForm.value.avatar = null;
    avatarImageSrc.value = null;
}

async function submitAvatarImg() {
    const formData = new FormData();
    formData.append('avatar', imagesForm.value.avatar);

    try {
        const response = await apiClient.post('/profile/updateImages', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        alert('Avatar uploaded successfully!');
    } catch (error) {
        console.error('Error uploading avatar:', error);
        alert('Failed to upload avatar. Please try again.');
    }
}

async function submitProfile() {
    try {
        await apiClient.patch('/profile/update', form.value);
        alert('Profile updated successfully!');
    } catch (error) {
        console.error('Error updating profile:', error);
        alert('Failed to update profile. Please try again.');
    }
}

</script>

<template>
<Navbar />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="group relative bg-white">
                    <img :src="imageSrc" class="w-full h-[200px] object-cover">

                    <div class="flex">
                        <div class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full overflow-hidden">
                            <img :src="`http://localhost:8000/storage/${user.avatar}`  || (user.avatar ? `/http://localhost:8000/storage/${user.avatar}` : defaultAvatar)"
                                 class="w-full h-full object-cover">
                            <button v-if="!avatarImageSrc"
                                    class="absolute bg-white/25 rounded-full flex items-center justify-center opacity-0 group-hover/avatar:opacity-100">
                                <CameraIcon class="w-8 h-8"/>

                                <input type="file" class="absolute left-0 bottom-0 right-0 opacity-0 cursor-pointer"
                                       @change="onAvatarChange">
                            </button>
                            <div v-else class="absolute items-center justify-center flex l gap-2">
                                <button @click="cancelAvatarImg" class="w-7 h-7 flex items-center justify-center bg-red-500/80
                                text-white rounded-full">
                                    <XMarkIcon class="w-5 h-5 "/>

                                </button>
                                <button @click="submitAvatarImg" class="w-7 h-7 flex items-center justify-center bg-emerald-500/80
                                text-white rounded-full">
                                    <CheckCircleIcon class="w-5 h-5"/>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 mt-4">Profile Information</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Update your account's profile information and email address.
                        </p>
                    </header>

                    <form @submit.prevent="submitProfile" class="mt-6 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <p v-if="form.errors?.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
                        </div>

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input id="first_name" type="text" v-model="form.first_name" autocomplete="first_name"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <p v-if="form.errors?.first_name" class="mt-2 text-sm text-red-600">{{ form.errors.first_name }}</p>
                        </div>

                        <div>
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input id="lastname" type="text" v-model="form.lastname" autocomplete="lastname"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <p v-if="form.errors?.lastname" class="mt-2 text-sm text-red-600">{{ form.errors.lastname }}</p>
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="gender" v-model="form.gender"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="" disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <p v-if="form.errors?.gender" class="mt-2 text-sm text-red-600">{{ form.errors.gender }}</p>
                        </div>

                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                            <input id="age" type="number" v-model="form.age" min="0" autocomplete="age"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <p v-if="form.errors?.age" class="mt-2 text-sm text-red-600">{{ form.errors.age }}</p>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input id="location" type="text" v-model="form.location" autocomplete="location"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <p v-if="form.errors?.location" class="mt-2 text-sm text-red-600">{{ form.errors.location }}</p>
                        </div>



                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save
                            </button>
                        </div>
                    </form>



                </section>
            </div>




        </div>
    </div>

    <Footer />
</template>
