<template>
    <Navbar/>

            <div class="page-container">
                <!-- Hero Section -->
                <section class="hero-section">
                    <div class="hero-wrapper">
                        <div class="hero-content animated-element">
                            <Transition @before-enter="beforeEnterTitle" @enter="enterTitle">
                                <h1 v-if="showTitle" class="hero-title">
                                    The Best Foreign Language & Technology Tutorials
                                </h1>
                            </Transition>

                            <Transition @before-enter="beforeEnterSubtitle" @enter="enterSubtitle">
                                <p v-if="showSubtitle" class="hero-subtitle">
                                    And the easy way to learn The World
                                </p>
                            </Transition>

                            <Transition @before-enter="beforeEnterDescription" @enter="enterDescription">
                                <p v-if="showDescription" class="hero-description">
                                    Select a track for a guided path through our 120+ video tutorial courses
                                </p>
                            </Transition>

                            <Transition @before-enter="beforeEnterButtons" @enter="enterButtons">
                                <div v-if="showButtons" class="hero-buttons">
                                    <button class="btn-primary">Start Subscription</button>
                                    <button class="btn-secondary">Browse Catalog</button>
                                </div>
                            </Transition>
                        </div>
                        <!-- Image/GIF alongside the content -->
                        <div class="hero-image animated-element">
                            <img :src="image" alt="Learning Screenshot" />
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-section animated-element">
                        <div class="search-title">
                            What are you going to learn next?
                        </div>
                        <div class="search-bar">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search courses..."
                                class="input-field"
                            />
                            <button
                                @click="searchCourses"
                                :disabled="!searchQuery.trim()"
                                class="search-button"
                            >
                                Search
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Popular Courses Section -->
                <section class="popular-courses-section animated-element">
                    <div class="container">
                        <h2 class="section-title">Popular Courses</h2>
                        <div class="courses-grid">
                            <div
                                @click="getCourse(course.id)"
                                v-for="course in popularCourses" :key="course.id" class="course-card">
                                <img :src="course.thumbnail" :alt="course.title" class="course-image">
                                <h3 class="course-title">{{ course.title }}</h3>
                                <p class="course-description">{{ course.description }}</p>
                                <p class="course-price">${{ course.price }}</p>
                            </div>
                        </div>
                        <div class="see-all-courses">
                            <button class="see-all-button">See All Courses</button>
                        </div>
                    </div>
                </section>

                <!-- Why Us Section -->
                <Icons />




                <!-- Latest Courses Section -->
                <section class="section-1a2b3c animated-element">
                    <div class="container-4d5e6f">
                        <h2 class="title-7g8h9i text-center">New Courses</h2>
                        <div class="grid-0a1b2c">
                            <div
                                @click="getCourse(lcourse.id)"
                                v-for="lcourse in latestCourses" :key="lcourse.id" class="card-3d4e5f">
                                <img :src="lcourse.thumbnail" :alt="lcourse.title" class="image-6f7g8h">
                                <h3 class="name-9i0j1k">{{ lcourse.title }}</h3>
                                <p class="description-2l3m4n">{{ lcourse.description }}</p>
                                <p class="price-5o6p7q">${{ lcourse.price }}</p>
                            </div>
                        </div>
                        <div class="button-container-8r9s0t">
                            <button class="button-1u2v3w">See All Courses</button>
                        </div>
                    </div>
                </section>







                <!-- Testimonials Section -->
                <section class="testimonials-section animated-element">
                    <div class="container">
                        <h2 class="section-title">What People are Saying</h2>
                        <div class="testimonials-grid">
                            <div v-for="comment in comments" :key="comment.id" class="testimonial-item">
                                <p class="testimonial-text">"{{ comment.text }}"</p>
                                <p class="testimonial-author">{{ comment.author }}</p>
                            </div>
                        </div>
                        <div class="more-testimonials">
                            <a href="#" class="more-link">Not sure? Read some more testimonials</a>
                        </div>
                    </div>
                </section>













                <!-- Call to Action Section -->
                <section class="cta-section animated-element">
                    <div class="container">
                        <div class="cta-content">
                            <h2 class="cta-title">Build Something Awesome with Us</h2>
                            <div class="cta-details">
                                <p class="cta-description">
                                    And do it faster with the help of our courses on Technology and more!
                                </p>
                                <button class="cta-button">Start Subscription</button>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

            <Footer />


