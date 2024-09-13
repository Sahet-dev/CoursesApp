<template>
    <div class="admin-message-container">
        <p class="admin-message-text">Please Choose content From sidebar to work on it</p>
    </div>
</template>

<script setup>
// No JavaScript logic needed for this simple static component.
</script>

<style scoped>
.admin-message-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh; /* Full viewport height */
    background: linear-gradient(135deg, #f5f7fa, #e4e7eb); /* Background gradient */
}

.admin-message-text {
    font-size: 2rem; /* Larger font size */
    font-weight: bold; /* Bold text */
    color: #2c3e50; /* Darker text color */
    background-color: #ecf0f1; /* Light background for contrast */
    padding: 20px 40px; /* Padding around the text */
    border-radius: 12px; /* Rounded corners */
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    border-left: 8px solid #3498db; /* Left border for accent */
    position: relative; /* For pseudo-element positioning */
    animation: pulse 3s infinite; /* Pulse animation */
    transition: all 0.3s ease; /* Smooth transition for hover */
}

/* Add a small icon before the text */
.admin-message-text::before {
    content: "📝"; /* Icon */
    font-size: 2rem; /* Icon size */
    position: absolute; /* Position relative to parent */
    left: -45px; /* Position on the left */
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust positioning */
    color: #3498db; /* Icon color */
}

/* Hover effect to change background and text color */
.admin-message-text:hover {
    background-color: #3498db; /* Hover background color */
    color: #ffffff; /* Hover text color */
    border-left-color: #2c3e50; /* Change border color */
}

/* Pulse animation to draw attention */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05); /* Scale up */
    }
    100% {
        transform: scale(1);
    }
}

/* Responsive design for smaller screens */
@media (max-width: 600px) {
    .admin-message-text {
        font-size: 1.5rem; /* Adjust font size */
        padding: 15px 30px; /* Adjust padding */
    }

    .admin-message-text::before {
        left: -35px; /* Adjust icon position */
    }
}
</style>
