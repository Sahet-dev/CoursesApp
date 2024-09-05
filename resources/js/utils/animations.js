// resources/js/utils/animations.js

import Lenis from '@studio-freight/lenis'
import gsap from 'gsap';

// Initialize Lenis
const lenis = new Lenis();

// Lenis scroll event listener
lenis.on('scroll', (e) => {
    // Custom scroll event logic
    // console.log(e);
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

// Export Lenis instance if needed in other components
export { lenis };

// Transition hooks for titles
const beforeEnterEl  = (el) => {
    el.style.opacity = 0;
    el.style.transform = 'translateY(-30px)';
};

const enterEl = (el, done, index) => {
    gsap.to(el, {
        opacity: 1,
        y: 0,
        duration: 2,
        ease: 'power3.out',
        delay: index * 0.2, // Stagger effect by increasing delay
        onComplete: done,
    });
};

// Export functions to use in other components
export { beforeEnterEl , enterEl };
