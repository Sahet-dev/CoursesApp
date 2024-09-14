<template>
    <div>
        <ul class="space-y-2" id="list">
            <li>
                <router-link
                    to="/moderator-dashboard/feedbacks"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    Feedbacks
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/user-management"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    User Management
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/content-management"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    Content-management
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/comments"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    Comments
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/reviews"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
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
