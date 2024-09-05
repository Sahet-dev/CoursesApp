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
        <h1 class="text-2xl font-bold mb-4">View and Edit Test</h1>
        <button @click="updateTest"
                class="px-4 py-2 rounded-md bg-blue-500 text-white font-semibold hover:bg-blue-600 transition duration-200">
            Update Test
        </button>
        <form @submit.prevent="submitTest" class="space-y-4">
            <!-- Questions Section -->
            <div v-for="(question, qIndex) in questions" :key="qIndex" class="border-t border-gray-300 pt-4 mt-4">
                <!-- Question Text -->
                <div>
                    <p v-if="!question.isNew" class="text-gray-700 font-medium mb-2">
                        Question {{ qIndex + 1 }}: {{ question.question_text }}
                    </p>
                    <div v-if="question.isNew">
                        <label :for="'question' + qIndex" class="block text-gray-700 font-medium mb-2">Question {{ qIndex + 1 }}:</label>
                        <input
                            v-model="question.question_text"
                            :id="'question' + qIndex"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter the question text"
                        />
                    </div>
                </div>

                <!-- Responses Section -->
                <div v-for="(response, rIndex) in question.responses" :key="rIndex" class="mt-2">
                    <div :class="response.is_correct ? 'bg-teal-100 p-2 rounded-md' : 'bg-white p-2 rounded-md'">
                        <div v-if="!response.isNew">
                            <p class="text-gray-700 font-medium mb-2">
                                Response {{ rIndex + 1 }}: {{ response.response_text }}
                            </p>
                        </div>

                        <div v-if="response.isNew">
                            <label :for="'response' + qIndex + '-' + rIndex" class="block text-gray-700 font-medium mb-2">
                                Response {{ rIndex + 1 }}:
                            </label>
                            <input
                                v-model="response.response_text"
                                :id="'response' + qIndex + '-' + rIndex"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter the response text" required
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
                                class="text-red-600 hover:text-red-700"
                            >
                                Remove Response
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Conditionally Render Add Response Button -->
                <button
                    v-if="question.isNew"
                    type="button"
                    @click="addResponse(qIndex)"
                    class="mt-2 text-blue-600 hover:text-blue-700"
                >
                    Add Response
                </button>
            </div>

            <!-- Add Question Button -->
            <button
                type="button"
                @click="addQuestion"
                class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow-sm hover:bg-gray-300 mb-4"
            >
                Add Question
            </button>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Create Test
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
import apiClient from "../../api/axios.js"; // Adjust the path as needed
import {useRoute, useRouter} from 'vue-router';


const router = useRouter();

const route = useRoute();
const notification = ref({
    message: '',
    visible: false
});
const testId = ref(null);

const errorMessage = ref('');
const successMessage = ref('');

const questions = ref([]);

// Fetch Existing Test Data
const fetchTest = async () => {
    try {
        const lessonId = route.params.id;
        const response = await apiClient.get(`/lessons/${lessonId}/questions`);
        questions.value = response.data.questions.map(question => ({
            ...question,
            isNew: false,
            responses: question.responses.map(response => ({
                ...response,
                isNew: false
            }))
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

// Add a New Question
const addQuestion = () => {
    questions.value.push({
        question_text: '',
        responses: Array.from({ length: 4 }, () => ({
            response_text: '',
            is_correct: false,
            isNew: true
        })),
        isNew: true
    });
};

// Remove a Question
const removeQuestion = (qIndex) => {
    questions.value.splice(qIndex, 1);
};

// Add a New Response
const addResponse = (qIndex) => {
    questions.value[qIndex].responses.push({
        response_text: '',
        is_correct: false,
        isNew: true
    });
};

// Remove a Response
const removeResponse = (qIndex, rIndex) => {
    questions.value[qIndex].responses.splice(rIndex, 1);
};

// Handle Checkbox Change
const handleCorrectResponseChange = (question, responseIndex) => {
    question.responses[responseIndex].is_correct = !question.responses[responseIndex].is_correct;

    if (question.responses[responseIndex].is_correct) {
        // Uncheck all other checkboxes if one is checked as correct
        question.responses.forEach((response, index) => {
            if (index !== responseIndex) {
                response.is_correct = false; // Uncheck other checkboxes
            }
        });
    }
};

// Validate Questions and Responses
const validateTest = () => {
    for (const question of questions.value) {
        // Check if there is at least one correct response for each question
        const hasCorrectResponse = question.responses.some(response => response.is_correct);
        if (!hasCorrectResponse) {
            errorMessage.value = `Question "${question.question_text}" must have at least one correct response.`;
            return false;
        }
    }
    return true;
};



const updateTest = async () => {

    const courseId = route.params.id;

    // Redirect to the UpdateTest page
    router.push({ name: 'UpdateTest', params: { id: courseId } });


};


// Submit Create Test Data
const submitTest = async () => {
    if (!validateTest()) {
        return; // Validation failed, do not submit
    }

    try {
        const payload = {
            lesson_id: route.params.id,
            questions: JSON.parse(JSON.stringify(questions.value)), // Deep clone to avoid reactivity issues
        };

        const response = await apiClient.put(`/lessons/${route.params.id}/questions`, payload); //
        console.log('Questions created successfully:', response.data);

        showNotification('New Question and Answers Created')
        successMessage.value = 'Questions created successfully!';
    } catch (error) {
        console.error('Error creating questions:', error.response?.data?.message || error.message);
        errorMessage.value = error.response?.data?.message || 'Error. Please try again.';
    }
};

// Fetch Test on Mount
onMounted(() => {
    fetchTest();
});
</script>
