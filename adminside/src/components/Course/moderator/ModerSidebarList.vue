<template>
    <div>
        <ul class="space-y-2" id="list">
            <li>
                <router-link
                    to="/moderator-dashboard"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    Moderator Dashboard Home
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/content-moderation"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    Content Moderation
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/user-reviews"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    User Reviews
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/reports"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    Reports
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/notifications"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
                >
                    Notifications
                </router-link>
            </li>
            <li>
                <router-link
                    to="/moderator-dashboard/support"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-green-100 hover:border-green-300 hover:text-green-900 transition-all duration-300"
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
