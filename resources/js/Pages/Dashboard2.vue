<script setup lang="ts">
import { Avatar, AvatarFallback } from '@/Components/ui/avatar';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';
import { ChartConfig } from '@/interface';
import Layout from '@/Layouts/App.vue';
import { ArcElement, Chart as ChartJS, Legend, Title, Tooltip } from 'chart.js';
import { DollarSign, Map, Package, Users } from 'lucide-vue-next';
import { Pie } from 'vue-chartjs';
import { Trip } from './Trips.vue';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

interface Total {
    name: string;
    value: number;
}
defineProps<{
    totals: Total[];
    trips: Trip[];
    dailyTripCount: number;
    tripDistributionData: ChartConfig;
}>();
</script>

<template>
    <Layout page=" OTS Admin  Dashboard">
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
                <div class="grid gap-4 md:gap-8 lg:grid-cols-2 xl:grid-cols-2">
                    <!-- TODO: fill out this table -->
                    <!-- <Card class="xl:col-span-2">
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
                    </Card> -->
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
                                        trip.project_user.user.name[0]
                                    }}</AvatarFallback>
                                </Avatar>
                                <div class="grid gap-1">
                                    <p class="text-sm font-medium leading-none">
                                        {{ trip.title }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ trip.project_user.user.name }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <div class="flex flex-col gap-4">
                        <!-- Daily Tripsense Analytics (black div) -->
                        <div class="b w-full rounded-lg p-4">
                            <h3
                                class="mb-2 flex items-center gap-2 text-lg font-bold"
                            >
                                <div
                                    class="h-5 w-5 rounded-full bg-green-800"
                                ></div>
                                <div>Daily Tripsense Analytics</div>
                            </h3>
                            <!-- Example: Show today's trip count -->
                            <div>
                                <span class="font-bold">{{
                                    dailyTripCount
                                }}</span>
                                <span class="ml-2">trips today</span>
                            </div>
                            <div class="mt-2">
                                <p>
                                    Trip data shows "KNUST Shuttle Study" and
                                    "Urban Transport Study 2024" have few but
                                    long trips, while "Rural Transport Study
                                    2024" has more, shorter trips. "Public
                                    Transport Efficiency 2024" and "Freight
                                    Transport Analysis 2024" have no trips yet,
                                    indicating possible delays or ongoing
                                    planning.
                                </p>
                            </div>
                        </div>
                        <!-- Pie Chart: Distribution of Trips Among Project Users (green div) -->
                        <div
                            class="flex h-[55%] flex-col items-center justify-center rounded-lg"
                        >
                            <h3 class="mb-2 text-lg font-bold">
                                Trip Distribution by User
                            </h3>
                            <!-- Pie chart component (replace with your chart library) -->
                            <Pie
                                :data="tripDistributionData.data"
                                :options="tripDistributionData.options"
                            />
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </Layout>
</template>
