<template>
    <div class="flex h-screen overflow-y-auto">
        <!-- Sidebar -->
        <aside class="w-54 h-full bg-blue-50 border-r border-gray-200 p-4 overflow-y-auto">
            <AdminSidebarList role="teacher"/>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <router-view />
        </main>
    </div>
</template>

<script>
import AdminSidebarList from "./AdminSidebarList.vue";
import DashboardHeader from "../../DashboardHeader.vue";

export default {
    name: 'TeacherDashboard',
    components: {
        AdminSidebarList,
        DashboardHeader
    }
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
