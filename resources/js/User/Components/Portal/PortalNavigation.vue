<script setup>
import { Link } from '@inertiajs/vue3'

const navigation = [
    {
        label: 'Dashboard',
        routeName: 'portal.dashboard',
    },
    {
        label: 'Upload Documents',
        routeName: 'portal.documents.upload',
    },
    {
        label: 'My Documents',
        routeName: 'portal.documents.index',
    },
    {
        label: 'Application Status',
        routeName: 'portal.application.status',
    },
    {
        label: 'Appointment',
        routeName: 'portal.appointment.status',
    },
]

const isActive = (routeName) => route().current(routeName)
</script>

<template>
    <nav class="space-y-2">
        <Link
            v-for="item in navigation"
            :key="item.routeName"
            :href="route(item.routeName)"
            class="block rounded-lg px-4 py-3 text-sm font-medium transition"
            :class="
                isActive(item.routeName)
                    ? 'bg-blue-500 text-white'
                    : 'text-slate-300 hover:bg-slate-800 hover:text-white'
            "
        >
            {{ item.label }}
        </Link>
    </nav>
</template>
