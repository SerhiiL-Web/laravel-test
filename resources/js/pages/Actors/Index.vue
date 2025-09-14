<template>
    <AppLayout>
        <div class="flex-1 py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Actor Submissions
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    View all submitted actor information
                </p>
            </div>

            <!-- Success Message -->
            <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Add New Submission Button -->
            <div class="mb-6">
                <Link
                    href="/create"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                >
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add New Submission
                </Link>
            </div>

            <!-- Table -->
            <div class="bg-white shadow-lg rounded-xl border border-slate-200 overflow-hidden">
                <div v-if="actors.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new actor submission.</p>
                    <div class="mt-6">
                        <Link
                            href="/create"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        >
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            New Submission
                        </Link>
                    </div>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-slate-50 to-blue-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    First Name
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Address
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Gender
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Height
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <tr v-for="actor in actors" :key="actor.id" class="hover:bg-slate-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">
                                    {{ actor.first_name || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ actor.address || 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <span v-if="actor.gender" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800">
                                        {{ actor.gender }}
                                    </span>
                                    <span v-else class="text-slate-400">N/A</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ actor.height || 'N/A' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
    actors: Array,
})
</script>
