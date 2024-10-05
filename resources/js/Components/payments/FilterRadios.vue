<script setup>
const emit = defineEmits(['filter']);
const filter = (e) => {
    //i'm not using lang="ts" so i can be able to use the event (e) variable without worrying about types
    emit('filter', e.target.value);
};
defineProps({
    statuses:
        {
            type: Array,
            required: true,
        } || [],
});
</script>
<template>
    <div class="flex items-center gap-5">
        <label class="flex flex-nowrap items-center">
            <input
                type="radio"
                name="show"
                id="all"
                value="all"
                checked
                @change="filter"
            />
            <span class="ml-1 whitespace-nowrap">Show All</span>
        </label>
        <label
            class="flex flex-nowrap items-center"
            v-for="status in statuses"
            :key="status.id"
        >
            <input
                type="radio"
                name="show"
                :id="status.id"
                :value="status.name"
                @change="filter"
            />
            <span class="ml-1 whitespace-nowrap capitalize">{{
                status.name
            }}</span>
        </label>
    </div>
</template>
