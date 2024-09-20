<template>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack container-fluid mb-4">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3"><h1
                    class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Support</h1><!---->
                </div>
                <div v-if="isAdmin && article" class="d-flex align-items-center gap-2 gap-lg-3">
                    <router-link :to="`/support/${article.data.data.id}/edit`" class="btn btn-sm fw-bold btn-primary">
                        Update article
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
                                <div class="menu menu-rounded menu-column menu-title-gray-700 menu-state-title-primary menu-active-bg-light-primary fw-semibold">
                                    <!--begin::Item-->
                                    <div v-if="categories" v-for="category in (categories.data.data)" :key="category.id" class="menu-item mb-1">
                                        <!--begin::Link-->
                                        <a :href="`/support/categories/${category.id}`" :class="[ isActive(category) ? 'active': '']" class="menu-link py-3">{{ category.name }}</a>
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
                            <div v-if="article" class="mb-13">
                                <!--begin::Content-->
                                <div class="mb-4">
                                    <!--begin::Title-->
                                    <h4 class="fs-2x text-gray-800 w-bolder mb-6 ce-block__content">{{ article.data.data.title }}</h4>
                                    <!--end::Title-->
                                </div>
                                <div id="editorjs"></div>
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
</template>

<script>
import ArticleService from "../../services/ArticleService"
import EditorJS from '@editorjs/editorjs';
import Header from "@editorjs/header";
import Underline from "@editorjs/underline";
import List from "@editorjs/list";
import SimpleImage from "@editorjs/simple-image";
import Link from "@editorjs/link";
import {useAuthStore} from "../../store/auth"
import {mapState} from "pinia/dist/pinia";

export default {
    async mounted() {
        this.article = await ArticleService.show(this.$route.params.id)
        this.categories = await ArticleService.getCategories()

        const editor = new EditorJS({
            holder: 'editorjs',
            placeholder: 'Write your article here...',
            autofocus: true,
            readOnly: true,
            tools: {
                header: Header,
                underline: Underline,
                list: List,
                simpleImage: SimpleImage,
                link: Link
            },
            data: JSON.parse(this.article.data.data.content)
        });
    },

    methods: {
        isActive(category) {
            return category.id === this.article.data.data.category.id
        }
    },

    data() {
        return {
            article: null,
            categories: null,
        }
    },

    computed: {
        ...mapState(useAuthStore, ['isAdmin', 'isCustomer'])
    },
}
</script>
