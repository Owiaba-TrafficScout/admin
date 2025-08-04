<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';
import Layout from '@/Layouts/App.vue';
import { Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';

/* ──────────────────────────
   Chart.js + vue-chartjs setup
   ────────────────────────── */
import { Chart as ChartJS, registerables } from 'chart.js';
import { Bar, Line, Pie } from 'vue-chartjs';
ChartJS.register(...registerables);

// ──────────────────────────
const page = usePage();
const tenantId = ref(page.props.selected_project.tenant_id);

// --- Component State ---
const query = ref('');
const isLoading = ref(false);
const statusMessage = ref('');
const textInsight = ref('');
const chartConfigBackend = ref<any>(null);
const error = ref('');

// Add chart type selection state
const selectedChartType = ref('AI'); // Default to AI

// --- Reactive props for <Chart /> ---
const chartType = computed(() => {
    if (selectedChartType.value === 'AI') {
        return chartConfigBackend.value?.type ?? 'bar';
    }
    return selectedChartType.value.toLowerCase();
});

// Chart-specific computed properties
const barChartData = computed(
    () => chartConfigBackend.value?.data ?? { labels: [], datasets: [] },
);

const lineChartData = computed(
    () => chartConfigBackend.value?.data ?? { labels: [], datasets: [] },
);

const pieChartData = computed(
    () => chartConfigBackend.value?.data ?? { labels: [], datasets: [] },
);

function sanitiseOptions(raw?: any) {
    const base = { responsive: true, maintainAspectRatio: false };
    if (!raw) return base;
    return {
        ...base,
        ...raw,
        plugins: raw.plugins ?? {},
        scales: raw.scales ?? {},
    };
}

const barChartOptions = computed(() =>
    sanitiseOptions(chartConfigBackend.value?.options),
);

const lineChartOptions = computed(() =>
    sanitiseOptions(chartConfigBackend.value?.options),
);

const pieChartOptions = computed(() => {
    const options = sanitiseOptions(chartConfigBackend.value?.options);
    // Remove scales for pie chart as it doesn't use them
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { scales, ...pieOptions } = options;
    return pieOptions;
});

// --- API Interaction ---
const askDataPilot = async () => {
    if (!query.value || isLoading.value) return;
    if (!tenantId.value) {
        error.value = 'Tenant ID is missing. Please refresh or re-login.';
        return;
    }

    isLoading.value = true;
    error.value = '';
    textInsight.value = '';
    chartConfigBackend.value = null;
    statusMessage.value = 'Initializing agent...';

    try {
        console.log('Sending:', {
            question: query.value,
            tenant_id: tenantId.value,
        });
        const { data } = await axios.post(
            'http://localhost:5000/api/query',
            { question: query.value, tenant_id: tenantId.value },
            {
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
            },
        );

        if (data.error) {
            error.value = data.error;
            statusMessage.value = 'An error occurred.';
        } else {
            textInsight.value = data.text_insight || '';
            chartConfigBackend.value = data.chart_config ?? null;
            statusMessage.value = 'Analysis complete.';
        }
    } catch (e: any) {
        error.value = e.message ?? 'Unknown connection error.';
        statusMessage.value = 'Connection failed.';
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Head title="Tripsense" />

    <Layout page="Tripsense">
        <!-- header slot kept -->
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tripsense Analytics
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Input Card (unchanged) -->
                <Card>
                    <CardHeader>
                        <CardTitle>Ask a Question</CardTitle>
                        <CardDescription>
                            Ask a question about your transportation data in
                            plain English. Tripsense will analyze it and provide
                            insights.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid w-full items-center gap-4">
                            <div class="flex flex-col space-y-1.5">
                                <textarea
                                    id="query"
                                    class="block w-full rounded-md border border-gray-300 p-2 text-base shadow-sm focus:border-primary focus:ring focus:ring-primary/20"
                                    placeholder="e.g., What were the top 5 trip purposes last month?"
                                    v-model="query"
                                    @keyup.enter.prevent="askDataPilot"
                                    :disabled="isLoading"
                                    rows="3"
                                ></textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <Button
                                @click="askDataPilot"
                                :disabled="isLoading || !query"
                            >
                                {{
                                    isLoading ? 'Analyzing...' : 'Ask Tripsense'
                                }}
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Results Section -->
                <div v-if="isLoading || error || textInsight">
                    <Card>
                        <CardHeader>
                            <CardTitle>Analysis Result</CardTitle>
                            <CardDescription v-if="isLoading">
                                {{ statusMessage }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <!-- Loading / Error / Success blocks unchanged except chart part -->
                            <div
                                v-if="isLoading"
                                class="flex items-center justify-center p-8"
                            >
                                <!-- spinner svg unchanged -->
                            </div>

                            <div
                                v-if="error"
                                class="relative rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700"
                                role="alert"
                            >
                                <strong class="font-bold">Error: </strong>
                                <span class="block sm:inline">{{ error }}</span>
                            </div>

                            <div
                                v-if="!isLoading && textInsight"
                                class="space-y-6"
                            >
                                <div>
                                    <h3 class="mb-2 text-lg font-semibold">
                                        Text Insight
                                    </h3>
                                    <p
                                        class="whitespace-pre-wrap text-gray-700"
                                    >
                                        {{ textInsight }}
                                    </p>
                                </div>

                                <div v-if="chartConfigBackend" class="mt-6">
                                    <div
                                        class="mb-4 flex items-center justify-between"
                                    >
                                        <h3 class="text-lg font-semibold">
                                            Visualization
                                        </h3>

                                        <!-- Chart Type Radio Buttons -->
                                        <div class="flex gap-4">
                                            <label
                                                class="flex items-center gap-2"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="selectedChartType"
                                                    value="AI"
                                                    class="text-primary focus:ring-primary"
                                                />
                                                <span class="text-sm">AI</span>
                                            </label>
                                            <label
                                                class="flex items-center gap-2"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="selectedChartType"
                                                    value="bar"
                                                    class="text-primary focus:ring-primary"
                                                />
                                                <span class="text-sm">Bar</span>
                                            </label>
                                            <label
                                                class="flex items-center gap-2"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="selectedChartType"
                                                    value="line"
                                                    class="text-primary focus:ring-primary"
                                                />
                                                <span class="text-sm"
                                                    >Line</span
                                                >
                                            </label>
                                            <label
                                                class="flex items-center gap-2"
                                            >
                                                <input
                                                    type="radio"
                                                    v-model="selectedChartType"
                                                    value="pie"
                                                    class="text-primary focus:ring-primary"
                                                />
                                                <span class="text-sm">Pie</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="h-96">
                                        <!-- Dynamic Chart Components -->
                                        <Bar
                                            v-if="chartType === 'bar'"
                                            :data="barChartData"
                                            :options="barChartOptions"
                                        />
                                        <Line
                                            v-else-if="chartType === 'line'"
                                            :data="lineChartData"
                                            :options="lineChartOptions"
                                        />
                                        <Pie
                                            v-else-if="chartType === 'pie'"
                                            :data="pieChartData"
                                            :options="pieChartOptions"
                                        />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Layout>
</template>
