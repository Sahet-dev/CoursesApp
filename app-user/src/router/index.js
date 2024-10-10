import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Auth/Login.vue';
import Register from '../components/Auth/Register.vue';
import CoursePage from "../components/CourseDetail/CoursePage.vue";
import MainPage from "../components/Main/MainPage.vue";
import Profile from "../components/Auth/Profile.vue";
import UpdateProfile from "../components/Auth/UpdateProfile.vue";
import Index from "../components/Reviews/Index.vue";
import FeedbackForm from "../components/FeedbackForm.vue";
import CourseCatalog from "../components/CourseCatalog.vue";
import Prices from "../components/Prices.vue";
import SuccessPage from "../components/SuccessPage.vue";
import CoursesList from "../components/CoursesList.vue";

const routes = [
    {
        path: '/',
        name: 'MainPage',
        component: MainPage,
    },
    {
        path: '/courses/catalog',
        name: 'CoursesList',
        component: CoursesList
    },
    {
        path: '/course/:id',
        name: 'CoursePage',
        component: CoursePage,
        props: true,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/user/:id',
        name: 'Profile',
        component: Profile,
    },
    {
        path: '/profile/:id',
        name: 'UserProfile',
        component: UpdateProfile,
    },
    {
        path: '/reviews',
        name: 'Reviews',
        component: Index,
    },
    {
        path: '/feedback',
        name: 'Feedback',
        component: FeedbackForm,
    },
    {
        path: '/catalog',
        name: 'CourseCatalog',
        component: CourseCatalog,
    },
    {
        path: '/prices',
        name: 'Prices',
        component: Prices,
    },
    {
        path: '/success',
        name: 'Success',
        component: SuccessPage,
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
