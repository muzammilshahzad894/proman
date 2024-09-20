<template>
    <div class="page d-flex flex-row flex-column-fluid">
        <Aside/>
        <div  id="kt_wrapper" class="d-flex flex-column flex-row-fluid wrapper">
            <Header/>
            <alerts />

            <div id="kt_content" class="content d-flex flex-column flex-column-fluid">
                <!-- begin:: Content Body -->
                <div class="post d-flex flex-column-fluid">
                    <div
                        id="kt_content_container"
                        :class="{
                          'container-fluid': contentWidthFluid,
                          'container-xxl': !contentWidthFluid,
                        }">
                        <router-view />
                    </div>
                </div>
                <!-- end:: Content Body -->
            </div>
            <Footer/>
        </div>
    </div>
</template>

<script>
import Header from "../components/layout/Header.vue"
import Aside from "../components/layout/Aside.vue"
import Footer from "../components/layout/Footer.vue"
import Alerts from "../components/Alerts.vue"
import { reinitializeComponents } from "../keenthemes/keenthemes";
// import { MenuComponent } from '../keenthemes/components/MenuComponent'

export default {
    name: "Layout",
    components: {Footer, Aside, Header, Alerts},

    data() {
        return {
            contentWidthFluid: false,
            // MenuComponent: null,
        }
    },

    created() {

    },

    mounted() {
        reinitializeComponents()
    },

    watch: {
        // MenuComponent.hideDropdowns(undefined)
    }
}
</script>

<style scoped>

</style>
