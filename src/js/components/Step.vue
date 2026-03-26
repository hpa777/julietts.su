<template>
    <div class="max-w-full md:w-[36rem] lg:w-[41.8rem]" v-show="cost > 0 && idx === currentStep">
        <slot></slot>
        <div class="mt-4 flex flex-col gap-y-4 lg:mt-16 lg:flex-row lg:items-center lg:justify-between">
            <div class="text-base lg:text-2xl font-light">
                <span v-html="costText ? costText : lexicon.cost_text"></span>
                <span class="font-normal text-mgray"
                    >{{ cost.toLocaleString() }} <span>{{ lexicon.currency_sign }}</span></span
                >
            </div>
            <div class="flex gap-4">
                <button
                    v-if="idx"
                    @click="$emit('back')"
                    v-html="lexicon.back_btn"
                    class="h-[2.5rem] w-[6.56rem] rounded-xl border border-mgray bg-white text-sm text-mgray transition-colors hover:bg-mgray hover:text-white lg:h-[3.44rem] lg:text-base"></button>
                <button
                    @click="$emit('next')"
                    :disabled="!valid"
                    v-html="lexicon.next_btn"
                    class="ml-auto h-[2.5rem] w-[6.56rem] rounded-xl border border-mred bg-mred text-sm font-semibold text-white transition-colors hover:border-lgray hover:bg-white hover:text-mred disabled:opacity-50 lg:h-[3.44rem] lg:text-base"></button>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    idx: Number,
    currentStep: Number,
    cost: Number,
    valid: Boolean,
    lexicon: Object,
    costText: String
})
</script>

<style></style>
