-- RESTAURAÇÃO SELETIVA DE TRADUÇÕES PERDIDAS
-- Convertendo dados em árabe (179) para inglês (176)

-- 1. FAQs em inglês (traduzidos do árabe)
INSERT IGNORE INTO faqs (language_id, question, answer, serial_number) VALUES
(176, 'What are the main features of the platform?', 'Our platform offers easy-to-use dashboard, secure payments, inventory management, customer analytics, and 24/7 support to help grow your business.', 8),
(176, 'How do I get started with my online store?', 'Simply sign up, choose a template, add your products, set up payment methods, and launch your store. Our step-by-step guide makes it easy.', 9),
(176, 'Is my data secure on the platform?', 'Yes, we use bank-level security with SSL encryption and follow industry best practices to protect your business and customer data.', 10),
(176, 'What payment methods can I offer customers?', 'We support credit cards, PayPal, Stripe, and many other payment gateways to give your customers flexible payment options.', 11),
(176, 'Can I track my sales performance?', 'Yes, our built-in analytics tools help you track sales, monitor customer behavior, and gain insights to grow your business with real-time reports.', 12),
(176, 'Do you provide support when needed?', 'Absolutely! Our support team is available 24/7 to help with any questions or issues. We also have a comprehensive help center.', 13),
(176, 'Can I scale my store as my business grows?', 'Yes! Our platform is designed to grow with your business. You can easily add more products, users, and features as you expand.', 14);

-- 2. Features em inglês 
INSERT IGNORE INTO features (language_id, icon, title, text, serial_number) VALUES
(176, 'fas fa-chart-line', 'Analytics Dashboard', 'Get detailed insights into your sales, customer behavior, and business performance with our comprehensive analytics tools.', 5),
(176, 'fas fa-mobile-alt', 'Mobile Responsive', 'Your store looks perfect on all devices. Our templates are fully responsive and optimized for mobile shopping.', 6),
(176, 'fas fa-shipping-fast', 'Fast Shipping', 'Integrate with multiple shipping providers to offer fast and reliable delivery options to your customers.', 7),
(176, 'fas fa-headset', 'Premium Support', 'Get expert help when you need it with our dedicated support team available around the clock.', 8);

-- 3. Processes em inglês
INSERT IGNORE INTO processes (language_id, icon, color, title, text, serial_number) VALUES
(176, 'fas fa-user-plus', '4A90E2', 'Create Account', 'Sign up for a new account and get access to all platform features to start building your online presence.', 9),
(176, 'fas fa-store', '50E3C2', 'Setup Store', 'Configure your store settings, upload your logo, and customize your brand to create a unique shopping experience.', 10),
(176, 'fas fa-rocket', '7ED321', 'Launch Business', 'Go live with your store and start selling to customers worldwide with our powerful e-commerce platform.', 11);

-- 4. Counter Information em inglês
INSERT IGNORE INTO counter_information (language_id, user_id, icon, color, amount, title, created_at, updated_at) VALUES
(176, NULL, 'fas fa-globe', 'E3F2FD', '150', 'Countries Served', NOW(), NOW()),
(176, NULL, 'fas fa-award', 'FFF3E0', '98', 'Customer Satisfaction', NOW(), NOW()),
(176, NULL, 'fas fa-clock', 'F3E5F5', '24/7', 'Support Available', NOW(), NOW());

-- 5. Testimonials em inglês
INSERT IGNORE INTO testimonials (language_id, main_image, image, comment, name, designation, serial_number, created_at, updated_at) VALUES
(176, NULL, 'testimonial-en-1.png', 'This platform transformed my business! The user-friendly interface made managing my store a breeze, and customer support is always there when I need help. Highly recommend to any online seller!', 'John Smith', 'Small Business Owner', 7, NOW(), NOW()),
(176, NULL, 'testimonial-en-2.png', 'Since switching to this platform, our sales increased by 30%! The payment gateway integration and analytics tools gave us the insights we needed to grow.', 'Emily Chen', 'E-commerce Entrepreneur', 8, NOW(), NOW()),
(176, NULL, 'testimonial-en-3.png', 'I love how scalable the platform is. As my business grew, I never had to worry about upgrading systems or losing control. Everything just works, and the support team is amazing!', 'Robert Johnson', 'Fashion Retailer', 9, NOW(), NOW());

-- 6. ULinks em inglês
INSERT IGNORE INTO ulinks (language_id, name, url, created_at, updated_at) VALUES
(176, 'Help Center', '/help', NOW(), NOW()),
(176, 'API Documentation', '/docs', NOW(), NOW()),
(176, 'Developer Resources', '/developers', NOW(), NOW()),
(176, 'Status Page', '/status', NOW(), NOW());