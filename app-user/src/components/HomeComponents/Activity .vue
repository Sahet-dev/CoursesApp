<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <div v-if="isLoading" class="flex justify-center items-center h-64">
            <span class="text-gray-500">Loading activities...</span>
        </div>

        <div v-else-if="error" class="rounded-lg p-6 text-center">
            <p class="text-red-500 text-lg">{{ error }}</p>
        </div>

        <div v-else-if="activities.length" class="bg-white rounded-lg p-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Activities</h2>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Lesson Selection Over Time -->
                <div class="h-64">
                    <h3 class="text-lg font-semibold mb-2">Lesson Selections Over Time</h3>
                    <Line v-if="lessonSelectionData.labels.length" :chart-data="lessonSelectionData" :options="chartOptions" />
                    <p v-else class="text-gray-500">No data available.</p>
                </div>

                <!-- Most Accessed Lessons -->
                <div class="h-64">
                    <h3 class="text-lg font-semibold mb-2">Most Accessed Lessons</h3>
                    <Bar v-if="mostAccessedLessonsData.labels.length" :chart-data="mostAccessedLessonsData" :options="chartOptions" />
                    <p v-else class="text-gray-500">No data available.</p>
                </div>

                <!-- Interaction Types Distribution -->
                <div class="h-64">
                    <h3 class="text-lg font-semibold mb-2">Interaction Types Distribution</h3>
                    <Pie v-if="interactionTypesData.labels.length" :chart-data="interactionTypesData" :options="chartOptions" />
                    <p v-else class="text-gray-500">No data available.</p>
                </div>
            </div>

            <!-- Activities List -->
            <ul class="space-y-4">
                <li
                    v-for="activity in activities"
                    :key="activity.id"
                    class="flex items-start space-x-4 border-b pb-4 last:border-b-0"
                >
                    <div class="w-14 h-14 flex items-center justify-center bg-blue-50 rounded-full shadow-md">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-8 text-blue-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 text-lg font-medium">{{ activity.action }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ activity.date }}</p>
                    </div>
                </li>
            </ul>
        </div>

        <div v-else class="rounded-lg p-6 text-center">
            <p class="text-gray-500 text-lg">No activities found</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import apiClient from "../../axios/index.js";

// Import Chart.js and necessary components
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement,
} from 'chart.js';
import { Line, Bar, Pie } from 'vue-chartjs';

// Register Chart.js components
ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement
);

// Define reactive data
const activities = ref([]);
const isLoading = ref(false);
const error = ref(null);

// Fetch activities without needing a userId
const fetchActivities = async () => {
    isLoading.value = true;
    error.value = null;
    try {
        const response = await apiClient.get('/user/activities');
        activities.value = response.data;
        console.log('Fetched Activities:', activities.value);
    } catch (err) {
        console.error('Error fetching activities:', err);
        if (err.response && err.response.status === 401) {
            error.value = 'Unauthorized. Please log in.';
            // Optionally, redirect to login page
        } else {
            error.value = 'Failed to load activities.';
        }
    } finally {
        isLoading.value = false;
    }
};

// Fetch activities on component mount
onMounted(() => {
    fetchActivities();
});

// Process data for charts
const interactions = computed(() => {
    if (activities.value.length > 0) {
        return activities.value[0].interactions;
    }
    return [];
});

// 1. Lesson Selection Over Time
const lessonSelectionData = computed(() => {
    const selections = interactions.value.filter(
        (inter) => inter.interaction_type === 'select_lesson'
    );

    if (selections.length === 0) {
        return {
            labels: [],
            datasets: [
                {
                    label: 'Lesson Selections',
                    data: [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    fill: true,
                },
            ],
        };
    }

    // Group by date
    const countsByDate = selections.reduce((acc, curr) => {
        const date = curr.timestamp.split(' ')[0];
        acc[date] = (acc[date] || 0) + 1;
        return acc;
    }, {});

    const labels = Object.keys(countsByDate).sort();
    const data = labels.map((date) => countsByDate[date]);

    return {
        labels,
        datasets: [
            {
                label: 'Lesson Selections',
                data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                fill: true,
            },
        ],
    };
});

// 2. Most Accessed Lessons
const mostAccessedLessonsData = computed(() => {
    const selections = interactions.value.filter(
        (inter) => inter.interaction_type === 'select_lesson'
    );

    if (selections.length === 0) {
        return {
            labels: [],
            datasets: [
                {
                    label: 'Access Count',
                    data: [],
                    backgroundColor: '#10b981',
                },
            ],
        };
    }

    // Count selections per lesson
    const countsByLesson = selections.reduce((acc, curr) => {
        acc[curr.lesson_id] = (acc[curr.lesson_id] || 0) + 1;
        return acc;
    }, {});

    // Sort and get top 5
    const sortedLessons = Object.entries(countsByLesson)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);

    const labels = sortedLessons.map((item) => `Lesson ${item[0]}`);
    const data = sortedLessons.map((item) => item[1]);

    return {
        labels,
        datasets: [
            {
                label: 'Access Count',
                data,
                backgroundColor: '#10b981',
            },
        ],
    };
});

// 3. Interaction Types Distribution
const interactionTypesData = computed(() => {
    const counts = interactions.value.reduce((acc, curr) => {
        acc[curr.interaction_type] = (acc[curr.interaction_type] || 0) + 1;
        return acc;
    }, {});

    const labels = Object.keys(counts);
    const data = Object.values(counts);

    if (labels.length === 0) {
        return {
            labels: [],
            datasets: [
                {
                    data: [],
                    backgroundColor: [],
                },
            ],
        };
    }

    const backgroundColors = [
        '#3b82f6',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6',
    ];

    return {
        labels,
        datasets: [
            {
                data,
                backgroundColor: backgroundColors.slice(0, labels.length),
            },
        ],
    };
});

// Chart options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: false,
        },
    },
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>
