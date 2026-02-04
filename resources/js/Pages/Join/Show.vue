<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import QuizRenderer from '@/Components/QuizRenderer.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';

const { t } = useTranslations();

const props = defineProps({
    classroom: Object,
    quizTemplate: Object,
});

const page = usePage();
const photoPreview = ref(null);

const form = useForm({
    name: '',
    nisn: '',
    photo: null,
    biodata: {
        place_of_birth: '',
        date_of_birth: '',
        gender: '',
        address: '',
        parent_name: '',
        parent_phone: '',
    },
    answers: {},
});

const hasQuiz = computed(() => props.quizTemplate && props.quizTemplate.questions?.length > 0);

function handlePhotoChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.photo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removePhoto() {
    form.photo = null;
    photoPreview.value = null;
}

const submit = () => {
    form.post(route('join.store', props.classroom.unit_code), {
        forceFormData: true,
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="'Join ' + classroom.name" />

        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ classroom.name }}</h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600">Please fill out the form below to register.</p>
            </div>

            <div v-if="page.props.flash?.success" class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Photo Upload -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ t('join.photo') }}</h2>
                    <div class="flex items-center space-x-4">
                        <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-gray-200">
                            <img v-if="photoPreview" :src="photoPreview" alt="Preview" class="w-full h-full object-cover" />
                            <svg v-else class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <label class="cursor-pointer inline-flex items-center px-4 py-3 min-h-[44px] bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50">
                                <span>{{ t('join.upload_photo') }}</span>
                                <input type="file" accept="image/*" class="hidden" @change="handlePhotoChange" />
                            </label>
                            <button v-if="photoPreview" type="button" @click="removePhoto" class="ml-2 text-sm text-red-600 hover:text-red-800 py-2">
                                {{ t('join.remove') }}
                            </button>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.photo" />
                </div>

                <!-- Basic Info -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ t('join.basic_info') }}</h2>

                    <div class="space-y-4">
                        <div>
                            <InputLabel for="name" :value="t('join.full_name')" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                :placeholder="t('join.full_name_placeholder')"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="nisn" value="NISN (Optional)" />
                            <input
                                id="nisn"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                v-model="form.nisn"
                                placeholder="Enter your NISN"
                            />
                            <InputError class="mt-2" :message="form.errors.nisn" />
                        </div>
                    </div>
                </div>

                <!-- Additional Biodata -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ t('join.additional_info') }}</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="place_of_birth" :value="t('join.place_of_birth')" />
                            <TextInput
                                id="place_of_birth"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.biodata.place_of_birth"
                                placeholder="City/Town"
                            />
                        </div>

                        <div>
                            <InputLabel for="date_of_birth" :value="t('join.date_of_birth')" />
                            <input
                                id="date_of_birth"
                                type="date"
                                class="mt-1 block w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                v-model="form.biodata.date_of_birth"
                            />
                        </div>

                        <div>
                            <InputLabel for="gender" :value="t('join.gender')" />
                            <select
                                id="gender"
                                v-model="form.biodata.gender"
                                class="mt-1 block w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">{{ t('join.select_gender') }}</option>
                                <option value="male">{{ t('join.male') }}</option>
                                <option value="female">{{ t('join.female') }}</option>
                            </select>
                        </div>

                        <div>
                            <InputLabel for="parent_phone" :value="t('join.parent_phone')" />
                            <input
                                id="parent_phone"
                                type="tel"
                                inputmode="tel"
                                class="mt-1 block w-full min-h-[44px] border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                v-model="form.biodata.parent_phone"
                                placeholder="Phone number"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="parent_name" :value="t('join.parent_name')" />
                            <TextInput
                                id="parent_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.biodata.parent_name"
                                placeholder="Parent or guardian name"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <InputLabel for="address" :value="t('join.address')" />
                            <textarea
                                id="address"
                                v-model="form.biodata.address"
                                rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Your current address"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Quiz Section -->
                <div v-if="hasQuiz" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">{{ quizTemplate.title }}</h2>
                    <QuizRenderer
                        :questions="quizTemplate.questions"
                        v-model="form.answers"
                    />
                    <InputError class="mt-2" :message="form.errors.answers" />
                </div>

                <!-- Submit -->
                <div class="flex justify-center pb-4">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="w-full sm:w-auto px-8 py-4 min-h-[48px] text-base"
                    >
                        {{ form.processing ? t('join.submitting') : t('join.submit') }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
