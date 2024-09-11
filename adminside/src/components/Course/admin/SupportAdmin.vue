<template>
    <div class=" ">




        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>


        <div class="m-6 p-14">
             <h2>Title</h2>
         </div>
    </div>




</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from "../../../api/axios.js"; // Adjust path if needed
import { ClassicEditor, Bold, Essentials, Italic, Mention, Paragraph,
    Undo, Link, Code,  Strikethrough, Subscript, Superscript,
    Underline, Font,CodeBlock, Indent, IndentBlock   } from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';
import Footer from "../../../views/components/Footer.vue";

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
    price: 0
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
