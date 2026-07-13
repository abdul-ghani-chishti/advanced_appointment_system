<script setup>
import { Head } from '@inertiajs/vue3'
import PortalLayout from '@/User/Layouts/AuthenticatedUserPortalLayout.vue'

const appointment = null
</script>

<template>
    <Head title="Appointment Status" />

    <PortalLayout>
        <section>
            <h1 class="text-3xl font-bold">
                Appointment status
            </h1>

            <p class="mt-3 text-slate-400">
                Your assigned appointment will appear here after processing.
            </p>
        </section>

        <section class="mt-8 rounded-2xl border border-slate-800 bg-slate-900 p-8">
            <div v-if="appointment">
                <h2 class="text-2xl font-bold text-green-400">
                    Appointment assigned
                </h2>

                <dl class="mt-6 grid gap-5 md:grid-cols-2">
                    <div>
                        <dt class="text-sm text-slate-400">Date</dt>
                        <dd class="mt-1 text-lg font-semibold">
                            {{ appointment.date }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-400">Time</dt>
                        <dd class="mt-1 text-lg font-semibold">
                            {{ appointment.time }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div v-else class="text-center">
                <div class="text-5xl">⏳</div>

                <h2 class="mt-5 text-2xl font-bold">
                    No appointment assigned yet
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-slate-400">
                    Your application must first be processed by the nightly job.
                    You will receive an email when an appointment is assigned.
                </p>
            </div>
        </section>
    </PortalLayout>
</template>
