<template>
    <div class="listing__details--content__step mt-5 date-range-sec">
        <h3 class="days-title">7 nights in Greater London</h3>
        <div class="date-range">Nov 18, 2024 - Nov 25, 2024</div>
        <div class="range-calendar">
            <VDatePicker 
                is-range
                columns="2"
                :min-date="minDate"
                :color="selectedColor"
            />
        </div>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name: 'ReservationDateDetail',
    data() {
        return {
            minDate: new Date().toISOString().split('T')[0],
            selectedColor: ref('gray'),
        };
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