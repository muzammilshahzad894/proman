<template>
    <div class="pl-3 mt-3 d-flex">
        <i class="pi pi-info-circle ms-3 mt-2 lightIconStyle"></i>
        <span class="text-danger ms-2 label-style" v-if="saveCardOnlyText()">Your credit card will not be charged, but is used to hold your reservation.</span>
        <span class="text-danger ms-2 label-style" v-else>Please enter your credit card information to complete the reservation</span>
   </div>
</template>
<script>
export default {
    name: 'CardInfoText',
    methods: {
        saveCardOnlyText() {
            return false;
        }
    }
}
</script>

<style lang="scss">
.lightIconStyle {
    font-size: 1.5rem !important;
    color: #ced4da !important;
}
.label-style {
    font-weight: 400 !important;
    font-size: 1.35rem !important;
}
</style>