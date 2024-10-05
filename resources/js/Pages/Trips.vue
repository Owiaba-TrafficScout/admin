<script setup lang="ts">
import DataTable from '@/Components/trips/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { provide } from 'vue';
import { CarType } from './CarTypes.vue';
import { Project } from './Projects.vue';
export interface Trip {
    id: number;
    title: string;
    description: string;
    user: User;
    group_code: string;
    car: Car;
    project: Project;
    start_time: string;
    end_time: string;
    status: TripStatus;
    created_at: string;
    updated_at: string;
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
    role: Role;
}

interface Role {
    id: number;
    name: string;
}

let props = defineProps<{
    trips: Trip[];
    statuses: TripStatus[];
}>();

provide('trip_statuses', props.statuses);
</script>

<template>
    <Layout page="Trips">
        <DataTable :items="trips" />
    </Layout>
</template>
