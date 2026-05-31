<?php

return [
    'kicker' => 'About Us',
    'title' => 'Renmote: Malang\'s trusted online motorcycle rental',
    'subtitle' => 'Renmote is a digital platform that connects renters with verified motorcycle rental vendors in Malang. Our goal is simple: make renting a motorcycle safe, transparent, and fast.',

    'mission_title' => 'Our mission',
    'mission_text' => 'Build a fully digitalized motorcycle rental ecosystem that reduces friction in booking and sets a standard for transparent pricing, documents, and communication between renter and vendor.',

    'vision_title' => 'Our vision',
    'vision_text' => 'Become the number one online motorcycle rental platform in Indonesia, trusted by travelers, students, and motorcycle communities for transaction safety, vendor quality, and ease of technology.',

    'values_title' => 'Values we hold',
    'values' => [
        [
            'title' => 'Safe & Verified',
            'desc' => 'Every vendor goes through document verification by admin. Renters upload official ID before their first booking.',
        ],
        [
            'title' => 'Transparent',
            'desc' => 'Prices, fees, and vendor policies are clearly stated. No hidden charges.',
        ],
        [
            'title' => 'Fast & Easy',
            'desc' => 'Book, pay deposit, upload documents, all on one platform. Vendors get automatic notifications.',
        ],
        [
            'title' => 'Fair & Accountable',
            'desc' => 'Clear service standards on both sides. Complaint and refund mechanisms are available when issues arise.',
        ],
    ],

    'stats_title' => 'Renmote at a glance',
    'stat_vendors' => 'registered vendors',
    'stat_vehicles' => 'active motorcycles',
    'stat_bookings' => 'bookings served',
    'stat_districts' => 'districts covered',

    'privacy_title' => 'Privacy Policy',
    'privacy_subtitle' => 'We are committed to protecting the data of renters and vendors who join Renmote.',

    'privacy_sections' => [
        [
            'title' => '1. Data we collect',
            'items' => [
                'Account data: name, email, password (hashed), phone number, gender, date of birth, profile photo.',
                'Identity data: KTP/KTM (renter), KTP + business documents (vendor) for verification.',
                'Transaction data: booking details, payment proof, rental history, delivery address.',
                'Communication data: chat content between renter and vendor for audit if disputes arise.',
                'Technical data: IP address, device type, and session cookies for login security.',
            ],
        ],
        [
            'title' => '2. How we use the data',
            'items' => [
                'Processing bookings, payments, and communications between renters and vendors.',
                'Identity verification to prevent fraud and platform abuse.',
                'Sending important notifications (booking status, payment verification, account updates).',
                'Internal analytics to improve services and develop new features.',
                'Complying with legal obligations if requested by competent authorities.',
            ],
        ],
        [
            'title' => '3. Access and security',
            'items' => [
                'Sensitive documents (KTP, business permits) are only accessible to admin and vendors handling active bookings.',
                'Passwords are stored as bcrypt hashes; the Renmote team cannot view your password.',
                'Payment proofs are protected with role-based access control.',
                'Servers use encrypted connections (HTTPS) for all data transmissions.',
            ],
        ],
        [
            'title' => '4. Your rights as a user',
            'items' => [
                'Request a copy of the personal data we store at any time.',
                'Update or remove account data from the My Account menu.',
                'Permanently deactivate your account via the delete account menu (data will be anonymized).',
                'Submit objections if there\'s misuse of your data.',
            ],
        ],
        [
            'title' => '5. Sharing with third parties',
            'items' => [
                'Midtrans (payment gateway) processes deposit transactions securely.',
                'We do not sell your personal data to any party.',
                'Data sharing only occurs upon a legitimate request from law enforcement.',
            ],
        ],
        [
            'title' => '6. Cookies & tracking',
            'items' => [
                'Session cookies are used to maintain login state and language preference.',
                'No third-party tracking for personalized advertising.',
                'You can disable cookies via your browser, but some features may not work optimally.',
            ],
        ],
    ],

    'contact_title' => 'Privacy questions?',
    'contact_text' => 'If you have questions about our privacy policy or want to exercise your data rights, contact our team:',
    'contact_email' => 'renmotebusiness@gmail.com',
    'contact_whatsapp' => '+62 895-2313-2567',
];
