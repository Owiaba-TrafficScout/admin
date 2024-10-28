import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useGlobalStore = defineStore('global', () => {
    const projects = ref([]);
    const selected_project = ref(null);
});
