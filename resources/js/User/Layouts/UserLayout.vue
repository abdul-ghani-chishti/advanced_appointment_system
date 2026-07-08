<script setup>
import UserHeader from '@/User/Components/UserHeader.vue'
import UserFooter from '@/User/Components/UserFooter.vue'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-white">
        <UserHeader :can-login="canLogin" :can-register="canRegister"/>

        <main>
            <slot />
        </main>

        <UserFooter />
    </div>
</template>
