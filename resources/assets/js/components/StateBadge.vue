<template>
    <span :class="stateClass" class="badge"><slot><span class="text-capitalize">{{ state }}</span></slot></span>
</template>

<script>
export default {
    props: {
        state: {
            type: String,
            default: 'active'
        }
    },
    computed: {
        stateClass() {
            if (this.state === 'inactive') {
                return 'badge-light-danger'
            }

            return 'badge-light-success'
        }
    }
}
</script>
