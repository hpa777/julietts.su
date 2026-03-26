<template>
    <div class="font-roboto text-base font-normal text-black">
        <!--div class="relative z-10 bg-white pb-1 shadow-head lg:pt-3">
            <img :src="baseUrl + lexicon.logo" class="mx-auto h-16 lg:h-auto" alt />
        </div-->
        <div class="flex items-start bg-contain" :style="{ backgroundImage: `url('${baseUrl + lexicon.background}')` }">
            <div v-if="finish" class="m-auto px-2 py-8" v-html="lexicon.final_message"></div>
            <work-table
                ref="workTableComponent"
                v-if="!finish"
                @select="selectItem"
                @change="changeItems"
                @remove="removeItem"
                @scroll="clearNewFlag"
                @dragover.prevent
                @drop="handleDrop($event)"
                :selectedItemKey="selectItemKey"
                :items="items"
                :chain="chain"
                :lexicon="lexicon" />
            <div v-if="!finish && chains" class="flex-grow bg-[#f4f4f4] px-2 py-3 lg:w-1/2 lg:bg-transparent lg:py-8 lg:pb-32 lg:pl-[4.4rem] lg:pt-16">
                <Step :idx="0" @next="currentStep = 1" :currentStep="currentStep" :cost="cost" :valid="chain.price !== undefined" :lexicon="lexicon" :costText="lexicon.step1_cost_text">
                    <h2 class="text-lg uppercase lg:text-[1.7rem]" v-html="lexicon.step1_title"></h2>
                    <p class="text-sm font-light lg:mt-8 lg:text-xl" v-html="lexicon.step1_description"></p>
                    <Tabs :data="chains" :show-add="false" :selected-tab="currentMaterial" :selectedItem="selectedChainIdx" :draggable="false" :keepSelected="true" @change="setChain">
                        <p class="mt-2 text-sm font-light lg:mt-8 lg:text-xl" v-html="lexicon.step1_description_dop"></p>
                    </Tabs>
                </Step>
                <Step :idx="1" @next="currentStep = 2" @back="currentStep = 0" :currentStep="currentStep" :cost="cost" :valid="items.length > 0" :lexicon="lexicon" class="step-2">
                    <h2 class="text-lg uppercase lg:text-[1.7rem]" v-html="lexicon.step2_title"></h2>
                    <p class="text-sm font-light lg:mt-8 lg:text-xl" v-html="lexicon.step2_description"></p>
                    <Tabs v-if="pendants" :data="pendants[currentMaterial].items" @select="addItem" @drop="dropItem">
                        <p class="mt-2 text-sm font-light lg:mt-8 lg:text-xl" v-html="lexicon.step2_description_dop"></p>
                    </Tabs>
                </Step>
                <Step :idx="2" @next="currentStep = 3" @back="currentStep = 1" :currentStep="currentStep" :cost="cost" :valid="true" :lexicon="lexicon">
                    <h2 class="text-lg uppercase lg:text-[1.7rem]" v-html="lexicon.step3_title"></h2>
                    <div v-if="possibleStoneTypes.length">
                        <p class="text-sm font-light lg:mt-8 lg:mb-4 lg:text-xl" v-html="lexicon.step3_description_dop"></p>
                        <ItemsList 
                            :data="stoneTypes.filter((el) => possibleStoneTypes.includes(el.id))" 
                            @change="changeStoneType" :draggable="false" :nameField="'longtitle'"></ItemsList>
                    </div>
                    <!--Wizard class="mb-4" v-model="items" :selectedItemKey="selectItemKey"></Wizard-->
                    <p class="text-sm font-light lg:mt-8 lg:text-xl" v-html="lexicon.step3_description"></p>
                    <buttons-list
                        class="grid-cols-4 md:grid-cols-7"
                        :data="chain.lenghts"
                        @change="(key, val) => ((chain.price = val.price), (chain.length = val.length))"
                        field="length"
                        :selected="chain.lenghts.findIndex((el) => el.isDefault == 1)"></buttons-list>
                </Step>
                <Step :idx="3" @next="currentStep = 4" @back="currentStep = 2" :currentStep="currentStep" :cost="cost" :valid="true" :lexicon="lexicon">
                    <h2 class="text-lg uppercase lg:text-[1.7rem]" v-html="lexicon.step4_title"></h2>
                    <p class="text-sm font-light lg:mt-8 lg:text-xl" v-html="lexicon.step4_description"></p>
                    <input-list v-model="items"></input-list>
                    <div class="mt-4" v-html="lexicon.engrav_text"></div>                    
                </Step>
                <Step :idx="4" @next="submitPressed++" @back="currentStep = 3" :currentStep="currentStep" :cost="cost" :valid="true" :lexicon="lexicon">
                    <h2 class="text-lg uppercase lg:text-[1.7rem]" v-html="lexicon.step5_title"></h2>
                    <p class="text-sm font-light lg:mt-8 lg:text-xl" v-html="lexicon.step5_description"></p>
                    <order-form :submitPressed="submitPressed" :lexicon="lexicon" @submit="submit"></order-form>
                    <div v-html="lexicon.form_text"></div>
                </Step>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { getChains, getPendants, getPendantsByRoot, getLexicon, sendMail, getStoneTypes } from '../services/ConstructorApi'
