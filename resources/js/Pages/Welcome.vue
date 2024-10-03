<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();
</script>

<template>
    <Head title="Welcome" />
    <!-- Navbar -->
    <nav class="bg-black py-2 text-white">
        <div class="flex items-center justify-between px-4">
            <div>
                <Link href="/" class="text-white">Home</Link>
            </div>
            <div class="flex gap-3">
                <Link href="/login" class="text-white">Login</Link>
                <Link href="/register" class="text-white">Register</Link>
            </div>
            <!-- show humbergur icon for mobile -->
            <div class="sm:hidden">
                <button class="text-white">
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"
                        ></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>
    <!-- Main content -->
    <div class="container mx-auto">
        <div
            class="flex items-center justify-center font-semibold"
            style="height: 90vh"
        >
            <div class="text-center">
                <h1 class="text-4xl font-bold text-red-500">
                    Welcome to your Admin platform
                </h1>
                <p class="my-4 text-lg font-semibold">
                    Go straight ahead and log in
                </p>
                <!-- online image showing a man in a meeting-->

                <div class="mt-8">
                    <Link
                        href="/dashboard"
                        class="rounded-full border border-black px-4 py-2 font-semibold hover:bg-black hover:text-white"
                        >Get Started</Link
                    >
                </div>
            </div>
        </div>
    </div>
</template>
