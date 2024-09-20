<template>
    <div class="modal fade" tabindex="-1" id="add_client_modal">
        <div class="modal-dialog">
            <Form @submit="submit" :validation-schema="createClientSchema">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Client</h5>
                        <div
                            class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            <font-awesome-icon icon="fa-close" size="lg"/>
                        </div>
                    </div>

                    <div class="modal-body">
                        <Alert v-if="showAlert" class="alert-danger"  :message="message"/>
                        <div>
                            <div class="mb-10">

                                <label for="name_first" class="required form-label">First name</label>
                                <Field name="name_first" id="first_name" class="form-control form-control-solid" />
                                <ErrorMessage name="name_first" class="text-danger mt-2"/>
                            </div>
                            <div class="mb-10">
                                <label for="name_last" class="required form-label">Last name</label>
                                <Field name="name_last" id="first_name" class="form-control form-control-solid"/>
                                <ErrorMessage name="name_last" class="text-danger mt-2"/>
                            </div>
                            <div class="mb-10">
                                <label for="email" class="required form-label">Email</label>
                                <Field name="email" id="first_name" class="form-control form-control-solid"/>
                                <ErrorMessage name="email" class="text-danger mt-2"/>
                            </div>
                            <div class="mb-10">
                                <label for="company" class="required form-label">Company</label>
                                <Field name="company" id="first_name" class="form-control form-control-solid"/>
                                <ErrorMessage name="company" class="text-danger mt-2"/>
                            </div>
                            <div class="mb-10">
                                <label for="coc_number" class="required form-label">COC Number</label>
                                <Field name="coc_number" id="first_name" class="form-control form-control-solid"/>
                                <ErrorMessage name="coc_number" class="text-danger mt-2"/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" :data-kt-indicator="isLoading">
                            <span class="indicator-label">Create</span>
                            <span class="indicator-progress">
                            Creating user...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"/>
                          </span>
                        </button>
                    </div>
                </div>
            </Form>
        </div>
    </div>
</template>

<script>
import {Field, Form, ErrorMessage} from 'vee-validate'
import {useUsersStore} from "../../store/users";
import Alert from "../Alert.vue"
import * as yup from 'yup'
import {Modal} from "bootstrap";

export default {
    name: 'AddClientModal',
    components: {
        Field,
        Form,
        ErrorMessage,
        Alert
    },
    setup() {
        const usersStore = useUsersStore()
        const createClientSchema = yup.object().shape({
            name_first: yup.string().required().label('First name'),
            name_last: yup.string().required().label('Last name'),
            company: yup.string().required().label('Company'),
            coc_number: yup.string().required().label('COC Number'),
            email: yup.string().email().required().label('Email'),
        })

        return {createClientSchema, usersStore}
    },

    mounted() {
        this.addClientModal = new Modal(document.getElementById('add_client_modal'))
    },

    data() {
        return {
            addClientModal: null,
            isSubmitting: false,
            showAlert: false,
            message: ''
        }
    },

    computed: {
      isLoading() {
          return this.isSubmitting ? 'on' : 'off'
      }
    },

    methods: {
        async submit(values, actions) {
            this.isSubmitting = true
            const newUser = await this.usersStore.store(values)

            if (this.usersStore.error) {
                this.showError()
            } else {
                this.usersStore.getAll()
                this.addClientModal.hide()
                this.$alert.add('Added user', values.name_first, 'success')
                this.$router.push(`/users/${newUser.uuid}`)
            }

            actions.resetForm()
            this.isSubmitting = false
        },

        showError(actions) {
            if (this.usersStore.error.status === 500) {
                this.showAlert = true
                this.message = 'Something went wrong. Please try again later.'
            } else {
                actions.setErrors(this.usersStore.getErrors)
            }
        },

        resetForm() {

        }
    }
}
</script>
