<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Create New Course</h1>
        <form class="space-y-4">
            <div>
                <label class="block mb-1" for="title">Course Title</label>
                <input id="title" type="text" class="border border-gray-300 p-2 w-full" />
            </div>
            <div>
                <label class="block mb-1" for="description">Description</label>
                <textarea id="description" class="border border-gray-300 p-2 w-full"></textarea>
            </div>
            <div>
                <label class="block mb-1" for="category">Category</label>
                <select id="category" class="border border-gray-300 p-2 w-full">
                    <option>Select Category</option>
                    <!-- Add more categories here -->
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Create Course</button>
        </form>
    </div>
</template>

<script>
export default {
    name: 'CreateNewCourse'
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
