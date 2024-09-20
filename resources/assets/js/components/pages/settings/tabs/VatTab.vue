<template>
<!--    <Alert v-if="showAlert" :class="error ? 'alert-danger' : 'alert-success'"  :message="message"/>-->
    <VatNumberForm v-for="country in taxableCountries" :country="country" :user="user" />
    <ToggleOssSwitch :user="user" />
</template>

<script>
import {useVatNumberStore} from "../../../../store/vatNumbers";
import VatNumberForm from "./forms/VatNumberForm.vue";
import ToggleOssSwitch from "./ToggleOssSwitch.vue";

export default {
    name: "VatTab",

    components: {
        VatNumberForm,
        ToggleOssSwitch,
        
    },

    props: {
        user: {
            type: Object,
            required: true,
        },
    },

    setup() {
        const vatNumberStore = useVatNumberStore()
        return { vatNumberStore }
    },

    async created() {
        await this.getVatNumbers()
    },

    data() {
        return {

        }
    },

    computed: {
        taxableCountries() {
            console.log('vat numbers log ', this.vatNumberStore.getVatNumbers)
            return this.vatNumberStore.getVatNumbers
        },
    },

    methods: {
        async getVatNumbers() {
            return await this.vatNumberStore.getVatNumbersForUser(this.user.uuid)
        },
    }
}
</script>

<style scoped>

</style>
