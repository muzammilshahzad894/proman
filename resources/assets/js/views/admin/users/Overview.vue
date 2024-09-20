<template>
    <div>
        <div class="d-flex justify-content-between">
            <div>
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3"><h1
                    class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Users</h1>
                    <!---->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link to="/" class="router-link-active text-muted text-hover-primary">Home</router-link>
                        </li>
                    </ul>
                </div>
            </div>

            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_client_modal">Add client</button>
            </div>
        </div>

        <add-client-modal />

        <div class="mt-8 bg-white p-12 rounded-3">
            <DataTable
                :columns="columns"
                class="table table-hover table-row-bordered cursor-pointer"
                width="100%"
                :data="usersStore.users"
                :options="options"
            >
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>E-MAIL</th>
                    <th>COMPANY</th>
                    <th>KVK</th>
                    <th>Status</th>
                </tr>
                </thead>
            </DataTable>
        </div>
    </div>
</template>

<script>
import AddClientModal from './../../../components/modals/AddClientModal.vue'
import DataTable from 'datatables.net-vue3';
import DataTableBs5 from 'datatables.net-bs5';
import { useUsersStore} from "../../../store/users";
import { useRouter } from 'vue-router';
import {useAuthStore} from "../../../store/auth";

export default {
    name: "UserOverview",
    components: {
        DataTable,
        AddClientModal
    },
    created() {
        DataTable.use(DataTableBs5)
    },

    setup() {
        const router = useRouter()
        const usersStore = useUsersStore()

        const tableLayout = `
        <'row b-table-header' Pfr>
        t
        <'row b-table-footer'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'p>>
        `

        const options = {
            dom: tableLayout,
            language: {
                paginate: {
                    next: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>',
                    previous: '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>'
                },
                lengthMenu: '_MENU_',
                searchPlaceholder: 'Search users',
                search: '',
            },
            createdRow: (row, data) => {
                row.addEventListener('click', () => {
                    router.push({
                        name: 'user',
                        params: {
                            uuid: data.uuid
                        }
                    })
                })
            }
        }

        return { usersStore, options }
    },

    mounted() {
        this.usersStore.getAll()
    },

    data() {
        return {
        }
    },

    methods: {
        generateStateColumn(data, type, row, meta){
            const indicator = data === 'active' ? 'active-indicator' : 'inactive-indicator'
            return `<div class="indicator ${indicator}"></div>`
        }
    },

    computed: {
        columns() {
            return [
                { data: 'id' },
                { data: 'name_first' },
                { data: 'name_last' },
                { data: 'email' },
                { data: 'info.company' },
                { data: 'info.coc_number' },
                { data: 'state', render: this.generateStateColumn },
            ]
        }
    },
}
</script>

<style>
@import 'datatables.net-bs5';

.b-table-footer {
    margin-top: 2rem;
}

.b-table-header {
    margin-bottom: 2rem;
}

.indicator {
    height: 10px;
    width: 10px;
    border-radius: 100%;
    margin-top: 5px;
}
.active-indicator {
    background-color: var(--kt-success);
}

.inactive-indicator {
    background-color: var(--kt-danger);
}


</style>
