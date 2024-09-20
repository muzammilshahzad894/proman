<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <Form
            class="form w-100"
            id="kt_login_signin_form"
            @submit="submit"
            :validation-schema="passwordRules">
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">Setup New Password for Booky</h1>
                <!--end::Title-->
            </div>
            <!--begin::Heading-->

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bold text-dark">Email</label>
                <!--end::Label-->

                <!--begin::Input-->
                <Field
                    class="form-control form-control-lg form-control-solid"
                    type="text"
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

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bold text-dark fs-6 mb-0">Password</label>
                    <!--end::Label-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Input-->
                <Field
                    class="form-control form-control-lg form-control-solid"
                    type="password"
                    name="password"
                    v-model="password"
                    autocomplete="off"
                />
                <!--end::Input-->
                <div class="fv-plugins-message-container">
                    <div class="fv-help-block">
                        <ErrorMessage name="password" />
                    </div>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bold text-dark fs-6 mb-0">Confirm Password</label>
                    <!--end::Label-->
                </div>
                <!--end::Wrapper-->

                <!--begin::Input-->
                <Field
                    class="form-control form-control-lg form-control-solid"
                    type="password"
                    name="password_confirmation"
                    v-model="password_confirmation"
                    autocomplete="off"
                />
                <!--end::Input-->
                <div class="fv-plugins-message-container">
                    <div class="fv-help-block">
                        <ErrorMessage name="password_confirmation" />
                    </div>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button
                    type="submit"
                    ref="submitButton"
                    id="kt_sign_in_submit"
                    class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">Update Wachtwoord</span>

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
import * as yup from 'yup'
import AuthService from "../../services/AuthService";
import Alert from "../../components/Alert.vue";

export default {
    name: "ResetPassword",

    components: {
        Alert,
        Field,
        Form,
        ErrorMessage
    },

    data() {
        return {
            email: '',
            password: '',
            password_confirmation: '',
            message: '',
            error: false,
            showAlert: false,
            hideButton: false,
        }
    },

    computed: {
        passwordRules: () => {
            return yup.object().shape({
                email: yup.string().email().required().label("Email"),
                password: yup.string().min(8).required().label('Password'),
                password_confirmation: yup.string().min(8).label('Confirm Password').required()
                    .oneOf([yup.ref('password')], 'Wachtwoorden komen niet overeen.')
            })
        }
    },

    methods: {
        async submit() {
            console.log('Submitting!')

            this.showAlert = false
            this.message = ''
            this.error = false

            try {
                const payload = {
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    token: this.$route.query.token,
                }

                const response = await AuthService.resetPassword(payload)
                console.log(response)
                this.showAlert = true
                this.message = 'Wachtwoord successvol gereset.'
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
