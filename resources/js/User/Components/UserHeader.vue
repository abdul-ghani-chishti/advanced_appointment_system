<script setup>
import {Link} from '@inertiajs/vue3'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})
</script>

<template>
    <header class="sticky top-0 z-50 border-b border-slate-800 bg-slate-950/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">

            <!-- Logo -->
            <Link href="/" class="text-2xl font-bold tracking-wide">
                Advance Appoint
                <span class="text-blue-400"> System</span>
            </Link>

            <!-- Navigation -->
            <nav class="hidden items-center gap-8 md:flex">
                <Link
                    :href="route('home')"
                    class="text-slate-300 transition hover:text-blue-400"
                >
                    Home
                </Link>

                <Link
                    :href="route('about')"
                    class="text-slate-300 transition hover:text-blue-400"
                >
                    About
                </Link>

                <Link
                    :href="route('services')"
                    class="text-slate-300 transition hover:text-blue-400"
                >
                    Services
                </Link>

                <Link
                    :href="route('contact')"
                    class="text-slate-300 transition hover:text-blue-400"
                >
                    Contact
                </Link>
            </nav>

            <!-- Right Side -->
            <div v-if="canLogin" class="flex items-center gap-3">
                <template v-if="$page.props.auth.user">
                    <Link
                        :href="route('portal.dashboard')"
                        class="rounded-lg bg-blue-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-blue-600"
                    >
                        Dashboard
                    </Link>
                </template>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="text-slate-300 transition hover:text-white"
                    >
                        Login
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="rounded-lg bg-blue-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-blue-600"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </div>
    </header>
</template>
