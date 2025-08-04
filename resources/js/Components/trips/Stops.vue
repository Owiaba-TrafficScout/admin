<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';
import { Stop } from '@/interface';

defineProps<{
    stops: Stop[];
}>();
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline"> Stops </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[auto] max-w-[50vw] overflow-y-auto">
            <div
                class="border-stroke shadow-default dark:border-strokedark dark:bg-boxdark rounded-sm border bg-white"
                style="width: 100vw"
            >
                <div class="xl:px-7.5 px-4 py-6 md:px-6">
                    <h4 class="text-xl font-bold text-black dark:text-white">
                        Trip Stops
                    </h4>
                </div>

                <!-- Table Header -->
                <div
                    class="border-stroke py-4.5 dark:border-strokedark 2xl:px-7.5 grid grid-cols-5 border-t px-4 sm:grid-cols-10 md:px-6"
                >
                    <div class="flex items-center">
                        <p class="text-xs font-semibold">Start Time</p>
                    </div>
                    <div class="flex items-center">
                        <p class="text-xs font-semibold">Start X</p>
                    </div>
                    <div class="hidden items-center sm:flex">
                        <p class="text-xs font-semibold">Start Y</p>
                    </div>
                    <div class="flex items-center">
                        <p class="text-xs font-semibold">Stop Time</p>
                    </div>

                    <div class="flex items-center">
                        <p class="text-xs font-semibold">Stop Loc X</p>
                    </div>
                    <div class="hidden items-center sm:flex">
                        <p class="text-xs font-semibold">Stop Loc Y</p>
                    </div>

                    <div class="flex items-center">
                        <p class="text-xs font-semibold">P-Count</p>
                    </div>
                    <div class="flex items-center">
                        <p class="text-xs font-semibold">P-Board</p>
                    </div>
                    <div class="flex items-center">
                        <p class="text-xs font-semibold">P-Alight</p>
                    </div>
                    <div class="flex items-center">
                        <p class="text-xs font-semibold">traffic</p>
                    </div>
                </div>

                <!-- Table Rows -->
                <div
                    v-for="stop in stops"
                    :key="stop.id"
                    class="border-stroke py-4.5 dark:border-strokedark 2xl:px-7.5 border- grid grid-cols-10 px-4 md:px-6"
                >
                    <div class="flex items-center">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.start_time }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <p
                                class="text-sm font-semibold text-black dark:text-white"
                            >
                                {{ stop.start_location_x }}
                            </p>
                        </div>
                    </div>
                    <div class="hidden items-center sm:flex">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.start_location_y }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.stop_time }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <p
                                class="text-sm font-semibold text-black dark:text-white"
                            >
                                {{ stop.stop_location_x }}
                            </p>
                        </div>
                    </div>
                    <div class="hidden items-center sm:flex">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.stop_location_y }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.passengers_count }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.passengers_boarding }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.passengers_alighting }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <p
                            class="text-sm font-semibold text-black dark:text-white"
                        >
                            {{ stop.is_traffic }}
                        </p>
                    </div>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
