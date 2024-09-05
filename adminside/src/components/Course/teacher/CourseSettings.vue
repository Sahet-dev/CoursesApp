<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Course Settings</h1>
        <form class="space-y-4">
            <!-- Add form fields for course settings -->
            <div>
                <label class="block mb-1" for="visibility">Course Visibility</label>
                <select id="visibility" class="border border-gray-300 p-2 w-full">
                    <option>Public</option>
                    <option>Private</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Save Settings</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'CourseSettings'
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
