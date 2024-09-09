<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { UserCircleIcon, CameraIcon,  XMarkIcon, CheckCircleIcon } from "@heroicons/vue/24/outline";
import {Inertia} from "@inertiajs/inertia";
import {showNotification} from "../../../../../adminside/src/notification.js";

const imagesForm = useForm({
    avatar: null,
    cover: null,
})
defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const avatarImageSrc = ref('')

const form = useForm({
    name: user.name,
    email: user.email,
    first_name: user.first_name,
    lastname: user.lastname,
    gender: user.gender,
    age: user.age,
    location: user.location,
    avatar: null,
});
const imageSrc = ref('/storage/avatars/pngwing.com.png');


function onAvatarChange(event){
    imagesForm.avatar = event.target.files[0]
    if (imagesForm.avatar){
        const reader = new FileReader()
        reader.onload = ()=> {
            avatarImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.avatar)
    }
}


function cancelAvatarImg(){
    imagesForm.avatar = null;
    avatarImageSrc.value = null;
}

function submitAvatarImg() {
    const formData = new FormData();
    formData.append('avatar', imagesForm.avatar); // Append the selected file to the FormData

    Inertia.post(route('profile.updateImages'), formData, {
        forceFormData: true, // This ensures that Inertia sends a FormData object, not a JSON object
        onSuccess: (page) => {
            console.log(page.props.message); // Handle success message
            alert('Avatar uploaded successfully!');
        },
        onError: (errors) => {
            console.error('Error uploading avatar:', errors); // Handle errors
            alert('Failed to upload avatar. Please try again.');
        },
        preserveState: true, // Keep the current page state
    });
}

// function submitAvatarImg(){
//     imagesForm.post(route('profile.updateImages'),{
//         preserveScroll: true,
//         onSuccess: (user)=>{
//             showNotification.value = true;
//             cancelAvatarImg();
//             setTimeout(()=>{showNotification.value=false}, 5000)
//         }
//     });
// }


const avatarPreview = ref(null);

async function handleAvatarChange(event) {
    const file = event.target.files[0];
    if (file) {
        // Display the preview of the avatar
        avatarPreview.value = URL.createObjectURL(file);

        // Create FormData object
        const formData = new FormData();
        formData.append('avatar', file);

        // Make an Inertia request to upload the image
        try {
            await Inertia.post(route('profile.avatar.upload'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onSuccess: (page) => {
                    console.log(page.props.message); // Message or response data from the server
                    alert('Avatar uploaded successfully!');
                },
                onError: (errors) => {
                    console.error('Error uploading avatar:', errors);
                    alert('Failed to upload avatar. Please try again.');
                },
                preserveState: true, // Optional: Keeps the current page state
            });
        } catch (error) {
            console.error('Error uploading avatar:', error);
            alert('Failed to upload avatar. Please try again.');
        }
    }
}


</script>

<template>
    <div class="group  relative bg-white">
        <img :src="imageSrc"
             class="w-full h-[200px] object-cover">
        <div class="absolute top-2 right-2 ">
            <button   class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0  ">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                </svg>


                <input type="file" class="absolute left-0 bottom-0 right-0 opacity-0 cursor-pointer"
                        >
            </button>

        </div>
        <div class="flex">
            <!--        User avatar Image-->

            <div class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px]  rounded-full overflow-hidden ">

                <img :src="avatarImageSrc  || (user.avatar ? `/storage/${user.avatar}` : '/images/avatar_default.png')"
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



















        <form @submit.prevent="form.patch(route('profile.update', { forceFormData: true }))" class="mt-6 space-y-6">
            <!-- Avatar Upload -->



            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- First Name Field -->
            <div>
                <InputLabel for="first_name" value="First Name" />
                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    autocomplete="first_name"
                />
                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <!-- Last Name Field -->
            <div>
                <InputLabel for="lastname" value="Last Name" />
                <TextInput
                    id="lastname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.lastname"
                    autocomplete="lastname"
                />
                <InputError class="mt-2" :message="form.errors.lastname" />
            </div>

            <!-- Gender Field -->
            <div>
                <InputLabel for="gender" value="Gender" />
                <select id="gender" v-model="form.gender" class="mt-1 block w-full">
                    <option value="" disabled>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <InputError class="mt-2" :message="form.errors.gender" />
            </div>

            <!-- Age Field -->
            <div>
                <InputLabel for="age" value="Age" />
                <TextInput
                    id="age"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.age"
                    min="0"
                    autocomplete="age"
                />
                <InputError class="mt-2" :message="form.errors.age" />
            </div>

            <!-- Location Field -->
            <div>
                <InputLabel for="location" value="Location" />
                <TextInput
                    id="location"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.location"
                    autocomplete="location"
                />
                <InputError class="mt-2" :message="form.errors.location" />
            </div>

            <!-- Email Field -->
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Email Verification Section -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>

    </section>
</template>
