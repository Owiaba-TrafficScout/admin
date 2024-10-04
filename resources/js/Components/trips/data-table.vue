<script setup>
import { computed, defineProps, ref } from 'vue';
import SearchForm from './SearchForm.vue';
import FilterRadios from './FilterRadios.vue';
const props = defineProps({
    items:
        {
            type: Array,
            required: true,
        } || [],
});

const search = ref('');
const handleSearch = (s) => {
    search.value = s;
};
const filteredItems = computed(() => {
    if (search.value != '')
        return props.items.filter((item) => {
            return (
                item.title.toLowerCase().includes(search.value.toLowerCase()) ||
                item.description
                    .toLowerCase()
                    .includes(search.value.toLowerCase()) ||
                item.group_code
                    .toLowerCase()
                    .includes(search.value.toLowerCase()) ||
                item.trip_status_id
                    .toLowerCase()
                    .includes(search.value.toLowerCase())
            );
        });
    return props.items;
});

const handleFilter = (filter) => {
    if (filter === 'active') {
        search.value = 'active';
    } else if (filter === 'inactive') {
        search.value = 'inactive';
    } else {
        search.value = '';
    }
};
</script>

<template>
    <div class="relative rounded-lg border bg-white p-10">
        <div class="flex items-center justify-between">
            <!-- Search bar -->
            <SearchForm @search="handleSearch" />

            <!-- Filter Radios -->
            <FilterRadios @filter="handleFilter" />
        </div>
        <table class="w-full text-left text-sm text-gray-500">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">User ID</th>
                    <th class="px-4 py-3">Group Code</th>
                    <th class="px-4 py-3">Car ID</th>
                    <th class="px-4 py-3">Project ID</th>
                    <th class="px-4 py-3">Trip Status ID</th>
                </tr>
            </thead>
            <tr v-for="item in filteredItems" :key="item.id" class="border-b">
                <td>{{ item.id }}</td>
                <td>{{ item.title }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.user_id }}</td>
                <td>{{ item.group_code }}</td>
                <td>{{ item.car_id }}</td>
                <td>{{ item.project_id }}</td>
                <td>{{ item.trip_status_id }}</td>
            </tr>
        </table>
    </div>
</template>
