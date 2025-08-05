import { Tenant } from '@/interface';
import { Project } from '@/Pages/Projects.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { reactive, ref, watch } from 'vue';

export const useGlobalStore = defineStore('global', () => {
    const page = usePage();
    //copy project from usepage
    const selected_project = ref<Project>(page.props.selected_project);

    // copy tenant from usepage
    const selected_tenant = ref<Tenant>(page.props.selected_tenant);
    const projects = reactive<Project[]>(page.props.projects);
    // Whenever page props change, keep our store in sync
    watch(
        () => page.props.selected_project,
        (newVal) => {
            selected_project.value = newVal;
        },
    );

    watch(
        () => page.props.selected_tenant,
        (newVal) => {
            selected_tenant.value = newVal;
        },
    );
    watch(
        () => page.props.projects,
        (newVal) => {
            projects.splice(0, projects.length, ...newVal);
        },
    );

    const form = useForm<{ project_id: number }>({
        project_id: 0,
    });

    const tenantForm = useForm<{ tenant_id: number }>({
        tenant_id: 0,
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

    async function handleTenantSelect(id: number) {
        tenantForm.tenant_id = id;
        try {
            await tenantForm.post(route('tenant.selected.store'), {
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
        selected_tenant,
        handleTenantSelect,
        projects,
    };
});
