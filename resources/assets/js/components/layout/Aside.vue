<template>
    <div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
        <!--begin::Aside Toolbarl-->
        <div class="aside-toolbar flex-column-auto" id="kt_aside_toolbar">
            <!--begin::Aside user-->
            <AsideToolbar/>
            <!--end::Aside user-->
        </div>
        <!--end::Aside Toolbarl-->
        <!--begin::Aside menu-->
        <div class="aside-menu flex-column-fluid">
            <!--begin::Aside Menu-->
            <div class="hover-scroll-overlay-y px-2 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px">
                <!--begin::Menu-->
                <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                    <!--begin:Menu item-->
                    <div class="menu-item pt-5">
                        <!--begin:Menu content-->
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Pages</span>
                        </div>
                        <!--end:Menu content-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div v-if="isAdmin" class="menu-item">
                        <!--begin:Menu link-->
                        <router-link to="/users" class="menu-link">
                            <span class="menu-icon">
                                <font-awesome-icon icon="fa-users" size="lg" />
                            </span>
                            <span class="menu-title">Users</span>
                        </router-link>
                        <!--end:Menu link-->
                    </div>
                    <!--begin:Menu item-->
                    <div v-if="isAdmin" class="menu-item">
                        <!--begin:Menu link-->
                        <router-link to="/platforms" class="menu-link">
                            <span class="menu-icon">
                               <font-awesome-icon icon="fa-solid fa-layer-group" size="lg" />
                            </span>
                            <span class="menu-title">Platforms</span>
                        </router-link>
                        <!--end:Menu link-->
                    </div>
                    <!--begin:Menu item-->
                    <div v-if="isCustomer" class="menu-item">
                        <!--begin:Menu link-->
                        <router-link to="/products" class="menu-link">
                               <span class="menu-icon">
                                    <font-awesome-icon icon="fa-solid fa-money-bill-trend-up" size="lg" />
                                </span>
                            <span class="menu-title">Products</span>
                        </router-link>
                        <!--end:Menu link-->
                        <!--begin:Menu link-->
                        <router-link to="/sales" class="menu-link">
                               <span class="menu-icon">
                                    <font-awesome-icon icon="fa-solid fa-money-bill-trend-up" size="lg" />
                                </span>
                            <span class="menu-title">Sales</span>
                        </router-link>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div v-if="isCustomer" class="menu-item">
                        <!--begin:Menu link-->
                        <router-link to="/sales" class="menu-link">
                              <span class="menu-icon">
                                <font-awesome-icon icon="fa-solid fa-hand-holding-dollar" size="lg" />
                            </span>
                            <span class="menu-title">VAT</span>
                        </router-link>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div v-if="isCustomer" class="menu-item">
                        <!--begin:Menu link-->
                        <router-link to="/settings" class="menu-link">
                            <span class="menu-icon">
                                <font-awesome-icon icon="fa-solid fa-gear" size="lg" />
                            </span>
                            <span class="menu-title">Settings</span>
                        </router-link>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <router-link to="/support" class="menu-link" >
										<span class="menu-icon">
											<font-awesome-icon icon="fa-solid fa-book-open" size="lg" />
										</span>
                            <span class="menu-title">Support</span>
                        </router-link>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="../../demo8/dist/documentation/getting-started/changelog.html">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/coding/cod003.svg-->
											<span class="svg-icon svg-icon-2">
                                                <font-awesome-icon icon="fa-solid fa-code" size="lg" />
											</span>
                                            <!--end::Svg Icon-->
										</span>
                            <span class="menu-title">Changelog</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Aside Menu-->
        </div>
        <!--end::Aside menu-->
        <!--begin::Footer-->
        <div class="aside-footer flex-column-auto py-5" id="kt_aside_footer">
            <a href="../../demo8/dist/documentation/getting-started.html" class="btn btn-custom btn-primary w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="200+ in-house components and 3rd-party plugins">
                <span class="btn-label">Docs &amp; Components</span>
                <!--begin::Svg Icon | path: icons/duotune/general/gen005.svg-->
                <span class="svg-icon btn-icon svg-icon-2">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z" fill="currentColor" />
									<rect x="7" y="17" width="6" height="2" rx="1" fill="currentColor" />
									<rect x="7" y="12" width="10" height="2" rx="1" fill="currentColor" />
									<rect x="7" y="7" width="6" height="2" rx="1" fill="currentColor" />
									<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
								</svg>
							</span>
                <!--end::Svg Icon-->
            </a>
        </div>
        <!--end::Footer-->
    </div>
</template>

<script>
import AsideToolbar from './aside/AsideToolbar.vue'
import { mapState } from 'pinia'
import { useAuthStore } from "../../store/auth"

export default {
    name: "Aside",
    components: {AsideToolbar},
    data() {
        return {

        }
    },

    created() {
        this.minimizeAside()
    },

    methods: {
        minimizeAside() {

        }
    },

    computed: {
        ...mapState(useAuthStore, ['isAdmin', 'isCustomer'])
    }
}
</script>

<style scoped>

</style>
