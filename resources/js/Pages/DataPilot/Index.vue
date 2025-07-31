<template>
    <App page="DataPilot">
        <div class="flex h-screen bg-background">
            <!-- Sidebar -->
            <div class="flex w-64 flex-col border-r border-border bg-muted/50">
                <!-- Header -->
                <div class="border-b border-border p-4">
                    <div class="flex items-center space-x-2">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-primary"
                        >
                            <Bot class="h-4 w-4 text-primary-foreground" />
                        </div>
                        <div>
                            <h1 class="text-sm font-semibold">AI Assistant</h1>
                            <p class="text-xs text-muted-foreground">
                                Always ready to help
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Conversation History -->
                <div class="custom-scrollbar flex-1 overflow-y-auto">
                    <div class="space-y-2 p-4">
                        <div
                            class="mb-2 text-xs font-medium uppercase tracking-wide text-muted-foreground"
                        >
                            Recent Conversations
                        </div>
                        <div
                            v-for="(conversation, index) in conversations"
                            :key="index"
                            class="group cursor-pointer rounded-md p-3 transition-colors hover:bg-accent"
                            :class="{
                                'bg-accent': activeConversation === index,
                            }"
                            @click="activeConversation = index"
                        >
                            <div class="flex items-start space-x-2">
                                <MessageCircle
                                    class="mt-0.5 h-4 w-4 flex-shrink-0 text-muted-foreground"
                                />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium">
                                        {{ conversation.title }}
                                    </p>
                                    <p
                                        class="truncate text-xs text-muted-foreground"
                                    >
                                        {{ conversation.preview }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Chat Button -->
                <div class="border-t border-border p-4">
                    <button
                        @click="startNewChat"
                        class="flex w-full items-center justify-center space-x-2 rounded-md bg-primary p-3 text-primary-foreground transition-colors hover:bg-primary/90"
                    >
                        <Plus class="h-4 w-4" />
                        <span class="text-sm font-medium">New Chat</span>
                    </button>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="flex flex-1 flex-col">
                <!-- Chat Header -->
                <div class="border-b border-border bg-card p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary to-primary/80"
                            >
                                <Bot class="h-5 w-5 text-primary-foreground" />
                            </div>
                            <div>
                                <h2 class="font-semibold">AI Assistant</h2>
                                <div
                                    class="flex items-center space-x-1 text-sm text-muted-foreground"
                                >
                                    <div
                                        class="h-2 w-2 rounded-full bg-green-500"
                                    ></div>
                                    <span>Online</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="clearChat"
                                class="rounded-md p-2 transition-colors hover:bg-accent"
                                title="Clear chat"
                            >
                                <Trash2 class="h-4 w-4" />
                            </button>
                            <button
                                @click="toggleTheme"
                                class="rounded-md p-2 transition-colors hover:bg-accent"
                                title="Toggle theme"
                            >
                                <Sun class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages Container -->
                <div
                    class="custom-scrollbar flex-1 space-y-4 overflow-y-auto p-4"
                    ref="messagesContainer"
                >
                    <div
                        v-if="messages.length === 0"
                        class="flex flex-1 items-center justify-center"
                    >
                        <div class="space-y-4 text-center">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-muted"
                            >
                                <MessageCircle
                                    class="h-8 w-8 text-muted-foreground"
                                />
                            </div>
                            <div class="space-y-2">
                                <h3 class="font-semibold">
                                    Start a conversation
                                </h3>
                                <p
                                    class="max-w-md text-sm text-muted-foreground"
                                >
                                    Ask me anything! I'm here to help with
                                    questions and provide insights.
                                </p>
                            </div>
                            <div class="flex flex-wrap justify-center gap-2">
                                <button
                                    v-for="suggestion in suggestions"
                                    :key="suggestion"
                                    @click="sendMessage(suggestion)"
                                    class="rounded-full bg-muted px-3 py-1.5 text-xs transition-colors hover:bg-accent"
                                >
                                    {{ suggestion }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <TransitionGroup name="message" tag="div">
                        <div
                            v-for="(message, index) in messages"
                            :key="index"
                            class="animate-fade-in flex items-start space-x-3"
                            :class="{
                                'flex-row-reverse space-x-reverse':
                                    message.role === 'user',
                            }"
                        >
                            <div
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full"
                                :class="
                                    message.role === 'user'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted'
                                "
                            >
                                <User
                                    v-if="message.role === 'user'"
                                    class="h-4 w-4"
                                />
                                <Bot v-else class="h-4 w-4" />
                            </div>
                            <div class="flex-1 space-y-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-medium">
                                        {{
                                            message.role === 'user'
                                                ? 'You'
                                                : 'AI Assistant'
                                        }}
                                    </span>
                                    <span class="text-xs text-muted-foreground">
                                        {{ formatTime(message.timestamp) }}
                                    </span>
                                </div>
                                <div class="prose prose-sm max-w-none">
                                    <div
                                        class="rounded-lg p-3"
                                        :class="
                                            message.role === 'user'
                                                ? 'bg-primary text-primary-foreground'
                                                : 'bg-muted'
                                        "
                                    >
                                        <div
                                            v-if="
                                                typeof message.content ===
                                                'string'
                                            "
                                        >
                                            {{ message.content }}
                                        </div>
                                        <div v-else>
                                            <div
                                                v-if="
                                                    message.content.type ==
                                                    'table'
                                                "
                                            >
                                                Table Displayed
                                            </div>
                                            <component
                                                v-else
                                                :is="
                                                    getChartComponent(
                                                        message.content.type.toLowerCase(),
                                                    )
                                                "
                                                :id="message.content.type"
                                                :options="{ responsive: true }"
                                                :data="message.content.data"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>

                    <!-- Typing indicator -->
                    <div
                        v-if="isTyping"
                        class="animate-fade-in flex items-start space-x-3"
                    >
                        <div
                            class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-muted"
                        >
                            <Bot class="h-4 w-4" />
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium"
                                    >AI Assistant</span
                                >
                                <span class="text-xs text-muted-foreground"
                                    >typing...</span
                                >
                            </div>
                            <div class="rounded-lg bg-muted p-3">
                                <div class="typing-dots"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t border-border bg-card p-4">
                    <div class="flex items-end space-x-2">
                        <div class="relative flex-1">
                            <textarea
                                v-model="inputMessage"
                                @keydown="handleKeydown"
                                :disabled="isTyping"
                                placeholder="Type your message..."
                                class="w-full resize-none rounded-lg border border-input bg-background px-4 py-3 pr-12 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                rows="1"
                                ref="messageInput"
                            ></textarea>
                            <button
                                @click="sendMessage(inputMessage)"
                                :disabled="!inputMessage.trim() || isTyping"
                                class="absolute bottom-2 right-2 rounded-md bg-primary p-2 text-primary-foreground transition-colors hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <Send class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">
                        Press Enter to send, Shift+Enter for new line
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
import {
    Bot,
    MessageCircle,
    Plus,
    Send,
    Sun,
    Trash2,
    User,
} from 'lucide-vue-next';
import { nextTick, onMounted, ref } from 'vue';

import { chatMessage, Conversation, FinalAnalysis, Message } from '@/interface';
import App from '@/Layouts/App.vue';
import axios from 'axios';
import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Tooltip,
} from 'chart.js';
import { Bar, Line, Pie } from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    PointElement,
    LineElement,
    ArcElement,
    Tooltip,
    Legend,
);

