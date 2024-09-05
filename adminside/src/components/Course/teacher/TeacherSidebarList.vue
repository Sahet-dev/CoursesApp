<template>
    <div>
        <ul v-if="role === 'teacher'" class="space-y-2" id="list">
            <li>
                <router-link
                    to="/teacher-dashboard"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Teacher Dashboard Home
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/course-overview"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Course Overview
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/create-new-course"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Create New Course
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/edit-course/:courseId"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Edit Course
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/delete-course/:courseId"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Delete Course
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/enrollment-list"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Enrollment List
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/student-performance"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Student Performance
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/notifications"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Notifications
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/profile-management"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Profile Management
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/course-settings"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Course Settings
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/support"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Support
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'TeacherSidebarList',
    props: ['role']
};
</script>

<style scoped>
/* Scoped styles if needed */
#list a.router-link-exact-active {
    color: #5daeec;
    text-decoration-line: underline
}
</style>
