<template>
    <modal :modal-id="modalId" ref="modal">

        <template #header>
            <h5 class="modal-title">Add Platform</h5>
            <div
                @click="hide"
                class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                aria-label="Close"
            >
                <font-awesome-icon icon="fa-close" size="lg"/>
            </div>
        </template>

        <Alert v-if="showAlert" class="alert-danger"  :message="message"/>
        <Form @submit="submit" :validation-schema="addPlatformSchema">
            <label for="name" class="required form-label">Name</label>
            <Field name="name" id="name" class="form-control form-control-solid" />
            <ErrorMessage name="name" class="text-danger mt-2"/>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :data-kt-indicator="isSubmitting">
                    <span class="indicator-label">Add</span>
                    <span class="indicator-progress">
                            Adding platform...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"/>
                          </span>
                </button>
            </div>
        </Form>
    </modal>
</template>

<script>
import Modal from './Modal.vue'
import {Field, Form, ErrorMessage} from 'vee-validate'
import Alert from '../Alert.vue'
import {usePlatformStore} from "../../store/platforms";
import * as yup from 'yup'

export default {
    components: {
        Modal,
        Alert,
        Field,
        Form,
        ErrorMessage
    },

    props: {
      modalId: {
          type: String,
          default: 'add_platform_modal'
      }
    },

    setup() {
        const platformStore = usePlatformStore()
        const addPlatformSchema = yup.object().shape({
            name: yup.string().required().label('name')
        })

        return {
            platformStore,
            addPlatformSchema
        }
    },

    data() {
        return {
            isSubmitting: false,
            message: '',
            showAlert: false
        }
    },

    methods: {
        async submit(values, actions) {
            this.isSubmitting = true
            await this.platformStore.store(values.name)

            if (this.platformStore.error) {
                this.showError()
                actions.setErrors(this.platformStore.getErrors)
            } else {
                this.$alert.add('Added platform', values.name, 'success')
                this.platformStore.get()
                actions.resetForm()
                this.hide()
            }
        },

        showError() {
            if (this.platformStore.error.status === 500) {
                this.showAlert = true
                this.message = this.platformStore.error?.data?.error
            }
        },

        hide() {
            this.$refs.modal.hide()
        }
    }
}
</script>
