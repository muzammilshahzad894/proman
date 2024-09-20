<template>
    <Alert v-if="showAlert" :class="error ? 'alert-danger' : 'alert-success'"  :message="message"/>
    <Form class="form vat-form"
          @submit="submit"
          :validation-schema="vatRules">
        <div class="row mb-7">
            <div class="col-md-3 text-md-end">
                <label class="fs-6 fw-bold form-label mt-3">
                    <span>{{ country.name }}</span>
                </label>
            </div>
            <div class="col-md-9">
                <Field type="hidden" name="code" :value="country.code" />
                <div v-if="vatNumber" class="d-flex">
                    <Field
                        type="text"
                        name="number"
                        disabled
                        id="input-vat-number"
                        class="form-control form-control-lg form-control-solid"
                        :data-country="country.code"
                        v-model="vatNumber.number"/>
                    <button
                        v-show="!editing"
                        @click="toggleEditor"
                        type="button"
                        class="btn btn-outline-primary vat-form-submit">
                        Edit
                    </button>
                    <button
                        v-show="editing"
                        @click="toggleEditor"
                        type="button"
                        class="btn btn-outline-danger vat-form-clear">
                        <font-awesome-icon icon="fa-solid fa-xmark" />
                    </button>
                    <button
                        v-show="editing"
                        type="submit"
                        :name="country.name"
                        class="btn btn-outline-primary vat-form-submit">
                        Save
                    </button>
                </div>
                <div v-else class="d-flex">
                    <Field
                        type="text"
                        name="number"
                        id="input-vat-number"
                        class="form-control form-control-lg form-control-solid"
                        :placeholder="`Voeg een BTW nummer toe voor ${country.code}`" />
                    <button
                        type="reset"
                        class="btn btn-outline-danger vat-form-clear">
                        <font-awesome-icon icon="fa-solid fa-xmark" />
                    </button>
                    <button
                        type="submit"
                        :name="country.name"
                        class="btn btn-outline-primary vat-form-submit">
                        Add
                    </button>
                </div>
                <ErrorMessage name="number" class="text-danger"/>
            </div>
        </div>
    </Form>
</template>

<script>
import Alert from "../../../../Alert.vue"
import * as yup from "yup"
import {useVatNumberStore} from "../../../../../store/vatNumbers"
import {ErrorMessage, Field, Form} from "vee-validate"


export default {
    name: "VatNumberForm",

    components: {
        Alert,
        Field,
        Form,
        ErrorMessage
    },

    props: {
        country: {
            type: Object,
            required: true,
        },

        user: {
            type: Object,
            required: true,
        }
    },

    setup() {
        const vatNumberStore = useVatNumberStore()
        return { vatNumberStore }
    },

    data() {
        return {
            number: '',
            message: '',
            showAlert: false,
            error: false,
            isSubmitting: false,
            editing: false
        }
    },

    computed: {
        vatNumber() {
            return this.country.vat_numbers[0]
        },

        vatRules: () => {
            return yup.object().shape({
                code: yup.string().max(2).required(),
                number: yup.string().length(14).required().nullable().label('BTW nummer'),
            })
        },

        serverError() {
            return this.vatNumberStore.getErrors
        },
    },

    methods: {
        async submit(values) {
            this.resetData()

            if (this.vatNumber) {
                await this.update()
            } else {
                await this.store(values)
            }

            if (this.serverError) {
                this.showError()
                return false
            }

            this.showSuccess()

            return true
        },

        async store(values) {
            const {code, number} = values
            await this.vatNumberStore.store(this.user.uuid, code, number)
        },

        async update() {
            await this.vatNumberStore.update(this.vatNumber.uuid, {number: this.vatNumber.number})
        },

        resetData() {
            this.isSubmitting = true
            this.showAlert = false
            this.message = ''
            this.error = false
        },

        showError() {
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
            this.editing = false
        },

        toggleEditor() {
            const input = document.querySelector(`input[data-country="${this.country.code}"]`)
            input.disabled = !input.disabled
            return this.editing = !this.editing
        },
    }
}
</script>

<style scoped>

</style>
