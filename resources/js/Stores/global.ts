import { Project } from '@/Pages/Projects.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { Ref, ref } from 'vue';

export const useGlobalStore = defineStore('global', () => {
    //copy project from usepage
    const selected_project: Ref<Project> = ref(
        JSON.parse(JSON.stringify(usePage().props.selected_project)),
    );

    const form = useForm({
        project_id: 0,
    });

    const handleSelect = (id: number) => {
        form.project_id = id;

        console.log(form.project_id);
        // go to route tenant.selected.store
        form.post(route('project.selected.store'), {
            onError: (e) => {
                console.log(e);
            },
        });
    };

    return {
        selected_project,
        handleSelect,
    };
});
