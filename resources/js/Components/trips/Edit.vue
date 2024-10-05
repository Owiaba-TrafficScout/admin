<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline"> {{ triggerText }} </Button>
        </PopoverTrigger>
        <PopoverContent class="w-80"> </PopoverContent>
    </Popover>
</template>
