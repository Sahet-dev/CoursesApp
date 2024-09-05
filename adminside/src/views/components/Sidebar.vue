<template>
    <nav class="w-64 bg-gray-200 p-4">
        <ul>
            <li><router-link to="/">Home</router-link></li>
            <li><router-link to="/dashboard">Home</router-link></li>
<!--            <li v-if="isAdmin"><router-link to="/admin">Admin Panel</router-link></li>-->
<!--            <li v-if="isCourseCreator"><TeacherDashboard /></li>-->
<!--            <li v-if="isModerator"><router-link to="/moderate">Moderate Content</router-link></li>-->
        </ul>
    </nav>
</template>

<script>
import TeacherDashboard from "../../components/Course/teacher/TeacherDashboard.vue";
export default {
    computed: {
        isAdmin() {
            return this.$root.user.role === 'admin';
        },
        isCourseCreator() {
            return this.$root.user.role === 'teacher';
        },
        isModerator() {
            return this.$root.user.role === 'moderator';
        },
    },
};
</script>

<style scoped>
/* Your styles here */
</style>
