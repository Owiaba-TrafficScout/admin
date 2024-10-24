<script setup lang="ts">
import DataTable from '@/Components/cartypes/data-table.vue';
import Layout from '@/Layouts/App.vue';
export interface CarType {
    id: number;
    name: string;
}

defineProps<{
    car_types: CarType[];
}>();
</script>

<template>
    <Layout page="Car Types">
        <DataTable :items="car_types" />
    </Layout>
</template>
