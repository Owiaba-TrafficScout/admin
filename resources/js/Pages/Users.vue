<script setup lang="ts">
import DataTable from '@/Components/users/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { provide } from 'vue';
import { User } from './Trips.vue';

const props = defineProps<{
    users: User[] | null;
    roles: { id: number; name: string }[];
    allUsers: User[] | null;
}>();
const pageTitle = (() => {
    if (!props.users?.length) return 'Users';

    const firstUser = props.users[0];
    if (!firstUser?.pivot) return 'Users';

    return firstUser.pivot.role_id ? 'Project members' : 'Organization members';
})();
provide('roles', props.roles);
</script>

<template>
    <Layout :page="pageTitle">
        <DataTable :items="users" :allUsers="allUsers" />
    </Layout>
</template>
