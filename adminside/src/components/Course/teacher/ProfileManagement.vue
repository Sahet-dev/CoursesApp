<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Profile Management</h1>
        <form class="space-y-4">
            <div>
                <label class="block mb-1" for="name">Name</label>
                <input id="name" type="text" class="border border-gray-300 p-2 w-full" />
            </div>
            <div>
                <label class="block mb-1" for="email">Email</label>
                <input id="email" type="email" class="border border-gray-300 p-2 w-full" />
            </div>
            <div>
                <label class="block mb-1" for="password">Password</label>
                <input id="password" type="password" class="border border-gray-300 p-2 w-full" />
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Profile</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'ProfileManagement'
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
