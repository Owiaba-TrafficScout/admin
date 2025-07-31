export interface Speed {
    id: number;
    time: string;
    location_x: number;
    location_y: number;
    velocity: number;
    is_traffic: boolean;
}

export interface Stop {
    id: number;
    start_time: string | null;
    start_location_x: number | null;
    start_location_y: number | null;
    stop_time: string | null;
    stop_location_x: number | null;
    stop_location_y: number | null;
    passengers_count: number | null;
    passengers_boarding: number | null;
    passengers_alighting: number | null;
    is_traffic: boolean | null;
}

export interface chatMessage {
    question: string;
    tenant_id: number;
    project_ids?: number[];
}

export interface ChartConfig {
    type: string;
    data: {
        labels: string[];
        datasets: Array<{
            label: string;
            data: number[];
            backgroundColor: string[];
        }>;
    };
}

export interface FinalAnalysis {
    question: string;
    sql_query: string;
    data_count: number;
    chart_config: ChartConfig;
    insights: string;
    follow_up_questions: string[];
    execution_time: string; // ISO 8601 timestamp
    tenant_id: number;
    project_ids: number[] | null;
    exploration_depth: number;
    analysis_type: 'single_analyst' | 'collaborative' | string;
}

export interface Conversation {
    title: string;
    preview: string;
}

export interface Message {
    role: 'user' | 'assistant';
    content: string | ChartConfig;
    timestamp: Date;
}
