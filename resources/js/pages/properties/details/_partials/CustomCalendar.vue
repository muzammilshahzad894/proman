<template>
    <VDatePicker 
        v-model.range="range"
        :columns="columns"
        :min-date="minDate"
        :color="color"
    />
</template>

<script>
import { ref } from 'vue';
export default {
    name: 'CustomCalendar',
    props: {
        dateRange: {
            type: [Array, Object],
            default: () => [],
        },
        columns: {
            type: Number,
            default: 1,
        },
        minDate: {
            type: String,
            default: new Date().toISOString().split('T')[0],
        },
        color: {
            type: String,
            default: 'gray',
        },
        updateRange: {
            type: Function,
            default: () => {},
        },
    },
    
    data() {
        return {
            range: ref([]),
        };
    },
    
    watch: {
        range: {
            handler(newValue) {
                if(newValue?.start && newValue?.end) {
                    this.updateRange(newValue);
                }
            },
        },
        dateRange: {
            handler(newValue) {
                console.log('newvalue of date range',newValue);
                if(newValue?.start && newValue?.end) {
                    this.range = newValue;
                } else {
                    this.range = [];
                }
            },
        },
    },
}
</script>