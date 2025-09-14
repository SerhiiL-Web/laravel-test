<template>
    <AppLayout>
        <div class="flex-1 py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="max-w-md mx-auto">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Submit Actor Information
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Please enter your first name and last name, and also provide your address.
                </p>
            </div>

            <form @submit.prevent="submit" novalidate class="mt-8 space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="text"
                                autocomplete="email"
                                :class="[
                                    'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 placeholder-slate-400 text-slate-900',
                                    errors.email 
                                        ? 'border-red-300 focus:ring-red-500 bg-red-50' 
                                        : 'border-slate-300 focus:ring-blue-500'
                                ]"
                                placeholder="Enter your email address"
                            />
                            <div v-if="errors.email" class="mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-md px-3 py-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ errors.email }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Actor Description</label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="6"
                                :class="[
                                    'w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200 placeholder-slate-400 text-slate-900 resize-none',
                                    errors.description 
                                        ? 'border-red-300 focus:ring-red-500 bg-red-50' 
                                        : 'border-slate-300 focus:ring-blue-500'
                                ]"
                                placeholder="Please describe the actor including first name, last name, address, height, weight, gender, and age..."
                            ></textarea>
                            <div v-if="errors.description" class="mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-md px-3 py-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ errors.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <button
                        type="submit"
                        :disabled="processing"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    >
                        <span v-if="processing" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                        <span v-else>Submit Actor Information</span>
                    </button>

                    <div class="text-center">
                        <Link
                            href="/"
                            class="inline-flex items-center text-blue-600 hover:text-purple-600 font-medium transition-colors duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            View All Submissions
                        </Link>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    errors: Object,
})

const form = useForm({
    email: '',
    description: '',
})

const processing = computed(() => form.processing)

const submit = () => {
    console.log('Form submitted with data:', {
        email: form.email,
        description: form.description
    })
    

    form.post('/actors', {
        onSuccess: () => {
            console.log('Form submitted successfully')
        },
        onError: (errors) => {
            console.log('Validation errors:', errors)
        },
    })
}
</script>
