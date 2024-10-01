<template>
    <div class="mx-auto p-6 bg-white rounded-md shadow-md mb-4 pb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Course and Add Lessons </h2>
        <form @submit.prevent="createCourseAndAddLesson" enctype="multipart/form-data">
            <!-- Course Form -->
            <div class="mb-4">
                <label for="title" class="block text-lg font-medium text-gray-700">Course Title</label>
                <input v-model="course.title" id="title" type="text" class="w-full px-3 py-2 border rounded-md" required />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-lg font-medium text-gray-700">Course Description</label>
                <textarea v-model="course.description" id="description" class="w-full px-3 py-2 border rounded-md" required></textarea>
            </div>
            <div class="mb-4">
                <label for="thumbnail" class="block text-lg font-medium text-gray-700">Course Thumbnail</label>
                <input @change="handleFileUpload" id="thumbnail" type="file" class="w-full px-3 py-2 border rounded-md" required />
                <img v-if="thumbnailPreview" :src="thumbnailPreview" alt="Thumbnail Preview" class="mt-2 w-32 h-32 object-cover" />
            </div>
            <div class="mb-4">
                <label for="price" class="block text-lg font-medium text-gray-700">Price</label>
                <input v-model="course.price" id="price" type="number" step="0.01" class="w-full px-3 py-2 border rounded-md" required />
            </div>

            <div class="mb-4">
                <label for="courseType" class="block text-lg font-medium text-gray-700">Course Type</label>
                <select v-model="course.type" id="courseType" class="w-full px-3 py-2 border rounded-md" required>
                    <option value="subscription">Include in Subscription</option>
                    <option value="free">Free</option>
                    <option value="purchase">Purchasable</option>

                </select>
            </div>

            <!-- Lesson Form -->
            <div v-for="(lesson, index) in lessons" :key="index" class="mb-4 border-b border-gray-300 pb-4">
                <h3 class="text-lg font-medium text-gray-700 mb-2">Lesson {{ index + 1 }}</h3>
                <div class="mb-4">
                    <label :for="'lessonTitle' + index" class="block text-lg font-medium text-gray-700">Lesson Title</label>
                    <input v-model="lesson.title" :id="'lessonTitle' + index" type="text" class="w-full px-3 py-2 border rounded-md" required />
                </div>
                <div class="mb-4">
                    <label :for="'video_url' + index" class="block text-lg font-medium text-gray-700">Video file</label>
                    <input @change="handleVideoUpload($event, index)" :id="'video_url' + index" type="file" class="w-full px-3 py-2 border rounded-md" required />
                </div>
                <div class="mb-4">
                    <label :for="'markdown_text' + index" class="mt-6 block text-lg font-medium text-gray-700">Markdown Text</label>
                    <ckeditor
                        v-model="lesson.markdown_text"
                        :editor="editor"
                        :config="editorConfig"
                    />

                </div>
            </div>
            <button type="button" @click="addLesson" class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow-sm hover:bg-gray-300 mb-4">+ Add Another Lesson</button>

            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600">Create Course and Add Lessons</button>
            <div v-if="errorMessage" class="text-red-500 mt-2">{{ errorMessage }}</div>
        </form>

        <!-- Display Existing Lessons -->
        <div v-if="existingLessons.length > 0" class="mt-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Existing Lessons</h3>
            <ul class="space-y-4">
                <li v-for="lesson in existingLessons" :key="lesson.id" class="p-4 border border-gray-300 rounded-md shadow-sm">
                    <h4 class="text-lg font-medium text-gray-800">{{ lesson.title }}</h4>
                    <p class="text-gray-600 mt-1">{{ lesson.video_url }}</p>
                    <p class="text-gray-600 mt-1">{{ lesson.markdown_text }}</p>
                </li>
            </ul>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mt-4 text-red-600">
            {{ error }}
        </div>
    </div>




</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from "../../api/axios.js"; // Adjust path if needed
import { ClassicEditor, Bold, Essentials, Italic, Mention, Paragraph,
    Undo, Link, Code,  Strikethrough, Subscript, Superscript,
    Underline, Font,CodeBlock, Indent, IndentBlock   } from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import Footer from "../../views/components/Footer.vue";

const editor = ClassicEditor;
const editorConfig = {
    plugins: [
        Bold, Essentials, Italic, Mention, Paragraph,
        Undo, Link, Code, Strikethrough, Subscript,
        Superscript, Underline, Font, CodeBlock,
        Indent, IndentBlock
    ],
    toolbar: {
        items: [
            'undo', 'redo', '|', 'bold', 'italic', 'underline',
            'strikethrough', 'subscript', 'superscript',
            'fontFamily', 'fontColor', 'fontBackgroundColor',
            'codeBlock', 'outdent', 'indent', ]
    }
};

const router = useRouter();
const course = ref({
    title: '',
    description: '',
    thumbnail: null,
    price: 0,
    type: ''
});
const lessons = ref([{ title: '', video_url: null, markdown_text: '' }]);
const existingLessons = ref([]);
const error = ref('');
const errorMessage = ref('');
const thumbnailPreview = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        course.value.thumbnail = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            thumbnailPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleVideoUpload = (event, index) => {
    const file = event.target.files[0];
    if (file) {
        lessons.value[index].video_url = file; // Store the file object directly
    }
};

const addLesson = () => {
    lessons.value.push({ title: '', video_url: null, markdown_text: '' });
};

const createCourseAndAddLesson = async () => {
    const formData = new FormData();
    formData.append('title', course.value.title);
    formData.append('description', course.value.description);
    formData.append('price', course.value.price);
    formData.append('thumbnail', course.value.thumbnail); // Append the file
    formData.append('type', course.value.type);

    // Append lesson data
    lessons.value.forEach((lesson, index) => {
        formData.append(`lessons[${index}][title]`, lesson.title);
        formData.append(`lessons[${index}][video_url]`, lesson.video_url); // Append the file object
        formData.append(`lessons[${index}][markdown_text]`, lesson.markdown_text);
    });

    try {
        const response = await apiClient.post('/courses', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        course.value = { title: '', description: '', thumbnail: null, price: 0 }; // Clear form
        lessons.value = [{ title: '', video_url: null, markdown_text: '' }]; // Clear lesson form
        thumbnailPreview.value = null; // Clear thumbnail preview
        errorMessage.value = '';

        // Optionally redirect or show success message
        router.push('content-management');
    } catch (error) {
        console.error('Failed to create course:', error);
        errorMessage.value = 'Failed to create course.';
    }
};
</script>

<style scoped>
/* Course Creation Form styles */
</style>
