<template>
    <div id="kt-content" class="content d-flex flex-column flex-column-fluid">
        <h1>Welcome back, {{ user.name_first }}!</h1>
    </div>
</template>

<script>
import { useAuthStore } from "../store/auth"
import { useAlertsStore } from "../store/alerts"

export default {
    name: "Dashboard",

    setup() {
        const authStore = useAuthStore()
        const alertsStore = useAlertsStore()
        return { authStore, alertsStore }
    },

    methods: {
    },

    computed: {
        user() {
            return this.authStore.getUser
        }
    },

    created() {
        // this.authStore.getAuthUser()
    }
}
</script>

<style scoped>

</style>
