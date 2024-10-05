<script setup>
const emit = defineEmits(['filter']);
const filter = (e) => {
    emit('filter', e.target.value);
};
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
        <label class="flex flex-nowrap items-center">
            <input
                type="radio"
                name="show"
                id="active"
                value="active"
                @change="filter"
            />
            <span class="ml-1 whitespace-nowrap">Show Active</span>
        </label>
        <label class="flex flex-nowrap items-center">
            <input
                type="radio"
                name="show"
                id="inactive"
                value="inactive"
                @change="filter"
            />
            <span class="ml-1 whitespace-nowrap">Show Inactive</span>
        </label>
    </div>
</template>
