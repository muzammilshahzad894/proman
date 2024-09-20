<template>
    <div class="alert alert-dismissible" :class="this.class" role="alert">
        {{ this.message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</template>

<script>
export default {
    name: "Alert",

    props: {
        class: {
            type: String,
            required: true,
        },
        message: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
