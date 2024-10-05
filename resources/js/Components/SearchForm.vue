<script setup>
import Input from '@/Components/ui/input/Input.vue';
import { Search } from 'lucide-vue-next';
import { defineEmits } from 'vue';
const emit = defineEmits(['search']);
const search = (e) => {
    emit('search', e.target.value);
};
</script>
<template>
    <form class="flex w-full items-center px-4 py-3">
        <label for="" class="sr-only">Search</label>
        <div class="relative w-full">
            <Search
                class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"
            />
            <Input
                type="search"
                placeholder="Search products..."
                class="w-full appearance-none bg-background pl-8 shadow-none md:w-2/3 lg:w-1/3"
                @input="search"
            />
        </div>
    </form>
</template>
