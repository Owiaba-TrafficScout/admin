<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import Layout from '@/Layouts/App.vue';
import { Link } from '@inertiajs/vue3';
import { ArrowUpRight, DollarSign, Map, Package, Users } from 'lucide-vue-next';
import { Payment } from './Payments.vue';
import { Trip } from './Trips.vue';

interface Total {
    name: string;
    value: number;
}
defineProps<{
    totals: Total[];
    payments: Payment[];
    trips: Trip[];
}>();
</script>

<template>
    <Layout page="Dashboard">
        <div class="flex min-h-screen w-full flex-col">
            <main class="flex flex-1 flex-col gap-4 p-4 md:gap-8 md:p-8">
                <div class="grid gap-4 md:grid-cols-2 md:gap-8 lg:grid-cols-4">
                    <Card v-for="total in totals" :key="total.name">
                        <CardHeader
                            class="flex flex-row items-center justify-between space-y-0 pb-2"
                        >
                            <CardTitle class="text-sm font-medium capitalize">
                                {{ 'Total ' + total.name }}
                            </CardTitle>
                            <DollarSign
                                class="h-4 w-4 text-muted-foreground"
                                v-if="total.name.toLowerCase() == 'payments'"
                            />
                            <Users
                                class="h-4 w-4 text-muted-foreground"
                                v-else-if="total.name.toLowerCase() == 'users'"
                            />
                            <Map
                                class="h-4 w-4 text-muted-foreground"
                                v-else-if="total.name.toLowerCase() == 'trips'"
                            />
                            <Package
                                class="h-4 w-4 text-muted-foreground"
                                v-else-if="
                                    total.name.toLowerCase() == 'projects'
                                "
                            />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">
                                <span
                                    v-if="
                                        total.name.toLowerCase() == 'payments'
                                    "
                                    >$</span
                                >{{ total.value }}
                            </div>
                            <!-- <p class="text-xs text-muted-foreground">
                                +20.1% from last month
                            </p> -->
                        </CardContent>
                    </Card>
                </div>
                <div class="grid gap-4 md:gap-8 lg:grid-cols-2 xl:grid-cols-3">
                    <Card class="xl:col-span-2">
                        <CardHeader class="flex flex-row items-center">
                            <div class="grid gap-2">
                                <CardTitle>Payments</CardTitle>
                                <CardDescription>
                                    Recent payments made
                                </CardDescription>
                            </div>
                            <Button as-child size="sm" class="ml-auto gap-1">
                                <Link :href="route('payments.index')">
                                    View All
                                    <ArrowUpRight class="h-4 w-4" />
                                </Link>
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>User</TableHead>
                                        <TableHead
                                            class="hidden xl:table-column"
                                        >
                                            Status
                                        </TableHead>
                                        <TableHead
                                            class="hidden xl:table-column"
                                        >
                                            Date
                                        </TableHead>
                                        <TableHead class="text-right">
                                            Amount
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="payment in payments"
                                        :key="payment.id"
                                    >
                                        <TableCell>
                                            <div class="font-medium">
                                                {{ payment.user.name }}
                                            </div>
                                            <div
                                                class="hidden text-sm text-muted-foreground md:inline"
                                            >
                                                {{ payment.user.email }}
                                            </div>
                                        </TableCell>
                                        <TableCell
                                            class="hidden xl:table-column"
                                        >
                                            <Badge
                                                class="text-xs"
                                                variant="outline"
                                            >
                                                {{ payment.status }}
                                            </Badge>
                                        </TableCell>
                                        <TableCell
                                            class="hidden md:table-cell lg:hidden xl:table-column"
                                        >
                                            {{ payment.date }}
                                        </TableCell>
                                        <TableCell class="text-right">
                                            {{ 'R ' + payment.amount }}
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle>Recent Trips</CardTitle>
                        </CardHeader>
                        <CardContent class="grid gap-8">
                            <div
                                class="flex items-center gap-4"
                                v-for="trip in trips"
                                :key="trip.id"
                            >
                                <Avatar class="hidden h-9 w-9 sm:flex">
                                    <AvatarFallback>{{
                                        trip.user.name[0]
                                    }}</AvatarFallback>
                                </Avatar>
                                <div class="grid gap-1">
                                    <p class="text-sm font-medium leading-none">
                                        {{ trip.title }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ trip.user.name }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </main>
        </div>
    </Layout>
</template>
