<template>
    <div class="modal fade modal-credential" id="credential-modal" tabindex="-1" aria-labelledby="credential-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <Form
                    v-if="platform"
                    class="form-credential"
                    @submit="submit"
                    :validation-schema="credentialRules">
                    <input type="hidden" name="platform" :value="platform.name">
                    <div class="modal-header">
                        <h5 class="modal-title" id="credential-label">{{ platform.name + ' credentials'}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="credential" class="row mb-4">
                            <div class="col-6">
                                <button
                                    @click="toggleEditor"
                                    type="button"
                                    class="btn btn-warning"
                                    id="credential-edit">{{ editing ? 'Cancel' : 'Edit' }}</button>
                            </div>
                        </div>
                        <div class="row mx-1">
                            <Alert v-if="showAlert" :class="error ? 'alert-danger' : 'alert-success'"  :message="message"/>
                        </div>
                        <div class="row mb-4">
                            <div class="col">
                                <p>
                                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatem natus, ratione quos, quaerat possimus excepturi fugiat suscipit vitae delectus cumque iusto corrupti quidem? Vitae incidunt ex aliquid veniam quod beatae!
                                </p>
                            </div>
                        </div>
                        <div class="row fv-row mb-7">
                            <div class="col-md-3">
                                <label class="fs-6 fw-bold form-label mt-3">
                                    <span>Client ID</span>
                                </label>
                            </div>
                            <div v-if="credential" class="col-md-9">
                                <Field
                                    type="text"
                                    id="credential-client-id"
                                    class="form-control form-control-solid"
                                    name="client_id"
                                    :disabled="!editing"
                                    v-model="credential.client_id"/>
                            </div>
                            <div v-else class="col-md-9">
                                <Field
                                    type="text"
                                    id="credential-client-id"
                                    class="form-control form-control-solid"
                                    name="client_id" />
                                <ErrorMessage name="client_id" class="text-danger"/>
                            </div>
                        </div>
                        <div class="row fv-row mb-7">
                            <div class="col-md-3">
                                <label class="fs-6 fw-bold form-label mt-3">
                                    <span>Client Secret</span>
                                </label>
                            </div>
                            <div v-if="credential" class="col-md-9">
                                <Field
                                    type="password"
                                    id="credential-client-secret"
                                    class="form-control form-control-solid"
                                    name="client_secret"
                                    :disabled="!editing"
                                    v-model="credential.client_secret"/>
                            </div>
                            <div v-else class="col-md-9">
                                <Field
                                    type="password"
                                    id="credential-client-secret"
                                    class="form-control form-control-solid"
                                    name="client_secret" />
                                <ErrorMessage name="client_secret" class="text-danger"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary credential-submit">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>

<script>
import Alert from "../../../../Alert.vue"
import {ErrorMessage, Field, Form} from "vee-validate"
import {useCredentialStore} from "../../../../../store/credentials"
import * as yup from "yup";

export default {
    name: "CredentialForm",

    components: {
        Alert,
        Field,
        Form,
        ErrorMessage
    },

    setup() {
        const credentialStore = useCredentialStore()
        return { credentialStore }
    },

    props: {
        platform: {
            type: Object,
            required: true,
        },

        credential: {
            type: [Object, null],
            required: false,
        },
    },

    created() {
        this.resetData()
    },

    updated() {
        if (null === this.credential) {
            const clientIdInput = document.getElementById('credential-client-id')
            const clientIdSecret = document.getElementById('credential-client-secret')

            if (clientIdInput) {
                clientIdInput.value = ''
            }

            if (clientIdSecret) {
                clientIdSecret.value = ''
            }
        }
    },

    data() {
        return {
            message: '',
            showAlert: false,
            error: false,
            isSubmitting: false,
            editing: false
        }
    },

    computed: {
        credentialRules: () => {
            return yup.object().shape({
                client_id: yup.string().required().label('Client ID'),
                client_secret: yup.string().required().label('Client Secret'),
            })
        },

        serverError() {
            return this.credentialStore.getErrors
        },
    },

    methods: {
        async submit(values) {
            this.resetData()

            if (this.credential) {
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
            console.log('storing...')
            const {client_id, client_secret} = values
            await this.credentialStore.store(this.platform.name, client_id, client_secret)
        },

        async update() {
            console.log('updating...')
            await this.credentialStore.update(this.credential.uuid, {
                client_id: this.credential.client_id,
                client_secret: this.credential.client_secret
            })
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
            return this.editing = !this.editing
        },
    },
}
</script>

<style scoped>

</style>
