import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
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

const routes = [
    {
        path: '/',
        name: 'MainPage',
        component: MainPage,
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

];

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
