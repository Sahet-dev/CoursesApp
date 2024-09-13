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
                    to="/admin-dashboard/new-course"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Create Course
                </router-link>
            </li>

            <li>
                <router-link
                    to="/admin-dashboard/feedbacks"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Feedback
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/course-engagement"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Engagement Metrics
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/active-users"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Active Users
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/new-subscriptions"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    New Subscriptions
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/churn-rate"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Churn Rate
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/retention-rate"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Retention Rates
                </router-link>
            </li>
            <li>
                <router-link
                    to="/admin-dashboard/financial-metrics"
                    class="block p-3 rounded-md border border-transparent text-gray-700
          hover:bg-blue-100 hover:border-blue-300 hover:text-blue-900 transition-all duration-300"
                >
                    Financial Metrics
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
