<script setup lang="ts">
import DataTable from '@/Components/trips/data-table.vue';
import { Speed, Stop } from '@/interface';
import Layout from '@/Layouts/App.vue';
import { ref } from 'vue';
import { CarType } from './CarTypes.vue';
import { Project } from './Projects.vue';
export interface Trip {
    id: number;
    title: string;
    description: string;
    project_user: projectUser;
    group_code: string;
    car: Car;
    speeds: Speed[];
    stops: Stop[];
    project: Project;
    start_time: string;
    end_time: string;
    created_at: string;
    updated_at: string;
}
export interface projectUser {
    id: number;
    user: User;
    project: Project;
}

interface Car {
    id: number;
    car_number: string;
    type: CarType;
}

export interface User {
    id: number;
    name: string;
    email: string;
    pivot: {
        id: number;
        role_id: number;
        tenant_role_id: number;
        role: Role;
    };
}

interface Role {
    id: number;
    name: string;
}

defineProps<{
    trips: Trip[];
}>();

const btnClasses = ref(` ml-10 w-32 inline-flex items-center rounded-md border
    border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold
    uppercase tracking-widest text-white transition duration-150 ease-in-out
    hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200
    dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white
    dark:focus:ring-offset-gray-800 dark:active:bg-gray-300`);
</script>

<template>
    <Layout page="Trips">
        <div class="mt-2 flex max-w-[90vw] flex-col gap-5 overflow-auto">
            <a
                :href="route('export.trips')"
                :class="btnClasses"
                v-if="trips.length > 0"
                >Export Trips</a
            >
            <DataTable :items="trips" v-if="trips.length > 0" />
            <p v-else class="text-xl font-bold text-gray-500">Empty</p>
        </div>
    </Layout>
</template>
