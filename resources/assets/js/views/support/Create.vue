<template>
    <div>
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container d-flex flex-stack container-fluid">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3"><h1
                        class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        <span v-if="isEditing">Edit article</span>
                        <span v-else>Create article</span>

                    </h1><!---->
                    </div>
                    <div v-if="isEditing" class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="#"
                           class="btn btn-sm fw-bold bg-body btn-color-gray-700 btn-active-color-danger"
                           data-bs-toggle="modal"
                           data-bs-target="#kt_modal_delete_article">Delete</a>
                    </div>

                    <!-- modal -->
                    <div class="modal fade" tabindex="-1" id="kt_modal_delete_article">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Are you sure you want to delete this article?</h5>

                                    <!--begin::Close-->
                                    <div
                                        class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    >
                                        <span class="svg-icon svg-icon-2x"></span>
                                    </div>
                                    <!--end::Close-->
                                </div>

                                <div class="modal-footer">
                                    <button
                                        type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal"
                                    >
                                        Cancel
                                    </button>
                                    <button @click="destroy" class="btn btn-danger">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div id="kt_app_content_container" class="app-container container-fluid">
                        <div class="d-flex flex-column flex-lg-row">
                            <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
                                <form class="form" action="#" id="kt_subscriptions_create_new">
                                    <input type="text" v-model="title"
                                           class="form-control form-control-white border-transparent w-100 mb-5"
                                           placeholder="Article title"/>
                                    <div class="card card-flush pt-3 mb-5 mb-lg-10">
                                        <div class="card-body pt-4">
                                            <div id="editorjs"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div
                                class="flex-column flex-lg-row-auto w-100 w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">
                                <div class="card card-flush pt-3 mb-0" id="kt_add_summary" data-kt-sticky="true"
                                     data-kt-sticky-name="add-subscription-summary"
                                     data-kt-sticky-offset="{default: false, lg: '200px'}"
                                     data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-left="auto"
                                     data-kt-sticky-top="150px" data-kt-sticky-animation="false"
                                     data-kt-sticky-zindex="95">
                                    <div class="card-header">
                                        <div class="card-title"><h2>Article details</h2></div>
                                    </div>
                                    <div class="card-body pt-0 fs-6">
                                        <div class="mb-7">
                                            <div class="d-flex align-items-center mb-4 ">
                                                <select class="form-select form-select-solid me-2" v-model="category_id"
                                                        aria-label="Select example">
                                                    <option value="" disabled selected>Select category</option>
                                                    <option v-if="categories" :value="category.id"
                                                            v-for="category in categories.data.data">{{ category.name }}
                                                    </option>
                                                </select>
                                                <button
                                                    @click="categoryModal.show()"
                                                    class="btn btn-icon btn-success"
                                                >
                                                    <font-awesome-icon icon="fa-solid fa-plus"/>
                                                </button>

                                                <div class="modal modal-fullscreen fade" tabindex="-1" id="kt_modal_category">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Create category</h5>

                                                                <!--begin::Close-->
                                                                <div
                                                                    class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"
                                                                >
                                                                    <span class="svg-icon svg-icon-2x"></span>
                                                                </div>
                                                                <!--end::Close-->
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="fv-row mb-10"><label class="d-flex align-items-center fs-5 fw-semobold mb-2"><span class="required">Name</span><i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify your unique app name"></i></label><input v-model="newCategoryName" type="text" class="form-control form-control-lg form-control-solid" placeholder="Category name" name="appName"><span role="alert" class="fv-plugins-message-container invalid-feedback">App name is a required field</span></div>

                                                                <div class="fv-row mb-10">
                                                                    <label class="d-flex align-items-center fs-5 fw-semobold mb-2">
                                                                        <span class="required">Description</span>
                                                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify your unique app name"></i>
                                                                    </label>
                                                                    <textarea v-model="newCategoryDescription" type="text" class="form-control form-control-lg form-control-solid" placeholder="Category description" name="appName" />
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-light"
                                                                    data-bs-dismiss="modal"
                                                                >
                                                                    Cancel
                                                                </button>
                                                                <button @click="createCategory" type="button" class="btn btn-primary">
                                                                    Create
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="mb-0">
                                            <button @click="save" type="submit" class="btn btn-primary"
                                                    id="kt_subscriptions_create_button"><span class="indicator-label">Save</span><span
                                                class="indicator-progress">Please wait... <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                        </div>
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
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Underline from '@editorjs/underline';
import List from '@editorjs/list';
import SimpleImage from '@editorjs/simple-image';
import Link from '@editorjs/link';
import ArticleService from '../../services/ArticleService';
import { Modal } from 'bootstrap';


export default {
    async mounted() {
        await this.initEditor()
        await this.getCategories()
        this.categoryModal =  new Modal(document.getElementById('kt_modal_category'))

    },

    data() {
        return {
            title: '',
            categories: null,
            article: null,
            editor: null,
            category_id: '',
            newCategoryName: '',
            newCategoryDescription: '',
            categoryModal: null,
        }
    },

    computed: {
        isEditing() {
            return this.$attrs.isEditing
        }
    },

    methods: {

        async getCategories() {
            this.categories = await ArticleService.getCategories()
        },

        save() {
            this.editor.save().then((outputData) => {
                ArticleService.store({
                    title: this.title,
                    content: JSON.stringify(outputData),
                    category_id: this.category_id
                }).then((response) => {
                    this.$alert.add('Saved article', this.title, 'success')
                    this.$router.push(`/support/${response.data.data.id}`)
                }).catch((e) => {
                    this.$alert.add('Could not save article', 'Please try again later.', 'danger')
                    console.log(e)
                })
            })
        },

        destroy() {
            ArticleService.destroy(this.article.data.data.id).then(() => {
                window.location.href = '/support'
            })
        },

        update() {
            this.editor.save().then((outputData) => {
                ArticleService.update({
                    id: this.article.data.data.id,
                    title: this.title,
                    content: JSON.stringify(outputData)
                }).then((response) => {
                    this.$router.push(`/support/${response.data.data.id}`)
                })
            })
        },

        createCategory () {
            ArticleService.createCategory({
                'name': this.newCategoryName,
                'description': this.newCategoryDescription
            }).then(async (response) => {
                this.categoryModal.hide()
                this.$alert.add('Successfully created category', null, 'success')
                this.newCategoryDescription = ''
                this.newCategoryName = ''

                await this.getCategories()
                this.category_id = response.data.data.id
            }).catch((e) => {
                this.$alert.add('Could not add category', 'Please try again later', 'danger')
            })
        },

        submit() {
            if (this.isEditing) {
                this.save()
            } else {
                this.update()
            }
        },

        async initEditor() {
            const config = {
                holder: 'editorjs',
                placeholder: 'Write your article here...',
                tools: {
                    header: Header,
                    underline: Underline,
                    list: List,
                    simpleImage: SimpleImage,
                    link: Link
                },
            }

            if (this.isEditing) {
                this.article = await ArticleService.show(this.$route.params.id)
                this.title = this.article.data.data.title
                this.category_id = this.article.data.data.category.id
                config['data'] = JSON.parse(this.article.data.data.content)
            }

            this.editor = new EditorJS(config);
        }
    }
}
</script>
