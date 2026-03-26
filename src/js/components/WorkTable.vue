<template>
    <div class="relative flex w-[5.3rem] flex-shrink-0 flex-col bg-white pt-8 md:w-[6.7rem] md:pt-12 lg:w-1/2" :class="{ fullview: fullImage, isIPhone: isIPhone }">
        <div class="absolute bottom-72 left-0 hidden w-full lg:block">
            <img :src="baseUrl + lexicon.watermark" class="mx-auto" alt />
        </div>
        <div
            ref="workTable"
            id="work-table"
            class="h-[593px] flex-grow select-none overflow-hidden scrollbar scrollbar-thumb-mscrl scrollbar-thumb-rounded-full scrollbar-w-2 lg:h-[42rem]"
            :class="{
                'overflow-y-auto': items.length,
            }">
            <div :class="{ 'h-full': !items.length }" class="relative">
                <div
                    id="product"
                    class="absolute left-0 right-0 top-0 mx-auto w-fit origin-top"
                    :class="{
                        'h-full': !items.length,
                        'transition-transform': enableTransform,
                        'product-scale': items.length && !fullImage,
                    }">
                    <transition name="slide-up">
                        <img v-if="chain" :key="'ch' + chain.id" :src="baseUrl + chain.image_full" class="max-h-full" alt="" />
                    </transition>
                    <TransitionGroup tag="div" name="list" class="work-table-items absolute inset-0 flex flex-col items-center justify-center">
                        <div
                            class="product-item relative"
                            v-for="(item, key) in items"
                            :key="item"
                            :class="{
                                'is-tip-first': item.is_tip === 1,
                                'is-tip-last': item.is_tip === 2,
                                'is-elem': item.is_tip === undefined,
                                'is-new-elem': item.isNew === true,
                                'is-before-tip': isBeforeTip(key),
                            }">
                            <div
                                @click="$emit('select', key)"
                                :class="{ '!border-lgray': selectedItemKey === key }"
                                v-touch:drag="startDrag"
                                v-touch:release="stopDrag"
                                class="w-[4.7rem] min-w-max cursor-pointer rounded-2xl border-2 border-transparent group-[.is-active]:border-mred hover:border-lgray lg:w-36 lg:border-[3px]">
                                <img class="product-item__img mx-auto" :src="baseUrl + item.image_full" alt="" draggable="false" @load="loadImage" oncontextmenu="return false;" />
                            </div>
                            <button
                                v-if="key < items.length - 1 && !isBeforeTip(key) && !item.is_tip"
                                @click="$emit('change', key)"
                                class="absolute -bottom-3 left-0 h-8 w-4 text-lgray hover:text-mred lg:-bottom-20 lg:-left-20 lg:h-auto lg:w-auto lg:pr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="120" fill="none" viewBox="0 0 15 59" class="max-h-full max-w-full">
                                    <path
                                        fill="currentColor"
                                        fill-rule="evenodd"
                                        d="M14.23 1.259a.5.5 0 0 1 .43.56l-.582 4.462a.5.5 0 1 1-.992-.13l.423-3.234a34.5 34.5 0 0 0 .162 53.3l-.442-3.233a.5.5 0 0 1 .99-.135l.61 4.458a.5.5 0 0 1-.427.563l-4.458.61a.5.5 0 1 1-.136-.99l3.29-.45a35.5 35.5 0 0 1-.167-54.942l-3.293-.43a.5.5 0 0 1 .13-.992l4.462.583Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button @click="$emit('remove', key)" class="absolute right-2 top-1 p-1 text-base leading-[0.6] text-lgray hover:text-mred lg:right-3 lg:top-1 lg:text-3xl lg:leading-none">&times;</button>
                        </div>
                    </TransitionGroup>
                </div>
            </div>
        </div>
        <div class="full-size-button py-8 text-center lg:h-40 lg:pb-48">
            <button
                @click="changeFullMode"
                v-show="items.length > 0"
                class="rounded-md border border-mgray bg-white p-[0.4rem] text-sm text-mgray transition-colors hover:text-white lg:h-[3.44rem] lg:w-[14rem] lg:text-base lg:hover:bg-mgray">
                <span class="hidden lg:inline">{{ fullImage ? lexicon.decrease_btn : lexicon.increase_btn }}</span>
                <svg class="lg:hidden" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 14 14">
                    <path
                        fill="#666"
                        fill-rule="evenodd"
                        d="M2.334 7.01h-1.5v6.406h6.407v-1.5H3.395l3.669-3.67-1.06-1.06-3.67 3.67V7.01ZM11.087 2.102l-3.669 3.67 1.06 1.06 3.67-3.669V7.01h1.5V.602H7.241v1.5h3.846Z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { onUpdated, ref, watch } from 'vue'
