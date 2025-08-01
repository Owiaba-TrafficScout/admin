<script setup lang="ts">
import SearchForm from '@/Components/SearchForm.vue';
import FilterRadios from '@/Components/trips/FilterRadios.vue';
import { Trip } from '@/Pages/Trips.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, defineProps, ref } from 'vue';
import Tooltip from '../Tooltip.vue';
import Edit from './Edit.vue';
import Speeds from './Speeds.vue';
import Stops from './Stops.vue';

const props = defineProps<{
    items: Trip[];
}>();
const search = ref('');
const selectedTrips = ref<number[]>([]); // store selected trip IDs
const selectAll = ref(false); // store "select all" checkbox state

const handleSearch = (s: string) => {
    search.value = s;
};

// Toggle selection of a single trip
function toggleTrip(tripId: number) {
    if (selectedTrips.value.includes(tripId)) {
        selectedTrips.value = selectedTrips.value.filter((id) => id !== tripId);
    } else {
        selectedTrips.value.push(tripId);
    }
}

// Toggle all items
function toggleSelectAll() {
    selectAll.value = !selectAll.value;
    if (selectAll.value) {
        selectedTrips.value = filteredItems.value.map((item) => item.id);
    } else {
        selectedTrips.value = [];
    }
}

// Bulk delete
function deleteSelectedTrips() {
    if (!selectedTrips.value.length) return;
    if (!confirm('Are you sure you want to delete these trips?')) return;

    const form = useForm({
        trip_ids: selectedTrips.value,
    });

    // Example: you can define a route or pass the endpoint as needed
    form.delete(route('trips.bulkDestroy'), {
        onError: (errors) => {
            // Handle errors if needed
            console.error('Error deleting trips:', errors);
        },
        onSuccess: () => {
            // Clear selection
            selectedTrips.value = [];
            selectAll.value = false;
        },
    });
}

// Filter logic
const handleDelete = (id: number) => {
    if (confirm('Are you sure you want to delete this item?')) {
        const form = useForm({});
        form.delete(route('trips.destroy', id));
    }
};

const filteredItems = computed(() => {
    if (search.value !== '') {
        return props.items.filter(
            (item) =>
                item.title.toLowerCase().includes(search.value.toLowerCase()) ||
                item.description
                    .toLowerCase()
                    .includes(search.value.toLowerCase()) ||
                item.group_code
                    .toLowerCase()
                    .includes(search.value.toLowerCase()),
        );
    }
    return props.items;
});

const handleFilter = (filter: string) => {
    if (filter === 'active') {
        search.value = 'active';
    } else if (filter === 'inactive') {
        search.value = 'inactive';
    } else {
        search.value = '';
    }
};
</script>

