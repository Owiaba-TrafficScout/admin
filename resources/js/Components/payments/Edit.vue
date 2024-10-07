<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';
import { Payment } from '@/Pages/Payments.vue';
import { useForm } from '@inertiajs/vue3';
import { inject } from 'vue';

const props = defineProps<{
    payment: Payment;
}>();

const emit = defineEmits(['update']);

const form = useForm({
    date: new Date(props.payment.date).toLocaleDateString('en-CA'),
    amount: props.payment.amount,
    description: props.payment.description,
    payment_status_id: props.payment.status.id,
});

const submit = () => {
    // dynamicaly post to update or store rout
    form.patch(route('payments.update', props.payment.id), {
        onSuccess: () => {
            form.reset();
            emit('update');
        },
    });
};

const statuses = inject('statuses') as { id: number; name: string }[];
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
                        <CardTitle class="text-2xl"> Edit Payment </CardTitle>
                        <CardDescription> Update fields below </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="title">Date</Label>
                            <Input
                                id="date"
                                type="date"
                                v-model="form.date"
                                required
                            />
                            <div
                                v-if="form.errors.date"
                                class="text-xs text-red-500"
                            >
                                {{ form.errors.date }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="description">description</Label>
                            <textarea
                                name="description"
                                id="description"
                                v-model="form.description"
                            ></textarea>
                            <div
                                v-if="form.errors.description"
                                class="text-xs text-red-500"
                            >
                                {{ form.errors.description }}
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="group_code">Amount</Label>
                            <Input
                                id="group_code"
                                type="text"
                                v-model="form.amount"
                                required
                            />
                            <div
                                v-if="form.errors.amount"
                                class="text-xs text-red-500"
                            >
                                {{ form.errors.amount }}
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <Label for="payment_status_id">Status</Label>
                            <select
                                id="payment_status_id"
                                v-model="form.payment_status_id"
                                required
                                >
                                <template
                                    v-for="status in statuses"
                                    :key="status.id"
                                >
                                    <option
                                        :value="status.id"
                                        :selected="
                                            form.payment_status_id == status.id
                                        "
                                    >
                                        {{ status.name }}
                                    </option>
                                </template>
                            </select>
                            <div v-if="form.errors.payment_status_id">
                                {{ form.errors.payment_status_id }}
                            </div>
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
