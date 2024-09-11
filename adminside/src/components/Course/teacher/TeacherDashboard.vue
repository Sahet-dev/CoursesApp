<template>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-54 h-full bg-blue-50 border-r border-gray-200 p-4 overflow-y-auto">
            <TeacherSidebarList role="teacher"/>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6  ">
            <router-view />
        </main>
    </div>


</template>

<script>
import TeacherSidebarList from './TeacherSidebarList.vue';
import DashboardHeader from "../../DashboardHeader.vue";

export default {
    name: 'TeacherDashboard',
    components: {
        TeacherSidebarList,
        DashboardHeader
    }
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
