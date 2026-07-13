<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import PortalLayout from '@/User/Layouts/AuthenticatedUserPortalLayout.vue'
import StatusBadge from '@/User/Components/Portal/StatusBadge.vue'

const page = usePage()

const requiredDocuments = [
    { name: 'Passport', uploaded: true },
    { name: 'Previous Degree', uploaded: true },
    { name: 'Transcript', uploaded: false },
    { name: 'Admission Letter', uploaded: false },
]

const uploadedCount = requiredDocuments.filter(
    (document) => document.uploaded,
).length

const currentStatus = 'pending_upload'
</script>

<template>
    <Head title="Portal Dashboard" />

    <PortalLayout>
        <section>
            <p class="text-sm font-semibold uppercase tracking-widest text-blue-400">
                User portal
            </p>

            <h1 class="mt-3 text-3xl font-bold md:text-4xl">
                Welcome, {{ page.props.auth.user?.name }}
            </h1>

            <p class="mt-3 max-w-2xl text-slate-400">
                Upload the required documents and follow the progress of your
                appointment application.
            </p>
        </section>

        <section class="mt-10 grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Current status</p>

                <div class="mt-4">
                    <StatusBadge :status="currentStatus" />
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Documents uploaded</p>

                <p class="mt-3 text-3xl font-bold">
                    {{ uploadedCount }}/{{ requiredDocuments.length }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                <p class="text-sm text-slate-400">Appointment</p>

                <p class="mt-3 text-xl font-semibold text-amber-300">
                    Not assigned
                </p>
            </div>
        </section>

        <section class="mt-8 grid gap-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold">
                            Required documents
                        </h2>

                        <p class="mt-2 text-sm text-slate-400">
                            Upload every required document before nightly processing.
                        </p>
                    </div>

                    <Link
                        :href="route('portal.documents.upload')"
                        class="rounded-lg bg-blue-500 px-4 py-2 text-sm font-semibold hover:bg-blue-600"
                    >
                        Upload
                    </Link>
                </div>

                <div class="mt-6 space-y-3">
                    <div
                        v-for="document in requiredDocuments"
                        :key="document.name"
                        class="flex items-center justify-between rounded-xl border border-slate-800 bg-slate-950 p-4"
                    >
                        <span>{{ document.name }}</span>

                        <span
                            :class="
                                document.uploaded
                                    ? 'text-green-400'
                                    : 'text-amber-400'
                            "
                        >
                            {{ document.uploaded ? 'Uploaded' : 'Required' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-7">
                <h2 class="text-xl font-bold">
                    Processing workflow
                </h2>

                <div class="mt-6 space-y-4">
                    <div class="rounded-xl bg-slate-950 p-4">
                        1. Upload all required documents
                    </div>

                    <div class="rounded-xl bg-slate-950 p-4">
                        2. Wait for nightly processing
                    </div>

                    <div class="rounded-xl bg-slate-950 p-4">
                        3. OCR extracts document information
                    </div>

                    <div class="rounded-xl bg-slate-950 p-4">
                        4. Priority score is calculated
                    </div>

                    <div class="rounded-xl bg-slate-950 p-4">
                        5. Appointment is assigned by email
                    </div>
                </div>
            </div>
        </section>
    </PortalLayout>
</template>
