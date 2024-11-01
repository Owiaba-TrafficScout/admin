<script setup lang="ts">
import Layout from '@/Layouts/App.vue';
import { CopyIcon, Plus } from 'lucide-vue-next';
import { ref } from 'vue';

import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { provide } from 'vue';
import { Trip, User } from './Trips.vue';
export interface Project {
    id: number;
    name: string;
    email: string;
    description: string;
    code: string;
    start_date: string;
    end_date: string;
    trips: Trip[];
    users: User[];
}

const props = defineProps<{
    projects: Project[];
    roles: { id: number; email: string }[];
}>();

const is_tenant_admin = usePage().props.auth.is_tenant_admin;

provide('roles', props.roles);

const btnClasses = ref(` ml-10 w-32 inline-flex items-center rounded-md border
    border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold
    uppercase tracking-widest text-white transition duration-150 ease-in-out
    hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200
    dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white
    dark:focus:ring-offset-gray-800 dark:active:bg-gray-300`);

const form = useForm({
    email: '',
});

const sendInvitation = () => {
    form.post(
        route('projects.invite', {
            project: usePage().props.selected_project.id,
        }),
        {
            onSuccess: () => {
                form.reset();
            },
            onError: (e) => {
                console.log(e);
            },
        },
    );
};

const copyProjectCode = async () => {
    await navigator.clipboard.writeText(usePage().props.selected_project.code);
    alert('Project code copied to clipboard');
};
</script>

<template>
    <Layout page="Project">
        <div class="flex flex-col gap-5">
            <div class="mt-2 flex flex-col gap-5">
                <a
                    v-if="is_tenant_admin"
                    :href="route('projects.create')"
                    :class="btnClasses"
                    class="w-fit"
                    >New Project <Plus class="ml-2" :size="16"
                /></a>
            </div>
            <h1 class="mt-20 text-lg font-semibold capitalize text-black">
                Invite users to this Project
            </h1>
            <div class="mt-5 flex flex-row gap-5">
                <div>
                    <TextInput
                        id="email"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Email"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <button
                    v-if="is_tenant_admin"
                    @click="sendInvitation"
                    :class="btnClasses"
                    class="w-fit"
                >
                    Send Invitation
                </button>
            </div>

            <div>
                <h3 class="mt-20 text-lg font-semibold capitalize text-black">
                    or coppy and share project code 👇
                </h3>
                <!-- click to copy project code -->
                <p
                    @click="copyProjectCode"
                    class="my-5 flex cursor-pointer flex-row text-black"
                >
                    {{ usePage().props.selected_project.code }}
                    <CopyIcon class="ml-2" :size="18" />
                </p>
            </div>
        </div>
    </Layout>
</template>
