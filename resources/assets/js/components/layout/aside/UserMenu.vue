<template>
    <!--begin::Menu-->
    <div
        v-if="visible"
        id="user-menu"
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semobold py-4 fs-6 w-275px"
        :class="{'show' : visible}"
        data-kt-menu="true">
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-5">
                    <img alt="Logo" :src="'media/avatars/300-1.jpg'" />
                </div>
                <!--end::Avatar-->

                <!--begin::Username-->
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        Max Smith
                        <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2"
                        >Pro</span
                        >
                    </div>
                    <a href="#" class="fw-semobold text-muted text-hover-primary fs-7"
                    >max@kt.com</a
                    >
                </div>
                <!--end::Username-->
            </div>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu separator-->
        <div class="separator my-2"></div>
        <!--end::Menu separator-->

        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <router-link to="/pages/profile/overview" class="menu-link px-5">
                My Profile
            </router-link>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <router-link to="/pages/profile/overview" class="menu-link px-5">
                <span class="menu-text">My Projects</span>
                <span class="menu-badge">
                                  <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
                                </span>
            </router-link>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div
            class="menu-item px-5"
            data-kt-menu-trigger="hover"
            data-kt-menu-placement="left-start"
            data-kt-menu-flip="center, top">
            <router-link to="/pages/profile/overview" class="menu-link px-5">
                <span class="menu-title">My Subscription</span>
                <span class="menu-arrow"></span>
            </router-link>

            <!--begin::Menu sub-->
            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <router-link to="/pages/profile/overview" class="menu-link px-5">
                        Referrals
                    </router-link>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <router-link to="/pages/profile/overview" class="menu-link px-5">
                        Billing
                    </router-link>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <router-link to="/pages/profile/overview" class="menu-link px-5">
                        Payments
                    </router-link>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <router-link
                        to="/pages/profile/overview"
                        class="menu-link d-flex flex-stack px-5">
                        Statements
                        <i
                            class="fas fa-exclamation-circle ms-2 fs-7"
                            data-bs-toggle="tooltip"
                            title="View your statements"></i>
                    </router-link>
                </div>
                <!--end::Menu item-->

                <!--begin::Menu separator-->
                <div class="separator my-2"></div>
                <!--end::Menu separator-->

                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <div class="menu-content px-3">
                        <label
                            class="form-check form-switch form-check-custom form-check-solid"
                        >
                            <input
                                class="form-check-input w-30px h-20px"
                                type="checkbox"
                                value="1"
                                checked="checked"
                                name="notifications"
                            />
                            <span class="form-check-label text-muted fs-7">
                                                Notifications
                                            </span>
                        </label>
                    </div>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu sub-->
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <router-link to="/pages/profile/overview" class="menu-link px-5">
                My Statements
            </router-link>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu separator-->
        <div class="separator my-2"></div>
        <!--end::Menu separator-->

        <!--begin::Menu item-->
        <div class="menu-item px-5 my-1">
            <router-link to="/pages/profile/overview" class="menu-link px-5">
                Account Settings
            </router-link>
        </div>
        <!--end::Menu item-->

        <!--begin::Menu item-->
        <div class="menu-item px-5">
            <a @click="logout()" class="menu-link px-5"> Sign Out </a>
        </div>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</template>

<script>
import { useAuthStore } from '../../../store/auth'

export default {
    name: "UserMenu",

    computed: {

    },

    setup() {
        const authStore = useAuthStore()
        return { authStore }
    },

    methods: {
        logout() {
            this.authStore.logout(email, password).then(r => this.$router.push('/login'))
        },
    }
}
</script>

<style scoped>

</style>
