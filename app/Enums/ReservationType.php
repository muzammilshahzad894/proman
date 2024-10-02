<?php

namespace App\Enums;

enum ReservationType: string
{
    case TYPE_PENDING_RESERVATION = 'Pending Reservation';
    case TYPE_BOOKED_RESERVATION = 'Booked Reservation';
    case TYPE_BOOKED_RESERVATION_FULL_PAYMENT = 'Booked Reservation (Full Payment)';
    case TYPE_RECEIPT_OF_DEPOSIT = 'Receipt of Deposit';
    case TYPE_FINAL_RECEIPT_AND_DIRECTIONS = 'Final Receipt and Directions';
    case TYPE_OWNER_CONFIRMATION = 'Owner Confirmation';
    case TYPE_HOUSEKEEPER_JOB_ASSIGNED = 'Housekeeper job assigned';
    case TYPE_MANAGER_ALERT = 'Manager Alert';
    case TYPE_BOOKED_RESERVATION_ACCEPT_TERMS_CONDITIONS = 'Booked Reservation- Accept Terms & Conditions';
    case TYPE_BOOKED_RESERVATION_DEPOSIT_RECEIPT = 'Booked Reservation - 50% Deposit Receipt';
    case TYPE_POWNER_SELF_BOOKED_CONFIRMATION = 'Owner "Self Booked" Confirmation';
    case TYPE_RESERVATION_BALANCE_PAYMENT_IS_DUE = 'Reservation balance payment is due';
    case TYPE_PAYMENT_PROCESS_EXISTING_RESERVATION = 'Payment Process Existing Reservation';
    case OWNER_LOGIN_DETAILS = 'Owner login details';
    case CANCELLED_RESERVATION = 'Cancelled Reservation';
}
