<script setup>
import { Head } from '@inertiajs/vue3'
import PortalLayout from '@/User/Layouts/AuthenticatedUserPortalLayout.vue'

defineProps({
    documents: {
        type: Array,
        default: () => [],
    },
})
// const documents = [
//     {
//         type: 'Passport',
//         filename: 'passport.pdf',
//         status: 'Uploaded',
//         uploadedAt: '12 July 2026',
//     },
//     {
//         type: 'Previous Degree',
//         filename: 'degree.pdf',
//         status: 'Uploaded',
//         uploadedAt: '12 July 2026',
//     },
// ]
</script>

<template>
    <Head title="My Documents" />

    <PortalLayout>
        <section>
            <h1 class="text-3xl font-bold">
                My documents
            </h1>

            <p class="mt-3 text-slate-400">
                Review the documents currently attached to your application.
            </p>
        </section>

        <section class="mt-8 overflow-hidden rounded-2xl border border-slate-800">
            <table class="w-full text-left">
                <thead class="bg-slate-900 text-sm text-slate-400">
                <tr>
                    <th class="px-6 py-4">Document</th>
                    <th class="px-6 py-4">Filename</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Uploaded</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-800 bg-slate-950">
                <tr
                    v-for="document in documents"
                    :key="document.type"
                >
                    <td class="px-6 py-4 font-medium">
                        {{ document.type }}
                    </td>

                    <td class="px-6 py-4 text-slate-400">
                        {{ document.filename }}
                    </td>

                    <td class="px-6 py-4 text-green-400">
                        {{ document.status }}
                    </td>

                    <td class="px-6 py-4 text-slate-400">
                        {{ document.uploaded_at }}
                    </td>
                </tr>
                <tr v-if="documents.length === 0">
                    <td
                        colspan="4"
                        class="px-6 py-12 text-center text-slate-400"
                    >
                        You have not uploaded any documents yet.
                    </td>
                </tr>
                </tbody>
            </table>
        </section>
    </PortalLayout>
</template>
