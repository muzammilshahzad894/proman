<template>
  <section v-for="(categoryProducts,categoryNameIndex) in products" :key="categoryProducts">
    <h1>{{ categoryNameIndex }}</h1>
    <div class="row mb-2" >
      <div class="col-md-4  mb-2" v-for="product in categoryProducts" :key="product">
        <Card>
          <template #header>
            <img
              alt="user header"
              :src="'https://via.placeholder.com/300.png/09f/fff%20C/O%20https://placeholder.com/'"
            />
          </template>
          <template #title> {{ product.title }} </template>
          <template #content>
            <div v-html="product.description"></div>
          </template>
          <template #footer>
            <Button icon="pi pi-check" label="Save" />
            <Button
              icon="pi pi-times"
              label="Cancel"
              class="p-button-secondary"
              style="margin-left: 0.5em"
            />
          </template>
        </Card>
      </div>
    </div>
  </section>
</template>

<script>
import Card from "primevue/card";
import Button from "primevue/button";
import { useProductStore } from "../store/products";

export default {
  name: "ProductsList",
  components: {
    Card,
    Button,
  },
  setup() {
    const productStore = useProductStore();
    return { productStore };
  },
  computed: {
    products() {
      return this.productStore.getProducts;
    },
  },
  methods: {
    async getProducts() {
      return await this.productStore.get();
    },
  },
  async created() {
    await this.getProducts();
  },
};
</script>


<style>
</style>