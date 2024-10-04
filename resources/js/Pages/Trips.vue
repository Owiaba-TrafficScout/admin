<script setup lang="ts">
import DataTable from '@/Components/trips/data-table.vue';
import Layout from '@/Layouts/App.vue';
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

interface TripStatus {
    id: number;
    name: string;
}

interface Car {
    id: number;
    car_number: string;
}

interface Project {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
}

defineProps<{
    trips: Trip[];
}>();
</script>

<template>
    <Layout page="Trips">
        <DataTable :items="trips" />
    </Layout>
</template>