</template>

<script setup>
import '../../../css/mainPage.css'
import imageUrl from '../../../../storage/app/public/Screenshot 2024-08-27 124053.png';
import {onMounted, ref, watch} from "vue";
import Footer from "@/Pages/components/Footer.vue";
import Lenis from '@studio-freight/lenis'
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import Icons from "@/Components/Icons.vue";
import Navbar from "@/Pages/components/Navbar.vue";
import {Inertia} from "@inertiajs/inertia";



const props = defineProps({
    popularCourses: {
        type: Array,
        required: true,
    },
    latestCourses: {
        type: Array,
        required: true,
    },
});


gsap.registerPlugin(ScrollTrigger);

const lenis = new Lenis()

lenis.on('scroll', (e) => {
    // console.log(e)
})

function raf(time) {
    lenis.raf(time)
    requestAnimationFrame(raf)
}

requestAnimationFrame(raf)


const image = imageUrl;
const searchQuery = ref('');
const transitionsCompleted = ref(0);
const totalTransitions = 4;


const searchCourses = () => {
    Inertia.get(route('courses.search'), { search: searchQuery.value }, {
        preserveState: true,
        replace: true,
    });
};


const comments = ref([
    { id: 1, text: 'This platform has changed the way I learn. The tutorials are spot on.', author: 'Jane Doe' },
    { id: 2, text: 'Amazing content and great instructors!', author: 'John Smith' },
    { id: 3, text: 'Highly recommend for anyone looking to upskill.', author: 'Alice Johnson' }
]);




onMounted(() => {
    gsap.utils.toArray('.animated-element').forEach((element) => {
        gsap.fromTo(
            element,
            { opacity: 0, y: 100 },
            {
                opacity: 1,
                y: 0,
                duration: 1.2,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: element,
                    start: 'top 95%',
                    end: 'bottom 70%',
                    scrub: true,
                    markers: false,
                },
            }
        );
    });
});



const showTitle = ref(false);
const showSubtitle = ref(false);
const showDescription = ref(false);
const showButtons = ref(false);

const beforeEnterTitle = (el) => {
    el.style.opacity = 0;
    el.style.transform = "translateY(-30px)";
};

const enterTitle = (el, done) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 2,
        ease: "power3.out",
        onComplete: done,
    });
};

const beforeEnterSubtitle = (el) => {
    el.style.opacity = 0;
    el.style.transform = "translateY(-20px)";
};

const enterSubtitle = (el, done) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 1,
        delay: 1,
        ease: "power3.out",
        onComplete: done,
    });
};

const beforeEnterDescription = (el) => {
    el.style.opacity = 0;
    el.style.transform = "translateY(-50px)";
};

const enterDescription = (el, done) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 1,
        delay: 1,
        ease: "power3.out",
        onComplete: done,
    });
};

const beforeEnterButtons = (el) => {
    el.style.opacity = 0;
    el.style.transform = "translateY(0px)";
};

const enterButtons = (el, done) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 1,
        delay: 1.5,
        ease: "power3.out",
        onComplete: done,
    });
};

const onTransitionComplete = () => {
    transitionsCompleted.value += 1;
};

watch(transitionsCompleted, (newVal) => {
    if (newVal === totalTransitions) {
        ScrollTrigger.refresh();
    }
});
const getCourse = (id) => {
    Inertia.get(route('courseDetail', { id }), {
        preserveState: true,
        replace: true,
    });
};


onMounted(() => {
    showTitle.value = true;
    showSubtitle.value = true;
    showDescription.value = true;
    showButtons.value = true;
});


</script>


