<template>
    <div class="container">
        <h1 class="pt-3 pb-3 property-title">Stylish ensuite double bedroom in trendy Dalston</h1> 
    </div>
    <div class="container gallery-section">
        <div class="gallery">
            <div class="large-image">
                <a href="/vue-assets/img/property/properties1.png" class="glightbox">
                    <img 
                        src="/vue-assets/img/property/properties1.png" 
                        alt="placeholder" 
                        class="img-fluid border-top-left-radius border-bottom-left-radius"
                    />
                </a>
            </div>
            <div class="small-images">
                <a  
                    v-for="(image, index) in gallery"
                    :key="image.id"
                    :href="image.image"
                    class="glightbox"
                >
                    <img 
                        :src="image.image" 
                        alt="placeholder"
                        :class="{'border-top-right-radius': image.id / 2 === 1, 'border-bottom-right-radius': image.id / 2 === 2}"
                    />
                </a>
            </div>
        </div>
    </div>
    <div class="container gallary-slide-section"> 
        <swiper
            :slides-per-view="1"
            :space-between="50"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
        >
            <swiper-slide v-for="image in gallery" :key="image.id" class="swiper-slide">
                <img :src="image.image"  alt="placeholder" class="img-fluid" />
                <span class="badge badge-primary">{{ image.id }}/4</span>
            </swiper-slide>
        </swiper>
    </div>
    
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="listing__details--wrapper">
                        <div class="mt-3">
                            <div class="listing__details--content__step mb-50">
                                <h3 class="listing__details--content__title">About this property:</h3>
                                <p class="listing__details--content__desc">
                                    Description It is a long established fact that a reader will be distrac by any websites look for ways
                                    prevent AdBlock from blocking annoying ads. As a result, we've focused on improving our funct
                                    so that we can overcome these anti-ad blocking attempts. Of course, you can help us continue to
                                    improve our ad blocking ability by reporting any time you run into a website that won't allow you
                                    to block the readable content of a page when looking at its layout. It is a long established fact
                                </p>
                                <div class="apartment__info listing__d--info">
                                    <div class="apartment__info--wrapper d-flex">
                                        <div class="apartment__info--list">
                                            <span class="apartment__info--icon">
                                                <img src="/vue-assets/img/icon/bed-realistic.png" alt="img">
                                            </span>
                                            <p>
                                                <span class="apartment__info--count">3</span>
                                                <span class="apartment__info--title">Bedrooms</span>
                                            </p>
                                        </div>
                                        <div class="apartment__info--list">
                                            <span class="apartment__info--icon">
                                                <img src="/vue-assets/img/icon/bathroom.png" alt="img">
                                            </span>
                                            <p>
                                                <span class="apartment__info--count">3</span>
                                                <span class="apartment__info--title"> Bthrooms</span>
                                            </p>
                                        </div>
                                        <div class="apartment__info--list">
                                            <span class="apartment__info--icon"><img src="/vue-assets/img/icon/sleep.png" alt="img"></span>
                                            <p>
                                                <span class="apartment__info--count">3</span>
                                                <span class="apartment__info--title">SLeeps</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="listing__details--content__step properties__amenities mb-50">
                                <h3 class="listing__details--content__title mb-40">Properties Amenities</h3>
                                <div class="properties__amenities--wrapper d-flex">
                                    <ul class="properties__amenities--step">
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Air Conditioning</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Microwave</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Central Heating</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Washer</span>
                                        </li>
                                    </ul>
                                    <ul class="properties__amenities--step">
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Laundry</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Outdoor Shower</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Swimming Pool</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Wifi</span>
                                        </li>
                                    </ul>
                                    <ul class="properties__amenities--step">
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Window Covering</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Refrigerator</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Alarm System</span>
                                        </li>
                                        <li class="properties__amenities--list d-flex align-items-center">
                                            <span class="properties__amenities--mark__icon">
                                                <img src="/vue-assets/img/svg/tick.svg" alt="img">
                                            </span>
                                            <span class="properties__amenities--text">Window Coverings</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr>
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
                                <!-- <VCalendar
                                    v-model="selectedDateRange"
                                    is-range
                                    :min-date="minDate"
                                    :max-date="maxDate"
                                    :disabled-dates="disabledDates"
                                    :attributes="[{ key: 'selected', highlight: true, dates: selectedDateRange }]"
                                    columns="2"
                                    show-weekdays
                                    show-adjacent-months
                                    :nav-buttons="true"
                                /> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="listing__widget">
                        <div class="widget__step mb-30">
                            <h3 class="mb-3 reservation-details">$87 <span>night</span></h3>
                            <div class="date-selection">
                                <div class="checking-dates" @click="rangeCalendarVisible = !rangeCalendarVisible">
                                    <div class="date-item">
                                        <h5>Check-in</h5>
                                        <span>Add date</span>
                                    </div>
                                    <div class="date-item">
                                        <h5>Check-out</h5>
                                        <span>Add date</span>
                                    </div>
                                </div>
                                <div 
                                    v-if="rangeCalendarVisible"
                                    class="range-calendar-dropdown"
                                >
                                    <VDatePicker 
                                        is-range
                                        columns="2"
                                        :min-date="minDate"
                                        :color="selectedColor"
                                    />
                                    <div class="calendar-actions d-flex justify-content-end gap-3 mb-4 mr-5">
                                        <button class="clear-date-btn">Clear dates</button>
                                        <button class="btn btn-dark close-btn" @click="rangeCalendarVisible = false">Close</button>
                                    </div>
                                </div>
                            </div>
                            <div class="guest-details">
                                <div class="guest-details-sec d-flex justify-content-between align-items-center" @click="guestSelectionVisible = !guestSelectionVisible">
                                    <div class="guest-list">
                                        <h5>Guests</h5>
                                        <span>Add date</span>
                                    </div>
                                    <font-awesome-icon 
                                        :icon="guestSelectionVisible ? 'caret-up' : 'caret-down'"
                                    />
                                </div>
                                <div
                                    v-if="guestSelectionVisible"
                                    class="guest-selection-dropdown"
                                >
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <div class="guest-list">
                                            <h5>Adults</h5>
                                            <span>Age 13+</span>
                                        </div>
                                        <div class="increment-decrement d-flex justify-content-center align-items-center gap-3">
                                            <span class="increment"><font-awesome-icon icon="minus" /></span>
                                            <span class="count">1</span>
                                            <span class="decrement"><font-awesome-icon icon="plus" /></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="guest-list">
                                            <h5>Children</h5>
                                            <span>Age >13</span>
                                        </div>
                                        <div class="increment-decrement d-flex justify-content-center align-items-center gap-3">
                                            <span class="increment"><font-awesome-icon icon="minus" /></span>
                                            <span class="count">1</span>
                                            <span class="decrement"><font-awesome-icon icon="plus" /></span>
                                        </div>
                                    </div>
                                    <div class="calendar-actions d-flex justify-content-end gap-3 mt-5 p-0 m-0">
                                        <button class="clear-date-btn" @click="guestSelectionVisible = false">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import GLightbox from 'glightbox';
