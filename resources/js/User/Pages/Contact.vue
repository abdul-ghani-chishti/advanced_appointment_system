<script setup>
import { Head } from '@inertiajs/vue3'
import UserLayout from '@/User/Layouts/UserLayout.vue'
import ContactForm from '@/User/Components/Contact/ContactForm.vue'
import ContactInformation from '@/User/Components/Contact/ContactInformation.vue'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})
</script>

<template>
    <Head title="Contact" />

    <UserLayout :can-login="canLogin" :can-register="canRegister">

        <!-- Hero -->
        <section class="bg-slate-900 py-20">
            <div class="mx-auto max-w-7xl px-6 text-center">
                <h1 class="text-5xl font-bold">Contact Us</h1>
                <p class="mx-auto mt-6 max-w-3xl text-lg text-slate-300">
                    Have questions about appointments or document submission?
                    We'd love to hear from you.
                </p>
            </div>
        </section>

        <!-- Content -->
        <section class="py-20">
            <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-2">
                <ContactInformation />

                <ContactForm />
            </div>
        </section>
    </UserLayout>
</template>
