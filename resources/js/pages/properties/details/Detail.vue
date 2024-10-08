<template>
    <Gallery />
    
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="listing__details--wrapper">
                        <div class="mt-3">
                            <Overview />
                            <Amenities />
                            <hr>
                            <ReservationDateDetail 
                                :minDate="minDate"
                                :range="range"
                                :columns="columns"
                                :checkInDate="checkInDate"
                                :checkOutDate="checkOutDate"
                                :updateRange="updateRange"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <ReservationSummary
                        :minDate="minDate"
                        :dateRange="range"
                        :columns="columns"
                        :checkInDate="checkInDate"
                        :checkOutDate="checkOutDate"
                        :updateRange="updateRange"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import '@public/vue-assets/css/calendar.css';
import { ref } from 'vue';
import Gallery from '@/pages/properties/details/_partials/Gallery.vue';
import Overview from '@/pages/properties/details/_partials/Overview.vue';
import Amenities from '@/pages/properties/details/_partials/Amenities.vue';
import ReservationDateDetail from '@/pages/properties/details/_partials/ReservationDateDetail.vue';
import ReservationSummary from '@/pages/properties/details/_partials/ReservationSummary.vue';

export default {
    name: 'Detail',
    components: {
        Gallery,
        Overview,
        Amenities,
        ReservationDateDetail,
        ReservationSummary,
    },
    data() {
        return {
            minDate: new Date().toISOString().split('T')[0],
            range: ref([]),
            columns: 2,
            checkInDate: '',
            checkOutDate: '',
        };
    },
    methods: {
        updateRange(range) {
            if(range?.start && range?.end) {
                this.checkInDate = range.start;
                this.checkOutDate = range.end;
                this.range = range;
            }
        },
    },
  };
</script>