import WorkTable from './WorkTable.vue'
import Tabs from './Tabs.vue'
import Step from './Step.vue'
//import Wizard from './Wizard.vue'
import ItemsList from './ItemsList.vue'
import InputList from './InputList.vue'
import ButtonsList from './ButtonsList.vue'
import OrderForm from './OrderForm.vue'
import { checkIncludePoint } from './tools'

const baseUrl = process.env.MIX_BASE_URL

const finish = ref(false)
const submitPressed = ref(0)
const currentStep = ref(0)
const chain = ref({ lenghts: [] })
const items = ref([])
const selectItemKey = ref()

let itemsLength = 0
const possibleStoneTypes = ref([])
watch(items.value, (n) => {    
    if (n.length === 0 && itemsLength === 1) {
        currentStep.value = 1
    }
    itemsLength = n.length
    const pst = new Set()
    n.forEach((el) => {
        if (el.stone_types_list !== undefined) {
            el.stone_types_list.forEach((stl) => pst.add(stl.stonetype))
        }
    })
    possibleStoneTypes.value = [...pst]
})

const lexicon = ref({})
getLexicon()
    .then((response) => (lexicon.value = response.data))
    .catch((error) => console.warn(error))

const chains = ref()
const currentMaterial = ref(0)
const selectedChainIdx = ref(0)
getChains()
    .then((response) => {
        chains.value = response.data.items
        chains.value.every((p, i) => {
            const f = p.items.findIndex((v) => v.as_default == 1)
            if (f !== -1) {                
                setChain(p.items[f], i)
                selectedChainIdx.value = f
                return false
            }
            return true
        })
    })
    .catch((error) => console.warn(error))

const pendants = ref()
getPendants()
    .then((response) => (pendants.value = response.data.items))
    .catch((error) => console.warn(error))

const stoneTypes = ref()
getStoneTypes()
    .then((response) => stoneTypes.value = response.data.items)
    .catch((error) => console.warn(error))

const setChain = (item, tabIdx) => {
    currentMaterial.value = tabIdx
    if (!item) return;    
    chain.value = item
    chain.value.tabname = chains.value[tabIdx].pagetitle
    
}

const getPrice = (el) => (!isNaN(el) ? parseInt(el, 10) : 0)

const cost = computed(() => {
    let sum = 0
    let chainPrice = 0
    if (chain.value) {
        chainPrice = getPrice(chain.value.price)
    }
    let itemsPrice = 0
    items.value.forEach((el) => {
        itemsPrice += getPrice(el.stonePrice > 0 ? el.stonePrice : el.price)
        if (el.text !== undefined && el.text !== '') {
            itemsPrice += getPrice(lexicon.value.cost_engraving)
        }
    })
    sum = chainPrice + itemsPrice
    return sum
})

