<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import PortalLayout from '@/User/Layouts/PortalLayout.vue'
import StatusBadge from '@/User/Components/Portal/StatusBadge.vue'

const props = defineProps({
    application: {
        type: Object,
        required: true,
    },
})

const timeline = computed(() => {
    const allUploaded = props.application.all_documents_uploaded

    const processingStatuses = [
        'processing',
        'processed',
        'appointment_assigned',
    ]

    const hasStartedProcessing = processingStatuses.includes(
        props.application.status
    )

    const processingCompleted = [
        'processed',
        'appointment_assigned',
    ].includes(props.application.status)

    return [
        {
            title: 'All required documents uploaded',
            completed: allUploaded,
        },
        {
            title: 'Waiting for nightly processing',
            completed: allUploaded,
        },
        {
            title: 'OCR and text extraction',
            completed: hasStartedProcessing,
        },
        {
            title: 'Priority score calculated',
            completed: processingCompleted,
        },
        {
            title: 'Appointment assigned',
            completed:
                props.application.status === 'appointment_assigned',
        },
    ]
})
</script>

<template>
    <Head title="Application Status" />

    <PortalLayout>
        <section>
            <h1 class="text-3xl font-bold">
                Application status
            </h1>

            <div class="mt-5">
                <StatusBadge :status="currentStatus" />
            </div>
        </section>

        <section class="mt-8 rounded-2xl border border-slate-800 bg-slate-900 p-8">
            <h2 class="text-xl font-bold">
                Processing progress
            </h2>

            <div class="mt-7 space-y-5">
                <div
                    v-for="(step, index) in timeline"
                    :key="step.title"
                    class="flex items-center gap-4"
                >
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full font-bold"
                        :class="
                            step.completed
                                ? 'bg-green-500 text-white'
                                : 'bg-slate-800 text-slate-400'
                        "
                    >
                        {{ step.completed ? '✓' : index + 1 }}
                    </div>

                    <p
                        :class="
                            step.completed
                                ? 'text-white'
                                : 'text-slate-400'
                        "
                    >
                        {{ step.title }}
                    </p>
                </div>
            </div>
        </section>
    </PortalLayout>
</template>
