<!-- <script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';
import { computed } from 'vue';

const amount = computed(() => {
    return selected_plan.value.price;
});

const planId = computed(() => {
    return selected_plan.value.id;
});

const form = useForm({
    org_name: '',
    org_email: '',
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    plan_id: planId,
    amount: amount,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};

const nameWithPrice = (plan: SubscriptionPlan) => {
    return `${plan.name} - GHS${plan.price}`;
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="org_name" value="Organization Name" />

                <TextInput
                    id="org_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.org_name"
                    required
                    autofocus
                    autocomplete="org_name"
                />

                <InputError class="mt-2" :message="form.errors.org_name" />
            </div>

            <div class="mt-4">
                <InputLabel for="org_email" value="Organization's Email" />

                <TextInput
                    id="org_email"
                    type="org_email"
                    class="mt-1 block w-full"
                    v-model="form.org_email"
                    required
                    autocomplete="org_email"
                />

                <InputError class="mt-2" :message="form.errors.org_email" />
            </div>
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
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4">
                <label class="typo__label">Select Your Plan</label>
                <div class="flex flex-col gap-3">
                    <Multiselect
                        v-model="selected_plan"
                        :options="subscriptionPlans"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :preserve-search="true"
                        placeholder="Select plan"
                        :custom-label="nameWithPrice"
                        track-by="id"
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
                        Pay {{ 'GHS' + form.amount }} and Register
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
    </GuestLayout>
</template> -->
