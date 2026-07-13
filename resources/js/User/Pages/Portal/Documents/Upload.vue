<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import PortalLayout from '@/User/Layouts/AuthenticatedUserPortalLayout.vue'

const form = useForm({
    passport: null,
    degree: null,
    transcript: null,
    admission_letter: null,
})

const submit = () => {
    console.table({
        passport: form.passport,
        degree: form.degree,
        transcript: form.transcript,
        admission_letter: form.admission_letter,
    })
}

const setFile = (field, event) => {
    form[field] = event.target.files[0] ?? null
}
</script>

<template>
    <Head title="Upload Documents" />

    <PortalLayout>
        <section>
            <h1 class="text-3xl font-bold">
                Upload documents
            </h1>

            <p class="mt-3 text-slate-400">
                Upload clear PDF, JPG, or PNG copies of every required document.
            </p>
        </section>

        <form
            class="mt-8 space-y-6 rounded-2xl border border-slate-800 bg-slate-900 p-8"
            @submit.prevent="submit"
        >
            <div>
                <label class="mb-2 block font-medium">
                    Passport
                </label>

                <input
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('passport', $event)"
                />
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Previous degree
                </label>

                <input
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('degree', $event)"
                />
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Transcript
                </label>

                <input
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('transcript', $event)"
                />
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Admission letter
                </label>

                <input
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('admission_letter', $event)"
                />
            </div>

            <button
                type="submit"
                class="rounded-lg bg-blue-500 px-6 py-3 font-semibold hover:bg-blue-600"
            >
                Upload documents
            </button>
        </form>
    </PortalLayout>
</template>
