<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { User } from '@/types';
import { useForm } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';
import { computed, ref, Ref } from 'vue';

const props = defineProps<{
    users: User[];
}>();

const selected_users: Ref<User[]> = ref([]);

const selectedUsersIds = computed(() =>
    selected_users.value.map((user) => user.id),
);
const form = useForm({
    userIds: selectedUsersIds,
});

const submit_route = props.users[0]?.pivot
    ? 'project.users.store'
    : 'users.tenant.store';

const submit = () => {
    form.post(route(submit_route), {
        onError: (e) => {
            console.log(e);
        },
        onSuccess: () => {
            selected_users.value = [];
            form.reset();
        },
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="w-auto min-w-[30vw]">
        <div>
            <div class="flex flex-row gap-3">
                <Multiselect
                    v-model="selected_users"
                    :options="users"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :preserve-search="true"
                    placeholder="Select users"
                    label="name"
                    track-by="id"
                    multiple
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
                    class="ms-4 w-56"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    style="text-align: center"
                >
                    Add Users
                </PrimaryButton>
            </div>
        </div>
    </form>
</template>
