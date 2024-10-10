<template>
    <div class="admin-message-container">
        <p class="admin-message-text">Please Choose content From sidebar to work on it</p>
    </div>
</template>

<script setup>
</script>

<style scoped>
.admin-message-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: linear-gradient(135deg, #f5f7fa, #e4e7eb);
}

.admin-message-text {
    font-size: 2rem;
    font-weight: bold;
    color: #2c3e50;
    background-color: #ecf0f1;
    padding: 20px 40px;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border-left: 8px solid #3498db;
    position: relative;
    animation: pulse 3s infinite;
    transition: all 0.3s ease;
}

.admin-message-text::before {
    content: "📝";
    font-size: 2rem;
    position: absolute;
    left: -45px;
    top: 50%;
    transform: translateY(-50%);
    color: #3498db;
}

.admin-message-text:hover {
    background-color: #3498db;
    color: #ffffff;
    border-left-color: #2c3e50;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

@media (max-width: 600px) {
    .admin-message-text {
        font-size: 1.5rem;
        padding: 15px 30px;
    }

    .admin-message-text::before {
        left: -35px;
    }
}
</style>

