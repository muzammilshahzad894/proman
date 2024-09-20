<template>
    <Alert v-if="showAlert" :class="error ? 'alert-danger' : 'alert-success'"  :message="message"/>

    <Form
        id="tab_general_form"
        class="form"
        @submit="submit"
        :validation-schema="userRules"
        v-slot="{ errors, isSubmitting }">
        <div class="row fv-row mb-7 fv-plugins-icon-container">
            <div class="col-md-3 text-md-end">
                <label class="fs-6 fw-bold form-label mt-3">
                    <span>Company Name</span>
                </label>
            </div>
            <div class="col-md-9">
                <Field type="text" class="form-control form-control-solid" name="company" :value="user.info.company ?? ''"/>
                <ErrorMessage name="company" class="text-danger"/>
            </div>
        </div>

        <div class="row fv-row mb-7 fv-plugins-icon-container">
            <div class="col-md-3 text-md-end">
                <label class="fs-6 fw-bold form-label mt-3">
                    <span>CoC Number</span>
                </label>
            </div>
            <div class="col-md-9">
                <Field type="text" class="form-control form-control-solid" name="coc_number" :value="user.info.coc_number ?? ''"/>
                <ErrorMessage name="coc_number" class="text-danger"/>
            </div>
        </div>

        <div class="row fv-row mb-7">
            <div class="col-md-3 text-md-end">
                <label class="fs-6 fw-bold form-label mt-3">
                    <span>E-mail</span>
                </label>
            </div>
            <div class="col-md-9">
                <Field type="email" class="form-control form-control-solid" name="email" :value="user.email" />
                <ErrorMessage name="email" class="text-danger"/>
            </div>
        </div>

        <div class="row fv-row mb-7">
            <div class="col-md-3 text-md-end">
                <label class="fs-6 fw-bold form-label mt-3">
                    <span>Phone Number</span>
                </label>
            </div>
            <div class="col-md-9">
                <Field type="text" class="form-control form-control-solid" name="phone" :value="user.info.phone ?? ''"/>
                <ErrorMessage name="phone" class="text-danger"/>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9 offset-md-3">
                <div class="separator mb-6"></div>
                <div class="d-flex justify-content-end">
                    <button
                        type="submit"
                        ref="submitButton"
                        id="tab_general_form_submit"
                        data-kt-ecommerce-settings-type="submit"
                        class="btn btn-primary">
                        <span class="indicator-label">Save</span>
                        <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </div>
    </Form>
</template>

<script>
import { Field, Form, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'
import Alert from "../../../Alert.vue";
import {useAuthStore} from "../../../../store/auth";

export default {
    name: "GeneralTab",

    components: {
        Alert,
        Field,
        Form,
        ErrorMessage
    },

    props: {
        user: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            message: '',
            showAlert: false,
            error: false,
            isSubmitting: false,
        }
    },

    computed: {
        userRules: () => {
            return yup.object().shape({
                coc_number: yup.string().required().label("Coc Number"),
                company: yup.string().required().label("Company"),
                email: yup.string().email().required().label("Email"),
                phone: yup.string().required().label("Phone number"),
            })
        },

        serverError() {
            return this.authStore.getErrors.response
        }
    },

    setup() {
        const authStore = useAuthStore()
        return { authStore }
    },

    methods: {
        async submit(values) {
            console.log(values)
            this.resetData()

            const payload = {
                email: values.email,
                phone: values.phone,
                coc_number: values.coc_number,
                company: values.company,
            }

            await this.authStore.update(this.user.uuid, payload)

            if (this.serverError) {
                this.showError()
                return
            }

            this.showSuccess()

            return true
        },

        resetData() {
            this.isSubmitting = true
            this.showAlert = false
            this.message = ''
            this.error = false
        },

        showError() {
            console.log('Nope! Error caught!')
            this.isSubmitting = false
            this.showAlert = true
            this.error = true

            if (this.serverError.status === 422) {
                this.message = this.serverError.data.message
            } else {
                this.message = 'Something went wrong. Please try again later.'
            }
        },

        showSuccess() {
            this.isSubmitting = false
            this.showAlert = true
            this.message = 'Succesvol geüpdatet!'
            this.hideButton = true
        }
    }
}
</script>

<style scoped>

</style>
