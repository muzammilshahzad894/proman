<template>
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <Form
            class="form w-100"
            id="kt_login_signin_form"
            @submit="submit"
            :validation-schema="loginRules"
            v-slot="{ errors, isSubmitting }">
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">Sign In to Metronic</h1>
                <!--end::Title-->
            </div>
            <!--begin::Heading-->

            <div class="mb-10 bg-light-info p-8 rounded">
                <div class="text-info">
                    Use account <strong>admin@demo.com</strong> and password
                    <strong>password</strong> to continue.
                </div>
            </div>

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

                    <!--begin::Link-->
                    <router-link to="/forgot-password" class="link-primary fs-6 fw-bold">
                        Forgot Password ?
                    </router-link>
                    <!--end::Link-->
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

            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button
                    type="submit"
                    ref="submitButton"
                    id="kt_sign_in_submit"
                    class="btn btn-lg btn-primary w-100 mb-5"
                    :disabled="isSubmitting">
                    <span class="indicator-label" :class="{ 'd-none' : isSubmitting }"> Aanmelden </span>

                    <span class="indicator-progress" :class="{ 'show' : isSubmitting }">
                        Een ogenblik geduld...
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
import { useAuthStore } from "../../store/auth"
import Alert from "../../components/Alert.vue";

export default {
    name: "Login",

    components: {
        Alert,
        Field,
        Form,
        ErrorMessage
    },

    data() {
        return {
            email: 'demo@demo.com',
            password: 'password',
            message: '',
            showAlert: false,
            error: false,
            isSubmitting: false,
        }
    },

    setup() {
        const authStore = useAuthStore()
        return { authStore }
    },

    computed: {
        loginRules: () => {
            return yup.object().shape({
                email: yup.string().email().required().label("Email"),
                password: yup.string().min(4).required().label("Password"),
            })
        },

        authenticated() {
            return this.authStore.isAuthenticated
        },

        user() {
            return this.authStore.getUser
        },

        serverError() {
            return this.authStore.getErrors
        }
    },

    methods: {
        async submit() {
            this.resetData()

            await this.authStore.login(this.email, this.password)

            if (!this.authenticated) {
                this.showError()
                return
            }

            await this.authStore.getAuthUser()

            if (!this.user && this.serverError) {
                this.showError()
                return
            }

            this.showSuccess()

            return this.$router.push('/')
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
            this.message = 'Succesvol ingelogd!'
            this.hideButton = true
        }
    }
}
</script>

<style scoped lang="scss">
.show {
    display: block;
}
</style>
