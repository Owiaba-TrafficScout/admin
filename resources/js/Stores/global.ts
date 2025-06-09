import { Project } from '@/Pages/Projects.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useGlobalStore = defineStore('global', () => {
    const page = usePage();
    //copy project from usepage
    const selected_project = ref<Project>(page.props.selected_project);
    // Whenever page props change, keep our store in sync
    watch(
        () => page.props.selected_project,
        (newVal) => {
            selected_project.value = newVal;
        },
    );

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
