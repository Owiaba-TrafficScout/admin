<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
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
    car_type_ids: [] as number[],
});

const submit = () => {
    form.car_type_ids = car_types.value.map((carType) => carType.id);
    form.post(route('project.cartype.add'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Layout page="Add Car Type">
        <Head title="Add Car Type" />

        <form @submit.prevent="submit" class="mt-10 w-auto min-w-[30vw]">
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
                        tagPlaceholder="press enter to create new car type"
                        @tag="addCarType"
                        :show-labels="false"
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
                        Add Cartype(s)
                    </PrimaryButton>
                </div>
            </div>
        </form>
    </Layout>
</template>
