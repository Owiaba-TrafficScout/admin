<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '@/Layouts/App.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';
import { ref, Ref } from 'vue';
import { CarType } from '../CarTypes.vue';

const props = defineProps<{
    carTypes: CarType[];
}>();

const car_types: Ref<CarType[]> = ref([]);
const carTypeForm = useForm({
    name: '',
});
const addCarType = (newCarType: string) => {
    carTypeForm.name = newCarType;
    carTypeForm.post(route('car-types.store'), {
        onError: (errors) => {
            console.log(errors);
        },
        onSuccess: () => {
            const cType = props.carTypes.find(
                (carType) => carType.name === carTypeForm.name,
            );
            if (cType !== undefined) car_types.value.push(cType);
            carTypeForm.reset();
        },
    });
};

const form = useForm({
    name: '',
    description: '',
    start_date: '',
    end_date: '',
});

const submit = () => {
    form.post(route('projects.store'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Layout page="Create New Project">
        <Head title="Create New Project" />

        <form @submit.prevent="submit" class="mt-10 w-auto min-w-[30vw]">
            <div>
                <InputLabel for="name" value="Name" />

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
                <InputLabel for="description" value="Description" />
                <textarea
                    name="description"
                    id="description"
                    v-model="form.description"
                ></textarea>

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
                        tagPosition="top"
                        tagPlaceholder="Add this as new car type"
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
        </form>
    </Layout>
</template>
