<template>
    <div>
        <ul class="space-y-2" id="list">

            <li>
                <router-link
                    to="/admin-dashboard/user-management"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    User Management
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/content-management"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Content Management
                </router-link>
            </li>

            <li>
                <router-link
                    to="/admin-dashboard/analytics"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Analytics
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/support"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Support
                </router-link>
            </li>

            <li>
                <router-link
                    to="/admin-dashboard/customization"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Customization
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/new-course"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Create Course
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'AdminSidebarList',
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
