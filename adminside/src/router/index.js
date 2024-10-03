import { createRouter, createWebHistory } from 'vue-router';

import Dashboard from "../views/Dashboard.vue";
import EnrollmentList from '../components/Course/teacher/EnrollmentList.vue';
import StudentPerformance from '../components/Course/teacher/StudentPerformance.vue';
import Reviews from '../components/Course/teacher/Reviews.vue';
import ProfileManagement from '../components/Course/teacher/FinanceReports.vue';
import apiClient from "../api/axios.js";
import Login from "../components/Login.vue";

// Import components for admin routes
import UserManagement from '../components/Course/admin/UserManagement.vue';
import ContentManagement from '../components/Course/admin/ContentManagement.vue';
import Analytics from '../components/Course/admin/Analytics.vue';

import Comments from '../components/Course/moderator/Comments.vue';
import CourseCreationForm from "../components/Course/CourseCreationForm.vue";
import CourseUpdateForm from "../components/Course/CourseUpdateForm.vue";
import CourseData from "../components/Course/CourseData.vue";
import NotFoundPage from "../components/NotFoundPage.vue";
import ViewCreateTest from "../components/Course/ViewCreateTest.vue";
import LessonTestUpdate from "../components/Course/LessonTestUpdate.vue";
import TeacherContentManagement from "../components/Course/teacher/TeacherContentManagement.vue";
import FeedbackList from "../components/Course/teacher/FeedbackList.vue";
import AdminMessage from "../components/Course/AdminMessage.vue";
import ActiveUsersCard from "../components/Course/admin/analytics/ActiveUsersCard.vue";
import EngagementMetricsTable from "../components/Course/admin/analytics/EngagementMetricsTable.vue";
import NewSubscriptionsCard from "../components/Course/admin/analytics/NewSubscriptionsCard.vue";
import ReatantionRatesTable from "../components/Course/admin/analytics/ReatantionRatesTable.vue";
import RateCard from "../components/Course/admin/analytics/RateCard.vue";
import FinancialMetrics from "../components/Course/admin/analytics/FinancialMetrics.vue";

const routes = [
    // Teacher routes
    {
        path: '/teacher-dashboard',
        component: Dashboard,
        children: [
            { path: '', component: AdminMessage },
            { path: 'courses', component: TeacherContentManagement },
            { path: 'new-course', name: 'NewCourse', component: CourseCreationForm },
            { path: 'enrollment-list', component: EnrollmentList },
            { path: 'student-performance', component: StudentPerformance },
            { path: 'reviews', component: Reviews },
            { path: 'financial-reports', component: ProfileManagement },
        ]
    },
    {
        path: '/login',
        name: 'Login',
        component: Login

    },

    // Admin routes
    {
        path: '/admin-dashboard',
        component: Dashboard,
        children: [
            {
                path: '/tests/:id/update',
                name: 'UpdateTest',
                component: LessonTestUpdate,
                props: true,
            },
            {
                path: '/tests/:id/create',
                name: 'ViewCreateTest',
                component: ViewCreateTest,
                props: true,
            },

            { path: '', component: AdminMessage },
            { path: 'user-management', component: UserManagement },
            { path: 'content-management', component: ContentManagement },
            { path: 'feedbacks', component: FeedbackList },
            { path: 'analytics', component: Analytics },
            { path: 'new-course', component: CourseCreationForm },

            { path: 'course-engagement', component: EngagementMetricsTable },
            { path: 'active-users', component: ActiveUsersCard },
            { path: 'new-subscriptions', component: NewSubscriptionsCard },
            { path: 'churn-rate', component: RateCard },
            { path: 'retention-rate', component: ReatantionRatesTable },
            { path: 'financial-metrics', component: FinancialMetrics },



        ]
    },

    // // Moderator routes
    {
        path: '/moderator-dashboard',
        component: Dashboard,
        children: [
            { path: '', component: AdminMessage },
            { path: 'user-management', component: UserManagement },
            { path: 'feedbacks', component: FeedbackList },
            { path: 'content-management', component: ContentManagement },
            { path: 'comments', component: Comments },
            { path: 'reviews', component: Reviews },

        ]
    },
    {
        path: '/courses/:id/edit',
        name: 'CourseUpdate',
        component: CourseUpdateForm,
        props: true,
    },
    {
        path: '/courses/:id/data',
        name: 'CourseData',
        component: CourseData,
        props: true,
    },

    {
        path: '/where-am-i',
        name: 'Dashboard',
        component: Dashboard
    },

    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFoundPage
    },


];

const router = createRouter({
    history: createWebHistory('/'),
    routes
});

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!token) {
            next('/login');
        } else {
            try {
                await apiClient.get('/user'); // Verify token
                next();
            } catch (error) {
                localStorage.removeItem('token');
                next('/login');
            }
        }
    } else {
        next();
    }
});

export default router;
