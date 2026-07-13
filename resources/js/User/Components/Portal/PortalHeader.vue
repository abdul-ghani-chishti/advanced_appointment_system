<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
    <header class="border-b border-slate-800 bg-slate-950">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
            <Link :href="route('home')" class="text-xl font-bold text-white">
                Advance Appoint
                <span class="text-blue-400">System</span>
            </Link>

            <div class="flex items-center gap-4">
                <span class="hidden text-sm text-slate-300 sm:block">
                    {{ page.props.auth.user?.name }}
                </span>

                <Link
                    :href="route('profile.edit')"
                    class="text-sm text-slate-300 hover:text-white"
                >
                    Profile
                </Link>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-lg border border-slate-700 px-4 py-2 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white"
                >
                    Logout
                </Link>
            </div>
        </div>
    </header>
</template>
