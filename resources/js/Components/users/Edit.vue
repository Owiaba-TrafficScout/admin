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
import { computed, inject, ref } from 'vue';

const props = defineProps<{
    user: User;
}>();

const emit = defineEmits(['update']);
const roleId = ref(
    JSON.parse(
        JSON.stringify(
            props.user.pivot.role_id ?? props.user.pivot.tenant_role_id,
        ),
    ),
);
const update_route = props.user.pivot.role_id
    ? 'project.user.update'
    : 'users.tenant.update';

const form = useForm({
    [props.user.pivot.role_id ? 'role_id' : 'tenant_role_id']: roleId.value,
});

const submit = () => {
    // dynamicaly post to update or store rout
    form.patch(route(update_route, props.user.id), {
        onSuccess: () => {
            emit('update');
        },
        onError: (errors) => {
            console.error('Error updating role:', errors);
        },
    });
};
const selectedRole = computed<number>({
    get: () => form.role_id ?? form.tenant_role_id,
    set: (value: number) => {
        if (props.user.pivot.role_id) {
            form.role_id = value;
        } else {
            form.tenant_role_id = value;
        }
    },
});

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
                            <select id="role" v-model="selectedRole" required>
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                    :selected="role.id === selectedRole"
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
