<script setup>
defineProps({
    icon: String,
    title: String,
    description: String,
})
</script>

<template>
        <div class="rounded-2xl bg-slate-900
         p-8 transition duration-300 hover:-translate-y-1
         hover:bg-slate-800 hover:shadow-xl">
            <div class="text-5xl">
                {{ icon }}
            </div>

            <h2 class="mt-5 text-2xl font-semibold text-white">
                {{ title }}
            </h2>

            <p class="mt-4 leading-7 text-slate-400">
                {{ description }}
            </p>
        </div>
</template>

<style scoped>

</style>
