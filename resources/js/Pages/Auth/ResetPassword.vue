<script setup>
import {computed, ref} from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: String,
    token: String,
});
const maskedEmail = computed(() => {
    if (!props.email) return '';

    const [name, domain] = props.email.split('@');
    if (!domain) return props.email;

    // Show first 2 letters, mask the rest except last letter and domain
    const visibleName = name.slice(0, 2);
    const maskedPart = '*'.repeat(Math.max(0, name.length - 3)); // Hide middle part
    const lastLetter = name.slice(-1);

    return `${visibleName}${maskedPart}${lastLetter}@${domain}`;
});
const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const message = ref('');
const messageClass = ref('');

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            if (form.hasErrors) {
                message.value = 'Please fix the errors below.';
                messageClass.value = 'text-red-600';
            } else {
                message.value = 'Password reset successfully!';
                messageClass.value = 'text-green-600';
                form.reset('password', 'password_confirmation');
            }
        },
    });
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-center text-gray-800">Reset Password</h2>

            <!-- Success/Error Message -->
            <p v-if="message" :class="messageClass" class="mt-4 text-center text-sm font-semibold"></p>

            <form @submit.prevent="submit" class="mt-4 space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input
                        disabled
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                    <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password:</label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                    <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                    <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ form.errors.password_confirmation }}</p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 disabled:opacity-50"
                >
                    {{ form.processing ? 'Resetting...' : 'Reset Password' }}
                </button>
            </form>
        </div>
    </div>
</template>
