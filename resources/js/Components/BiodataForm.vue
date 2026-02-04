<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({
            name: '',
            nisn: '',
            photo: null,
            biodata: {},
        }),
    },
});

const emit = defineEmits(['update:modelValue']);

const form = ref({ ...props.modelValue });
const photoPreview = ref(null);

watch(form, (newValue) => {
    emit('update:modelValue', newValue);
}, { deep: true });

watch(() => props.modelValue, (newValue) => {
    form.value = { ...newValue };
}, { deep: true });

function handlePhotoChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.value.photo = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removePhoto() {
    form.value.photo = null;
    photoPreview.value = null;
}
</script>

<template>
    <div class="space-y-6">
        <!-- Photo Upload -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
            <div class="flex items-center space-x-4">
                <div
                    class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-gray-200"
                >
                    <img
                        v-if="photoPreview"
                        :src="photoPreview"
                        alt="Preview"
                        class="w-full h-full object-cover"
                    />
                    <svg
                        v-else
                        class="w-12 h-12 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>
                </div>
                <div>
                    <label
                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50"
                    >
                        <span>Upload Photo</span>
                        <input
                            type="file"
                            accept="image/*"
                            class="hidden"
                            @change="handlePhotoChange"
                        />
                    </label>
                    <button
                        v-if="photoPreview"
                        type="button"
                        @click="removePhoto"
                        class="ml-2 text-sm text-red-600 hover:text-red-800"
                    >
                        Remove
                    </button>
                </div>
            </div>
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Full Name <span class="text-red-500">*</span>
            </label>
            <input
                v-model="form.name"
                type="text"
                required
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter your full name"
            />
        </div>

        <!-- NISN -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
            <input
                v-model="form.nisn"
                type="text"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter your NISN (optional)"
            />
        </div>

        <!-- Additional Biodata Fields -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Place of Birth</label>
            <input
                v-model="form.biodata.place_of_birth"
                type="text"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="City/Town"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
            <input
                v-model="form.biodata.date_of_birth"
                type="date"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
            <select
                v-model="form.biodata.gender"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
                <option value="">Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea
                v-model="form.biodata.address"
                rows="3"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Your current address"
            ></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name</label>
            <input
                v-model="form.biodata.parent_name"
                type="text"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Parent or guardian name"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Phone</label>
            <input
                v-model="form.biodata.parent_phone"
                type="tel"
                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Phone number"
            />
        </div>
    </div>
</template>