import { checkIncludePoint } from './tools'
import { useMediaQuery } from '@vueuse/core'
import { isIPhone } from '@basitcodeenv/vue3-device-detect'

const baseUrl = process.env.MIX_BASE_URL

const props = defineProps({
    chain: Object,
    items: Array,
    selectedItemKey: Number,
    lexicon: Object,
})

const fullImage = ref(false)
const enableTransform = ref(false)
const workTable = ref(null)

defineExpose({
    workTable,
})

const changeFullMode = () => {
    fullImage.value = !fullImage.value

    setTimeout(() => {
        let c = workTable.value.scrollTop
        //console.log(c)
        workTable.value.scrollTo(0, workTable.value.scrollHeight / 2) //{ top: c  / 2, behavior: 'smooth' }
    }, 160)
}

const emit = defineEmits(['scroll', 'select', 'change', 'remove'])

onUpdated(() => {
    const ne = workTable.value.querySelector('.is-new-elem')
    if (ne) {
        ne.scrollIntoView({ block: 'center', behavior: 'smooth' })
        emit('scroll')
    }
    enableTransform.value = props.items.length > 0
})

const isBeforeTip = (idx) => {
    return props.items[props.items.length - 1].is_tip == 2 && props.items.length - 2 == idx
}

const isLargeScreen = useMediaQuery('(min-width: 1024px)')

const resizeImage = (obj) => {
    const k = isLargeScreen.value ? 0.5 : 0.25
    obj.img.style.height = obj.height * k + 'px'
}

watch(isLargeScreen, () => images.forEach((el) => resizeImage(el)))

const images = []

const loadImage = (e) => {
    if (e.target.style.height != '') return
    const el = {
        img: e.target,
        height: e.target.clientHeight,
    }
    resizeImage(el)
    images.push(el)
}

let dragElement = null,
    offsetX = 0,
    offsetY = 0,
    dragElementKey = 0,
    timer = null

const startDrag = (event) => {
    if (fullImage.value) return true
    if (dragElement === null) {
        dragElement = event.target.parentElement
        const cr = dragElement.getBoundingClientRect()
        dragElement.style.width = `${cr.width}px`
        dragElement.style.height = `${cr.height}px`
        let elem = dragElement.parentElement
        elem.classList.add('dragg-parent')
        elem.style.height = `${cr.height}px`
        dragElementKey = 0
        while ((elem = elem.previousElementSibling)) {
            dragElementKey++
        }
        offsetX = cr.width / 2
        offsetY = cr.height / 2
        dragElement.classList.add('dragging')
        document.body.classList.add('hasdrag')
    }
    let { clientX, clientY } = event instanceof TouchEvent ? event.touches[0] : event
    dragElement.style.left = `${clientX - offsetX}px`
    dragElement.style.top = `${clientY - offsetY}px`
    if (timer !== null) clearTimeout(timer)
    timer = setTimeout(() => {
        Array.from(workTable.value.querySelectorAll('.product-item')).some((el, k) => {
            if (k == dragElementKey) return false
            if (checkIncludePoint(el, clientX, clientY)) {
                emit('change', dragElementKey, k)
                return true
            }
            return false
        })
    }, 100)
}

const stopDrag = (mouseEvent) => {
    if (dragElement === null) return
    //const key = props.items.findIndex((el) => el.id == item.id)
    let { clientX, clientY } = mouseEvent instanceof TouchEvent ? mouseEvent.changedTouches[0] : mouseEvent
    dragElement.classList.remove('dragging')
    dragElement.attributeStyleMap.clear()
    dragElement.parentElement.classList.remove('dragg-parent')
    dragElement.parentElement.attributeStyleMap.clear()
    dragElement = null
    document.body.classList.remove('hasdrag')
    if (!checkIncludePoint(workTable.value, clientX, clientY)) {
        emit('remove', dragElementKey)
        return
    }
    /*
    Array.from(workTable.value.querySelectorAll('.product-item')).some((el, k) => {
        if (checkIncludePoint(el, clientX, clientY)) {
            emit('change', dragElementKey, k)
            return true
        }
        return false
    })
        */
}
</script>

<style></style>
