<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';
import { computed, ref } from 'vue';

defineProps<{ tenants: Tenant[] }>();
interface Tenant {
    id: number;
    name: string;
}

const selected_tenant = ref({ id: 0, name: '' });

const selectedTenantId = computed(() => selected_tenant.value.id);
const form = useForm({
    tenant_id: selectedTenantId,
});

const handleNext = () => {
    // go to route tenant.selected.store
    form.post(route('tenant.selected.store'), {});
};
</script>

<template>
    <Head title="select-tenant" />

    <!-- Main content -->
    <div class="container mx-auto">
        <div
            class="flex items-center justify-center font-semibold"
            style="height: 90vh"
        >
            <div class="mt-4 w-1/2">
                <label class="typo__labell">Select Organization</label>

                <Multiselect
                    class="mt-4 w-full"
                    v-model="selected_tenant"
                    :options="tenants"
                    :close-on-select="true"
                    :clear-on-select="false"
                    :preserve-search="true"
                    placeholder="Select Organization"
                    label="name"
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
                    class="mt-4 w-full"
                    :disabled="selected_tenant.id === 0"
                    @click.prevent="handleNext"
                >
                    Next
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>
