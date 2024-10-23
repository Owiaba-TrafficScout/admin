<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';
import { Label } from '@/Components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';
import { User } from '@/Pages/Trips.vue';

import { useForm } from '@inertiajs/vue3';
import { inject } from 'vue';

const props = defineProps<{
    user: User;
}>();

const emit = defineEmits(['update']);

const form = useForm({
    tenant_role_id: props.user.pivot.role.id,
});

const submit = () => {
    // dynamicaly post to update or store rout
    form.patch(route('users.update', props.user.id), {
        onSuccess: () => {
            form.reset();
            emit('update');
        },
    });
};

const roles = inject<{ id: number; name: string }[]>('roles');
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
                        <CardTitle class="text-2xl"> Change Role </CardTitle>
                    </CardHeader>
                    <CardContent class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="role">Role</Label>
                            <select
                                id="role"
                                v-model="form.tenant_role_id"
                                required
                            >
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                    :selected="role.id === form.tenant_role_id"
                                >
                                    {{ role.name }}
                                </option>
                            </select>
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
