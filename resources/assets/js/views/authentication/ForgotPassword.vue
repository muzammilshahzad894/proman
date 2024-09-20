<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <Form
            class="form w-100"
            id="kt_login_signin_form"
            @submit="submit"
            :validation-schema="emailRules">
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">Reset Password</h1>
                <!--end::Title-->
            </div>
            <!--begin::Heading-->

            <div class="mb-10 bg-light-info p-8 rounded">
                <div class="text-info">
                    <p>Typ hier uw e-mailadres in. Als het e-mailadres bekend is in ons systeem, ontvangt u een mail om uw wachtwoord opnieuw in te stellen.</p>
                </div>
            </div>

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bold text-dark fs-6 mb-0">E-mail</label>
                    <!--end::Label-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Input-->
                <Field
                    class="form-control form-control-lg form-control-solid"
                    type="email"
                    name="email"
                    v-model="email"
                    autocomplete="off"
                />
                <!--end::Input-->
                <div class="fv-plugins-message-container">
                    <div class="fv-help-block">
                        <ErrorMessage name="email" />
                    </div>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button
                    v-if="!hideButton"
                    type="submit"
                    ref="submitButton"
                    id="kt_sign_in_submit"
                    class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">Send Password Reset Mail</span>

                    <span class="indicator-progress">
                        Please wait...
                        <span
                            class="spinner-border spinner-border-sm align-middle ms-2"
                        ></span>
                      </span>
                </button>
                <!--end::Submit button-->
            </div>
            <!--end::Actions-->

            <Alert v-if="showAlert" :class="error ? 'alert-danger' : 'alert-success'"  :message="message"/>
        </Form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</template>

<script>
import { Field, Form, ErrorMessage } from 'vee-validate'
import AuthService from '../../services/AuthService'
import * as yup from 'yup'
import Alert from "../../components/Alert.vue";

export default {
    name: "ForgotPassword",

    data() {
        return {
            email: '',
            message: '',
            error: false,
            showAlert: false,
            hideButton: false,
        }
    },

    components: {
        Alert,
        Field,
        Form,
        ErrorMessage
    },

    computed: {
        emailRules: () => {
            return yup.object().shape({
                email: yup.string().email().required().label('E-mail'),
            })
        }
    },

    methods: {
        async submit(email) {
            console.log('Submitting!')

            this.showAlert = false
            this.message = ''
            this.error = false

            try {
                const response = await AuthService.forgotPassword(email)
                console.log(response)
                this.showAlert = true
                this.message = 'Als dit e-mailadres bij ons bekend is, wordt er een e-mail verstuurd.'
                this.hideButton = true
            } catch (error) {
                console.log(error)
                this.showAlert = true
                this.error = true

                if (error.response.status === 422) {
                    this.message = error.response.data.message
                } else {
                    this.message = 'Something went wrong. Please try again later.'
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