const base_url = 'http://127.0.0.1:8000';

const messages = ref<Message[]>([]);
const inputMessage = ref('');
const isTyping = ref(false);
const messagesContainer = ref<HTMLElement>();
const messageInput = ref<HTMLTextAreaElement>();
const activeConversation = ref(0);

const conversations = ref<Conversation[]>([
    {
        title: 'Getting Started',
        preview: 'How can I help you today?',
    },
    {
        title: 'Vue.js Questions',
        preview: 'Tell me about Vue 3 composition API',
    },
    {
        title: 'Design Systems',
        preview: 'What is Shadcn?',
    },
]);

const suggestions = [
    'What can you help me with?',
    'Explain Vue 3 composition API',
    'Help me write a function',
    'Design tips for UI',
];

const sendMessage = async (content: string) => {
    if (!content.trim() || isTyping.value) return;

    const userMessage: Message = {
        role: 'user',
        content: content,
        timestamp: new Date(),
    };

    messages.value.push(userMessage);
    inputMessage.value = '';
    isTyping.value = true;

    await nextTick();
    scrollToBottom();

    let chatMessage: chatMessage = {
        question: content,
        tenant_id: 1,
    };

    try {
        const response = await axios.post(`${base_url}/chat`, chatMessage);
        const finalAnalysis: FinalAnalysis = response.data.final_analysis;

        const assistantMessage: Message = {
            role: 'assistant',
            content: finalAnalysis.insights,
            timestamp: new Date(),
        };

        const assistantMessageGraph: Message = {
            role: 'assistant',
            content: finalAnalysis.chart_config,
            timestamp: new Date(),
        };

        messages.value.push(assistantMessageGraph, assistantMessage);
    } catch (error) {
        const errorMessage: Message = {
            role: 'assistant',
            content: 'Sorry, I encountered an error. Please try again.',
            timestamp: new Date(),
        };

        messages.value.push(errorMessage);
    } finally {
        isTyping.value = false;
        await nextTick();
        scrollToBottom();
    }
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendMessage(inputMessage.value);
    }
};

const scrollToBottom = () => {
    if (messagesContainer.value) {
        messagesContainer.value.scrollTop =
            messagesContainer.value.scrollHeight;
    }
};

const clearChat = () => {
    messages.value = [];
};

const startNewChat = () => {
    messages.value = [];
    inputMessage.value = '';
};

const toggleTheme = () => {
    document.documentElement.classList.toggle('dark');
};

const formatTime = (date: Date): string => {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const getChartComponent = (type: string) => {
    // Return appropriate chart component based on type
    // You'll need to import and register your chart components
    switch (type) {
        case 'bar':
            return Bar;
        case 'line':
            return Line;
        case 'pie':
            return Pie;
        default:
            return 'div';
    }
};

onMounted(() => {
    messageInput.value?.focus();
});
</script>
