<script setup lang="ts">
import DataTable from '@/Components/users/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { provide } from 'vue';
export interface User {
    id: number;
    name: string;
    email: string;
    role: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    users: User[];
    roles: { id: number; name: string }[];
}>();

provide('roles', props.roles);
</script>

<template>
    <Layout page="Users">
        <DataTable :items="users" />
    </Layout>
</template>
