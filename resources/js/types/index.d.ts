import { Tenant } from '@/interface';
import { Project } from '@/Pages/Projects.vue';

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export interface FlashMessage {
    success?: string;
    error?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
        is_tenant_admin: boolean;
        is_project_admin: boolean;
        is_super_admin: boolean;
    };
    flash: FlashMessage;
    selected_project: Project;
    selected_tenant: Tenant;
    projects: Project[];
    tenants: Tenant[] | null;
};
