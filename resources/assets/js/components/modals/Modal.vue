<template>
    <div class="modal fade" tabindex="-1" :id="modalId">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <slot name="header" />
                </div>

                <div class="modal-body">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Modal} from "bootstrap";

export default {
    props: {
        modalId: {
            required: true,
            type: String
        }
    },

    data() {
        return {
            modal: null
        }
    },

    mounted() {
        this.modal = new Modal(document.getElementById(this.modalId))
    },

    methods: {
        hide() {
            this.modal.hide()
        }
    }
}
</script>
