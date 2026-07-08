<script setup>
import {Head, Link} from '@inertiajs/vue3';
import UserLayout from '@/User/Layouts/UserLayout.vue'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Home"/>
        <UserLayout :can-login="canLogin" :can-register="canRegister">
            <!-- Hero Section -->
            <section class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 py-20 lg:grid-cols-2">
                <div>
                    <p class="mb-4 inline-block rounded-full bg-blue-500/10 px-4 py-2 text-sm text-blue-300">
                        Smart document-based appointment system
                    </p>

                    <h1 class="text-4xl font-bold leading-tight md:text-6xl">
                        Upload documents.
                        <br/>
                        Get appointment priority automatically.
                    </h1>

                    <p class="mt-6 max-w-xl text-lg text-slate-300">
                        A modern platform where users upload required documents such as degree,
                        transcript, passport, and admission letter. The system processes them
                        securely and helps assign appointments based on priority.
                    </p>

                    <!--                <div class="mt-8 flex flex-wrap gap-4">-->
                    <!--                    <Link-->
                    <!--                        :href="route('register')"-->
                    <!--                        class="rounded-xl bg-blue-500 px-7 py-3 font-semibold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-600"-->
                    <!--                    >-->
                    <!--                        Create Account-->
                    <!--                    </Link>-->

                    <!--                    <Link-->
                    <!--                        :href="route('login')"-->
                    <!--                        class="rounded-xl border border-slate-700 px-7 py-3 font-semibold text-slate-200 hover:bg-slate-900"-->
                    <!--                    >-->
                    <!--                        Login-->
                    <!--                    </Link>-->
                    <!--                </div>-->
                </div>

                <!-- Hero Card -->
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 shadow-2xl">
                    <div class="rounded-2xl bg-slate-950 p-6">
                        <h2 class="text-xl font-semibold">Document Processing Flow</h2>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-xl bg-slate-900 p-4">
                                📄 Upload documents
                            </div>
                            <div class="rounded-xl bg-slate-900 p-4">
                                🔍 OCR extracts text
                            </div>
                            <div class="rounded-xl bg-slate-900 p-4">
                                🤖 AI validates information
                            </div>
                            <div class="rounded-xl bg-slate-900 p-4">
                                ⭐ Priority score is calculated
                            </div>
                            <div class="rounded-xl bg-blue-500 p-4 font-semibold">
                                📧 Appointment email sent
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Hero Section end-->
            <!-- Features -->
            <section class="mx-auto max-w-7xl px-6 py-16">
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                        <h3 class="text-xl font-semibold">Secure Uploads</h3>
                        <p class="mt-3 text-slate-400">
                            Users can upload multiple documents safely through a simple dashboard.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                        <h3 class="text-xl font-semibold">OCR & AI Processing</h3>
                        <p class="mt-3 text-slate-400">
                            Extract text from documents and analyze important fields automatically.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6">
                        <h3 class="text-xl font-semibold">Priority Appointments</h3>
                        <p class="mt-3 text-slate-400">
                            Assign appointments based on GPA, admission date, and other priority rules.
                        </p>
                    </div>
                </div>
            </section>
            <!-- Features end-->
        </UserLayout>
</template>
