<template>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <transition name="fade">
            <div
                v-if="notification.visible"
                class="fixed top-4 right-4 bg-green-500 text-white p-3 rounded"
            >
                {{ notification.message }}
            </div>
        </transition>
        <h1 class="text-2xl font-bold mb-4">Update Test</h1>

        <form @submit.prevent="submitTest" class="space-y-4">
            <!-- Questions Section -->
            <div v-for="(question, qIndex) in questions" :key="qIndex" class="border-t border-gray-300 pt-4 mt-4">
                <!-- Question Text -->
                <div>
                    <label :for="'question' + qIndex" class="block text-gray-700 font-medium mb-2">Question {{ qIndex + 1 }}:</label>
                    <input
                        v-model="question.question_text"
                        :id="'question' + qIndex"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter the question text"
                    />
                </div>

                <!-- Responses Section -->
                <div v-for="(response, rIndex) in question.responses" :key="rIndex" class="mt-2">
                    <div :class="response.is_correct ? 'bg-teal-100 p-2 rounded-md' : 'bg-white p-2 rounded-md'">
                        <label :for="'response' + qIndex + '-' + rIndex" class="block text-gray-700 font-medium mb-2">
                            Response {{ rIndex + 1 }}:
                        </label>
                        <input
                            v-model="response.response_text"
                            :id="'response' + qIndex + '-' + rIndex"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter the response text"
                            required
                        />
                        <div class="mt-2 flex items-center">
                            <input
                                :checked="response.is_correct"
                                @change="handleCorrectResponseChange(question, rIndex)"
                                :id="'is_correct' + qIndex + '-' + rIndex"
                                type="checkbox"
                                class="mr-2"
                            />
                            <label :for="'is_correct' + qIndex + '-' + rIndex" class="text-gray-700">Correct</label>
                        </div>
                        <button
                            type="button"
                            @click="removeResponse(qIndex, rIndex)"
                            class="text-red-600 hover:text-red-700 mt-2"
                        >
                            Remove Response
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Save Changes
                </button>
            </div>

            <!-- Success and Error Messages -->
            <div v-if="errorMessage" class="mt-4 text-red-600 bg-red-100 border border-red-300 rounded-md p-2">
                {{ errorMessage }}
            </div>
            <div v-if="successMessage" class="mt-4 text-green-600 bg-green-100 border border-green-300 rounded-md p-2">
                {{ successMessage }}
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from "../../api/axios.js";
import { useRoute } from 'vue-router';

const route = useRoute();
const notification = ref({ message: '', visible: false });
const testId = ref(null);
const errorMessage = ref('');
const successMessage = ref('');
const questions = ref([]);

const fetchTest = async () => {
    try {
        const lessonId = route.params.id;
        const response = await apiClient.get(`/lessons/${lessonId}/questions`);
        questions.value = response.data.questions.map(question => ({
            ...question,
            responses: question.responses.map(response => ({ ...response }))
        })) || [];
        testId.value = response.data.testId || null;
    } catch (error) {
        console.error('Error fetching questions data:', error.response?.data?.message || error.message);
        errorMessage.value = 'Error fetching questions data.';
    }
};

function showNotification(message) {
    notification.value.message = message;
    notification.value.visible = true;

    setTimeout(() => {
        notification.value.visible = false;
    }, 3000);
}

const removeResponse = (qIndex, rIndex) => {
    questions.value[qIndex].responses.splice(rIndex, 1);
};

const handleCorrectResponseChange = (question, responseIndex) => {
    question.responses[responseIndex].is_correct = !question.responses[responseIndex].is_correct;

    if (question.responses[responseIndex].is_correct) {
        question.responses.forEach((response, index) => {
            if (index !== responseIndex) {
                response.is_correct = false;
            }
        });
    }
};

const validateTest = () => {
    for (const question of questions.value) {
        const hasCorrectResponse = question.responses.some(response => response.is_correct);
        if (!hasCorrectResponse) {
            errorMessage.value = `Question "${question.question_text}" must have at least one correct response.`;
            return false;
        }
    }
    return true;
};

const submitTest = async () => {
    if (!validateTest()) {
        return;
    }

    try {
        const payload = {
            lesson_id: route.params.id,
            questions: JSON.parse(JSON.stringify(questions.value)),
        };

        const response = await apiClient.put(`/lessons/${route.params.id}/questions`, payload);
        console.log('Questions updated successfully:', response.data);
        showNotification('Test updated successfully!');
        successMessage.value = 'Test updated successfully!';
    } catch (error) {
        console.error('Error updating questions:', error.response?.data?.message || error.message);
        errorMessage.value = error.response?.data?.message || 'Error. Please try again.';
    }
};

onMounted(() => {
    fetchTest();
});
</script>

<style scoped>
/* Transition Classes */
.fade-enter-from {
    opacity: 0;
    transform: translateY(-60px);
}
.fade-enter-to {
    opacity: 1;
    transform: translateY(0);
}
.fade-enter-active {
    transition: all 0.3s ease;
}
.fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}
.fade-leave-to {
    opacity: 0;
    transform: translateY(-60px);
}
.fade-leave-active {
    transition: all 0.3s ease;
}
</style>
