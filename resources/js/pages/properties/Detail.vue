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
                        <div class="listing__details--content">
                            <div class="listing__details--content__step">
                                <h2 class="listing__details--title mb-25">House In Foxhall Ave, Kingston</h2>
                                <div class="listing__details--price__id d-flex align-items-center">
                                    <div class="listing__details--price d-flex">
                                        <span class="listing__details--price__new">$13000 / Night</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
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
                            <div class="listing__details--content__step properties__amenities mb-80">
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
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="listing__widget">
                        <div class="widget__step mb-30">
                            <h2 class="widget__step--title">Send Messege</h2>
                            <div class="widget__form">
                                <form action="#">
                                    <div class="widget__form--input mb-20">
                                        <input class="widget__form--input__field" placeholder="Full name" type="text">
                                    </div>
                                    <div class="widget__form--input mb-20">
                                        <input class="widget__form--input__field" placeholder="Phone Number" type="text">
                                    </div>
                                    <div class="widget__form--input mb-20">
                                        <input class="widget__form--input__field" placeholder="Email Address" type="email">
                                    </div>
                                    <div class="widget__form--input">
                                        <textarea class="widget__form--textarea__field" placeholder="Write You Messege"></textarea>
                                    </div>
                                    <button class="widget__form--btn solid__btn" type="submit">Send Messege</button>
                                </form>
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

export default {
    components: {
        Swiper,
        SwiperSlide,
        GLightbox,
    },
    data() {
        return {
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

@media (max-width: 650px) {
    .gallery-section {
        display: none;
    }

    .gallary-slide-section {
        display: block;
    }
}
</style>