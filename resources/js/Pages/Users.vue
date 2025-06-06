<script setup lang="ts">
import DataTable from '@/Components/users/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { provide } from 'vue';
import { Project } from './Projects.vue';
import { User } from './Trips.vue';

const props = defineProps<{
    users: User[];
    roles: { id: number; name: string }[];
    project: Project;
}>();

provide('roles', props.roles);
</script>

<template>
    <Layout page="Users">
        <DataTable :items="users" :project="project" />
    </Layout>
</template>
