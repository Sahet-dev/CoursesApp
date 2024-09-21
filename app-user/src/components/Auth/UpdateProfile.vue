<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import axios from 'axios';
import { UserCircleIcon, CameraIcon, XMarkIcon, CheckCircleIcon } from "@heroicons/vue/24/outline";

const imagesForm = ref({
    avatar: null,
    cover: null,
});
const user = ref({});
const avatarImageSrc = ref('');

const form = ref({
    name: '',
    email: '',
    first_name: '',
    lastname: '',
    gender: '',
    age: '',
    location: '',
    avatar: null,
});

const imageSrc = ref('/storage/avatars/pngwing.com.png');

// Fetch user data
async function fetchUserData() {
    const response = await axios.get('/api/user'); // Update with your API endpoint
    user.value = response.data;
    form.value = { ...form.value, ...user.value }; // Initialize form with user data
}
fetchUserData();

function onAvatarChange(event) {
    imagesForm.value.avatar = event.target.files[0];
    if (imagesForm.value.avatar) {
        const reader = new FileReader();
        reader.onload = () => {
            avatarImageSrc.value = reader.result;
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
        const response = await axios.post('/api/profile/updateImages', formData, {
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
        await axios.patch('/api/profile/update', form.value);
        alert('Profile updated successfully!');
    } catch (error) {
        console.error('Error updating profile:', error);
        alert('Failed to update profile. Please try again.');
    }
}

</script>

<template>
    <div class="group relative bg-white">
        <img :src="imageSrc" class="w-full h-[200px] object-cover">
        <div class="absolute top-2 right-2">
            <button class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center">
                <input type="file" class="absolute left-0 bottom-0 right-0 opacity-0 cursor-pointer" @change="onAvatarChange">
                <CameraIcon class="w-8 h-8"/>
            </button>
        </div>
        <div class="flex">
            <div class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full overflow-hidden">
                <img :src="avatarImageSrc || (user.avatar ? `/storage/${user.avatar}` : '/images/avatar_default.png')" class="w-full h-full object-cover">
                <button v-if="!avatarImageSrc" class="absolute bg-white/25 rounded-full flex items-center justify-center opacity-100 group-hover/avatar:opacity-100">
                    <CameraIcon class="w-8 h-8"/>
                </button>
                <div v-else class="absolute items-center justify-center flex gap-2">
                    <button @click="cancelAvatarImg" class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                        <XMarkIcon class="w-5 h-5"/>
                    </button>
                    <button @click="submitAvatarImg" class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
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
                <InputLabel for="name" value="Name" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name"/>
                <InputError class="mt-2" :message="form.errors?.name" />
            </div>

            <div>
                <InputLabel for="first_name" value="First Name" />
                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" autocomplete="first_name"/>
                <InputError class="mt-2" :message="form.errors?.first_name" />
            </div>

            <div>
                <InputLabel for="lastname" value="Last Name" />
                <TextInput id="lastname" type="text" class="mt-1 block w-full" v-model="form.lastname" autocomplete="lastname"/>
                <InputError class="mt-2" :message="form.errors?.lastname" />
            </div>

            <div>
                <InputLabel for="gender" value="Gender" />
                <select id="gender" v-model="form.gender" class="mt-1 block w-full">
                    <option value="" disabled>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <InputError class="mt-2" :message="form.errors?.gender" />
            </div>

            <div>
                <InputLabel for="age" value="Age" />
                <TextInput id="age" type="number" class="mt-1 block w-full" v-model="form.age" min="0" autocomplete="age"/>
                <InputError class="mt-2" :message="form.errors?.age" />
            </div>

            <div>
                <InputLabel for="location" value="Location" />
                <TextInput id="location" type="text" class="mt-1 block w-full" v-model="form.location" autocomplete="location"/>
                <InputError class="mt-2" :message="form.errors?.location" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username"/>
                <InputError class="mt-2" :message="form.errors?.email" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton type="submit">Save</PrimaryButton>
            </div>
        </form>
    </section>
</template>