const clearNewFlag = () => {
    const oldnew = items.value.find((el) => el.isNew === true)
    if (oldnew) {
        oldnew.isNew = false
    }
}

const selectItem = (key) => {
    if (items.value[key] !== undefined) {
        selectItemKey.value = key
    }
}

const addItem = (item, tabIdx) => {

    const curLen = items.value.length
    if (curLen >= lexicon.value.max_items) return
    clearNewFlag()
    const newItem = { ...item }
    newItem.isNew = true
    newItem.text = ''
    newItem.typename = ''
    newItem.colorname = ''
    newItem.placename = ''
    newItem.tabname = pendants.value[currentMaterial.value].items[tabIdx].pagetitle
    newItem.stonePrice = 0
    newItem.stoneName = ''
    setItemStoneType(newItem)
    const hasLastTip = curLen > 0 && items.value[curLen - 1].is_tip !== undefined    
    if (newItem.is_tip == 1) {
        newItem.is_tip = 2
    }
    if (hasLastTip) {
        items.value.splice(curLen - 1, newItem.is_tip === 2 ? 1 : 0, newItem)
    } else {
        items.value.push(newItem)
    }
    /* Для двух кончиков
    if (newItem.is_tip == 1 && curLen > 0) {
        if (items.value[0].is_tip != 1) {
            items.value.splice(0, 0, newItem)
        } else if (!hasLastTip) {
            newItem.is_tip = 2
            items.value.push(newItem)
        } else {
            return
        }
    } else if (hasLastTip) {
        items.value.splice(curLen - 1, 0, newItem)
    } else {
        items.value.push(newItem)
    }
        */
    selectItemKey.value = undefined
    getPendantsByRoot(item.id)
        .then((response) => {
            newItem.items = response.data.items
        })
        .catch((error) => console.warn(error))
}

let currentStoneType = undefined
const setItemStoneType = (item) => {
    if (!item.stone_types_list || !currentStoneType) return
    item.stone_types_list.every((stl) => {
        if (stl.stonetype == currentStoneType.id) {
            item.stonePrice = stl.price
            item.stoneName = currentStoneType.longtitle
            return false
        }
        return true
    })    
}

const changeStoneType = (stoneType) => {
    currentStoneType = stoneType    
    items.value.forEach((item) => {        
        setItemStoneType(item)
    })
}

const workTableComponent = ref(null)

const dropItem = (e, tabIdx) => {
    if (checkIncludePoint(workTableComponent.value.workTable, e.clientX, e.clientY)) {
        addItem(e.item, tabIdx)
    }
}

const changeItems = (key, k) => {
    k = k === undefined ? key + 1 : k
    const c = items.value[key]
    items.value[key] = items.value[k]
    items.value[k] = c

    if (key == selectItemKey.value) {
        selectItemKey.value++
    } else if (key == selectItemKey.value - 1) {
        selectItemKey.value--
    }
}

const removeItem = (key) => {
    if (key == selectItemKey.value) {
        selectItemKey.value = undefined
    } else if (key < selectItemKey.value) {
        selectItemKey.value--
    }
    items.value.splice(key, 1)
}

const handleDrop = (event) => {
    console.log(event)
    const item = JSON.parse(event.dataTransfer.getData('application/json'))
    switch (currentStep.value) {
        case 0:
            chain.value = item
            break
        case 1:
            addItem(item)
            break
    }
}

const submit = (e) => {
    finish.value = true
    sendMail({
        contact: e,
        chain: {
            id: chain.value.id,
            tabname: chain.value.tabname,
            name: chain.value.pagetitle,
            length: chain.value.length,
            price: chain.value.price,
        },
        items: items.value.map((el) => {
            return {
                id: el.id,
                tabname: el.tabname,
                name: el.pagetitle,
                type: el.typename,
                color: el.colorname,
                place: el.placename,
                price: el.price,
                text: el.text,
                stonePrice: el.stonePrice,
                stoneName: el.stoneName
            }
        }),
        cost: cost.value,
    })
        .then((response) => console.log(response))
        .catch((error) => console.warn(error))
}
</script>