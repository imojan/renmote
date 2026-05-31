<?php

return [
    'kicker' => 'Help Center',
    'title' => 'FAQ & Help Center',
    'subtitle' => 'Find answers to common questions or reach out to our support team.',

    'tab_renter' => 'Renter',
    'tab_vendor' => 'Vendor',

    'sidebar_title' => 'Need more help?',
    'sidebar_subtitle' => 'Our support team is ready to help you anytime.',
    'whatsapp_label' => 'WhatsApp Support',
    'email_label' => 'Email Support',
    'hours_label' => 'Operating Hours',
    'hours_value' => '24/7 Support',
    'hours_subtitle' => 'Our team is ready to assist around the clock.',

    'tip_title' => 'Quick tip',
    'tip_message' => 'Before chatting support, search the FAQ — your answer might already be here.',

    'renter_groups' => [
        [
            'title' => 'Account & Profile',
            'items' => [
                [
                    'q' => 'How do I create a renter account?',
                    'a' => 'Click Sign Up in the top right, choose the Renter role, then fill in your name, email, and password. After that, you can log in and start booking.',
                ],
                [
                    'q' => 'Do I need to upload my KTP?',
                    'a' => 'Yes, KTP/KTM is required during the first booking for identity verification. Documents are stored securely and only visible to admin and the vendor handling your booking.',
                ],
                [
                    'q' => 'How do I change my profile photo and data?',
                    'a' => 'Open My Account from the topbar, then click the Profile tab. You can edit your name, email, profile photo, gender, birth date, and phone number. Click Save to apply.',
                ],
                [
                    'q' => 'I forgot my password, what now?',
                    'a' => 'On the Sign In page, click Forgot password? then enter your email. A reset link will be sent to your registered email. Click the link to create a new password.',
                ],
            ],
        ],
        [
            'title' => 'Booking & Payment',
            'items' => [
                [
                    'q' => 'How do I book a motorcycle?',
                    'a' => 'Pick a motorcycle on the Home or Search page, click Rent Now, choose the start and end dates, set the fulfillment method (pickup/delivery), upload your documents, then proceed to the 30% deposit payment.',
                ],
                [
                    'q' => 'How much is the deposit?',
                    'a' => 'The deposit is 30% of the total rental fee. The remaining 70% is paid directly to the vendor at handover.',
                ],
                [
                    'q' => 'Which payment methods are supported?',
                    'a' => 'The deposit is paid via Midtrans with options: QRIS, GoPay, ShopeePay, and Virtual Account (BCA, BNI, BRI, Permata). The payment token is valid for 30 minutes.',
                ],
                [
                    'q' => 'My Midtrans token expired, what should I do?',
                    'a' => 'Open your booking\'s payment page and click Re-issue Payment. The system will generate a new invoice and Snap token.',
                ],
                [
                    'q' => 'How do I upload payment proof?',
                    'a' => 'After the deposit is paid, you\'ll be redirected to the upload page. Pick a file (JPG/PNG/PDF up to 6MB), add an optional note, then Submit. Admin/vendor will verify within 1×24 hours.',
                ],
            ],
        ],
        [
            'title' => 'During the Rental',
            'items' => [
                [
                    'q' => 'How do I extend the rental?',
                    'a' => 'Reach out to the vendor via Chat at least 6 hours before the rental ends. The vendor will check unit availability and tell you about extra fees.',
                ],
                [
                    'q' => 'What should I check when receiving the motorcycle?',
                    'a' => 'Check brakes, lights, tires, horn, fuel level, and document the body condition (photo/video). Make sure keys, registration, and helmet are handed over.',
                ],
                [
                    'q' => 'The bike broke down. Who do I contact?',
                    'a' => 'Contact the vendor first via Chat. If they\'re unreachable, contact Renmote admin via the official WhatsApp listed in the footer.',
                ],
                [
                    'q' => 'What if I\'m late returning the bike?',
                    'a' => 'Late returns may incur extra fees per hour or per day per vendor policy. Contact the vendor immediately if there\'s any issue.',
                ],
            ],
        ],
        [
            'title' => 'Cancellation & Refund',
            'items' => [
                [
                    'q' => 'Can I cancel a booking?',
                    'a' => 'Pending bookings can be cancelled directly from the Booking History page. Confirmed bookings need vendor approval to cancel.',
                ],
                [
                    'q' => 'Can my deposit be refunded?',
                    'a' => 'Refunds depend on the vendor\'s policy and booking status. Cancellations during Pending may be refunded, while Confirmed bookings are usually non-refundable.',
                ],
                [
                    'q' => 'How long does the refund take?',
                    'a' => 'Refunds are processed via manual transfer to your account, typically within 3–7 business days depending on the bank.',
                ],
            ],
        ],
    ],

    'vendor_groups' => [
        [
            'title' => 'Vendor Registration',
            'items' => [
                [
                    'q' => 'What are the requirements to become a vendor?',
                    'a' => 'Active KTP, business permit (SIUP/NIB) or business letter from your local subdistrict, profile/location photo, and domicile within Renmote\'s service area (currently Malang and surrounding areas).',
                ],
                [
                    'q' => 'How do I register as a vendor?',
                    'a' => 'Click Become a Vendor in the topbar, sign up with the Vendor role. After login, you\'ll be asked to complete shop data and upload verification documents.',
                ],
                [
                    'q' => 'How long is the verification process?',
                    'a' => 'Admin reviews submissions within 3×24 working hours. Status will change from Pending to Approved or Rejected with a reason.',
                ],
                [
                    'q' => 'My registration was rejected. Can I try again?',
                    'a' => 'Yes. You can edit the registration form with valid documents and resubmit from the same menu.',
                ],
            ],
        ],
        [
            'title' => 'Vehicle Management',
            'items' => [
                [
                    'q' => 'How do I add a motorcycle to the catalog?',
                    'a' => 'Once Approved, open the Vehicles menu in the vendor dashboard, click Add Vehicle, fill in the name, category, daily rate, year, description, and upload a photo.',
                ],
                [
                    'q' => 'Can I update the price anytime?',
                    'a' => 'Yes. Open the Vehicles menu, click Edit on the relevant motorcycle, change the price, then Save. The change applies immediately to new bookings.',
                ],
                [
                    'q' => 'How do I temporarily disable a motorcycle?',
                    'a' => 'Edit the motorcycle and change its status to Unavailable. It will not show up in search results until you reactivate it.',
                ],
            ],
        ],
        [
            'title' => 'Booking Management',
            'items' => [
                [
                    'q' => 'How do I verify a renter\'s payment proof?',
                    'a' => 'Open the booking detail in the vendor dashboard, view the uploaded payment proof, then click Approve (if valid) or Reject with a reason.',
                ],
                [
                    'q' => 'When should I confirm or decline a booking?',
                    'a' => 'Ideally within 1×24 hours after the renter uploads payment proof. Quick confirmation keeps your rating and renters\' trust.',
                ],
                [
                    'q' => 'What if my motorcycle suddenly breaks before the rental day?',
                    'a' => 'Contact the renter ASAP via Chat and notify Renmote admin. The renter is entitled to a 100% deposit refund if the cancellation is purely due to unit conditions.',
                ],
                [
                    'q' => 'Can I export booking reports?',
                    'a' => 'Yes. On the Booking page, click Export Excel. The file will be downloaded containing all bookings matching the active filter.',
                ],
            ],
        ],
        [
            'title' => 'Disbursement',
            'items' => [
                [
                    'q' => 'What is the platform commission?',
                    'a' => 'The platform commission is set during vendor onboarding and detailed in the cooperation agreement. Contact admin for the latest information.',
                ],
                [
                    'q' => 'When does the deposit settle to my account?',
                    'a' => 'The deposit paid by the renter via Midtrans goes into Renmote\'s escrow, then settles to the vendor\'s account per the settlement cycle (typically D+3 after booking completion).',
                ],
                [
                    'q' => 'How do I change my bank account for settlements?',
                    'a' => 'Open the Profile menu in the vendor dashboard, edit Bank Name and Bank Account, then Save. The change will be verified by admin before the next settlement cycle.',
                ],
            ],
        ],
    ],
];
