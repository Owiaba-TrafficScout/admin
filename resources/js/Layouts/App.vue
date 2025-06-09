<script setup lang="ts">
import {
    Car,
    CircleUser,
    Home,
    Map,
    Menu,
    Package,
    ShoppingCart,
    Users,
} from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Alert from '@/Components/ui/alert/Alert.vue';
import AlertDescription from '@/Components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/Components/ui/alert/AlertTitle.vue';
import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Sheet, SheetContent, SheetTrigger } from '@/Components/ui/sheet';
import { Project } from '@/Pages/Projects.vue';
import { useGlobalStore } from '@/Stores/global';
import { Link, usePage } from '@inertiajs/vue3';
import Multiselect from '@suadelabs/vue3-multiselect';
import { computed, ref } from 'vue';

defineProps<{
    page: string;
}>();
const globalStore = useGlobalStore();
const projects = computed(() => usePage().props.projects);

const classes = ref(
    'flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary',
);

const currentProject = computed(() => usePage().props.selected_project);
</script>

<template>
    <div
        class="grid min-h-screen w-full overflow-auto md:grid-cols-[220px_1fr] lg:grid-cols-[280px_1fr]"
    >
        <div class="hidden border-r bg-muted/40 md:block">
            <div class="flex h-full max-h-screen flex-col gap-2">
                <div class="flex-1">
                    <nav
                        class="grid items-start px-2 text-sm font-medium lg:px-4"
                    >
                        <p
                            class="flex items-center gap-3 rounded-lg px-3 py-5 text-xl font-bold capitalize"
                        >
                            <ApplicationLogo
                                class="rounded-full bg-black p-1.5"
                            />
                            {{ currentProject?.name }}
                        </p>
                        <Link
                            :href="route('dashboard')"
                            :class="{
                                'bg-muted text-primary':
                                    route().current('dashboard'),
                            }"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-muted-foreground transition-all hover:text-primary"
                        >
                            <Home class="h-4 w-4" />
                            Dashboard
                        </Link>
                        <Link
                            :href="route('trips.index')"
                            :class="[
                                {
                                    'bg-muted text-primary':
                                        route().current('trips.index'),
                                },
                                classes,
                            ]"
                        >
                            <Map class="h-4 w-4" />
                            Trips
                        </Link>
                        <Link
                            :href="route('car-types.index')"
                            :class="[
                                {
                                    'bg-muted text-primary':
                                        route().current('car-types.index'),
                                },
                                classes,
                            ]"
                        >
                            <Car class="h-4 w-4" />
                            Car Types
                        </Link>
                        <!-- <Link
                            :href="route('payments.index')"
                            :class="[
                                {
                                    'bg-muted text-primary':
                                        route().current('payments.index'),
                                },
                                classes,
                            ]"
                         >
                            <Banknote class="h-4 w-4" />
                            Payments
                        </Link> -->
                        <Link
                            :href="route('users.index')"
                            :class="[
                                {
                                    'bg-muted text-primary':
                                        route().current('users.index'),
                                },
                                classes,
                            ]"
                        >
                            <Users class="h-4 w-4" />
                            Users
                        </Link>

                        <!-- HORIZONTAL RULE -->
                        <hr class="mt-[30%] size-4 w-full text-black" />

                        <Link
                            :href="route('projects.index')"
                            :class="[
                                {
                                    'bg-muted text-primary':
                                        route().current('projects.index'),
                                },
                                classes,
                            ]"
                        >
                            <Package class="h-4 w-4" />
                            Project Settings
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <header
                class="flex h-14 items-center gap-4 border-b bg-muted/40 px-4 lg:h-[60px] lg:px-6"
            >
                <Sheet>
                    <SheetTrigger as-child>
                        <Button
                            variant="outline"
                            size="icon"
                            class="shrink-0 md:hidden"
                        >
                            <Menu class="h-5 w-5" />
                            <span class="sr-only">Toggle navigation menu</span>
                        </Button>
                    </SheetTrigger>
                    <SheetContent side="left" class="flex flex-col">
                        <nav class="grid gap-2 text-lg font-medium">
                            <Link
                                href="#"
                                class="flex items-center gap-2 text-lg font-semibold"
                            >
                                <p
                                    class="flex items-center gap-3 rounded-lg px-3 py-5 text-xl font-bold capitalize"
                                >
                                    <ApplicationLogo
                                        class="rounded-full bg-black p-2"
                                    />
                                    {{ currentProject?.name }}
                                </p>
                                <span class="sr-only">Admin Panel</span>
                            </Link>
                            <Link
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Home class="h-5 w-5" />
                                Dashboard
                            </Link>
                            <Link
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl bg-muted px-3 py-2 text-foreground hover:text-foreground"
                            >
                                <ShoppingCart class="h-5 w-5" />
                                Trips
                            </Link>
                            <Link
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Package class="h-5 w-5" />
                                Projects
                            </Link>
                            <Link
                                href="#"
                                class="mx-[-0.65rem] flex items-center gap-4 rounded-xl px-3 py-2 text-muted-foreground hover:text-foreground"
                            >
                                <Users class="h-5 w-5" />
                                Users
                            </Link>
                        </nav>
                    </SheetContent>
                </Sheet>
                <div class="flex">
                    {{ 'Welcome ' + $page.props.auth.user.name + '!' }}
                </div>
                <div
                    class="ml-auto flex h-14 w-[30vw] border-b px-4 lg:h-[60px] lg:px-6"
                >
                    <div class="flex w-full items-center gap-2 font-semibold">
                        <!-- <Package2 class="h-6 w-6" /> -->
                        <Multiselect
                            class="w-full"
                            v-model="globalStore.selected_project"
                            :options="projects"
                            :close-on-select="true"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Select Project"
                            label="name"
                            track-by="id"
                            :allow-empty="false"
                            @select="
                                (selected: Project) =>
                                    globalStore.handleSelect(selected?.id ?? 0)
                            "
                            :show-labels="false"
                        >
                            <template #selection="{ values, isOpen }">
                                <span
                                    class="multiselect__single"
                                    v-if="values.length"
                                    v-show="!isOpen"
                                    >{{ values.length }} options selected</span
                                >
                            </template>
                        </Multiselect>
                    </div>
                </div>
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button
                            variant="secondary"
                            size="icon"
                            class="rounded-full"
                        >
                            <CircleUser class="h-5 w-5" />
                            <span class="sr-only">Toggle user menu</span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>My Account</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem>
                            <Link :href="route('profile.edit')"> Profile </Link>
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem>
                            <Link
                                :href="route('users.tenant')"
                                :class="[
                                    {
                                        'bg-muted text-primary':
                                            route().current('payments.index'),
                                    },
                                    classes,
                                ]"
                            >
                                <Users class="h-4 w-4" />
                                Users
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Logout
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </header>
            <main class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6">
                <div>
                    <Alert v-if="$page.props.flash.error" variant="destructive">
                        <AlertTitle>Error</AlertTitle>
                        <AlertDescription>
                            {{ $page.props.flash.error }}
                        </AlertDescription>
                    </Alert>
                    <Alert
                        v-else-if="$page.props.flash.success"
                        class="border border-green-500 text-green-500"
                    >
                        <AlertTitle>Done</AlertTitle>
                        <AlertDescription>
                            {{ $page.props.flash.success }}
                        </AlertDescription>
                    </Alert>
                </div>
                <div class="flex items-center">
                    <h1 class="text-lg font-semibold capitalize md:text-2xl">
                        {{ page }}
                    </h1>
                </div>
                <div
                    class="flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
                >
                    <!-- Content Page -->
                    <slot />
                    <!-- Content Page -->
                </div>
            </main>
        </div>
    </div>
</template>
