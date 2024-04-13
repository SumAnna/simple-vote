<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head } from '@inertiajs/vue3';
import {ref, reactive, watchEffect} from 'vue';

let props = defineProps({
    votes: {
        type: Object,
        default: () => ({})
    },
    hasVoteRecorded: {
        type: Boolean,
        default: false
    },
    questions: {
        type: Object,
        default: () => ({})
    }
});

let questionList = reactive({})
let voteList = reactive({});
let showResults = false;

const form = reactive({});
const voteResult = ref(false);
const resp = ref('');
const container = reactive({ resp });

const resetForm = () => {
    Object.keys(form.options).forEach(key => {
        form.options[key] = null;
    });
};

watchEffect(() => {
    if (form.options === undefined) {
        form.options = {};
    }
    if (props.questions && props.questions.data) {
        props.questions.data.forEach((question, index) => {
            if (form.options[index] === undefined) {
                form.options[index] = null;
            }
        });
    }

    if (props.hasVoteRecorded) {
        showResults = true;
    }

    questionList = props.questions;

    voteList = props.votes.data;
});

const submitPoll = () => {
    voteResult.value = true;
    let data = {option_ids: Object.values(form.options)}
    axios.post('/vote', data, {
        headers: {
            'Content-Type': 'application/json',
        }
    })
        .then(response => {
            if (response.status == 200 && true === response.data.saved) {
                container.resp += 'Your vote stored successfully!\n';
                showResults = true;
                questionList = response.data.results;
                voteList = response.data.votes;
            }
        })
        .catch(error => {
            if (error.response && error.response.data && error.response.data.errors) {
                for (const [key, value] of Object.entries(error.response.data.errors)) {
                    container.resp += value + '\n';
                }
            }
    });
};

const hasNeededOption = (optionId) => {
    return voteList.some(voteItem => (voteItem.option_id ?? voteItem.id) === optionId);
};


const closeModal = () => {
    voteResult.value = false;
    container.resp = '';
    resetForm();
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="bg-white container mx-auto my-8 p-5 border rounded-lg shadow max-w-[877px]" v-if="showResults">
                <h2 class="text-xl font-bold mb-4">Your Votes</h2>
                <div v-for="(options, question, index) in questionList" :key="index" class="my-8">
                    <p class="text-lg font-medium text-gray-500 mb-4">{{ question }}</p>
                    <div class="border-b border-indigo-400"></div>
                    <ul class="divide-y divide-gray-200">
                        <li class="py-4 flex justify-between items-center" v-for="(option, optionText) in options" :key="option.id">
                            <span>{{ optionText }}</span>
                            <span class="bg-blue-500 text-white font-bold py-1 px-3 rounded-full text-xs" v-if="hasNeededOption(option.id)">Voted</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="bg-white container mx-auto my-8 p-5 border rounded-lg shadow max-w-[877px]" v-else>
                <h1 class="text-xl font-bold mb-4">Poll: Your Opinion Matters!</h1>
                <form @submit.prevent="submitPoll">
                    <div
                        v-for="(options, question, index) in questions"
                        :key="index"
                        class="mb-4"
                    >
                        <label :for="'question-' + index" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ question }}
                        </label>
                        <div v-for="(option, optionText) in options" :key="option.id" class="mb-2">
                            <input
                                :id="'option-' + option.id"
                                type="radio"
                                :name="'question-' + index"
                                :value="option.id"
                                class="mr-2"
                                v-model="form.options[index]"
                            />
                            <label :for="'option-' + option.id" class="text-gray-600">
                                {{ optionText }}
                            </label>
                        </div>
                    </div>
                    <PrimaryButton> Submit </PrimaryButton>
                </form>
                <Modal :show="voteResult" @close="closeModal">
                    <div class="p-6">
                        <h2 v-if="container.resp.length > 0" class="text-lg font-medium text-gray-900">
                            {{ container.resp }}
                        </h2>
                        <div class="mt-6 flex justify-end">
                            <SecondaryButton @click="closeModal"> OK </SecondaryButton>
                        </div>
                    </div>
                </Modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