import { ref } from 'vue';

export default {
    components: {
        Swiper,
        SwiperSlide,
        GLightbox,
    },
    data() {
        return {
            minDate: new Date().toISOString().split('T')[0],
            selectedColor: ref('gray'),
            rangeCalendarVisible: false,
            guestSelectionVisible: false,
            gallery: [
                {
                    id: 1,
                    image: '/vue-assets/img/property/properties1.png',
                },
                {
                    id: 2,
                    image: '/vue-assets/img/property/properties2.png',
                },
                {
                    id: 3,
                    image: '/vue-assets/img/property/properties3.png',
                },
                {
                    id: 4,
                    image: '/vue-assets/img/property/properties4.png',
                },
            ],
        };
    },
    mounted() {
        GLightbox({
            selector: '.glightbox',
            loop: false,
            onOpen: () => console.log('Lightbox opened!'),
            onClose: () => console.log('Lightbox closed!'),
        });
    },
    setup() {
      const onSwiper = (swiper) => {
        console.log(swiper);
      };
      const onSlideChange = () => {
        console.log('slide change');
      };
      return {
        onSwiper,
        onSlideChange,
      };
    },
  };
</script>

<style scoped>
.property-title {
    font-size: 3rem;
    color: inherit;
    font-weight: 500;
}

