<template>
    <div class="listing__widget">
        <div class="widget__step mb-30">
            <h3 class="mb-3 reservation-details">$87 <span>night</span></h3>
            <div class="date-selection">
                <div class="checking-dates" ref="dateRangeOpen" @click="rangeCalendarVisible = !rangeCalendarVisible">
                    <div class="date-item">
                        <h5>Check-in</h5>
                        <span>{{ checkInDate ? $formatDate(checkInDate) : 'Add date' }}</span>
                    </div>
                    <div class="date-item">
                        <h5>Check-out</h5>
                        <span>{{ checkOutDate ? $formatDate(checkOutDate) : 'Add date' }}</span>
                    </div>
                </div>
                <div 
                    v-if="rangeCalendarVisible"
                    class="range-calendar-dropdown"
                    ref="dateRangeSelection"
                >
                    <CustomCalendar 
                        :dateRange="dateRange"
                        :columns="columns"
                        :min-date="minDate"
                        color="gray"
                        :updateRange="updateRange"
                    />
                    <div class="calendar-actions d-flex justify-content-end gap-3 mb-4 mr-5">
                        <button class="clear-date-btn" @click="clearDate">Clear dates</button>
                        <button class="btn btn-dark close-btn" @click="rangeCalendarVisible = false">Close</button>
                    </div>
                </div>
            </div>
            <div class="guest-details">
                <div 
                    class="guest-details-sec d-flex justify-content-between align-items-center"
                    ref="guestDetailsOpen"
                    @click="guestSelectionVisible = !guestSelectionVisible"
                >
                    <div class="guest-list">
                        <h5>Guests</h5>
                        <span v-if="adultCount > 0">{{ adultCount }} Adults</span>
                        <span v-if="childCount > 0">, {{ childCount }} Children</span>
                    </div>
                    <font-awesome-icon :icon="guestSelectionVisible ? 'caret-up' : 'caret-down'" />
                </div>
                <div
                    v-if="guestSelectionVisible"
                    ref="guestDetailsSelection"
                    class="guest-selection-dropdown"
                >
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="guest-list">
                            <h5>Adults</h5>
                            <span>Age 13+</span>
                        </div>
                        <div class="increment-decrement d-flex justify-content-center align-items-center gap-3">
                            <button 
                                class="decrement" 
                                @click="adultDecrement"
                                :disabled="adultCount === 1"
                            >
                                <font-awesome-icon icon="minus" />
                            </button>
                            <span class="count">{{ adultCount }}</span>
                            <button 
                                class="increment"
                                @click="adultIncrement"
                            >
                                <font-awesome-icon icon="plus" />
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="guest-list">
                            <h5>Children</h5>
                            <span>Age >13</span>
                        </div>
                        <div class="increment-decrement d-flex justify-content-center align-items-center gap-3">
                            <button 
                                class="decrement"
                                @click="childDecrement"
                                :disabled="childCount === 0"
                            >
                                <font-awesome-icon icon="minus" />
                            </button>
                            <span class="count">{{ childCount }}</span>
                            <button 
                                class="increment"
                                @click="childIncrement"
                            >
                                <font-awesome-icon icon="plus" />
                            </button>
                        </div>
                    </div>
                    <div class="calendar-actions d-flex justify-content-end gap-3 mt-5 p-0 m-0">
                        <button class="clear-date-btn" @click="guestSelectionVisible = false">Close</button>
                    </div>
                </div>
            </div>
            <div>
                <router-link :to="{ name: 'reservationStep1', params: { id: propertyId } }" 
                class="btn btn-primary w-100 mt-4 reserve-btn">
                    Reserve
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import CustomCalendar from '@/pages/properties/details/_partials/CustomCalendar.vue';
import { useRouter } from 'vue-router';

export default {
    name: 'ReservationSummary',
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
    data() {
        return {
            rangeCalendarVisible: false,
            guestSelectionVisible: false,
            adultCount: 1,
            childCount: 0,
            propertyId: this.$route.params.id,
        };
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    methods: {
        adultIncrement() {
            this.adultCount++;
        },
        adultDecrement() {
            if (this.adultCount > 1) {
                this.adultCount--;
            }
        },
        childIncrement() {
            this.childCount++;
        },
        childDecrement() {
            if (this.childCount > 0) {
                this.childCount--;
            }
        },
        clearDate() {
            this.checkInDate = '';
            this.checkOutDate = '';
            this.range = [];
        },
        handleClickOutside(event) {
            const dateRangeOpen = this.$refs.dateRangeOpen;
            const dateRangeSelection = this.$refs.dateRangeSelection;

            if (dateRangeSelection && !dateRangeSelection.contains(event.target) &&
                !dateRangeOpen.contains(event.target)) {
                this.rangeCalendarVisible = false;
            }
            
            const guestDetailsOpen = this.$refs.guestDetailsOpen;
            const guestDetailsSelection = this.$refs.guestDetailsSelection;
            
            if (guestDetailsSelection && !guestDetailsSelection.contains(event.target) &&
                !guestDetailsOpen.contains(event.target)) {
                this.guestSelectionVisible = false;
            }
        },
    },
    setup() {
        const router = useRouter();
        return { router }
    },
}
</script>

<style scoped>
.guest-list h5 {
    text-transform: uppercase;
    font-weight: 700;
    font-size: 11px;
}
  
.guest-list span {
    font-size: 13px;
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

.reserve-btn {
    background: var(--color-hover) !important;
    border: none;
    padding: 13px;
    font-size: 18px;
    border-radius: 6px;
}

.reserve-btn:focus {
    box-shadow: none;
    outline: none;
}
</style>