<template>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <!-- Footer Logo & About -->
                <div class="footer-section about">
                    <h2 class="logo">CourseApp.com</h2>
                    <p>
                        Learn foreign languages and technology with our expertly crafted courses. Join us to explore and grow your skills.
                    </p>
                </div>

                <!-- Footer Navigation Links -->
                <div class="footer-section links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Courses</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><button @click="goToFeedback">Feedback</button></li>

                    </ul>
                </div>

                <!-- Footer Contact Information -->
                <div class="footer-section contact">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Learning St, Education City</p>
                    <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> support@courseapp.com</p>
                </div>

                <!-- Footer Social Media Links -->
                <div class="footer-section social">
                    <h3>Follow Us</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            &copy; 2024 CourseApp.com | Designed by CourseApp Team
        </div>
    </footer>
</template>
<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();
function goToFeedback(){
    router.push({ name: 'Feedback'});
}
</script>
<style scoped>
/* Footer Component Styles */
.footer {
    background-color: #2d3748; /* Dark gray background */
    color: white;
    padding: 40px 0;
    font-size: 0.875rem;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    text-align: left;
}

.footer-section {
    flex: 1 1 200px;
    margin: 16px;
}

.footer-section h3 {
    font-size: 1.25rem;
    margin-bottom: 16px;
}

.footer-section p, .footer-section ul, .footer-section a {
    color: #e2e8f0; /* Light gray text */
    margin-bottom: 8px;
    text-decoration: none;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 8px;
}

.footer-section ul li a:hover {
    color: #ecc94b; /* Yellow text on hover */
}

/* Social Media Links */
.social-icons a {
    display: inline-block;
    margin-right: 16px;
    color: #e2e8f0; /* Light gray */
    font-size: 1.25rem;
}

.social-icons a:hover {
    color: #ecc94b; /* Yellow on hover */
}

/* Footer Bottom */
.footer-bottom {
    background-color: #1a202c; /* Darker gray */
    text-align: center;
    padding: 16px 0;
    font-size: 0.75rem;
    margin-top: 40px;
    color: #a0aec0; /* Light gray text */
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .footer-section {
        margin-bottom: 24px;
    }
}
</style>
