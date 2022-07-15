<?php

return [
    'channel_manager' => [
        'unknown_period_type' => 'Unknown period type.',
        'channel_update_failed' => 'Failed to update the channel manager.',
        'availability_sync_failed' => 'Failed to synchronise the property availability.',
        'set_reservation_failed' => 'Failed to create/update the reservation.',
        'reservation_delete_failed' => 'Failed to delete the reservation.',
        'set_blocked_date_failed' => 'Failed to create/update the blocked date.',
        'blocked_date_delete_failed' => 'Failed to delete the blocked date.',
    ],
    'access_forbidden' => 'Access forbidden!',
    'account_inactive' => 'The account is deactivated!',
    'invalid_reservation_given' => 'The given reservation is invalid.',
    'invalid_auth_provider' => 'Invalid auth provider.',
    'user_permissions' => 'User does not have the right permissions.',
    'self_delete' => 'You can not delete your own account.',
    'password_reset_token_expired' => 'Recovery link expired.',
    'password_reset_token_invalid' => 'Recovery token is invalid.',
    'zip_make_failed' => 'Failed to make a zip archive.',
    'zip_archive_closed' => 'The zip archive is closed!',
    'tmp_file_creation_failed' => 'Failed to create a temporary file.',
    'one_contact_required' => 'At least one of the contacts(email/phone) must be filled.',
    'landlord_locked' => 'The landlord is locked!',
    'invalid_property_cm_id' => 'The property channel manager ID has invalid format.',
    'channel_manager_credentials_missing' => 'The channel manager credentials are missing.',
    'receivers_not_given' => 'No any receiver given!',
    'email_in_use' => 'The email address is already in use!',
    'phone_in_use' => 'The phone number is already in use!',
    'email_account_not_exists' => 'There is no account with the provided email address.',
    'complaint_processed' => 'Your complaint has already been received and processed.',
    'date_interval_end_lt_start' => 'The end of the interval must be greater than or equal to the start.',
    'inaccessible_property' => 'Property :property doesn\'t exists and cannot be set.',
    'date_interval_invalid_value_type' => 'The value of :property must be an instance of \\Carbon\\Carbon.',
    'calendar_event_invalid_property' => 'The value of property must be an instance of \\App\\Models\\Property.',
    'calendar_event_invalid_details' => 'The value of details must be an array.',
    'invalid_calendar_event_type' => 'Invalid value given for calendar event type.',
    'invalid_calendar_event_id' => 'Invalid value given for calendar event id.',
    'inaccessible_ticket_category' => 'The given category is inaccessible!',
    'main_guest_detach_error' => 'It\'s not allowed to detach the main guest.',
    'main_guest_type_update' => 'It\'s not allowed to change the main guest type.',
    'extension_check_out_greater_reservation' => 'The check-out date should be greater than the reservation check-out.',
    'not_last_extension_check_out_edit' => 'You cannot update the check-out date, as another extension has been added after this one.',
    'not_last_extension_departure_edit' => 'You cannot update the departure date, as another extension has been added after this one.',
    'reservation_check_out_not_matches_last_extension' => 'The reservation check-out date should be equal to the last extension check-out date.',
    'reservation_check_in_not_matches_first_extension' => 'The reservation check-in date should be lower than the first extension check_in date.',
    'reservation_departure_not_matches_last_extension' => 'The reservation departure date should be equal to the last extension departure date.',
    'landlord_contract_overlaps' => 'The given agreement dates are overlapping with existing contract(s).',
    'unit_permit_overlaps' => 'The given permit dates are overlapping with existing ones.',
    'permit_free_interval_overlaps' => 'The given unit permit free interval dates are overlapping with existing ones.',
    'invalid_template' => 'Invalid email template!',
    'multi_submit_error' => 'Multiple submission is not allowed!',
    'no_customer' => 'Customer is not set!',
    'res_property_community_manager_missing' => 'There is no community manager assigned to the reservation property.',
    'res_property_community_manager_portal_reg' => 'The reservation property community manager registration method is portal.',
    'res_property_community_manager_email_reg' => 'The reservation property community manager registration method is email.',
    'res_property_community_manager_email_missing' => 'No email address provided for the community manager assigned to the reservation property.',
    'res_property_community_manager_phone_missing' => 'No phone number provided for the community manager assigned to the reservation property.',
    'res_customer_missing' => 'There is no customer assigned to the reservation.',
    'res_customer_email_missing' => 'No email address provided for the customer assigned to the reservation.',
    'res_customer_phone_missing' => 'No phone number provided for the customer assigned to the reservation.',
    'res_main_guest_missing' => 'There is no main guest assigned to the reservation.',
    'res_main_guest_email_missing' => 'No email address provided for the main guest assigned to the reservation.',
    'res_main_guest_phone_missing' => 'No phone number provided for the main guest assigned to the reservation.',
    'ambiguous_management_fee_options' => 'The given management fee options are ambiguous.',
    'unmatching_property_item' => 'The given item doesn\'t belong to the property.',
    'undefined_form' => 'Undefined form!',
    'undefined_res_reg_param' => 'Undefined or non-editable reservation registration parameter!',
    'gro_not_needed' => 'GRO is not needed for self check-in/out.',
    'invalid_issue_type' => 'Invalid issue type is given.',

    /**
     * System.
     */
    'undefined_repository_filter' => 'Undefined repository filter!',
    'undefined_payment_gateway' => 'Undefined payment gateway!',

    /**
     * Other.
     */
    'internal_error' => 'An internal error occurred.',
    'form_not_submitted' => 'The form is not submitted!',
    'form_submitted' => 'The form is already submitted!',
    'submitted_data_incomplete' => 'The submitted data is incomplete.',
    'max_limit_exceeded' => 'Maximum limit exceeded!',
    'linked_relation_exist' => 'The :resource has linked :relation.',

    /**
     * Inquiry / Quote.
     */
    'inquiry_booked' => 'Inquiry is already booked.',
    'not_inquiry_property' => 'Inquiry has no such property selected.',
    'quote_not_confirmed' => 'Quote is not confirmed!',
    'quote_blocked' => 'Quote blocked!',
    'quote_expired' => 'Quote expired!',
    'quote_booked' => 'The quote has already been booked.',
    'quote_property_not_selected' => 'No any property selected!',
    'quote_offline_booking_not_allowed' => 'Offline booking is not allowed!',
    'property_out_of_quote' => 'Trying to select a property that is not included in the quote.',
    'pre_agreed_service_update' => 'Trying to modify pre agreed additional service.',
    'property_not_available' => 'Property is not available.',
    'property_not_active_or_available' => 'Trying to select a property that is not active and/or available.',
    'blocking_inquiry_with_multiple_properties' => 'The inquiry could not block more than one property.',

    /**
     * Payments.
     */
    'payment_status_not_3d' => 'Payment is not prepared for secure bank authentication!',
    'payment_no_secure_identification' => 'The payment was not securely identified!',
    'transaction_cancelled' => 'The transaction has been cancelled.',
    'payment_link_expired' => 'Bill expired!',
    'bill_paid' => 'The bill has already been paid.',
    'bill_not_paid' => 'The bill is not paid!',
    'bill_cancelled' => 'The bill has been cancelled.',
    'invalid_pmt_response_status' => 'Invalid value provided for the payment response.',

    /**
     * Inventory inspections.
     */
    'inspection_already_exists' => 'An inventory inspection form is already assigned.',
    'inspection_not_exists' => 'There is no inventory inspection form assigned.',
    'item_condition_not_selected' => 'Please select the condition.',
    'item_photo_required' => 'A photo proof should be provided.',
    'no_items_to_inspect' => 'There are no items to create an inspection form!',

    /**
     * Tasks/tickets.
     */
    'system_controlled_task_edit' => 'Unable to modify system controlled task parameters.',
    'system_controlled_task_cancel' => 'Unable to cancel system a controlled task.',

    /**
     * Requests.
     */
    'invalid_data_map' => 'Internal Error! Invalid request data map.',
];
