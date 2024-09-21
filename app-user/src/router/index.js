import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Login from '../components/Auth/Login.vue';
import Register from '../components/Auth/Register.vue';
import CoursePage from "../components/CourseDetail/CoursePage.vue";
import MainPage from "../components/Main/MainPage.vue";
import Profile from "../components/Auth/Profile.vue";

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

];

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
