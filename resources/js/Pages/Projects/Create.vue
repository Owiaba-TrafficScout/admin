<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Layouts/App.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';
import { ref, Ref } from 'vue';
import { CarType } from '../CarTypes.vue';

defineProps<{
    carTypes: CarType[];
}>();

const car_types: Ref<CarType[]> = ref([]);

const addCarType = (newCarType: string) => {
    car_types.value.push({
        id: car_types.value.length + 1,
        name: newCarType,
    });
};

const form = useForm({
    name: '',
    description: '',
    start_date: '',
    end_date: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Layout page="Create New Project">
        <Head title="Create New Project" />
        {{ car_types }}
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Organization Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="description" value="Organization's Email" />

                <TextInput
                    id="description"
                    class="mt-1 block w-full"
                    v-model="form.description"
                    required
                    autocomplete="description"
                />

                <InputError class="mt-2" :message="form.errors.description" />
            </div>
            <div class="mt-4">
                <InputLabel for="start_date" value="Start Date" />

                <TextInput
                    id="start_date"
                    type="date"
                    class="mt-1 block w-full"
                    v-model="form.start_date"
                    required
                    autocomplete="start_date"
                />

                <InputError class="mt-2" :message="form.errors.start_date" />
            </div>
            <div class="mt-4">
                <InputLabel for="end_date" value="End Date" />

                <TextInput
                    id="end_date"
                    type="date"
                    class="mt-1 block w-full"
                    v-model="form.end_date"
                    required
                    autocomplete="end_date"
                />

                <InputError class="mt-2" :message="form.errors.end_date" />
            </div>

            <div class="mt-4">
                <label class="typo__label"
                    >Select or Create Your Car Types</label
                >
                <div class="flex flex-col gap-3">
                    <Multiselect
                        v-model="car_types"
                        :options="carTypes"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select Car Types"
                        label="name"
                        track-by="id"
                        multiple
                        taggable
                        @tag="addCarType"
                    >
                        <template #selection="{ values, isOpen }">
                            <span
                                class="multiselect__single"
                                v-if="values.length"
                                v-show="!isOpen"
                                >{{ values.length }} options selected</span
                            >
                        </template>
                    </Multiselect>

                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Create Project
                    </PrimaryButton>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>
            </div>
        </form>
    </Layout>
</template>
