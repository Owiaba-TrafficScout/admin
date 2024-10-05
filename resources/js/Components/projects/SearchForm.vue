<script setup>
import { defineEmits } from 'vue';
const emit = defineEmits(['search']);
const search = (e) => {
    emit('search', e.target.value);
};
</script>
<template>
    <form class="flex items-center px-4 py-3">
        <label for="" class="sr-only">Search</label>
        <div class="relative w-full">
            <input
                type="text"
                class="border-gray-3 border bg-gray-50"
                @input="search"
            />
        </div>
    </form>
</template>
