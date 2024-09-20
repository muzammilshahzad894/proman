<template>
    <div class="fixed-top pe-3 pt-3" style="z-index: 999999; width: 400px; margin-left: auto; top: 74px">

        <transition-group name="list">
            <div
                :class="alertType(alert.type)"
                class="alert alert-dismissible bg-primary flex-column flex-sm-row w-100 p-5 mb-10 shadow"
                style="display: flex"
                v-for="(alert, index) in alerts"
                v-show="!alert.hide"
                :key="alert.id"
                @mouseenter="removeTimer(index)"
                @mouseleave="setTimer(index)"
            >
                <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">
                    <font-awesome-icon :icon="alertIcon(alert.type)" size="lg" />
                </span>

                <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                    <h4 class="mb-2 text-light">{{ alert.title }}</h4>
                    <span v-if="alert.description">{{ alert.description }}</span>
                </div>

                <button
                    @click="remove(index)"
                    type="button"
                    class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto">
                    <span class="svg-icon svg-icon-1 svg-icon-light">
                     <font-awesome-icon icon="fa-solid fa-xmark"/>
                    </span>
                </button>

            </div>
        </transition-group>
    </div>
</template>

<script>
import {mapState, mapActions} from "pinia"
import {useAlertsStore} from "../store/alerts"

export default {
    data() {
        return {}
    },

    methods: {
        ...mapActions(useAlertsStore, ['setTimer', 'removeTimer', 'remove']),
        alertType(type) {
            if (type === 'danger') return 'bg-danger'
            if (type === 'warning') return 'bg-warning'
            if (type === 'success') return 'bg-success'

            return 'bg-primary'
        },
        alertIcon(type) {
            if (type === 'danger') return 'fa-triangle-exclamation'
            if (type === 'warning') return 'fa-circle-exclamation'
            if (type === 'success') return 'fa-check'

            return 'fa-circle-exclamation'
        }
    },

    computed: {
        ...mapState(useAlertsStore, ['alerts']),
    },
}
</script>

<style scoped>
.list-move,
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.list-leave-active {
    position: absolute;
}
</style>
