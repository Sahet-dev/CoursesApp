<template>
    <TabGroup>
        <TabList class="flex items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 bg-gradient-to-r outline-none font-bold from-blue-200 via-purple-100 to-pink-200 shadow-md orange">
            <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none focus:ring-2 px-3">
                <template v-if="selected">
                    <BookOpenIconSolid class="w-5 h-5 selected-tab outline-none"/>
                </template>
                <template v-else>
                    <BookOpenIcon class="w-5 h-5 orange"/>
                </template>
                <span :class="[selected ? 'selected-tab' : 'orange']">Guides</span>
            </Tab>
            <Tab v-slot="{ selected }" as="button" class="flex items-center space-x-2 outline-none focus:outline-none focus:ring-2 px-3">
                <template v-if="selected">
                    <ChatBubbleLeftRightIconSolid class="w-5 h-5 selected-tab outline-none"/>
                </template>
                <template v-else>
                    <ChatBubbleLeftRightIcon class="w-5 h-5 orange"/>
                </template>
                <span class="ml-2" :class="[selected ? 'selected-tab' : 'orange']">Comments</span>
            </Tab>
        </TabList>

        <TabPanels class="mt-2">
            <TabPanel key="guides" class="p-3">
                <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                    <div v-html="selectedLesson.markdown_text"></div>
                </div>
            </TabPanel>
            <TabPanel key="comments" class="p-3">
                <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
                    <slot></slot>
                </div>
            </TabPanel>
        </TabPanels>
    </TabGroup>
</template>

<script setup>
import {BookOpenIcon, ChatBubbleLeftRightIcon, HandThumbUpIcon} from "@heroicons/vue/24/outline/index.js";
import {
    HandThumbUpIcon as HandThumbUpIconSolid, BookOpenIcon as BookOpenIconSolid,
    ChatBubbleLeftRightIcon as ChatBubbleLeftRightIconSolid, ChevronDoubleRightIcon, ChevronDoubleLeftIcon
} from "@heroicons/vue/24/solid";

const props = defineProps({
    selectedLesson: Object,
});
</script>
