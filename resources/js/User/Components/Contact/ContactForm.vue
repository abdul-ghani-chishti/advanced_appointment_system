<script setup>
import { ref } from 'vue'

const form = ref({
    name:'',
    email:'',
    subject:'',
    message:''
})
const submit = ()=>{
    console.log(form.value)
}
</script>

<template>

    <form @submit.prevent="submit" class="rounded-2xl form-style bg-slate-900 p-8">

        <h2 class="text-3xl font-bold">
            Send a Message
        </h2>

        <div class="mt-8 space-y-5">
            <input
                v-model="form.name"
                type="text"
                placeholder="Full Name"
                class="w-full rounded-lg bg-slate-800 p-3 outline-none form-field-style"
            />

            <input
                v-model="form.email"
                type="email"
                placeholder="Email Address"
                class="w-full rounded-lg bg-slate-800 p-3 outline-none form-field-style"
            />

            <input
                v-model="form.subject"
                type="text"
                placeholder="Subject"
                class="w-full rounded-lg bg-slate-800 p-3 outline-none form-field-style"
            />

            <textarea
                v-model="form.message"
                rows="6"
                placeholder="Your Message"
                class="w-full rounded-lg bg-slate-800 p-3 outline-none form-field-style"
            ></textarea>

            <button
                class="rounded-lg bg-blue-500 px-6 py-3 font-semibold transition hover:bg-blue-600">
                Send Message
            </button>
        </div>
    </form>
</template>

<style>
.form-style{
    margin-left: 5%;
}
.form-field-style{
    margin-bottom: 2%;
    color: #60A5FA;
}
</style>
