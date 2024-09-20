<template>
  <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
      <div id="kt_content_container" class="container-xxl">
        <div class="card card-flush">
          <div class="card-header">
            <div class="card-title"><h2>Products</h2></div>
          </div>
          <div class="card-body pt-0">
            <!--v-if-->
            <div class="">
              
              <DataTable
                :columns="columns"
                class="table table-hover table-row-bordered cursor-pointer"
                width="100%"
                :data="products"
              >
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Platform</th>
                    <th>SKU</th>
                    <th>EAN</th>
                    <th>ASIN</th>
                    <th>Price</th>
                  </tr>
                </thead>
              </DataTable>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from "../../store/auth";
import { useProductStore } from "../../store/products";
import DataTable from "datatables.net-vue3";
import DataTableBs5 from "datatables.net-bs5";

export default {
  name: "Products",
  components: {
    DataTable,
  },

  setup() {
    const authStore = useAuthStore();
    const productStore = useProductStore();
    return { authStore, productStore };
  },

  computed: {
    user() {
      return this.authStore.getUser;
    },
    products() {
      return this.productStore.getProducts;
    },
    columns() {
      return [
        { data: "title" },
        { data: "product.platform.name" },
        { data: "sku" },
        { data: "product.ean" },
        { data: "product.asin" },
        { data: "purchase_price" },
      ];
    },
  },

  methods: {
    async getProducts() {
      return await this.productStore.get(this.user.uuid);
    },
  },
  async created() {
    await this.getProducts();
    DataTable.use(DataTableBs5);
  },
};
</script>

<style> 
@import "datatables.net-bs5";
</style>