<template>
    <div
        class="border-stroke shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 rounded-sm border bg-white px-5 pb-2.5 pt-6 xl:pb-1"
    >
        <div class="mb-5 flex items-center justify-between">
            <SearchForm @search="handleSearch" />
            <FilterRadios @filter="handleFilter" />
        </div>

        <!-- Bulk delete button (shown when at least one trip is selected) -->
        <div v-if="selectedTrips.length" class="mb-4">
            <button
                @click="deleteSelectedTrips"
                class="rounded bg-red-600 px-3 py-2 text-white hover:bg-red-700"
            >
                Delete Selected
            </button>
        </div>

        <div class="max-w-full overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-2 dark:bg-meta-4 text-left">
                        <!-- "Select All" checkbox -->
                        <th class="px-4 py-4">
                            <input
                                type="checkbox"
                                :checked="selectAll"
                                @change="toggleSelectAll"
                            />
                        </th>
                        <th
                            class="min-w-[220px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11"
                        >
                            Title
                        </th>
                        <th
                            class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                            Project
                        </th>
                        <th
                            class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                            Car Type
                        </th>
                        <th
                            class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                            User
                        </th>
                        <th
                            class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                            Start
                        </th>
                        <th
                            class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                            End
                        </th>
                        <th
                            class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                            Stops
                        </th>
                        <th
                            class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                            Speeds
                        </th>
                        <th
                            class="px-4 py-4 font-medium text-black dark:text-white"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredItems" :key="item.id">
                        <!-- Checkbox for each trip row -->
                        <td class="px-4 py-5">
                            <input
                                type="checkbox"
                                :value="item.id"
                                :checked="selectedTrips.includes(item.id)"
                                @change="toggleTrip(item.id)"
                            />
                        </td>
                        <td class="px-4 py-5 pl-9 xl:pl-11">
                            <h5 class="font-medium text-black dark:text-white">
                                {{ item.title }}
                            </h5>
                            <p class="text-sm">{{ item.group_code }}</p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="text-black dark:text-white">
                                {{ item.project_user.project.name }}
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="text-black dark:text-white">
                                <Tooltip
                                    :tooltip-content="item.car.car_number"
                                    :display-text="item.car.type.name"
                                />
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="text-black dark:text-white">
                                {{ item.project_user.user.name }}
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="text-black dark:text-white">
                                {{ new Date(item.start_time).toLocaleString() }}
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="text-black dark:text-white">
                                {{ new Date(item.end_time).toLocaleString() }}
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="text-black dark:text-white">
                                <Stops :stops="item.stops" />
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <p class="text-black dark:text-white">
                                <Speeds :speeds="item.speeds" />
                            </p>
                        </td>
                        <td class="px-4 py-5">
                            <div class="flex items-center space-x-3.5">
                                <Edit :trip="item">
                                    <button class="hover:text-primary">
                                        <svg
                                            class="fill-current"
                                            width="18"
                                            height="18"
                                            viewBox="0 0 18 18"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M8.99981 14.8219C3.43106 14.8219 0.674805 9.50624 0.562305 9.28124C0.47793 9.11249 0.47793 8.88749 0.562305 8.71874C0.674805 8.49374 3.43106 3.20624 8.99981 3.20624C14.5686 3.20624 17.3248 8.49374 17.4373 8.71874C17.5217 8.88749 17.5217 9.11249 17.4373 9.28124C17.3248 9.50624 14.5686 14.8219 8.99981 14.8219ZM1.85605 8.99999C2.4748 10.0406 4.89356 13.5562 8.99981 13.5562C13.1061 13.5562 15.5248 10.0406 16.1436 8.99999C15.5248 7.95936 13.1061 4.44374 8.99981 4.44374C4.89356 4.44374 2.4748 7.95936 1.85605 8.99999Z"
                                                fill=""
                                            />
                                            <path
                                                d="M9 11.3906C7.67812 11.3906 6.60938 10.3219 6.60938 9C6.60938 7.67813 7.67812 6.60938 9 6.60938C10.3219 6.60938 11.3906 7.67813 11.3906 9C11.3906 10.3219 10.3219 11.3906 9 11.3906ZM9 7.875C8.38125 7.875 7.875 8.38125 7.875 9C7.875 9.61875 8.38125 10.125 9 10.125C9.61875 10.125 10.125 9.61875 10.125 9C10.125 8.38125 9.61875 7.875 9 7.875Z"
                                                fill=""
                                            />
                                        </svg>
                                    </button>
                                </Edit>
                                <button
                                    class="hover:text-primary"
                                    @click="handleDelete(item.id)"
                                >
                                    <svg
                                        class="fill-current"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 18 18"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M13.7535 2.47502H11.5879V1.9969C11.5879 1.15315 10.9129 0.478149 10.0691 0.478149H7.90352C7.05977 0.478149 6.38477 1.15315 6.38477 1.9969V2.47502H4.21914C3.40352 2.47502 2.72852 3.15002 2.72852 3.96565V4.8094C2.72852 5.42815 3.09414 5.9344 3.62852 6.1594L4.07852 15.4688C4.13477 16.6219 5.09102 17.5219 6.24414 17.5219H11.7004C12.8535 17.5219 13.8098 16.6219 13.866 15.4688L14.3441 6.13127C14.8785 5.90627 15.2441 5.3719 15.2441 4.78127V3.93752C15.2441 3.15002 14.5691 2.47502 13.7535 2.47502ZM7.67852 1.9969C7.67852 1.85627 7.79102 1.74377 7.93164 1.74377H10.0973C10.2379 1.74377 10.3504 1.85627 10.3504 1.9969V2.47502H7.70664V1.9969H7.67852ZM4.02227 3.96565C4.02227 3.85315 4.10664 3.74065 4.24727 3.74065H13.7535C13.866 3.74065 13.9785 3.82502 13.9785 3.96565V4.8094C13.9785 4.9219 13.8941 5.0344 13.7535 5.0344H4.24727C4.13477 5.0344 4.02227 4.95002 4.02227 4.8094V3.96565ZM11.7285 16.2563H6.27227C5.79414 16.2563 5.40039 15.8906 5.37227 15.3844L4.95039 6.2719H13.0785L12.6566 15.3844C12.6004 15.8625 12.2066 16.2563 11.7285 16.2563Z"
                                        />
                                        <path
                                            d="M9.00039 9.11255C8.66289 9.11255 8.35352 9.3938 8.35352 9.75942V13.3313C8.35352 13.6688 8.63477 13.9782 9.00039 13.9782C9.33789 13.9782 9.64727 13.6969 9.64727 13.3313V9.75942C9.64727 9.3938 9.33789 9.11255 9.00039 9.11255Z"
                                        />
                                        <path
                                            d="M11.2502 9.67504C10.8846 9.64692 10.6033 9.90004 10.5752 10.2657L10.4064 12.7407C10.3783 13.0782 10.6314 13.3875 10.9971 13.4157C11.0252 13.4157 11.0252 13.4157 11.0533 13.4157C11.3908 13.4157 11.6721 13.1625 11.6721 12.825L11.8408 10.35C11.8408 9.98442 11.5877 9.70317 11.2502 9.67504Z"
                                        />
                                        <path
                                            d="M6.72245 9.67504C6.38495 9.70317 6.1037 10.0125 6.13182 10.35L6.3287 12.825C6.35683 13.1625 6.63808 13.4157 6.94745 13.4157C6.97558 13.4157 6.97558 13.4157 7.0037 13.4157C7.3412 13.3875 7.62245 13.0782 7.59433 12.7407L7.39745 10.2657C7.39745 9.90004 7.08808 9.64692 6.72245 9.67504Z"
                                        />
                                    </svg>
                                </button>
                                <a
                                    class="hover:text-primary"
                                    :href="route('export.trip', item.id)"
                                >
                                    <svg
                                        class="fill-current"
                                        width="18"
                                        height="18"
                                        viewBox="0 0 18 18"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M16.8754 11.6719C16.5379 11.6719 16.2285 11.9531 16.2285 12.3187V14.8219C16.2285 15.075 16.0316 15.2719 15.7785 15.2719H2.22227C1.96914 15.2719 1.77227 15.075 1.77227 14.8219V12.3187C1.77227 11.9812 1.49102 11.6719 1.12539 11.6719C0.759766 11.6719 0.478516 11.9531 0.478516 12.3187V14.8219C0.478516 15.7781 1.23789 16.5375 2.19414 16.5375H15.7785C16.7348 16.5375 17.4941 15.7781 17.4941 14.8219V12.3187C17.5223 11.9531 17.2129 11.6719 16.8754 11.6719Z"
                                        />
                                        <path
                                            d="M8.55074 12.3469C8.66324 12.4594 8.83199 12.5156 9.00074 12.5156C9.16949 12.5156 9.31012 12.4594 9.45074 12.3469L13.4726 8.43752C13.7257 8.1844 13.7257 7.79065 13.5007 7.53752C13.2476 7.2844 12.8539 7.2844 12.6007 7.5094L9.64762 10.4063V2.1094C9.64762 1.7719 9.36637 1.46252 9.00074 1.46252C8.66324 1.46252 8.35387 1.74377 8.35387 2.1094V10.4063L5.40074 7.53752C5.14762 7.2844 4.75387 7.31252 4.50074 7.53752C4.24762 7.79065 4.27574 8.1844 4.50074 8.43752L8.55074 12.3469Z"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
