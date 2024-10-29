<script setup lang="ts">
import Layout from '@/Layouts/App.vue';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';

import { usePage } from '@inertiajs/vue3';
import { provide } from 'vue';
import { Trip, User } from './Trips.vue';
export interface Project {
    id: number;
    name: string;
    description: string;
    start_date: string;
    end_date: string;
    trips: Trip[];
    users: User[];
}

const props = defineProps<{
    projects: Project[];
    roles: { id: number; name: string }[];
}>();

const is_tenant_admin = usePage().props.auth.is_tenant_admin;

provide('roles', props.roles);

const btnClasses = ref(` ml-10 w-32 inline-flex items-center rounded-md border
    border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold
    uppercase tracking-widest text-white transition duration-150 ease-in-out
    hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200
    dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white
    dark:focus:ring-offset-gray-800 dark:active:bg-gray-300`);
</script>

<template>
    <Layout page="Projects">
        <div class="mt-2 flex flex-col gap-5">
            <a
                v-if="is_tenant_admin"
                :href="route('projects.create')"
                :class="btnClasses"
                class="w-fit"
                >New Project <Plus class="ml-2" :size="16"
            /></a>
        </div>
    </Layout>
</template>
