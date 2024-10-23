<script setup lang="ts">
import DataTable from '@/Components/trips/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { provide, ref } from 'vue';
import { CarType } from './CarTypes.vue';
import { Project } from './Projects.vue';
export interface Trip {
    id: number;
    title: string;
    description: string;
    project_user: projectUser;
    group_code: string;
    car: Car;
    project: Project;
    start_time: string;
    end_time: string;
    status: TripStatus;
    created_at: string;
    updated_at: string;
}
export interface projectUser {
    id: number;
    user: User;
}
export interface TripStatus {
    id: number;
    name: string;
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
        role_id: number;
        tenant_role_id: number;
        role: Role;
    };
}

interface Role {
    id: number;
    name: string;
}

let props = defineProps<{
    trips: Trip[];
    statuses: TripStatus[];
}>();

const btnClasses = ref(` ml-10 w-32 inline-flex items-center rounded-md border
    border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold
    uppercase tracking-widest text-white transition duration-150 ease-in-out
    hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200
    dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white
    dark:focus:ring-offset-gray-800 dark:active:bg-gray-300`);

provide('trip_statuses', props.statuses);
</script>

<template>
    <Layout page="Trips">
        <div class="mt-2 flex flex-col gap-5">
            <a :href="route('export.trips')" :class="btnClasses"
                >Export Trips</a
            >
            <DataTable :items="trips" />
        </div>
    </Layout>
</template>
