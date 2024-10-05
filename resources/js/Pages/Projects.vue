<script setup lang="ts">
import DataTable from '@/Components/projects/data-table.vue';
import Layout from '@/Layouts/App.vue';
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

defineProps<{
    projects: Project[];
}>();
</script>

<template>
    <Layout page="Projects">
        <DataTable :items="projects" />
    </Layout>
</template>
