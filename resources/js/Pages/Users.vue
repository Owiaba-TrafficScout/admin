<script setup lang="ts">
import DataTable from '@/Components/users/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { provide } from 'vue';
import { User } from './Trips.vue';

const props = defineProps<{
    users: User[];
    roles: { id: number; name: string }[];
    allUsers: User[];
}>();
const pageTitle = props.users[0].pivot.role_id
    ? 'Project members'
    : 'Organization members';
provide('roles', props.roles);
</script>

<template>
    <Layout :page="pageTitle">
        <DataTable :items="users" :allUsers="allUsers" />
    </Layout>
</template>
