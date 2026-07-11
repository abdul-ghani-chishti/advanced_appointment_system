<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
})

const submit = () => {
    form.post(route('contact.store'), {

        preserveScroll: true,

        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <form
        class="rounded-2xl border border-slate-800 bg-slate-900 p-8 form-style"
        @submit.prevent="submit"
    >
        <h2 class="text-3xl font-bold text-white">
            Send a Message
        </h2>

        <p class="mt-3 text-slate-400">
            Complete the form and our support team will contact you.
        </p>

        <div class="mt-8 space-y-6">
            <!-- Name -->
            <div>
                <label
                    for="name"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Full name
                </label>

                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    placeholder="Enter your full name"
                    class="w-full rounded-lg border bg-slate-800 px-4 py-3 text-white outline-none transition placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 form-field-style"
                    :class="
                        form.errors.name
                            ? 'border-red-500'
                            : 'border-slate-700'
                    "
                />

                <p
                    v-if="form.errors.name"
                    class="mt-2 text-sm text-red-400"
                >
                    {{ form.errors.name }}
                </p>
            </div>

            <!-- Email -->
            <div>
                <label
                    for="email"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Email address
                </label>

                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    autocomplete="email"
                    placeholder="Enter your email address"
                    class="w-full rounded-lg border bg-slate-800 px-4 py-3 text-white outline-none transition placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 form-field-style"
                    :class="
                        form.errors.email
                            ? 'border-red-500'
                            : 'border-slate-700'
                    "
                />

                <p
                    v-if="form.errors.email"
                    class="mt-2 text-sm text-red-400"
                >
                    {{ form.errors.email }}
                </p>
            </div>

            <!-- Subject -->
            <div>
                <label
                    for="subject"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Subject
                </label>

                <input
                    id="subject"
                    v-model="form.subject"
                    type="text"
                    placeholder="What would you like help with?"
                    class="w-full rounded-lg border bg-slate-800 px-4 py-3 text-white outline-none transition placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 form-field-style"
                    :class="
                        form.errors.subject
                            ? 'border-red-500'
                            : 'border-slate-700'
                    "
                />

                <p
                    v-if="form.errors.subject"
                    class="mt-2 text-sm text-red-400"
                >
                    {{ form.errors.subject }}
                </p>
            </div>

            <!-- Message -->
            <div>
                <label
                    for="message"
                    class="mb-2 block text-sm font-medium text-slate-300"
                >
                    Message
                </label>

                <textarea
                    id="message"
                    v-model="form.message"
                    rows="6"
                    placeholder="Write your message here"
                    class="w-full resize-none rounded-lg border bg-slate-800 px-4 py-3 text-white outline-none transition placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 form-field-style"
                    :class="
                        form.errors.message
                            ? 'border-red-500'
                            : 'border-slate-700'
                    "
                ></textarea>

                <div class="mt-2 flex items-center justify-between gap-4">
                    <p
                        v-if="form.errors.message"
                        class="text-sm text-red-400"
                    >
                        {{ form.errors.message }}
                    </p>

                    <p
                        class="ml-auto text-xs"
                        :class="
                            form.message.length > 2000
                                ? 'text-red-400'
                                : 'text-slate-500'
                        "
                    >
                        {{ form.message.length }}/2000
                    </p>
                </div>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="flex w-full items-center justify-center rounded-lg bg-blue-500 px-6 py-3 font-semibold text-white transition hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-60"
            >
                <span v-if="form.processing">
                    Sending...
                </span>

                <span v-else>
                    Send Message
                </span>
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
