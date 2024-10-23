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
    };
    flash: FlashMessage;
};
