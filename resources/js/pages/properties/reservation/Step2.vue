<template>
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="listing__details--wrapper">
                        <div class="listing__details--content__step mb-50">
                            <h3 class="listing__details--content__title mb-5">
                                Payment Information
                            </h3>
                            <div class="d-flex flex-column">
                                <div class="row">
                                    <div class="col-md-7 mb-4">
                                        <Card>
                                            <template #header>
                                                <h4 class="bg-light p-4 font-weight-normal">Reservation Total: <span class="text-primary">$160.00</span></h4>
                                                <CardInfoText class="pl-3" />
                                            </template>
                                            <template #content>
                                                <div class="row">
                                                    <div class="col-md-12 card-number mb-3">
                                                        <div class="p-float-label">
                                                            <InputMask 
                                                                id="creditCardNo" 
                                                                name="creditCardNo" 
                                                                mask="9999 9999 9999 9999" 
                                                                :unmask="true"  
                                                                @click="moveCursorToStart" 
                                                                autocomplete="creditCardNo" 
                                                                tabindex="1" 
                                                                :autoClear="false"
                                                                class="input-style"
                                                                :tabindex="1"
                                                            />
                                                            <label for="creditCardNo" class="label-style">
                                                                Credit Card # <span class="required-asterik">*</span>
                                                            </label>
                                                        </div>
                                                        <img src="@public/img/creditcards.png" width="130" height="24" class="cards-image">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <div class="p-float-label">
                                                            <Dropdown 
                                                                id="creditCardMonth"
                                                                :options="months" 
                                                                optionValue="value" 
                                                                optionLabel="value" 
                                                                placeholder="--Select--" 
                                                                class="p-inputtext-lg input-style" 
                                                                style="width: 100%;" 
                                                                :tabindex="2" 
                                                            />
                                                            <label for="creditCardMonth" class="label-style">
                                                                MM <span class="required-asterik">*</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="p-float-label">
                                                            <Dropdown 
                                                                id="creditCardYear"
                                                                :options="creditCardYears" 
                                                                optionValue="value" 
                                                                optionLabel="value" 
                                                                placeholder="--Select--" 
                                                                class="p-inputtext-lg input-style" 
                                                                style="width: 100%;" 
                                                                :tabindex="3" 
                                                            />
                                                            <label for="creditCardYear" class="label-style">
                                                                YY <span class="required-asterik">*</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="p-float-label">
                                                            <InputText 
                                                                autocomplete="creditCardCvv" 
                                                                id="creditCardCvv" 
                                                                name="creditCardCvv" 
                                                                type="text"
                                                                :maxlength="16" 
                                                                class="p-inputtext-lg input-style" 
                                                                tabindex="4"
                                                            />
                                                            <label for="creditCardCvv" class="label-style">CVV <span class="required-asterik">*</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </Card>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="d-flex align-items-center">
                                            <Checkbox 
                                                checked="" 
                                                id="acceptTermsConditionCheckbox" 
                                                v-model="acceptTermsConditionCheckbox" 
                                                :binary="true"
                                                :tabindex="6" 
                                            />
                                            <label for="acceptTermsConditionCheckbox" class="ms-2 label-style">
                                                I accept Terms & Conditions and Privacy Policy
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="d-flex align-items-center">
                                            <Checkbox 
                                                checked="" 
                                                id="newsletterCheckbox" 
                                                v-model="newsletterCheckbox" 
                                                :binary="true"
                                                :tabindex="6" 
                                            />
                                            <label for="newsletterCheckbox" class="ms-2 label-style">
                                                I accept Terms & Conditions and Privacy Policy
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center gap-4">
                                            <router-link :to="{ name: 'reservationStep1', params: { id: propertyId } }" class="btn btn-primary mt-4 general-btn">
                                                <font-awesome-icon icon="angle-left" class="back-icon" /> Back
                                            </router-link>
                                            
                                            <button class="btn btn-primary mt-4 general-btn">
                                                <font-awesome-icon icon="check" class="next-icon" /> Reserve
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Check In:</h4>
                                    <span class="fw-bold fs-5">10/10/2024</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Check Out:</h4>
                                    <span class="fw-bold fs-5">10/10/2024</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Duration:</h4>
                                    <span class="fw-bold fs-5">2 Days</span>
                                </div>
                            </div>
                            <hr />
                            <div class="reservation-details">
                                <h3 class="mb-4">Payment Details</h3>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Lodging amount:</h4>
                                    <span class="fw-bold fs-5">$300.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Lodgers Tax:(2)%:</h4>
                                    <span class="fw-bold fs-5">$6.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Sales Tax:(5.5)%:</h4>
                                    <span class="fw-bold fs-5">$16.50</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Cleaning Fee:</h4>
                                    <span class="fw-bold fs-5">$150.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Sub Total:</h4>
                                    <span class="fw-bold fs-5">$472.50</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Discount:</h4>
                                    <span class="fw-bold fs-5">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <h4 class="fw-bold">Total:</h4>
                                    <span class="fw-bold fs-5">$472.50</span>
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
import { useRouter } from 'vue-router';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import InputMask from 'primevue/inputmask';
import Card from 'primevue/card';
import CardInfoText from "@/pages/properties/reservation/_partials/CardInfoText.vue";

export default {
    name: 'Step2',
    components: {
        InputText,
        Dropdown,
        Checkbox,
        Button,
        InputMask,
        Card,
        CardInfoText
    },
    data() {
        return {
            propertyId: this.$route.params.id,
            months: [
                { value: '01' },
                { value: '02' },
                { value: '03' },
                { value: '04' },
                { value: '05' },
                { value: '06' },
                { value: '07' },
                { value: '08' },
                { value: '09' },
                { value: '10' },
                { value: '11' },
                { value: '12' },
            ],
            creditCardYears: [
                { value: '2022' },
                { value: '2023' },
                { value: '2024' },
                { value: '2025' },
                { value: '2026' },
                { value: '2027' },
                { value: '2028' },
                { value: '2029' },
                { value: '2030' },
            ],
            acceptTermsConditionCheckbox: true,
            newsletterCheckbox: false,
        };
    },
    methods: {
        moveCursorToStart(event) {
            if (event.target.selectionStart !== 0) {
                // Move cursor to the start of the input field only if it's not already at the start
                event.target.selectionStart = event.target.selectionEnd = 0;
            }
        },
    },
    setup() {
        const router = useRouter();
        return { router }
    },
}
</script>

<style lang="scss">
    .listing__widget {
        position: sticky;
        top: 50px;
        
        .widget__step {
            padding-top: 1rem !important;
        }
        
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
    }
    
    .card-number {
        position: relative;
        
        .cards-image {
            position: absolute;
            bottom: 8px;
            right: 29px;
        }
    }
    
    .input-style {
        width: 100%;
        height: 40px;
        font-size: 1.25rem !important;
    }
    
    .required-asterik {
        color: red;
    }
    
    .label-style {
        color: rgb(102, 102, 102);
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