<script setup>
import Input from '@/Components/ui/input/Input.vue';
import { Search } from 'lucide-vue-next';
// removed invalid defineEmits import
const emit = defineEmits(['search']);
const search = (e) => {
    emit('search', e.target.value);
};
</script>
<template>
    <form class="flex w-full items-center px-4 py-3">
        <label for="" class="sr-only">Search</label>
        <div class="relative w-full">
            <div
                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
            >
                <Search class="h-4 w-4 text-muted-foreground" />
            </div>
            <Input
                type="search"
                placeholder="Search..."
                class="flex-1 bg-background pl-10 shadow-none"
                @input="search"
            />
        </div>
    </form>
</template>
