<template>
    <div class="mt-[0.85rem] flex max-h-[24rem] flex-col gap-y-3 overflow-y-auto p-[4px] scrollbar scrollbar-thumb-mscrl scrollbar-thumb-rounded-full scrollbar-w-2">
        <div v-for="(item, key) in items.filter((e) => !e.disable_engrav)" :key="key" class="flex items-center gap-x-4">
            <div class="flex h-[4.12rem] w-[4.12rem] overflow-hidden rounded-lg bg-white">
                <img class="m-auto" v-if="item.image_full" :src="baseUrl + item.image_full" alt="" />
            </div>
            <input v-model="item.text" type="text" class="h-[3.31rem] flex-grow rounded-xl border border-mgray bg-white indent-6 placeholder-[#b4b4b4]" :placeholder="item.description" />
        </div>        
    </div>
</template>

<script setup>
const baseUrl = process.env.MIX_BASE_URL
const items = defineModel()
</script>

<style></style>
