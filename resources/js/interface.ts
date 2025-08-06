export interface ChartOptions {
    responsive?: boolean;
    maintainAspectRatio?: boolean;
    plugins?: Record<string, any>;
    scales?: Record<string, any>;
}

export interface ChartConfig {
    type?: string;
    data: {
        labels: string[];
        datasets: Array<{
            label: string;
            data: number[];
            backgroundColor: string[];
            borderColor?: string[];
            borderWidth?: number;
            fill?: boolean;
        }>;
    };
    options?: ChartOptions;
    metadata?: Record<string, any> | null;
}

export interface Tenant {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
}
