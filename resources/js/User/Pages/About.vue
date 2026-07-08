<script setup>
import {Head} from '@inertiajs/vue3'
import UserLayout from '@/User/Layouts/UserLayout.vue'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})
</script>

<template>
    <Head title="About"/>

    <UserLayout :can-login="canLogin" :can-register="canRegister">

        <!-- Hero -->
        <section class="bg-slate-900 py-20">
            <div class="mx-auto max-w-7xl px-6 text-center">

                <h1 class="text-5xl font-bold text-white">
                    About Our System
                </h1>

                <p class="mx-auto mt-6 max-w-3xl text-lg text-slate-300">
                    Our Appointment Management System simplifies the process of
                    document submission, verification, and appointment scheduling.
                    Instead of manually reviewing every application, the system
                    intelligently processes uploaded documents and helps assign
                    appointments based on predefined priority rules.
                </p>

            </div>
        </section>

        <!-- Mission -->
        <section class="py-20">
            <div class="mx-auto grid max-w-7xl gap-12 px-6 md:grid-cols-2">

                <div>
                    <h2 class="text-3xl font-bold text-white">
                        Our Mission
                    </h2>

                    <p class="mt-6 text-slate-400">
                        We aim to make appointment scheduling transparent,
                        efficient, and fair by using automated document processing
                        and intelligent prioritization algorithms.
                    </p>

                    <p class="mt-4 text-slate-400">
                        Applicants can upload all required documents online,
                        while administrators can review applications through a
                        secure management portal.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-8">

                    <h3 class="text-2xl font-semibold text-white">
                        Key Features
                    </h3>

                    <ul class="mt-6 space-y-4 text-slate-300">
                        <li>✅ Secure document upload</li>
                        <li>✅ OCR-based text extraction</li>
                        <li>✅ Intelligent document analysis</li>
                        <li>✅ Priority-based appointment scheduling</li>
                        <li>✅ Automatic email notifications</li>
                        <li>✅ Secure administration panel</li>
                    </ul>

                </div>

            </div>
        </section>

        <!-- Technologies -->
        <section class="border-t border-slate-800 py-20">
            <div class="mx-auto max-w-7xl px-6">

                <h2 class="mb-10 text-center text-3xl font-bold text-white">
                    Technologies Used
                </h2>

                <div class="grid gap-6 md:grid-cols-4">

                    <div class="rounded-xl bg-slate-900 p-6 text-center">
                        <h3 class="font-semibold">Laravel</h3>
                        <p class="mt-2 text-sm text-slate-400">
                            Backend & API
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-900 p-6 text-center">
                        <h3 class="font-semibold">Vue.js</h3>
                        <p class="mt-2 text-sm text-slate-400">
                            User Interface
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-900 p-6 text-center">
                        <h3 class="font-semibold">OCR</h3>
                        <p class="mt-2 text-sm text-slate-400">
                            Document Processing
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-900 p-6 text-center">
                        <h3 class="font-semibold">MySQL</h3>
                        <p class="mt-2 text-sm text-slate-400">
                            Data Storage
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </UserLayout>
</template>
