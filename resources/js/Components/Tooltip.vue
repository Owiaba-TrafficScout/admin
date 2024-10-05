<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/Components/ui/tooltip';

defineProps<{
    buttonText: string;
    content: string;
}>();
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <Button variant="outline"> {{ buttonText }} </Button>
            </TooltipTrigger>
            <TooltipContent>
                <p>{{ content }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
