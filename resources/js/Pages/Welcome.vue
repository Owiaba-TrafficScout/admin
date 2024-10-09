<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';

defineProps<{
    success?: boolean;
}>();
</script>

<template>
    <Head title="Welcome" />

    <!-- Main content -->
    <div class="container mx-auto">
        <div
            class="flex items-center justify-center font-semibold"
            style="height: 90vh"
        >
            <div class="text-center">
                <h1
                    class="text-4xl font-bold text-red-500"
                    v-if="success == false"
                >
                    Oops! Something went wrong, Payment not successful
                </h1>
                <h1
                    class="text-4xl font-bold text-green-500"
                    v-else-if="success == true"
                >
                    Great! You have successfully registered and Paid
                </h1>
                <p class="my-4 text-lg font-semibold">
                    We will get back to you!
                </p>
                <!-- 
                <div class="mt-8">
                    <Link
                        href="/dashboard"
                        class="rounded-full border border-black px-4 py-2 font-semibold hover:bg-black hover:text-white"
                        >Get Started</Link
                    >
                </div> -->
            </div>
        </div>
    </div>
</template>
