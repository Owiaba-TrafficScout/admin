<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';

defineProps<{
    speeds: Speed[];
}>();
interface Speed {
    id: number;
    location_x: number;
    location_y: number;
    velocity: number;
    is_traffic: boolean;
}
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline"> Speeds </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[auto] max-w-[80vw]">
            <div
                class="border-stroke shadow-default dark:border-strokedark dark:bg-boxdark rounded-sm border bg-white"
            >
                <div class="xl:px-7.5 px-4 py-6 md:px-6">
                    <h4 class="text-xl font-bold text-black dark:text-white">
                        Trip Speeds
                    </h4>
                </div>

                <!-- Table Header -->
                <div
                    class="border-stroke py-4.5 dark:border-strokedark 2xl:px-7.5 grid grid-cols-6 border-t px-4 sm:grid-cols-8 md:px-6"
                >
                    <div class="col-span-3 flex items-center">
                        <p class="font-medium">Location X</p>
                    </div>
                    <div class="col-span-2 hidden items-center sm:flex">
                        <p class="font-medium">Location Y</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                        <p class="font-medium">Velocity</p>
                    </div>

                    <div class="col-span-1 flex items-center">
                        <p class="font-medium">Traffic</p>
                    </div>
                </div>

                <!-- Table Rows -->
                <div
                    v-for="speed in speeds"
                    :key="speed.id"
                    class="border-stroke py-4.5 dark:border-strokedark 2xl:px-7.5 grid grid-cols-6 border-t px-4 sm:grid-cols-8 md:px-6"
                >
                    <div class="col-span-3 flex items-center">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <p
                                class="text-sm font-medium text-black dark:text-white"
                            >
                                {{ speed.location_x }}
                            </p>
                        </div>
                    </div>
                    <div class="col-span-2 hidden items-center sm:flex">
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            {{ speed.location_y }}
                        </p>
                    </div>
                    <div class="col-span-2 flex items-center">
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            ${{ speed.velocity }}
                        </p>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            {{ speed.is_traffic ? 'Yes' : 'No' }}
                        </p>
                    </div>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
