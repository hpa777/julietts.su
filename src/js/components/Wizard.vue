<template>
    <div>
        <buttons-list :data="wizardSteps" :selected="currentStep" @change="(key) => changeOption(undefined, key, 'step')"></buttons-list>
        <div class="min-h-[24rem] mt-[0.85rem]">
            
                <items-list
                    v-if="selectedItemKey !== undefined && currentStep === 0"
                    :data="types"
                    :draggable="false"
                    @change="(item, key) => changeOption(item, key, 'type')"
                    :selectedItem="selectedTypeKey"></items-list>
            
                <!--Следующие шаги отключены-->
                <items-list
                    v-if="colors !== undefined && currentStep === 1 && false"
                    :data="colors"
                    :draggable="false"
                    @change="(item, key) => changeOption(item, key, 'color')"
                    :selectedItem="selectedColorKey"></items-list>
            
                <items-list
                    v-if="places !== undefined && currentStep === 2 && false"
                    :data="places"
                    :draggable="false"
                    @change="(item, key) => changeOption(item, key, 'place')"
                    :selectedItem="selectedPlaceKey"></items-list>
            
        </div>        
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import ButtonsList from './ButtonsList.vue'
import ItemsList from './ItemsList.vue'

const props = defineProps({
    selectedItemKey: Number,
})
const items = defineModel()

//todo to param
const wizardSteps = ref([
    { pagetitle: 'Выбрать тип', disabled: true },
/*    { pagetitle: 'Выбрать цвет', disabled: true },
    { pagetitle: 'Выбрать расположение', disabled: true }, */
])

const currentStep = ref(0)
const types = ref()
const colors = ref()
const places = ref()

const selectedTypeKey = ref();
const selectedColorKey = ref();
const selectedPlaceKey = ref();

watch(
    () => props.selectedItemKey,
    (v) => {
        if (v === undefined) {
            wizardSteps.value.forEach((el) => (el.disabled = true))
            return
        }
        const currentItem = items.value[v]
        if (currentItem === undefined) return
        types.value = currentItem.items
        colors.value = currentItem.type !== undefined ? currentItem.items[currentItem.type].items : undefined
        places.value = currentItem.color !== undefined && colors.value !== undefined ? colors.value[currentItem.color].items : undefined
        wizardSteps.value[0].disabled = currentItem.items === undefined
        /*
        wizardSteps.value[1].disabled = colors.value === undefined
        wizardSteps.value[2].disabled = places.value === undefined
        */
        currentStep.value = currentItem.step !== undefined ? currentItem.step : 0

        selectedTypeKey.value = currentItem.type
        selectedColorKey.value = currentItem.color
        selectedPlaceKey.value = currentItem.place
    }
)

const changeOption = (item, key, name) => {         
    const currentItem = items.value[props.selectedItemKey]
    currentItem[name] = key
    switch (name) {
        case 'type': {
            colors.value = item.items
            /*
            wizardSteps.value[1].disabled = colors.value === undefined
            wizardSteps.value[2].disabled = true
            */
            currentItem.color = undefined
            currentItem.place = undefined
            currentItem.typename = item.pagetitle
            break
        }
        case 'color': {
            places.value = item.items
            /*
            wizardSteps.value[2].disabled = places.value === undefined
            */
            currentItem.place = undefined
            currentItem.colorname = item.pagetitle
            break
        }
        case 'place' : {
            currentItem.placename = item.pagetitle
            break
        }
        case 'step': {
            currentStep.value = key            
            break
        }
    }
    if (item === undefined) return
    currentItem.image_full = item.image_full
    currentItem.price = item.price
    if (item.description !== undefined && item.description !== '') {
        currentItem.description = item.description
    }
    
}
</script>

<style></style>
