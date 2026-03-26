<template>
    <ul class="buttons-list mt-[1.1rem] grid grid-cols-2 gap-[0.6rem] md:grid-cols-4 lg:flex lg:flex-wrap">
        <li v-for="(item, key) in data.filter((el) => el.disabled === undefined || el.disabled === false)" :key="item" class="buttons-list__item group" :class="{ 'is-active': key == currentTab }">
            <button @click.prevent="(currentTab = key), $emit('change', key, item)"
                class="h-[2.5rem] w-full rounded-lg border border-mgray bg-white px-2 text-[0.8rem] leading-none text-mgray transition-colors group-[.is-active]:border-mred group-[.is-active]:bg-mred group-[.is-active]:text-white hover:border-mred hover:bg-mred hover:text-white md:w-auto md:px-[1.6rem] lg:h-[3.31rem] lg:text-base">
                {{ item[field] }}
            </button>
        </li>
    </ul>
</template>

<script setup>
import { ref, watch } from 'vue'
const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
    selected: {
        type: Number,
    },
    field: {
        type: String,
        default: 'pagetitle',
    },
})
const currentTab = ref(props.selected)
watch(
    () => props.selected,
    (v) => {
        if (v !== -1) currentTab.value = v
    }
)
</script>

<style></style>
