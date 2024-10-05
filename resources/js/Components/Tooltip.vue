<script setup lang="ts">
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/Components/ui/tooltip';

defineProps<{
    displayText: string;
    tooltipContent: string;
}>();
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <p>{{ displayText }}</p>
            </TooltipTrigger>
            <TooltipContent>
                <p>{{ tooltipContent }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
