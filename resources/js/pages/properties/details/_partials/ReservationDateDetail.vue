<template>
    <div class="listing__details--content__step mt-5 date-range-sec">
        <h3 class="days-title" v-if="checkInDate && checkOutDate">
            {{ $totalDays(checkInDate, checkOutDate) }} days booking
        </h3>
        <h3 class="days-title" v-else>
            Select check-in date
        </h3>
        <div class="date-range">
            <span v-if="checkInDate && checkOutDate">
                {{ $formatDate(checkInDate) }} - {{ $formatDate(checkOutDate) }}
            </span>
            <span v-else>
                Add your booking dates
            </span>
        </div>
        <div class="range-calendar">
            <CustomCalendar 
                :dateRange="dateRange"
                :columns="columns"
                :min-date="minDate"
                color="gray"
                :updateRange="updateRange"
            />
        </div>
    </div>
</template>

<script>
import CustomCalendar from '@/pages/properties/details/_partials/CustomCalendar.vue';

export default {
    name: 'ReservationDateDetail',
    components: {
        CustomCalendar,
    },
    props: {
        minDate: {
            type: String,
            default: new Date().toISOString().split('T')[0],
        },
        dateRange: {
            type: [Array, Object],
            default: () => [],
        },
        columns: {
            type: Number,
            default: 1,
        },
        checkInDate: {
            type: [String, Date],
            default: '',
        },
        checkOutDate: {
            type: [String, Date],
            default: '',
        },
        updateRange: {
            type: Function,
            default: () => {},
        },
    },
}
</script>

<style scoped>
.date-range-sec .days-title {
    font-size: 22px;
}

.date-range {
    color: #6a6a6a;
    font-size: 14px;
}
</style>