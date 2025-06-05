import { Project } from '@/Pages/Projects.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useGlobalStore = defineStore('global', () => {
    //copy project from usepage
    const selected_project = ref<Project>(usePage().props.selected_project);

    const form = useForm<{ project_id: number }>({
        project_id: 0,
    });

    async function handleSelect(id: number) {
        form.project_id = id;
        try {
            await form.post(route('project.selected.store'), {
                onError: (errors) => {
                    console.error('selection error', errors);
                },
            });
        } catch (e) {
            console.error('unexpected error', e);
        }
    }

    return {
        selected_project,
        handleSelect,
    };
});
