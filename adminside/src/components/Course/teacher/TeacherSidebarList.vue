<template>
    <div>
        <ul v-if="role === 'teacher'" class="space-y-2" id="list">

            <li>
                <router-link
                    to="/teacher-dashboard/courses"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Courses
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/new-course"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Create New Course
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
                    to="/teacher-dashboard/financial-reports"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Financial Reports
                </router-link>
            </li>
            <li>
                <router-link
                    to="/teacher-dashboard/reviews"
                    class="block p-3 rounded-md border border-transparent text-gray-700 hover:bg-gray-200 hover:border-gray-300 hover:text-gray-900 transition-all duration-300"
                >
                    Reviews

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
