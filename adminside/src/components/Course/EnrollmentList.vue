<template>
    <div class="enrollment-list p-6 bg-gray-50 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Enrollments</h2>
        <table class="min-w-full bg-white border rounded-lg">
            <thead class="bg-gray-100">
            <tr>
                <th class="py-3 px-4 border-b font-medium text-gray-600">Student Name</th>
                <th class="py-3 px-4 border-b font-medium text-gray-600">Email</th>
                <th class="py-3 px-4 border-b font-medium text-gray-600">Enrollment Date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="enrollment in enrollments" :key="enrollment.id" class="even:bg-gray-50">
                <td class="py-3 px-4 border-b text-gray-700">{{ enrollment.student_name }}</td>
                <td class="py-3 px-4 border-b text-gray-700">{{ enrollment.email }}</td>
                <td class="py-3 px-4 border-b text-gray-700">{{ enrollment.enrollment_date }}</td>
            </tr>
            </tbody>
        </table>
        <div v-if="errorMessage" class="text-red-500 mt-4">{{ errorMessage }}</div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../api/axios.js'; // Adjust the path if needed

const enrollments = ref([]);
const errorMessage = ref('');

const fetchEnrollments = async () => {
    try {
        const response = await apiClient.get('/enrollments');
        enrollments.value = response.data;
    } catch (error) {
        console.error('Failed to fetch enrollments:', error);
        errorMessage.value = 'Failed to fetch enrollments.';
    }
};

onMounted(fetchEnrollments);
</script>

<style scoped>
.enrollment-list {
    max-width: 800px;
    margin: 0 auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    text-align: left;
    padding: 1rem;
    border: 1px solid #e5e7eb;
}

th {
    background-color: #f3f4f6;
}

tr:nth-child(even) {
    background-color: #f9fafb;
}
</style>
