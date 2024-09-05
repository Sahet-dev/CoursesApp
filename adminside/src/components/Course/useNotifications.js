import { ref } from 'vue';

const notifications = ref([]);

const addNotification = (message, type = 'info') => {
    notifications.value.push({ message, type });
};

const removeNotification = (index) => {
    notifications.value.splice(index, 1);
};

const notificationClasses = (type) => {
    return {
        'info': 'bg-blue-100 border-blue-500 text-blue-700',
        'success': 'bg-green-100 border-green-500 text-green-700',
        'warning': 'bg-yellow-100 border-yellow-500 text-yellow-700',
        'error': 'bg-red-100 border-red-500 text-red-700'
    }[type] || 'bg-gray-100 border-gray-500 text-gray-700';
};

export const useNotifications = () => {
    return {
        notifications,
        addNotification,
        removeNotification,
        notificationClasses
    };
};
