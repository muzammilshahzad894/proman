<template>
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="listing__details--wrapper">
                        <div class="listing__details--content__step mb-50">
                            <h3 class="listing__details--content__title mb-5">
                                Billing Details
                            </h3>
                            <form class="add__property--form" action="#">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                First Name <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                Last Name <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                Address <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                City <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2"> 
                                                State <span class="text-danger">*</span>
                                            </label>
                                            <div class="select position-relative">
                                                <select class="add__listing--form__select">
                                                    <option selected="" value="1">State 1</option>
                                                    <option value="2">State 3</option>
                                                    <option value="3">State 4</option>
                                                    <option value="4">State 5</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                Zip <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                Email <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                Confirm Email <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                Mobile Phone <span class="text-danger">*</span>
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="add__listing--input__box mb-20">
                                            <label class="add__listing--input__label mb-2">
                                                Coupon
                                            </label>
                                            <input class="add__listing--input__field" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center gap-4">
                                            <router-link to="/reservation/step2" class="btn btn-primary mt-4 general-btn">
                                                <font-awesome-icon icon="angle-left" class="back-icon" /> Back
                                            </router-link>
                                            <router-link to="/reservation/step2" class="btn btn-primary mt-4 general-btn">
                                                <font-awesome-icon icon="angle-right" class="next-icon" /> Next
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="listing__widget">
                        <div class="widget__step mb-30">
                            <div class="reservation-summary mb-30">
                                <div class="reservation-summary-image">
                                    <img src="/vue-assets/img/property/properties1.png" alt="property" class="img-fluid" />
                                </div>
                                <div class="reservation-summary-content">
                                    <h3 class="reservation-summary-title">Stylish ensuite double bedroom in trendy Dalston</h3>
                                    <h3 class="property-category fw-normal fs-4">Category: <span class="fw-lighter fs-5">Home/Town</span></h3>
                                    <h3 class="property-rating fw-normal fs-5">
                                        <font-awesome-icon icon="star" /><span class="ms-2">4.5</span>
                                    </h3>
                                </div>
                            </div>
                            <hr />
                            <div class="reservation-details">
                                <h3 class="mb-4">Reservation Details</h3>
                                <div class="checkin-checkout-dates mt-2">
                                    <p class="d-flex justify-content-between check-in m-0">
                                        <span>CHECK IN:</span>
                                        <span class="fw-normal">10/10/2024</span>
                                    </p>
                                    <p class="d-flex justify-content-between check-out m-0">
                                        <span>CHECK OUT:</span>
                                        <span class="fw-normal">10/10/2024</span>
                                    </p>
                                </div>
                            </div>    
                            <hr class="mt-5">
                            <p class="fw-bold summary-note mt-3">Lodging Amount $300.00 (Taxes, fees, etc will be displayed on next page).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'Step1',
}
</script>

<style lang="scss">
    .listing__widget {
        position: sticky;
        top: 50px;
        
        .reservation-summary {
            display: flex;
            gap: 10px;
            
            .reservation-summary-image {
                height: 100px;
                width: 106px;
                min-height: 100px;
                min-width: 106px;
                
                img {
                    border-radius: 10px;
                    width: 100%;
                    height: 100%;
                }
            }
            
            .reservation-summary-content {
                .reservation-summary-title {
                    font-size: 16px;
                    line-height: 24px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-height: 50px;
                }
                
                .property-category {
                    max-height: 25px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                
                .property-rating {
                    max-height: 25px;
                }
            }
        }
        
        .reservation-details {
            .check-in span, .check-out span {
                font-size: 15px;
                font-weight: 700;
            } 
        }
        
        .summary-note {
            line-height: 20px;
            font-size: 13px;
        }
    }
    
    .general-btn {
        width: 100% !important;
        position: relative;
        font-size: 16px;
        
        .back-icon {
            position: absolute;
            left: 10px;
            top: 12px;
            font-size: 13px;
        }
        
        .next-icon {
            position: absolute;
            right: 10px;
            top: 12px;
            font-size: 13px;
        }
    }
</style>