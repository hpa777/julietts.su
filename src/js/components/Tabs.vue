<template>
    <div>
        <buttons-list :data="data" @change="changeTab" :selected="currentTab"></buttons-list>
        <slot></slot>
        
        <items-list class="mt-[0.85rem]" v-for="tab in data.filter((v, i) => currentTab == i)" :key="tab.id"           
            :data="tab.items"
            :showName="showName"
            :draggable="draggable"
            :selectedItem="currentProdIdx"
            @change="changeItem"
            @select="(e) => $emit('select', e, currentTab)"
            @drop="(e) => $emit('drop', e, currentTab)">
        </items-list>
        
        <div v-if="showAdd && currentProd" class="mt-4 text-center lg:text-right">
            <button
                @click="$emit('select', currentProd, currentTab)"
                class="h-[2.5rem] w-[12.5rem] rounded-xl border border-mgray bg-white text-sm text-mgray transition-colors hover:bg-mgray hover:text-white md:w-[12.81rem] lg:h-[3.44rem] lg:w-[14rem] lg:text-base">
                Добавить фигурку
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import ButtonsList from './ButtonsList.vue'
import ItemsList from './ItemsList.vue'
const props = defineProps({
    data: {
        type: Array,
        default: []
    },
    showName: {
        type: Boolean,
        default: true,
    },
    showAdd: {
        type: Boolean,
        default: true,
    },
    selectedTab: {
        type: Number,
        default: 0,
    },
    selectedItem: {
        type: Number
    },
    draggable: {
        type: Boolean,
        default: true,
    },
    keepSelected: {
        type: Boolean,
        default: false
    }
})

const currentTab = ref(props.selectedTab)

watch(
    () => props.selectedTab,
    (newVal) => { 
        currentTab.value = newVal
    }
)

const currentProd = ref()
const currentProdIdx = ref(props.keepSelected ? props.selectedItem : undefined)

watch(
    () => props.selectedItem,
    (newVal) => {
        currentProdIdx.value = props.keepSelected ? newVal : undefined
    }
)

const emit = defineEmits(['drop', 'change', 'select'])

const changeTab = (tab) => {            
    currentTab.value = tab
    if (props.keepSelected === true) {
        currentProd.value = props.data[tab].items[currentProdIdx.value]
        emit('change', currentProd.value, currentTab.value)
    }    
}

const changeItem = (item, key) => {
    currentProd.value = item
    currentProdIdx.value = key
    emit('change', item, currentTab.value)
}
</script>
