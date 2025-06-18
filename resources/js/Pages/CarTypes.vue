<script setup lang="ts">
import DataTable from '@/Components/cartypes/data-table.vue';
import Layout from '@/Layouts/App.vue';
import { ref } from 'vue';
export interface CarType {
    id: number;
    name: string;
}

defineProps<{
    car_types: CarType[];
}>();

const btnClasses = ref(` ml-10 w-32 inline-flex items-center rounded-md border
    border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold
    uppercase tracking-widest text-white transition duration-150 ease-in-out
    hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200
    dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white
    dark:focus:ring-offset-gray-800 dark:active:bg-gray-300`);
</script>

<template>
    <Layout page="Car Types">
        <div class="flex flex-col">
            <a
                :href="route('project.cartype.add.page')"
                :class="btnClasses"
                class="mb-5 w-fit"
                >Add Car Type</a
            >
            <DataTable :items="car_types" />
        </div>
    </Layout>
</template>
