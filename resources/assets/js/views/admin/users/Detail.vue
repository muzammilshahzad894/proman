<template>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack container-fluid">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3"><h1
                class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">User
                detail</h1>
                <!---->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <router-link to="/" class="router-link-active text-muted text-hover-primary">Home</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <router-link to="/users" class="router-link-active text-muted text-hover-primary">Users
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xxl-8">
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <img :src="'/media/avatars/300-1.jpg?>'" alt=""/>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div v-if="user" class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h1 class="text-gray-800 fs-2 fw-bold me-1">
                                                {{ userStore.fullName }}
                                            </h1>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semobold fs-6 mb-4 pe-2">
                                            <div
                                               class="d-flex align-items-center text-gray-400 mb-2">
                                                <font-awesome-icon icon="fa-envelope" size="lg" class="me-2"/>
                                                {{ user.email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex overflow-auto h-55px">
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
                                <li class="nav-item">
                                    <a
                                        data-bs-toggle="tab"
                                        href="#kt_tab_1"
                                        aria-current="page"
                                        class="active router-link-exact-active nav-link text-active-primary me-6">
                                        Overview </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        data-bs-toggle="tab"
                                        href="#kt_tab_2"
                                        class="nav-link text-active-primary me-6">
                                        Vat
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        data-bs-toggle="tab"
                                        href="#kt_tab_3"
                                        class="nav-link text-active-primary me-6">
                                        Platforms
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xxl-8">
                    <div class="col-xl-12">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane show active" id="kt_tab_1" role="tabpanel">
                                <div class="card mb-5 mb-xxl-8">
                                    <div class="card-body">
                                        <div class="card mb-5" id="kt_profile_details_view">
                                            <div class="card-header cursor-pointer">
                                                <div class="card-title m-0"><h3 class="fw-bold m-0">Profile Details</h3>
                                                </div>

                                                <a v-if="false" href="#/account/settings" class="btn btn-primary align-self-center">Edit Profile</a>
                                            </div>
                                            <div v-if="user" class="card-body p-9">
                                                <div class="row mb-7">
                                                    <label class="col-lg-4 fw-semobold text-muted">Full
                                                        Name</label>
                                                    <div class="col-lg-8"><span
                                                        class="fw-bold fs-6 text-dark">{{ fullName }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-7"><label class="col-lg-4 fw-semobold text-muted">Email</label>
                                                    <div class="col-lg-8 fv-row"><span
                                                        class="fw-semobold fs-6">{{ user.email }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-7"><label class="col-lg-4 fw-semobold text-muted">Phone</label>
                                                    <div class="col-lg-8 fv-row"><span class="fw-semobold fs-6">{{
                                                            user.info.phone
                                                        }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-7"><label class="col-lg-4 fw-semobold text-muted">UUID</label>
                                                    <div class="col-lg-8 fv-row"><span
                                                        class="fw-semobold fs-6">{{ user.uuid }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-7"><label class="col-lg-4 fw-semobold text-muted">Company</label>
                                                    <div class="col-lg-8 fv-row"><span class="fw-semobold fs-6">{{
                                                            user.info.company
                                                        }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-7"><label class="col-lg-4 fw-semobold text-muted">Country</label>
                                                    <div class="col-lg-8 fv-row"><span class="fw-semobold fs-6">{{
                                                            user.info.country
                                                        }}</span>
                                                    </div>
                                                </div>
                                                <div class="row mb-7"><label class="col-lg-4 fw-semobold text-muted">Website</label>
                                                    <div class="col-lg-8 fv-row"><a
                                                        :href="user.info.website" target="_blank"
                                                        class="fw-semobold fs-6">{{
                                                            user.info.website
                                                        }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="kt_tab_2" role="tabpanel">
                                <div class="card mb-5 mb-xxl-8">
                                    <div class="card-body">
                                        <div class="card mb-5" id="kt_profile_details_view">
                                            <div class="card-header cursor-pointer">
                                                <div class="card-title m-0">
                                                    <h3 class="fw-bold m-0">VAT Numbers</h3>
                                                </div>
                                            </div>
                                            <div class="card-body p-9">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table align-middle table-row-dashed fs-6 fw-semobold gy-4"
                                                        id="kt_subscription_products_table">
                                                        <thead>
                                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                            <th class="min-w-300px">Number</th>
                                                            <th class="min-w-300px">Country code</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody v-if="vatNumberStore.vatNumbers" class="text-gray-600">
                                                        <tr v-for="vat in vatNumberStore.vatNumbers" class="odd">
                                                            <td>{{ vat.number }}</td>
                                                            <td>{{ vat.country.code }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="kt_tab_3" role="tabpanel">
                                <div class="card mb-5 mb-xxl-8">
                                    <div class="card-body">
                                        <div class="card-header cursor-pointer">
                                            <div class="card-title m-0">
                                                <h3 class="fw-bold m-0">Platforms</h3>
                                            </div>
                                        </div>
                                        <platforms-table class="px-6"
                                                         v-if="platformStore.userPlatforms"
                                                         :platforms="platformStore.userPlatforms" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import { useUsersStore } from "../../../store/users"
import { useAdminVatNumberStore } from "../../../store/AdminVatNumbers"
import { usePlatformStore } from "../../../store/platforms"
import PlatformsTable from "../../../components/PlatformsTable.vue"
import { mapState } from "pinia";

export default {
    name: "UserDetail",

    components: {
        PlatformsTable,
    },

    props: {
        uuid: {
            type: String,
            required: true,
        }
    },

    setup() {
        const userStore = useUsersStore()
        const vatNumberStore = useAdminVatNumberStore()
        const platformStore = usePlatformStore()

        return {userStore, vatNumberStore, platformStore}
    },

    computed: {
        uuid() {
            return this.$route.params.uuid
        },

        ...mapState(useUsersStore, ['user'])
    },

    async mounted() {
        await this.userStore.show(this.uuid)
        await this.platformStore.getForUser(this.uuid)
        await this.vatNumberStore.getVatNumbersForUser(this.uuid)

        if (this.userStore.error?.status === 404) this.$router.push('/404')
    },

    methods: {}
}
</script>

<style scoped>

</style>
