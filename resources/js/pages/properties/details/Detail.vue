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
                            <ReservationDateDetail />
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <ReservationSummary />
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import '@public/vue-assets/css/calendar.css';
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
  };
</script>