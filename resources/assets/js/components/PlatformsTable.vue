<template>
    <div class="table-responsive">
        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
            <thead>
            <tr class="border-0">
                <th class="p-0 w-50px"></th>
                <th class="p-0 min-w-150px"></th>
                <th class="p-0 min-w-140px"></th>
                <th class="p-0 min-w-110px"></th>
                <th class="p-0 min-w-50px"></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="platform in platforms" :key="platform.uuid">
                <td>
                    <div class="symbol symbol-45px me-2"><span class="symbol-label"><img
                        src="/media/svg/brand-logos/plurk.svg" class="h-50 align-self-center"
                        alt=""></span></div>
                </td>
                <td>
                    <p class="text-dark fw-bold mb-1 fs-6">
                        {{ platform.name }}
                    </p>
                </td>
                <td class="text-end text-muted fw-semobold"></td>
                <td class="text-end">
                </td>
                <td class="text-end"><state-badge :state="platform.state" />
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import StateBadge from "./StateBadge.vue";
export default {
    props: {
        platforms: {
            type: Array,
            required: true,
            default: []
        }
    },
    components: {
        StateBadge
    }
}
</script>
