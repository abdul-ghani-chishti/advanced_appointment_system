<script setup>
import {Head} from '@inertiajs/vue3'
import UserLayout from '@/User/Layouts/UserLayout.vue'
import ServiceCards from "@/User/Components/Services/ServiceCards.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})

const services = [
    {
        icon: '📄',
        title: 'Document Upload',
        description: 'Securely upload transcripts, degrees, passports, admission letters and supporting documents.',
    },
    {
        icon: '🔍',
        title: 'OCR Processing',
        description: 'Extract text from scanned images and PDF documents automatically.',
    },
    {
        icon: '🤖',
        title: 'AI Validation',
        description: 'Analyze extracted information and verify important fields using AI.',
    },
    {
        icon: '⭐',
        title: 'Priority Scoring',
        description: 'Calculate applicant priority based on GPA, admission dates and custom rules.',
    },
    {
        icon: '📅',
        title: 'Appointment Scheduling',
        description: 'Automatically assign appointment dates according to priority scores.',
    },
    {
        icon: '📧',
        title: 'Email Notifications',
        description: 'Notify applicants immediately after appointment allocation.',
    },
]
</script>

<template>

    <Head title="Services"/>

    <UserLayout :can-login="canLogin" :can-register="canRegister">

        <!-- Hero -->
        <section class="bg-slate-900 py-20">
            <div class="mx-auto max-w-7xl px-6 text-center">

                <h1 class="text-5xl font-bold text-white">
                    Our Services
                </h1>

                <p class="mx-auto mt-6 max-w-3xl text-lg text-slate-300">
                    We simplify appointment management through secure document
                    submission, automated processing, and intelligent scheduling.
                </p>

            </div>
        </section>

        <!-- Services -->
        <section class="py-20">
            <div class="mx-auto grid max-w-7xl gap-8 px-6 md:grid-cols-2 lg:grid-cols-3">
                <ServiceCards
                v-for="service in services"
                :key="service.title"
                :icon="service.icon"
                :title="service.title"
                :description="service.description"
                />
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="border-t border-slate-800 py-20">
            <div class="mx-auto max-w-7xl px-6 text-center">

                <h2 class="text-4xl font-bold mb">
                    Why Choose Our Platform?
                </h2>

                <div class="mt-12 grid gap-8 md:grid-cols-4">

                    <div>
                        <div class="text-5xl">🔒</div>
                        <h3 class="mt-4 text-xl font-semibold">
                            Secure
                        </h3>
                    </div>

                    <div>
                        <div class="text-5xl">⚡</div>
                        <h3 class="mt-4 text-xl font-semibold">
                            Fast
                        </h3>
                    </div>

                    <div>
                        <div class="text-5xl">🎯</div>
                        <h3 class="mt-4 text-xl font-semibold">
                            Accurate
                        </h3>
                    </div>

                    <div>
                        <div class="text-5xl">🤝</div>
                        <h3 class="mt-4 text-xl font-semibold">
                            Transparent
                        </h3>
                    </div>

                </div>

            </div>
        </section>

    </UserLayout>

</template>