.gallery {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.large-image {
    grid-column: span 1;
}

.large-image a {
    width: 100%;
}

.large-image img {
    width: 100%;
    height: 350px;
    object-fit: cover;
}

.border-top-left-radius {
    border-top-left-radius: 2rem;
}

.border-bottom-left-radius {
    border-bottom-left-radius: 2rem;
}

.border-top-right-radius {
    border-top-right-radius: 2rem;
}

.border-bottom-right-radius {
    border-bottom-right-radius: 2rem;
}

.img-fluid {
    width: 100%;
    height: auto;
}

.small-images {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.small-images img {
    width: 100%;
    height: auto;
}

.small-images img {
    width: 100%;
    height: 170px;
    object-fit: cover;
}

.gallary-slide-section {
    display: none;
}

.swiper-slide {
    position: relative;
}

.swiper-slide .badge {
    position: absolute;
    top: 24px;
    right: 16px;
    background-color: rgba(34, 34, 34, 0.66) !important;
}

.date-range-sec .days-title {
    font-size: 22px;
}

.date-range {
    color: #6a6a6a;
    font-size: 14px;
}

::v-deep(.vc-container) {
  width: 100%;
  border: none;
  margin-top: 10px;
  z-index: 0;
}

::v-deep(.vc-arrow), ::v-deep(.vc-title) {
  background: none;
}

::v-deep(.vc-arrow) {
    border-radius: 50%;
}

::v-deep(.vc-arrow:focus) {
  outline: none !important;
  box-shadow: none !important;
}

::v-deep(.vc-header) {
    margin-bottom: 15px;
    padding: 0px;
}

::v-deep(.vc-weekdays), ::v-deep(.vc-week) {
    height: 40px;
}

::v-deep(.vc-week div:focus) {
    outline: none !important;
    box-shadow: none !important;
}

:deep(.vc-disabled) {
    position: relative;
    color: red;
}

::v-deep(.vc-disabled::after) {
    content: '';
    position: absolute;
    top: 50%;
    left: 20%;
    right: 20%;
    height: 1px;
    background: red;
    transform: translateY(-50%);
    z-index: 1;
}

.listing__widget {
    position: sticky;
    top: 20px;
}

.reservation-details {
    font-size: 24px;
}

.reservation-details span {
    font-weight: 300;
    font-size: 20px;
}

.checking-dates {
    display: flex;
    justify-content: space-between;
    border: 1px solid #ccc;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
}

.checking-dates .date-item {
    flex-grow: 1;
    padding: 10px;
    cursor: pointer;
}

.checking-dates .date-item:last-child {
    border-left: 1px solid rgb(176, 176, 176) !important;
}

.checking-dates .date-item h5 {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 11px;
}

.checking-dates .date-item span {
    font-size: 13px;
}

.guest-list h5 {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 11px;
}

.guest-list span {
    font-size: 13px;
}

.date-selection {
    position: relative;
}

.date-selection .range-calendar-dropdown {
    background: rgb(255, 255, 255);
    position: absolute;
    top: 56px;
    right: 0px;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 6px 20px;
    border-radius: 1rem;
    width: 600px;
    z-index: 1;
}

.range-calendar-dropdown ::v-deep(.vc-container) {
    z-index: 1 !important;
}

.calendar-actions {
    margin-right: 15px;
}

.calendar-actions .close-btn {
    padding: 5px 18px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 8px;
}

.calendar-actions .clear-date-btn {
    background: transparent;
    border: none;
    border-radius: 8px;
    text-decoration: underline;
    font-size: 15px;
    font-weight: bold;
}

.calendar-actions .clear-date-btn:hover {
    background: #F0F0F0;
}

.guest-details {
    border: 1px solid #ccc;
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
    cursor: pointer;
    position: relative;
}

.guest-details-sec {
    padding: 10px;
}

.guest-selection-dropdown {
    background: rgb(255, 255, 255);
    position: absolute;
    top: 60px;
    right: 0px;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 6px 20px;
    border-radius: 2px;
    width: 100%;
    padding: 20px 17px;
}

.increment-decrement .increment, .increment-decrement .decrement {
    height: 25px;
    width: 25px;
    text-align: center;
    border: 1px solid gray;
    border-radius: 50%;
    font-size: small;
}

@media (max-width: 650px) {
    .gallery-section {
        display: none;
    }

    .gallary-slide-section {
        display: block;
    }
}
</style>