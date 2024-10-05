<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';
import { Trip, TripStatus } from '@/Pages/Trips.vue';
import { useForm } from '@inertiajs/vue3';
import { inject } from 'vue';

const props = defineProps<{
    trip: Trip;
}>();

const emit = defineEmits(['update']);

const form = useForm({
    title: props.trip.title,
    description: props.trip.description,
    group_code: props.trip.group_code,
    start_time: props.trip.start_time,
    end_time: props.trip.end_time,
    trip_status_id: props.trip.status.id,
});

const submit = () => {
    // dynamicaly post to update or store rout
    form.patch(route('trips.update', props.trip.id), {
        onSuccess: () => {
            form.reset();
            emit('update');
        },
    });
};

// inject trip statuses
const statuses = inject<TripStatus[]>('trip_statuses');
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <slot />
        </PopoverTrigger>
        <PopoverContent class="w-80">
            <form @submit.prevent="submit">
                <Card class="w-full max-w-sm">
                    <CardHeader>
                        <CardTitle class="text-2xl"> Edit Trip </CardTitle>
                        <CardDescription> Update fields below </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="title">Title</Label>
                            <Input
                                id="title"
                                type="text"
                                v-model="form.title"
                                required
                            />
                            <div v-if="form.errors.title">
                                {{ form.errors.title }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="description">description</Label>
                            <textarea
                                name="description"
                                id="description"
                                v-model="form.description"
                            ></textarea>
                            <div v-if="form.errors.description">
                                {{ form.errors.description }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="group_code">Group Code</Label>
                            <Input
                                id="group_code"
                                type="text"
                                v-model="form.group_code"
                                required
                            />
                            <div v-if="form.errors.group_code">
                                {{ form.errors.group_code }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="start_time">Start Time</Label>
                            <Input
                                id="start_time"
                                type="datetime"
                                v-model="form.start_time"
                                required
                            />
                            <div v-if="form.errors.start_time">
                                {{ form.errors.start_time }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="end_time">End Time</Label>
                            <Input
                                id="end_time"
                                type="datetime"
                                v-model="form.end_time"
                                required
                            />
                            <div v-if="form.errors.end_time">
                                {{ form.errors.end_time }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="trip_status_id">Status</Label>
                            <select
                                id="trip_status_id"
                                v-model="form.trip_status_id"
                                required
                            >
                                <template
                                    v-for="status in statuses"
                                    :key="status.id"
                                >
                                    <option
                                        :value="status.id"
                                        :selected="
                                            form.trip_status_id == status.id
                                        "
                                    >
                                        {{ status.name }}
                                    </option>
                                </template>
                            </select>
                            <div v-if="form.errors.trip_status_id">
                                {{ form.errors.trip_status_id }}
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button class="w-full"> Update </Button>
                    </CardFooter>
                </Card>
            </form>
        </PopoverContent>
    </Popover>
</template>
