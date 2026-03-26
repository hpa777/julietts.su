import '../scss/app.css'
import { createApp } from 'vue'
import ConstructorLayout from './components/ConstructorLayout.vue'
import Vue3TouchEvents from 'vue3-touch-events'

const app = createApp({
    components: {
        ConstructorLayout,
    },
    template: '<constructor-layout></constructor-layout>',
})

app.use(Vue3TouchEvents, { dragOutside: true })
app.mount('#jul-constuct')
