<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';
import { User } from '@/Pages/Trips.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, inject } from 'vue';

const props = defineProps<{
    users: User[];
    projectId: number;
}>();
const remForm = useForm({
    user_id: 0,
    project_id: props.projectId,
});
const handleRemove = (userId: number) => {
    remForm.user_id = userId;
    remForm.post(
        route('projects.users.remove', {
            project: props.projectId,
            user: userId,
        }),
    );
};

const filteredUsers = computed(() => {
    return props.users.filter((user) => {
        return user.pivot.role_id >= 1;
    });
});

const roles: { id: number; name: string }[] = inject('roles') || [];
const form = useForm({
    role_id: 0,
});

const submit = (ProjectUserId: number, RoleId: number) => {
    if (confirm('Are you sure you want to change this user role?')) {
        form.role_id = RoleId;
        console.log(form.role_id);
        form.post(
            route('project.user.update', {
                project: props.projectId,
                projectUser: ProjectUserId,
            }),
            {
                onError: (e) => {
                    console.log(e);
                    console.log(form.role_id);
                    console.log('role_id', RoleId);
                },
            },
        );
    }
};
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline"> users </Button>
        </PopoverTrigger>
        <PopoverContent class="w-[auto] max-w-[100vw]">
            <div
                class="border-stroke shadow-default dark:border-strokedark dark:bg-boxdark rounded-sm border bg-white"
            >
                <div class="xl:px-7.5 px-4 py-6 md:px-6">
                    <h4 class="text-xl font-bold text-black dark:text-white">
                        Project users
                    </h4>
                </div>

                <!-- Table Header -->
                <div
                    class="border-stroke py-4.5 dark:border-strokedark 2xl:px-7.5 grid grid-cols-6 border-t px-4 sm:grid-cols-8 md:px-6"
                >
                    <div class="col-span-2 flex items-center">
                        <p class="font-medium">Name</p>
                    </div>
                    <div class="col-span-2 hidden items-center sm:flex">
                        <p class="font-medium">Email</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                        <p class="font-medium">Role</p>
                    </div>
                    <div class="col-span-2 flex items-center">
                        <p class="font-medium">Action</p>
                    </div>
                </div>

                <!-- Table Rows -->
                <div
                    v-for="user in filteredUsers"
                    :key="user.id"
                    class="border-stroke py-4.5 dark:border-strokedark 2xl:px-7.5 grid grid-cols-6 border-t px-4 sm:grid-cols-8 md:px-6"
                >
                    <div class="col-span-2 flex items-center">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center"
                        >
                            <p
                                class="text-sm font-medium text-black dark:text-white"
                            >
                                {{ user.name }}
                            </p>
                        </div>
                    </div>
                    <div class="col-span-2 hidden items-center sm:flex">
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            {{ user.email }}
                        </p>
                    </div>

                    <div class="col-span-2 flex items-center">
                        <div class="grid gap-2">
                            <select
                                id="role"
                                v-model="user.pivot.role"
                                @change="
                                    submit(user.pivot.id, user.pivot.role.id)
                                "
                                required
                            >
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role"
                                >
                                    {{ role.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-2 flex items-center">
                        <p
                            class="text-sm font-medium text-black dark:text-white"
                        >
                            <a
                                @click.prevent="handleRemove(user.id)"
                                :class="[
                                    {
                                        'text-red-500 hover:cursor-pointer hover:underline':
                                            user.pivot.role_id > 1,
                                        'hidden text-green-500 hover:cursor-not-allowed':
                                            user.pivot.role_id === 1,
                                    },
                                    'text-semibold',
                                ]"
                                >Remove</a
                            >
                        </p>
                    </div>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
