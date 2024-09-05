import { createRouter, createWebHistory } from 'vue-router';

// Import components for teacher routes
import Dashboard from "../views/Dashboard.vue";
import TeacherDashboardHome from '../components/Course/teacher/TeacherDashboardHome.vue';
import CourseOverview from '../components/Course/teacher/CourseOverview.vue';
import CreateNewCourse from '../components/Course/teacher/CreateNewCourse.vue';
import EditCourse from '../components/Course/teacher/EditCourse.vue';
import DeleteCourse from '../components/Course/teacher/DeleteCourse.vue';
import EnrollmentList from '../components/Course/teacher/EnrollmentList.vue';
import StudentPerformance from '../components/Course/teacher/StudentPerformance.vue';
import Notifications from '../components/Course/teacher/Notifications.vue';
import ProfileManagement from '../components/Course/teacher/ProfileManagement.vue';
import CourseSettings from '../components/Course/teacher/CourseSettings.vue';
import Support from '../components/Support.vue';
import apiClient from "../api/axios.js";
import Login from "../components/Login.vue";
import CreateTestForm from "../components/Course/LessonTestUpdate.vue";
import UpdateTest from '../components/Course/ViewCreateTest.vue';

// Import components for admin routes
import AdminDashboard from '../components/Course/admin/AdminDashboard.vue';
import UserManagement from '../components/Course/admin/UserManagement.vue';
import ContentManagement from '../components/Course/admin/ContentManagement.vue';
import SiteSettings from '../components/Course/admin/SiteSettings.vue';
import Analytics from '../components/Course/admin/Analytics.vue';
import SupportAdmin from '../components/Course/admin/SupportAdmin.vue';
import Security from '../components/Course/admin/Security.vue';
import Customization from '../components/Course/admin/Customization.vue';

// Import components for moderator routes
import ModeratorDashboard from '../components/Course/moderator/ModeratorDashboard.vue';
import ContentModeration from '../components/Course/moderator/ContentModeration.vue';
import UserReviews from '../components/Course/moderator/UserReviews.vue';
import Reports from '../components/Course/moderator/Reports.vue';
import Courses from "../views/CoursesLis.vue";
import CourseCreationForm from "../components/Course/CourseCreationForm.vue";
import CourseUpdateForm from "../components/Course/CourseUpdateForm.vue";
import CourseData from "../components/Course/CourseData.vue";
import NotFoundPage from "../components/NotFoundPage.vue";
import Unauthorized from "../components/Unauthorized.vue";
import ViewCreateTest from "../components/Course/ViewCreateTest.vue";
import LessonTestUpdate from "../components/Course/LessonTestUpdate.vue";

const routes = [
    // Teacher routes
    {
        path: '/teacher-dashboard',
        component: Dashboard,
        children: [
            { path: '', component: TeacherDashboardHome },
            { path: 'course-overview', component: CourseOverview },
            { path: 'create-new-course', component: CreateNewCourse },
            { path: 'edit-course/:courseId', component: EditCourse, props: true },
            { path: 'delete-course/:courseId', component: DeleteCourse, props: true },
            { path: 'enrollment-list', component: EnrollmentList },
            { path: 'student-performance', component: StudentPerformance },
            { path: 'notifications', component: Notifications },
            { path: 'profile-management', component: ProfileManagement },
            { path: 'course-settings', component: CourseSettings },
            { path: 'support', component: Support },
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
                component: ViewCreateTest, // Define UpdateTest route here
                props: true, // Pass route params as props to the component
            },

            { path: '', component: Courses },
            { path: 'user-management', component: UserManagement },
            { path: 'content-management', component: ContentManagement },
            { path: 'site-settings', component: SiteSettings },
            { path: 'analytics', component: Analytics },
            { path: 'support', component: SupportAdmin },
            { path: 'customization', component: Customization },
            { path: 'new-course', component: CourseCreationForm },
        ]
    },

    // // Moderator routes
    {
        path: '/moderator-dashboard',
        component: Dashboard,
        children: [
            { path: '', component: ContentModeration },
            { path: 'content-moderation', component: ContentModeration },
            { path: 'user-reviews', component: UserReviews },
            { path: 'reports', component: Reports },
            { path: 'notifications', component: Notifications },
            { path: 'support', component: Support },
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
        path: '/unauthorized',
        name: 'Unauthorized',
        component: Unauthorized
    },

    {
        path: '/:pathMatch(.*)*', // This will match any route that is not defined
        name: 'NotFound',
        component: NotFoundPage
    },

    // Redirect or catch-all route
    // {
    //     path: '/:catchAll(.*)',
    //     redirect: '/teacher-dashboard'
    // }
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
