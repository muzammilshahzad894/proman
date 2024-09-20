<template>
    <div class="form-check form-switch form-check-custom form-check-solid ">
      <label class="form-check-label toggle-oss-label-mr" for="toggleOssSwitch"
        >Toggle OSS</label
      >
      <input
        v-model="user.meta.oss_enabled"
        @click="toggleOss()"
        class="form-check-input"
        type="checkbox"
        id="toggleOssSwitch"
      />
    </div>
</template>

<script>
import { useVatNumberStore } from "../../../../store/vatNumbers";
export default {
  props: {
    user: {
      type: Object,
      required: true,
    },
  },
  setup() {
        const vatNumberStore = useVatNumberStore()
        return { vatNumberStore }
    },

  methods: {
    async toggleOss() {
      this.user.meta.oss_enabled = !this.user.meta.oss_enabled;
      var payload = {
        oss_enabled: this.user.meta.oss_enabled,
      };

      return await this.vatNumberStore.toggleOss(
        this.user.uuid,
        payload
      );
    },
  },
};
</script>

<style scoped>
.toggle-oss-label-mr{
  margin-right:5px;
}
</style>