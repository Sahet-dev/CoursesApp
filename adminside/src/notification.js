import { ref } from 'vue';

// Create a reactive notification object
const notification = ref({
    message: '',
    visible: false
});

// Function to show a notification
function showNotification(message) {
    notification.value.message = message;
    notification.value.visible = true;

    setTimeout(() => {
        notification.value.visible = false;
    }, 3000);
}

// Export the notification object and function
export { notification, showNotification };
