<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gray-100 p-4"
    >
        <div class="max-w-md rounded bg-white p-6 text-center shadow">
            <svg
                class="mx-auto h-20 w-20 text-green-500"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12l2 2 4-4M12 3v2M4.5 5l1 1M20.5 5l-1 1M5 20h2M17 20h2M3 9h2M19 9h2"
                />
            </svg>
            <h1 class="mb-2 mt-4 text-2xl font-bold text-green-600">
                {{ isEmail ? 'Email Verified' : 'Password reset' }}
            </h1>
            <p class="text-gray-600">
                {{
                    isEmail
                        ? 'Congratulations! Your email is now verified.'
                        : 'Your password has been reset.'
                }}
            </p>
            <button
                class="mt-6 rounded bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700"
                @click="router.visit(route('login'))"
                v-if="admin"
            >
                Go to Login
            </button>
            <p class="mt-4 text-sm text-gray-500">
                You can now close this window or return to the app.
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';

const props = withDefaults(
    defineProps<{
        admin: boolean;
        isEmail?: boolean;
    }>(),
    { isEmail: true },
);
</script>
