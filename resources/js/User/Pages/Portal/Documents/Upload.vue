<script setup>
import { computed, ref } from 'vue'
import { Head, useForm , usePage} from '@inertiajs/vue3'
import PortalLayout from '@/User/Layouts/AuthenticatedUserPortalLayout.vue'

const page = usePage()
const successMessage = computed(() => page.props.flash?.success)
const fileInputKey = ref(0)
const form = useForm({
    passport: null,
    degree: null,
    transcript: null,
    admission_letter: null,
})
const setFile = (field, event) => {
    form[field] = event.target.files?.[0] ?? null
}

const submit = () => {
    form.post(route('portal.documents.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () =>
        {
            form.reset()
            form.clearErrors()

            // Recreates the native file inputs and removes filenames.
            fileInputKey.value++
        }
    });
}
</script>

<template>
    <Head title="Upload Documents" />

    <PortalLayout>
        <section>
            <p  v-if="successMessage"
                class="rounded-lg border border-green-500/30 bg-green-500/10 p-4 text-green-300">
                {{ successMessage }}
            </p>
            <p v-if="form.errors.documents"
                class="rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-red-300">
                {{ form.errors.documents }}
            </p>
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
                    :key="`passport-${fileInputKey}`"
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('passport', $event)"
                />
                <p
                    v-if="form.errors.passport"
                    class="mt-2 text-sm text-red-400"
                >
                    {{ form.errors.passport }}
                </p>
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Previous degree
                </label>

                <input
                    :key="`degree-${fileInputKey}`"
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('degree', $event)"
                />
                <p
                    v-if="form.errors.degree"
                    class="mt-2 text-sm text-red-400"
                >
                    {{ form.errors.degree }}
                </p>
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Transcript
                </label>

                <input
                    :key="`transcript-${fileInputKey}`"
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('transcript', $event)"
                />
                <p
                    v-if="form.errors.transcript"
                    class="mt-2 text-sm text-red-400"
                >
                    {{ form.errors.transcript }}
                </p>
            </div>

            <div>
                <label class="mb-2 block font-medium">
                    Admission letter
                </label>

                <input
                    :key="`admission_letter-${fileInputKey}`"
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="block w-full rounded-lg border border-slate-700 bg-slate-950 p-3"
                    @change="setFile('admission_letter', $event)"
                />
                <p
                    v-if="form.errors.passpadmission_letterort"
                    class="mt-2 text-sm text-red-400"
                >
                    {{ form.errors.admission_letter }}
                </p>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="rounded-lg bg-blue-500 px-6 py-3 font-semibold hover:bg-blue-600 disabled:cursor-not-allowed disabled:opacity-60"
            >
                {{ form.processing ? 'Uploading...' : 'Upload documents' }}
            </button>
        </form>
    </PortalLayout>
</template>
