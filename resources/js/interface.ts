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

export interface Tenant {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
}
