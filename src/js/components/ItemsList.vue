<template>
    <div class="grid max-h-[29rem] grid-cols-3 gap-2 overflow-y-auto overflow-x-clip p-[4px] scrollbar scrollbar-thumb-mscrl scrollbar-thumb-rounded-full scrollbar-w-2 md:grid-cols-4 lg:gap-x-8 lg:gap-y-4">
        <div
            v-for="(item, key) in data"
            :key="item"
            @click="(currentItem = key), $emit('change', item, key)"
            @dblclick="$emit('select', item)"
            :class="{ 'is-active': key == currentItem || (currentItem === undefined && item.as_default == 1) }"
            class="group text-center text-mgray md:max-w-20 lg:max-w-[8.62rem]">
            <div
                :ref="setItemRef"
                v-touch:drag="startDrag(key)"
                v-touch:release="stopDrag(item)"
                class="flex aspect-square cursor-pointer overflow-hidden rounded-lg bg-white outline-none group-[.is-active]:outline-mred hover:outline-mred">
                <img class="m-auto w-full h-full object-contain" v-if="item.image_preview" :src="baseUrl + item.image_preview" alt="" draggable="false" oncontextmenu="return false;" />
            </div>
            <div class="mt-2 text-[0.69rem] leading-none lg:mt-4 lg:text-base" v-if="showName && item[nameField]" v-html="item[nameField]"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
const baseUrl = process.env.MIX_BASE_URL

const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
    draggable: {
        type: Boolean,
        default: true,
    },
    showName: {
        type: Boolean,
        default: true,
    },
    nameField: {
        type: String,
        default: 'pagetitle'
    },
    selectedItem: Number,
})

const emit = defineEmits(['drop', 'change', 'select'])

const currentItem = ref(props.selectedItem)
watch(
    () => props.selectedItem,
    (newKey) => {
        currentItem.value = newKey
    }
)

const itemRefs = []
const setItemRef = (el) => {
    if (el) {
        itemRefs.push(el)
    }
}

let dragElement = null,
    offsetX = 0,
    offsetY = 0

const startDrag = (key) => (event) => {
    if (props.draggable === false) return
    if (dragElement === null) {
        dragElement = itemRefs[key]
        const cr = dragElement.getBoundingClientRect()
        dragElement.style.width = `${cr.width}px`
        dragElement.style.height = `${cr.height}px`
        offsetX = cr.width / 2
        offsetY = cr.height / 2
        dragElement.classList.add('dragging')
        document.body.classList.add('hasdrag')
    }
    let { clientX, clientY } = event instanceof TouchEvent ? event.touches[0] : event
    clientX -= offsetX
    clientY -= offsetY
    dragElement.style.left = `${clientX}px`
    dragElement.style.top = `${clientY}px`
}

const stopDrag = (itemData) => (mouseEvent) => {
    if (dragElement === null) return
    let { clientX, clientY } = mouseEvent instanceof TouchEvent ? mouseEvent.changedTouches[0] : mouseEvent
    dragElement.classList.remove('dragging')
    dragElement.attributeStyleMap.clear()
    dragElement = null
    document.body.classList.remove('hasdrag')
    emit('drop', {
        item: itemData,
        clientX: clientX,
        clientY: clientY,
    })
}
</script>

<style></style>
