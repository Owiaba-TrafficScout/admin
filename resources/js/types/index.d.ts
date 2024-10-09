export interface User {
    id: number;
    name: string;
    email: string;
    role: { id: number; name: string };
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
    };
    flash: FlashMessage;
};
