import { ref } from 'vue';

export const isLoading = ref(false);
export const error = ref(null);

export const data = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [
        {
            label: 'User Activities',
            backgroundColor: '#0bbf5a',
            data: []
        }
    ]
};

export const options = {
    responsive: true,
    maintainAspectRatio: false
};
