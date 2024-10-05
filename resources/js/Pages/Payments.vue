<script setup lang="ts">
import DataTable from '@/Components/payments/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { Project } from '@/Pages/Projects.vue';
import { provide } from 'vue';
export interface Payment {
    id: number;
    user: User;
    project: Project;
    status: PaymentStatus;
    date: string;
    amount: number;
    description: string;
    created_at: string;
    updated_at: string;
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
interface PaymentStatus {
    id: number;
    name: string;
}
const props = defineProps<{
    payments: Payment[];
    statuses: PaymentStatus[];
}>();

provide('statuses', props.statuses);
</script>

<template>
    <Layout page="Payments">
        <DataTable :items="payments" />
    </Layout>
</template>
