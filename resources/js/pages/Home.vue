<template>
    <div class="container">
        <h2 class="section__heading--title text-center mb-5">Our Properties</h2>
    </div>
    
    <div class="container">
        <div class="listing__main--content">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="grid">
                    <div class="listing__featured--grid">
                        <div class="row mb--n30">
                            <div class="col-lg-3 col-md-3 col-sm-6 mb-30" v-for="(property, i) in properties" :key="i">
                                <article class="featured__card">
                                    <div class="featured__thumbnail position-relative">
                                        <div class="media">
                                            <router-link :to="{ name: 'propertyDetail', params: { id: property.id } }" class="featured__thumbnail--link">
                                                <img 
                                                    class="featured__thumbnail--img"
                                                    :src="property.image"
                                                    alt="featured-img"
                                                />
                                            </router-link>
                                        </div>
                                        <div class="featured__badge">
                                            <span class="badge__field">
                                                <font-awesome-icon icon="star" />
                                                {{ property.rating }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="featured__content">
                                        <div class="featured__content--top d-flex align-items-center justify-content-between">
                                            <h3 class="featured__card--title">
                                                <router-link :to="{ name: 'propertyDetail', params: { id: property.id } }">
                                                    {{ $truncateText(property.title, 20) }}
                                                </router-link>
                                            </h3>
                                        </div>
                                        <div class="featured__content--price d-flex align-items-center justify-content-between">
                                            <span class="featured__card--price">{{ property.price }}</span>
                                            <small class="featured__card--category">{{ property.category }}</small>
                                        </div>
                                        <ul class="featured__info d-flex">
                                            <li class="featured__info--items">
                                                <span class="featured__info--icon">
                                                    {{ property.bedrooms }}
                                                    <img src="/vue-assets/img/svg/bedroom.svg" alt="Icon" />
                                                </span>
                                                <span class="featured__info--text">Bedrooms</span>
                                            </li>
                                            <li class="featured__info--items">
                                                <span class="featured__info--icon">
                                                    {{ property.bathrooms }}
                                                    <img src="/vue-assets/img/svg/bathroom.svg" alt="Icon" />
                                                </span>
                                                <span class="featured__info--text">Bathrooms</span>
                                            </li>
                                            <li class="featured__info--items">
                                                <span class="featured__info--icon">
                                                    {{ property.sleeps }}
                                                    <img src="/vue-assets/img/svg/sleep.svg" alt="Icon" />
                                                </span>
                                                <span class="featured__info--text">Sleeps</span>
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // show load more button -->
            <div class="text-center mt-5">
                <button class="reviews__comment--btn solid__btn">Load More!</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Home',
    data() {
        return {
            properties: [
                {
                    id: 1,
                    title: 'Luxury Family Home',
                    price: '$130.00 / Night',
                    rating: 4,
                    bedrooms: 3,
                    bathrooms: 3,
                    sleeps: 2,
                    image: '/vue-assets/img/property/properties1.png',
                    category: 'Family'
                },
                {
                    id: 2,
                    title: 'Cozy Cottage Retreat',
                    price: '$95.00 / Night',
                    rating: 5,
                    bedrooms: 2,
                    bathrooms: 1,
                    sleeps: 4,
                    image: '/vue-assets/img/property/properties2.png',
                    category: 'Cottage'
                },
                {
                    id: 3,
                    title: 'Modern Apartment Downtown',
                    price: '$150.00 / Night',
                    rating: 4,
                    bedrooms: 2,
                    bathrooms: 2,
                    sleeps: 3,
                    image: '/vue-assets/img/property/properties3.png',
                    category: 'Apartment'
                },
                {
                    id: 4,
                    title: 'Beachfront Villa',
                    price: '$250.00 / Night',
                    rating: 5,
                    bedrooms: 4,
                    bathrooms: 3,
                    sleeps: 8,
                    image: '/vue-assets/img/property/properties4.png',
                    category: 'Villa'
                },
                {
                    id: 5,
                    title: 'Charming Log Cabin',
                    price: '$110.00 / Night',
                    rating: 4,
                    bedrooms: 2,
                    bathrooms: 1,
                    sleeps: 5,
                    image: '/vue-assets/img/property/properties5.png',
                    category: 'Cabin'
                },
                {
                    id: 6,
                    title: 'Urban Loft with Skyline Views',
                    price: '$175.00 / Night',
                    rating: 4,
                    bedrooms: 1,
                    bathrooms: 1,
                    sleeps: 2,
                    image: '/vue-assets/img/property/properties6.png',
                    category: 'Loft'
                },
                {
                    id: 7,
                    title: 'Spacious Family Retreat',
                    price: '$200.00 / Night',
                    rating: 5,
                    bedrooms: 5,
                    bathrooms: 4,
                    sleeps: 10,
                    image: '/vue-assets/img/property/properties7.png',
                    category: 'Family'
                },
                {
                    id: 8,
                    title: 'Chic Studio in the City',
                    price: '$85.00 / Night',
                    rating: 4,
                    bedrooms: 0,
                    bathrooms: 1,
                    sleeps: 2,
                    image: '/vue-assets/img/property/properties8.png',
                    category: 'Studio'
                },
                {
                    id: 9,
                    title: 'Countryside Farmhouse',
                    price: '$120.00 / Night',
                    rating: 5,
                    bedrooms: 3,
                    bathrooms: 2,
                    sleeps: 6,
                    image: '/vue-assets/img/property/properties1.png',
                    category: 'Farmhouse'
                },
                {
                    id: 10,
                    title: 'Elegant Penthouse Suite',
                    price: '$300.00 / Night',
                    rating: 5,
                    bedrooms: 3,
                    bathrooms: 3,
                    sleeps: 5,
                    image: '/vue-assets/img/property/properties2.png',
                    category: 'Penthouse'
                }
            ]
        }
    },
}
</script>

<style scoped>
.featured__card {
    box-shadow: 0 2px 20px 0 rgba(0, 0, 0, 0.06);
    border-radius: 2rem !important;
}
.featured__badge {
  /* visibility: hidden;
  opacity: 0;
  transition: opacity 0.3s ease; */
  top: 0;
  left: 0;
}

/* .featured__thumbnail:hover .featured__badge {
  visibility: visible;
  opacity: 1;
} */
 
.badge__field {
    border-bottom-left-radius: 0;
    border-top-right-radius: 0;    
}

.section__heading--title {
    margin-top: 50px;
    font-size: 30px;
    font-weight: 700;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.featured__content--top {
    margin-bottom: 5px !important;
}
.featured__card--title a:hover {
    color: #007bff !important;
}
.featured__thumbnail--link {
    border-radius: 0 !important;
    border-top-left-radius: 2rem !important;
    border-top-right-radius: 2rem !important;
    width: 100%;
}
.featured__thumbnail--img {
    border-radius: 0 !important;
    height: 280px;
    width: 100%;
}
.featured__content--price {
    margin-bottom: 15px !important;
}
.featured__card--price {
    font-size: 1.6rem !important;
}
</style>