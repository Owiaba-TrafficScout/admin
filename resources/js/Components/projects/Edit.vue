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
import { Project } from '@/Pages/Projects.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    project: Project;
}>();

const emit = defineEmits(['update']);

const form = useForm({
    name: props.project.name,
    description: props.project.description,
    start_date: props.project.start_date,
    end_date: props.project.end_date,
});

const submit = () => {
    // dynamicaly post to update or store rout
    form.patch(route('projects.update', props.project.id), {
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
                        <CardTitle class="text-2xl"> Edit Project </CardTitle>
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
                        <div class="grid gap-2">
                            <Label for="description">Description</Label>
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
                            <Label for="start_date">Start Date</Label>
                            <Input
                                id="start_date"
                                type="date"
                                v-model="form.start_date"
                                required
                            />
                            <div v-if="form.errors.start_date">
                                {{ form.errors.start_date }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="end_date">End Date</Label>
                            <Input
                                id="end_date"
                                type="date"
                                v-model="form.end_date"
                                required
                            />
                            <div v-if="form.errors.end_date">
                                {{ form.errors.end_date }}
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
