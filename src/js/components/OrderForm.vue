<template>
    <div class="my-4 flex flex-col gap-y-3">
        <div class="flex flex-col gap-y-[0.4rem]">
            <label for="jul-calc-input1" class="text-sm font-light lg:text-xl">{{ lexicon.label_name }}</label>
            <input v-model="name" id="jul-calc-input1" type="text" class="h-[3.31rem] flex-grow rounded-xl border border-mgray bg-white indent-6" />
        </div>

        <div class="grid gap-3 md:grid-cols-[15rem_1fr]">
            <div class="grid grid-rows-subgrid row-span-2">
                <label for="jul-calc-input2" class="text-sm font-light lg:text-xl" v-html="lexicon.label_phone"></label>
                <MaskInput
                    @input="processPhoneNumber"
                    :class="phoneCss"
                    v-model="phone"
                    id="jul-calc-input2"
                    type="tel"
                    :mask="lexicon.phone_mask ?? ''"
                    class="h-[3.31rem] flex-grow rounded-xl border border-mgray bg-white indent-6" />
            </div>
            <div class="grid grid-rows-subgrid row-span-2">
                <label for="jul-calc-input3" class="text-sm font-light lg:text-xl">{{ lexicon.label_email }}</label>
                <input v-model="email" id="jul-calc-input3" type="email" :class="emailCss" class="h-[3.31rem] flex-grow rounded-xl border border-mgray bg-white indent-6" />
            </div>
        </div>

        <div class="flex flex-col gap-y-[0.4rem]">
            <label for="jul-calc-input4" class="text-sm font-light lg:text-xl">{{ lexicon.label_address }}</label>
            <input v-model="address" id="jul-calc-input4" type="text" class="h-[3.31rem] flex-grow rounded-xl border border-mgray bg-white indent-6" />
        </div>
        <div class="flex flex-col gap-y-[0.4rem]">
            <label for="jul-calc-input5" class="text-sm font-light lg:text-xl">{{ lexicon.label_message }}</label>
            <textarea v-model="text" id="jul-calc-input5" class="h-28 flex-grow rounded-xl border border-mgray bg-white indent-6"></textarea>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { MaskInput } from 'vue-3-mask'

const props = defineProps({
    submitPressed: Number,
    items: Array,
    lexicon: Object,
})
const emit = defineEmits(['submit'])

watch(
    () => props.submitPressed,
    () => {
        if (!validateForm()) {
            emit('submit', {
                name: name.value,
                phone: phone.value,
                email: email.value,
                address: address.value,
                text: text.value,
            })
        }
    }
)

let phoneRegexp = null

onMounted(() => {
    phoneRegexp = new RegExp(props.lexicon.phone_regexp, 'g')
})

const validateForm = () => {
    phoneError.value = !VALID_PHONE(phone.value)
    emailError.value = email.value !== '' && !VALID_EMAIL(email.value)
    return phoneError.value || emailError.value
}

const name = ref('')
const phone = ref('')
const phoneError = ref(false)
const email = ref('')
const emailError = ref(false)
const address = ref('')
const text = ref('')

const phoneCss = computed(() => ({ 'outline-red-500 outline-2 outline': phoneError.value }))
const emailCss = computed(() => ({ 'outline-red-500 outline-2 outline': emailError.value }))

const processPhoneNumber = (e) => {
    let phone = e.target.value.replace(/[\s\-\(\)]/g, '')
    if (phone.startsWith('8') && phone.length > 1) {
        e.target.value = '7' + phone.substring(1)
    }
}

const VALID_PHONE = (phone) => phoneRegexp.test(phone)
const VALID_EMAIL = (email) => /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)
</script>
