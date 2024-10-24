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
import { CarType } from '@/Pages/CarTypes.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    car_type: CarType;
}>();

const emit = defineEmits(['update']);

const form = useForm({
    name: props.car_type.name,
});

const submit = () => {
    // dynamicaly post to update or store rout
    form.patch(route('car-types.update', props.car_type.id), {
        onSuccess: () => {
            form.reset();
            emit('update');
        },
    });
};
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
                        <CardTitle class="text-2xl"> Edit Car type </CardTitle>
                        <CardDescription> Update fields below </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="title">Name</Label>
                            <Input
                                id="title"
                                type="text"
                                v-model="form.name"
                                required
                            />
                            <div v-if="form.errors.name">
                                {{ form.errors.name }}
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
