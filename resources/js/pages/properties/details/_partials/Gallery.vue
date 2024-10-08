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
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import GLightbox from 'glightbox';

export default {
    name: 'Gallery',
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
        }
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
}
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