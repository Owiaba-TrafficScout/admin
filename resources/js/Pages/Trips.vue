<script setup lang="ts">
interface Trip {
    id: number;
    title: string;
    description: string;
    user_id: number;
    group_code: string;
    car_id: number;
    project_id: number;
    start_time: string;
    end_time: string;
    trip_status_id: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    trips: Trip[];
}>();
</script>

<template>
    <div>
        <ol>
            <li v-for="trip in trips" :key="trip.id">
                {{ trip.title }}
            </li>
        </ol>
    </div>
</template>
