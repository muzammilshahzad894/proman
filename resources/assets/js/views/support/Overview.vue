<template>
    <div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">

                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack container-fluid mb-4">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3"><h1
                        class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Support</h1><!---->
                    </div>
                    <div v-if="isAdmin" class="d-flex align-items-center gap-2 gap-lg-3">
                        <router-link to="/support/create" class="btn btn-sm fw-bold btn-primary">
                            Create article
                        </router-link>
                    </div>
                </div>

                <!--begin::FAQ card-->
                <div class="card">
                    <!--begin::Body-->
                    <div class="card-body p-lg-15">
                        <!--begin::Layout-->
                        <div class="d-flex flex-column flex-lg-row">
                            <!--begin::Sidebar-->
                            <div class="flex-column flex-lg-row-auto w-100 w-lg-275px mb-10 me-lg-20">
                                <!--begin::Catigories-->
                                <div class="mb-15">
                                    <h4 class="text-dark mb-7">Categories</h4>
                                    <!--begin::Menu-->
                                    <div
                                        class="menu menu-rounded menu-column menu-title-gray-700 menu-state-title-primary menu-active-bg-light-primary fw-semibold">
                                        <!--begin::Item-->
                                        <div v-if="categories" v-for="category in (categories.data.data)"
                                             :key="category.id" class="menu-item mb-1">
                                            <!--begin::Link-->
                                            <a :href="`/support/categories/${category.id}`" @click="getArticles"
                                                         :class="[ isActive(category) ? 'active': '']"
                                                         class="menu-link py-3">{{ category.name }}
                                            </a>
                                            <!--end::Link-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Menu-->
                                </div>
                                <!--end::Catigories-->
                            </div>
                            <!--end::Sidebar-->
                            <!--begin::Content-->
                            <div class="flex-lg-row-fluid">
                                <!--begin::Extended content-->
                                <div class="mb-13">
                                    <!--begin::Content-->
                                    <div v-if="!isByCategory" class="mb-15">
                                        <!--begin::Title-->
                                        <h4 class="fs-2x text-gray-800 w-bolder mb-6">Articles overview</h4>
                                        <!--end::Title-->
                                        <!--begin::Text-->
                                        <p class="fw-semibold fs-4 text-gray-600 mb-2">First, a disclaimer – the entire
                                            process of writing a blog post often takes more than a couple of hours, even
                                            if you can type eighty words as per minute and your writing skills are
                                            sharp.</p>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Content-->
                                    <!--begin::Item-->
                                    <div class="mb-15">
                                        <router-link :to="`/support/${article.id}`" v-if="articles"
                                                     v-for="article in articles.data.data" :key="articles.id"
                                                     class="m-0">
                                            <!--begin::Heading-->
                                            <div
                                                class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0">
                                                <!--begin::Title-->
                                                <h4 class="text-gray-700 fw-bold cursor-pointer mb-0">{{
                                                        article.title
                                                    }}</h4>
                                                <!--end::Title-->
                                            </div>
                                            <!--begin::Separator-->
                                            <div class="separator separator-dashed"></div>
                                            <!--end::Separator-->
                                        </router-link>
                                    </div>
                                </div>
                                <!--end::Extended content-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Layout-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::FAQ card-->
            </div>
            <!--end::Container-->
        </div>
    </div>
</template>

<script>
import ArticleService from "../../services/ArticleService";
import {useAuthStore} from "../../store/auth"
import {mapState} from "pinia/dist/pinia";

export default {
    async mounted() {
        await this.getCategories()
        await this.getArticles()

    },

    computed: {
        isByCategory() {
            return this.$route.name === 'supportCategoryOverview'
        },
        ...mapState(useAuthStore, ['isAdmin', 'isCustomer'])
    },

    methods: {
        isActive(category) {
            return category.id === parseInt(this.$route.params.categoryId)
        },

        async getArticles() {
            this.articles = this.isByCategory ? await ArticleService.getByCategoryId(this.$route.params.categoryId) : await ArticleService.getAll()
        },

        async getCategories() {
            this.categories = await ArticleService.getCategories()
        }
    },

    data() {
        return {
            categories: null,
            articles: null
        }
    },
}
</script>
