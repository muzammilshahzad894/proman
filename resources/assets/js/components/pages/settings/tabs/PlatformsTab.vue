<template>
    <div class="card card-flush h-xl-100">
        <div class="card-header p-0">
            <h3 class="card-title align-items-start flex-column">
                <span class="text-gray-400 mt-1 fw-bold fs-6">Bekijk en beheer hier je platformspecifieke gegevens</span>
            </h3>
        </div>
        <div class="card-body p-0">
            <div v-for="platform in platforms" class="d-flex justify-content-between mb-4">
                <div class="d-flex align-items-center me-5">
                    <div class="symbol symbol-40px me-3">
                        <a @click="prepareFormData(platform)" type="button" class="text-hover-primary" data-bs-toggle="modal" data-bs-target="#credential-modal">
                            <span class="symbol-label bg-light-info">
                                <span class="svg-icon svg-icon-2x svg-icon-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M16.925 3.90078V8.00077L12.025 10.8008V5.10078L15.525 3.10078C16.125 2.80078 16.925 3.20078 16.925 3.90078ZM2.525 13.5008L6.025 15.5008L10.925 12.7008L6.025 9.90078L2.525 11.9008C1.825 12.3008 1.825 13.2008 2.525 13.5008ZM18.025 19.7008V15.6008L13.125 12.8008V18.5008L16.625 20.5008C17.225 20.8008 18.025 20.4008 18.025 19.7008Z" fill="currentColor"></path>
                                        <path opacity="0.3" d="M8.52499 3.10078L12.025 5.10078V10.8008L7.125 8.00077V3.90078C7.125 3.20078 7.92499 2.80078 8.52499 3.10078ZM7.42499 20.5008L10.925 18.5008V12.8008L6.02499 15.6008V19.7008C6.02499 20.4008 6.82499 20.8008 7.42499 20.5008ZM21.525 11.9008L18.025 9.90078L13.125 12.7008L18.025 15.5008L21.525 13.5008C22.225 13.2008 22.225 12.3008 21.525 11.9008Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                            </span>
                        </a>
                    </div>
                    <div class="me-5">
                        <a @click="prepareFormData(platform)" type="button" class="text-gray-800 fw-bolder text-hover-primary fs-6" data-bs-toggle="modal" data-bs-target="#credential-modal">{{ platform.name }}</a>
                        <span class="fw-bold fs-7 d-block text-start text-success ps-0" :class="{ 'text-success' : isActive(platform), 'text-danger' : !isActive(platform) }">{{ capitalize(platform.state) }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="d-flex flex-center">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input h-20px w-30px" type="checkbox" disabled v-model="platform.is_active_for_user">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <CredentialForm :user="user" :platform="platform" :credential="credential"/>
    </div>
</template>

<script>
import {useCredentialStore} from "../../../../store/credentials";
import {usePlatformStore} from "../../../../store/platforms";
import CredentialForm from "./forms/CredentialForm.vue";

export default {
    name: "PlatformsTab",
    components: {CredentialForm},
    props: {
        user: {
            type: Object,
            required: true,
        },
    },

    setup() {
        const credentialStore = useCredentialStore()
        const platformStore = usePlatformStore()
        return { credentialStore, platformStore }
    },

    async created() {
        await this.getPlatforms()
        await this.getCredentials()
    },

    data() {
        return {
            platform: null,
            credential: null,
        }
    },

    computed: {
        platforms() {
            return this.platformStore.getPlatforms
        },

        credentials() {
            return this.credentialStore.getCredentials
        },
    },

    methods: {
        async getPlatforms() {
            return await this.platformStore.get()
        },

        async getCredentials() {
            return await this.credentialStore.get()
        },

        isActive(platform) {
            return platform.state === 'active'
        },

        prepareFormData(platform) {
            this.setPlatform(platform)
            this.setCredential(platform)
        },

        setPlatform(platform) {
            this.platform = platform
        },

        setCredential(platform) {
            this.credential = this.getCredentialForPlatform(platform)
        },

        getCredentialForPlatform(platform) {
            if (null === this.credentials) {
                return null
            }

            const credential = this.credentials.filter(credential => credential.platform_id === platform.id)

            if (!credential.length) {
                return null
            }

            return credential[0]
        }
    }
}
</script>

<style scoped>

</style>
