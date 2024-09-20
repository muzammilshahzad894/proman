<template>
    <add-platform-modal/>
    <div class="d-flex justify-content-between">
        <div>
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3"><h1
                class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                Platforms</h1>
                <!---->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <router-link to="/" class="router-link-active text-muted text-hover-primary">Home</router-link>
                    </li>
                </ul>
            </div>
        </div>

        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_platform_modal">
                Add platform
            </button>
        </div>
    </div>

    <div class="mt-12">
        <div class="col-xl-12">
            <div class="card card-xxl-stretch mb-5 mb-xl-10">
                <div class="card-body py-3">
                    <platforms-table v-if="platformStore.platforms" :platforms="platformStore.platforms" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {usePlatformStore} from "../../store/platforms";
import AddPlatformModal from "../../components/modals/AddPlatformModal.vue";
import PlatformsTable from "../../components/PlatformsTable.vue";

export default {
    name: "Platforms",
    components: {
        AddPlatformModal,
        PlatformsTable
    },

    setup() {
        const platformStore = usePlatformStore()

        return {platformStore}
    },

    async mounted() {
        await this.platformStore.get()
    },
}
</script>
