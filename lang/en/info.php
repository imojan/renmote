<?php

return [
    'guide' => [
        'kicker' => 'Guide',
        'title' => 'How to Rent on Renmote',
        'subtitle' => 'Full booking flow on Renmote, from search to return. Includes 30% down payment via Midtrans and document verification.',

        'steps' => [
            [
                'no' => '01',
                'title' => 'Find a motorcycle that fits',
                'desc' => 'Use the search bar or category filters (automatic, manual, sport, underbone, trail, premium scooter, big bike). Filter by district, rental dates, and price range.',
            ],
            [
                'no' => '02',
                'title' => 'Check unit details and availability',
                'desc' => 'Open the motorcycle page to view photos, description, daily price, vendor location, and existing bookings. Add to wishlist or click Rent Now.',
            ],
            [
                'no' => '03',
                'title' => 'Sign in or create an account',
                'desc' => 'Only registered users can book. Sign up as a Renter using an active email. Don\'t have an account? Click Sign Up at the top right.',
            ],
            [
                'no' => '04',
                'title' => 'Confirm dates and pickup method',
                'desc' => 'Choose start and end rental dates. Pick a fulfillment method: pickup at vendor location or delivery to your address. Add destination address if delivery.',
            ],
            [
                'no' => '05',
                'title' => 'Upload your KTP/KTM (mandatory) and SIM C',
                'desc' => 'KTP/KTM is required for identity verification. SIM C (motorcycle license) is recommended and may be checked by the vendor. Documents are stored securely and only visible to admin/related vendors.',
            ],
            [
                'no' => '06',
                'title' => 'Pay 30% deposit via Midtrans',
                'desc' => 'Continue to deposit payment of 30% of the total via QRIS, GoPay, ShopeePay, or Virtual Account. Snap token is valid for 30 minutes.',
            ],
            [
                'no' => '07',
                'title' => 'Upload payment proof',
                'desc' => 'Once the deposit is paid, upload your payment proof. Admin or vendor will verify within 1×24 hours. Booking status auto-updates to Confirmed once approved.',
            ],
            [
                'no' => '08',
                'title' => 'Pick up the bike & check the condition',
                'desc' => 'On the day, document the bike\'s condition (photo/video of body, tires, lights, fuel) before heading out. Pay the remaining 70% directly to the vendor as agreed.',
            ],
            [
                'no' => '09',
                'title' => 'Return on time & download invoice',
                'desc' => 'Return the motorcycle on schedule to avoid late fees. Invoice PDF is downloadable from the Booking History page for personal records.',
            ],
        ],

        'tips_title' => 'Quick tips for a smooth booking',
        'tips' => [
            'Pick verified vendors (blue badge) with clear ratings and reviews.',
            'Confirm pickup point, operating hours, and extra fees before the day via Chat.',
            'Always use official payment methods recorded by the system to keep a transaction record.',
            'For schedule changes, contact the vendor immediately via Chat to keep your booking safe.',
            'Save the PDF invoice and payment proof until the bike is fully returned.',
        ],

        'reference_title' => 'Content References',
        'reference_text' => 'This guide reflects Renmote\'s operations and common motorcycle rental practices in Indonesia, including the legal driving principle that requires a license matching the vehicle type (UU No. 22/2009, Article 77(1)).',
        'ref_uu_2009' => 'Law No. 22 of 2009 on Traffic and Road Transport (BPK RI)',
        'ref_korlantas' => 'Driver\'s License Info - Korlantas Polri',
        'ref_jdih' => 'JDIH Ministry of Transportation Portal',
    ],

    'terms' => [
        'kicker' => 'Terms & Conditions',
        'title' => 'Renmote Rental T&C',
        'subtitle' => 'Full terms apply to all rental transactions on the Renmote platform, for renters and registered vendors. By using this service, you agree to the terms below.',

        'tab_renter' => 'For Renters',
        'tab_vendor' => 'For Vendors',

        'renter_sections' => [
            [
                'title' => '1. Renter Requirements',
                'items' => [
                    'Must be at least 18 years old or hold an active KTP/KTM.',
                    'Must upload KTP/KTM via the account menu before the first booking.',
                    'An active SIM C (motorcycle license) is strongly recommended and may be requested by the vendor.',
                    'Booking data (name, address, contact) must match your personal identity.',
                ],
            ],
            [
                'title' => '2. Booking & Payment',
                'items' => [
                    'A booking becomes active after the 30% deposit is paid and the proof is verified.',
                    'Deposit is paid through Midtrans (QRIS, GoPay, ShopeePay, or Virtual Account). The remaining 70% is settled at handover.',
                    'Snap payment token is valid for 30 minutes. Once expired, you can re-issue an invoice.',
                    'Vendor and admin reserve the right to reject bookings with invalid documents/payment.',
                ],
            ],
            [
                'title' => '3. Cancellation & Refund',
                'items' => [
                    'Cancellation in Pending status by the renter: deposit may be refunded per vendor policy.',
                    'Cancellation after Confirmed: deposit is typically forfeited because the unit was already blocked for you.',
                    'If the vendor rejects your payment proof, you must re-upload within 1×24 hours.',
                    'Refunds are processed via manual transfer; processing takes 3–7 business days depending on the bank.',
                ],
            ],
            [
                'title' => '4. Vehicle Use',
                'items' => [
                    'The bike may only be used for legal and reasonable activities (no racing, ride-hailing, or illegal use).',
                    'Transferring the unit to a third party without written vendor consent is forbidden.',
                    'Obey traffic signs, speed limits, and the agreed operational area.',
                    'Helmets must be worn by both rider and passenger.',
                ],
            ],
            [
                'title' => '5. Late Returns & Extensions',
                'items' => [
                    'Late returns may incur additional fees per hour or per day per vendor policy.',
                    'Extension requests must be submitted at least 6 hours before the rental ends, via Chat.',
                    'Extensions are approved if the unit has not been booked by another renter.',
                ],
            ],
            [
                'title' => '6. Damage & Loss',
                'items' => [
                    'The renter is responsible for damages caused by negligence during the rental period.',
                    'Loss of the unit, keys, registration, or plates will be handled per legal procedures.',
                    'Repair costs are calculated from official workshop estimates or a joint inspection.',
                    'Renmote does not bear damage costs; responsibility lies between renter and vendor.',
                ],
            ],
        ],

        'vendor_sections' => [
            [
                'title' => '1. Vendor Registration Requirements',
                'items' => [
                    'Active KTP and a business permit document (SIUP/NIB/TDP) or business letter from the local subdistrict office.',
                    'Submit a profile/business location photo for verification.',
                    'The vendor must operate within Renmote\'s service area (currently focused on Malang City and surrounding regions).',
                    'Registration is reviewed within 3×24 working hours. Vendors cannot accept bookings until status is Approved.',
                ],
            ],
            [
                'title' => '2. Vendor Obligations',
                'items' => [
                    'Ensure motorcycles are roadworthy before handing them over.',
                    'Provide helmets and basic safety gear (at least 1 helmet per booking).',
                    'Verify the renter\'s deposit proof within 1×24 hours.',
                    'Confirm or decline bookings before the rental day; ghosting renters is not allowed.',
                    'Keep booking statuses up to date (Confirmed, Completed, Cancelled).',
                ],
            ],
            [
                'title' => '3. Pricing & Platform Commission',
                'items' => [
                    'Vendors set their own daily rental price; pricing is shown transparently to renters.',
                    'Renmote applies a platform commission as agreed during onboarding.',
                    'Payouts to the vendor\'s account follow the active settlement cycle.',
                ],
            ],
            [
                'title' => '4. Unilateral Vendor Cancellation',
                'items' => [
                    'Vendors may not cancel bookings unilaterally without a clear reason.',
                    'Cancellations due to unit conditions (broken/accident) must be reported to the renter and admin.',
                    'Vendors must refund 100% of the deposit for unjustified unilateral cancellations.',
                    'Repeated violations may lead to suspension or vendor status revocation.',
                ],
            ],
            [
                'title' => '5. Legal Responsibility',
                'items' => [
                    'Vendors must keep vehicle documents complete (active STNK, valid plates, paid taxes).',
                    'Damages/losses caused by an unroadworthy bike are the vendor\'s responsibility.',
                    'Vendors must cooperate with renters and authorities in case of accidents.',
                ],
            ],
            [
                'title' => '6. Violations & Sanctions',
                'items' => [
                    'Minor violations (slow verification, slow chat response): written warning.',
                    'Major violations (fraud, unroadworthy bikes, unilateral mark-ups): 7–30 day suspension.',
                    'Severe violations (violence to renters, fake documents): permanent revocation + legal report.',
                ],
            ],
        ],

        'reference_title' => 'Legal References',
        'reference_text' => 'These T&Cs reference Law No. 22 of 2009 on Traffic and Road Transport, the Indonesian Civil Code on lease agreements, and common digital platform practices in Indonesia.',
        'ref_uu_2009' => 'Law No. 22 of 2009 (BPK RI)',
        'ref_korlantas' => 'Driver\'s License & legal driving info - Korlantas Polri',
        'ref_jdih' => 'JDIH Ministry of Transportation for transport regulations',
    ],
];
