<script setup>
import { computed } from 'vue'

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
})

const statusClasses = {
    pending_upload:
        'bg-amber-500/10 text-amber-300 border-amber-500/30',

    uploaded:
        'bg-amber-500/10 text-amber-300 border-amber-500/30',

    waiting_processing:
        'bg-blue-500/10 text-blue-300 border-blue-500/30',

    processing:
        'bg-purple-500/10 text-purple-300 border-purple-500/30',

    processed:
        'bg-cyan-500/10 text-cyan-300 border-cyan-500/30',

    appointment_assigned:
        'bg-green-500/10 text-green-300 border-green-500/30',

    failed:
        'bg-red-500/10 text-red-300 border-red-500/30',
}

const statusLabels = {
    pending_upload: 'Documents Required',
    uploaded: 'Documents Uploaded',
    waiting_processing: 'Waiting for Processing',
    processing: 'Processing',
    processed: 'Processing Completed',
    appointment_assigned: 'Appointment Assigned',
    failed: 'Processing Failed',
}

const classes = computed(() => {
    return (
        statusClasses[props.status] ??
        'bg-slate-800 text-slate-300 border-slate-700'
    )
})

const label = computed(() => {
    return statusLabels[props.status] ?? props.status
})
</script>

<template>
    <pre>{{props}}</pre>
    <span
        class="inline-flex rounded-full border px-3 py-1 text-sm font-semibold"
        :class="classes"
    >
        {{ label }}
    </span>
    <span v-if="props.status === 'uploaded'"
        class="mt-2 inline-flex rounded-full border px-3 py-1 text-sm font-semibold"
        :class="classes"
    >
        Wait to Process
    </span>
</template>
