<script setup>
import PortalHeader from '@/User/Components/Portal/PortalHeader.vue'
import PortalNavigation from '@/User/Components/Portal/PortalNavigation.vue'
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-white">
        <PortalHeader />

        <div class="mx-auto grid max-w-7xl gap-8 px-6 py-10 lg:grid-cols-[250px_1fr]">
            <aside>
                <div class="sticky top-6 rounded-2xl border border-slate-800 bg-slate-900 p-4">
                    <PortalNavigation />
                </div>
            </aside>

            <main class="min-w-0">
                <slot />
            </main>
        </div>
    </div>
</template>
