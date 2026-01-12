<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import dashboard from "@/media/dashboard.png";
import featuresBg from "@/media/features-2-bg.png";
import txSuccess from "@/media/txn-success.png";
import featuresColumn from "@/media/features-column.png";

import logo1 from "@/media/customer-logo-1.png";
import logo2 from "@/media/customer-logo-2.png";
import logo3 from "@/media/customer-logo-3.png";
import logo4 from "@/media/customer-logo-4.png";
import logo5 from "@/media/customer-logo-5.png";
import {ref} from "vue";

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    merchantCount: {
        type: Number,
        default: 100,
    },
});

const billingPeriod = ref('monthly');
const currentTestimonial = ref(0);

// Free plan features
const freeFeatures = [
    'Up to 10 customers',
    'Up to 10 products & services',
    'Up to 10 invoices',
    'Crypto payments (ADA via Blockfrost)',
    'Fiat payments (Stripe currencies)',
    'Basic reporting',
    'Export data',
    'Basic support',
];

const freeLimitations = [
    'No API access',
    'No webhook notifications',
];

// Pro plan features
const proFeatures = [
    'Unlimited customers',
    'Unlimited products & services',
    'Unlimited invoices',
    'Crypto payments (ADA via Blockfrost)',
    'Fiat payments (Stripe currencies)',
    'Advanced reporting',
    'Export data',
    'Full API access',
    'Webhook notifications',
    'Priority support',
];

// Testimonials with pravatar.cc avatars
const testimonials = [
    {
        text: '«Mercury has revolutionized how we handle transactions at TechForward Inc. Its support for multiple cryptocurrencies has expanded our customer base. I highly recommend Mercury to any business looking to integrate secure and efficient crypto payments»',
        author: 'Jessica Lin',
        role: 'CTO, TechForward Inc.',
        avatar: 'https://i.pravatar.cc/150?img=5',
        logo: logo4,
    },
    {
        text: '«The integration was seamless and the dashboard is incredibly intuitive. We went from zero crypto payments to processing thousands in just weeks. The Blockfrost integration for ADA is rock solid.»',
        author: 'Marcus Chen',
        role: 'Founder, CryptoMart',
        avatar: 'https://i.pravatar.cc/150?img=12',
        logo: logo1,
    },
    {
        text: '«As a small business owner, I was skeptical about accepting crypto. Mercury made it simple and secure. The reporting features help me track everything effortlessly.»',
        author: 'Sarah Mitchell',
        role: 'Owner, Artisan Goods Co.',
        avatar: 'https://i.pravatar.cc/150?img=9',
        logo: logo2,
    },
    {
        text: '«The webhook notifications are a game-changer for our automated systems. We can now process orders instantly when payments come through. Excellent API documentation too!»',
        author: 'David Park',
        role: 'Lead Developer, ShopFlow',
        avatar: 'https://i.pravatar.cc/150?img=15',
        logo: logo3,
    },
    {
        text: '«Customer support is exceptional. They helped us set up everything and were available whenever we had questions. The platform just works, and that is exactly what we need.»',
        author: 'Emma Rodriguez',
        role: 'Operations Manager, GlobalTech',
        avatar: 'https://i.pravatar.cc/150?img=23',
        logo: logo5,
    },
];
</script>

<template>
    <GuestLayout title="Welcome">
        <!-- Hero Section -->
        <section id="hero" class="hero-section">
            <v-container class="py-16">
                <v-row justify="center">
                    <v-col cols="12" class="text-center">
                        <v-chip
                            variant="flat"
                            color="primary"
                            class="hero-badge mb-6"
                            size="small"
                        >
                            <span class="font-weight-medium">{{ merchantCount }}+ merchants sell with Mercury!</span>
                        </v-chip>

                        <h1 class="hero-title mb-4">
                            Empower Your Business<br>
                            with Crypto Payments
                        </h1>

                        <p class="hero-subtitle mb-8">
                            Start accepting cryptocurrency and reach a global audience
                        </p>

                        <div class="d-flex justify-center ga-3 mb-12">
                            <v-btn
                                variant="outlined"
                                color="dark"
                                size="large"
                                rounded="pill"
                                href="#features"
                            >
                                Learn more
                            </v-btn>
                            <v-btn
                                color="primary"
                                variant="flat"
                                size="large"
                                rounded="pill"
                                :href="route('register')"
                            >
                                Get started
                            </v-btn>
                        </div>

                        <!-- Dashboard Preview -->
                        <div class="dashboard-wrapper">
                            <img :src="dashboard" alt="Dashboard Preview" class="dashboard-image" />
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Features Section -->
        <section id="features" class="features-section">
            <v-parallax :src="featuresBg" class="features-parallax">
                <v-container class="py-16">
                    <!-- First Feature Row -->
                    <v-row align="center" class="mb-16">
                        <v-col cols="12" md="6" lg="5">
                            <v-chip variant="flat" color="primary" size="small" class="mb-4">
                                <v-icon start icon="mdi-star" size="small" />
                                Features
                            </v-chip>

                            <h2 class="section-title mb-4">
                                Experience the Future of<br>
                                Payment Processing
                            </h2>

                            <p class="section-subtitle mb-8">
                                Our SaaS platform provides secure, easy-to-use, and low transaction fee
                                solutions for accepting cryptocurrency payments.
                            </p>

                            <v-row class="mb-8">
                                <v-col cols="12" sm="6">
                                    <h4 class="feature-title mb-2">Security</h4>
                                    <p class="feature-text">
                                        Protect your transactions with our advanced security measures and
                                        encryption technology.
                                    </p>
                                </v-col>
                                <v-col cols="12" sm="6">
                                    <h4 class="feature-title mb-2">Ease of Use</h4>
                                    <p class="feature-text">
                                        Our user-friendly interface makes it simple to integrate and manage
                                        cryptocurrency payments.
                                    </p>
                                </v-col>
                            </v-row>

                            <v-btn
                                color="dark"
                                variant="flat"
                                size="large"
                                rounded="pill"
                                :href="route('register')"
                            >
                                Get started
                            </v-btn>
                        </v-col>

                        <v-col cols="12" md="6" lg="7" class="d-none d-md-flex justify-end">
                            <img :src="txSuccess" alt="Transaction Success" class="feature-image" />
                        </v-col>
                    </v-row>

                    <!-- Second Feature Row -->
                    <v-row align="center">
                        <v-col cols="12" md="6" lg="5">
                            <div class="feature-list-card">
                                <div class="feature-list-item">
                                    <div class="feature-icon-wrapper">
                                        <v-icon icon="mdi-connection" size="24" />
                                    </div>
                                    <div>
                                        <h4 class="feature-list-title">Seamless Integration</h4>
                                        <p class="feature-list-text">
                                            Designed with simplicity in mind, Mercury boasts an intuitive interface
                                            that requires no prior blockchain knowledge, making it accessible for
                                            all team members.
                                        </p>
                                    </div>
                                </div>

                                <v-divider class="my-4" />

                                <div class="feature-list-item">
                                    <div class="feature-icon-wrapper">
                                        <v-icon icon="mdi-chart-line" size="24" />
                                    </div>
                                    <div>
                                        <h4 class="feature-list-title">Detailed statistics</h4>
                                        <p class="feature-list-text">
                                            Get access to real-time analytics and get to know your customer with
                                            our streamlined workflows.
                                        </p>
                                    </div>
                                </div>

                                <v-divider class="my-4" />

                                <div class="feature-list-item">
                                    <div class="feature-icon-wrapper">
                                        <v-icon icon="mdi-headset" size="24" />
                                    </div>
                                    <div>
                                        <h4 class="feature-list-title">Customer Support and Service</h4>
                                        <p class="feature-list-text">
                                            We are committed to supporting users with a dedicated team that ensures
                                            businesses can maximize the platform's capabilities.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </v-col>

                        <v-col cols="12" md="6" lg="7" class="d-none d-md-flex justify-end">
                            <img :src="featuresColumn" alt="Features" class="feature-column-image" />
                        </v-col>
                    </v-row>
                </v-container>
            </v-parallax>
        </section>

        <!-- Logos Section -->
        <section class="logos-section">
            <v-container>
                <v-row justify="center" align="center" class="py-8">
                    <v-col cols="4" sm="2" class="text-center">
                        <img :src="logo1" alt="Partner Logo" class="partner-logo" />
                    </v-col>
                    <v-col cols="4" sm="2" class="text-center">
                        <img :src="logo2" alt="Partner Logo" class="partner-logo" />
                    </v-col>
                    <v-col cols="4" sm="2" class="text-center">
                        <img :src="logo3" alt="Partner Logo" class="partner-logo" />
                    </v-col>
                    <v-col cols="4" sm="2" class="text-center">
                        <img :src="logo4" alt="Partner Logo" class="partner-logo" />
                    </v-col>
                    <v-col cols="4" sm="2" class="text-center">
                        <img :src="logo5" alt="Partner Logo" class="partner-logo" />
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Testimonials Section -->
        <section id="reviews" class="testimonials-section">
            <v-container class="py-16">
                <v-row justify="center">
                    <v-col cols="12" md="10" lg="8">
                        <v-carousel
                            v-model="currentTestimonial"
                            show-arrows
                            hide-delimiter-background
                            delimiter-icon="mdi-circle"
                            height="auto"
                            :cycle="true"
                            interval="8000"
                            class="testimonial-carousel"
                        >
                            <template v-slot:prev="{ props }">
                                <v-btn
                                    icon
                                    variant="outlined"
                                    color="grey"
                                    size="large"
                                    v-bind="props"
                                    class="carousel-arrow carousel-arrow-prev"
                                >
                                    <v-icon>mdi-chevron-left</v-icon>
                                </v-btn>
                            </template>
                            <template v-slot:next="{ props }">
                                <v-btn
                                    icon
                                    variant="outlined"
                                    color="grey"
                                    size="large"
                                    v-bind="props"
                                    class="carousel-arrow carousel-arrow-next"
                                >
                                    <v-icon>mdi-chevron-right</v-icon>
                                </v-btn>
                            </template>
                            <v-carousel-item v-for="(testimonial, index) in testimonials" :key="index">
                                <div class="testimonial-content">
                                    <v-rating
                                        :model-value="5"
                                        color="primary"
                                        readonly
                                        density="compact"
                                        class="mb-6 justify-center"
                                    />

                                    <p class="testimonial-text mb-8">
                                        {{ testimonial.text }}
                                    </p>

                                    <div class="testimonial-author">
                                        <v-avatar size="56" class="mr-4">
                                            <v-img :src="testimonial.avatar" :alt="testimonial.author" />
                                        </v-avatar>
                                        <div class="text-start author-info">
                                            <div class="author-name">{{ testimonial.author }}</div>
                                            <div class="author-role">{{ testimonial.role }}</div>
                                        </div>
                                        <v-divider vertical class="mx-6 d-none d-sm-block" />
                                        <img :src="testimonial.logo" alt="Company Logo" class="company-logo d-none d-sm-block" />
                                    </div>
                                </div>
                            </v-carousel-item>
                        </v-carousel>
                    </v-col>
                </v-row>
            </v-container>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="pricing-section">
            <v-container class="py-16">
                <div class="text-center mb-10">
                    <v-chip variant="flat" color="grey-lighten-3" size="small" class="mb-4">
                        Simple
                    </v-chip>

                    <h2 class="section-title mb-3">Pricing Options</h2>

                    <p class="pricing-description">Choose the plan that best fits your needs</p>

                    <v-btn-toggle
                        v-model="billingPeriod"
                        mandatory
                        class="billing-toggle"
                        rounded="pill"
                    >
                        <v-btn value="monthly" variant="flat" rounded="pill" size="small">
                            Monthly
                        </v-btn>
                        <v-btn value="yearly" variant="flat" rounded="pill" size="small">
                            Yearly
                        </v-btn>
                    </v-btn-toggle>
                </div>

                <v-row justify="center">
                    <!-- Free Plan -->
                    <v-col cols="12" sm="6" md="5" lg="4">
                        <v-card class="pricing-card h-100" variant="outlined" rounded="xl">
                            <v-card-text class="pa-6">
                                <div class="text-center mb-6">
                                    <div class="plan-name">Free Plan</div>
                                    <div class="plan-price">
                                        <span class="price-amount">$0</span>
                                        <span class="price-period">/mo</span>
                                    </div>
                                </div>

                                <div class="features-list mb-4">
                                    <div
                                        v-for="feature in freeFeatures"
                                        :key="feature"
                                        class="feature-item"
                                    >
                                        <v-icon icon="mdi-check" color="primary" size="18" class="mr-2" />
                                        <span>{{ feature }}</span>
                                    </div>
                                </div>

                                <div class="features-list mb-6">
                                    <div
                                        v-for="limitation in freeLimitations"
                                        :key="limitation"
                                        class="feature-item limitation"
                                    >
                                        <v-icon icon="mdi-close" color="grey" size="18" class="mr-2" />
                                        <span>{{ limitation }}</span>
                                    </div>
                                </div>

                                <v-btn
                                    block
                                    variant="outlined"
                                    color="dark"
                                    size="large"
                                    rounded="pill"
                                    :href="route('register')"
                                >
                                    Sign up
                                </v-btn>
                            </v-card-text>
                        </v-card>
                    </v-col>

                    <!-- Pro Plan -->
                    <v-col cols="12" sm="6" md="5" lg="4">
                        <v-card class="pricing-card pricing-card-featured h-100" variant="flat" rounded="xl">
                            <v-card-text class="pa-6">
                                <div class="text-center mb-6">
                                    <div class="plan-name">Pro Plan</div>
                                    <div class="plan-price">
                                        <span class="price-amount">{{ billingPeriod === 'yearly' ? '$16' : '$20' }}</span>
                                        <span class="price-period">/mo</span>
                                    </div>
                                    <div v-if="billingPeriod === 'yearly'" class="price-savings">
                                        Save $48/year
                                    </div>
                                </div>

                                <div class="features-list mb-6">
                                    <div
                                        v-for="feature in proFeatures"
                                        :key="feature"
                                        class="feature-item"
                                    >
                                        <v-icon icon="mdi-check" color="white" size="18" class="mr-2" />
                                        <span>{{ feature }}</span>
                                    </div>
                                </div>

                                <v-btn
                                    block
                                    variant="flat"
                                    color="white"
                                    size="large"
                                    rounded="pill"
                                    class="text-primary"
                                    :href="route('register')"
                                >
                                    Get started
                                </v-btn>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </section>
    </GuestLayout>
</template>

<style scoped>
/* Hero Section */
.hero-section {
    background: linear-gradient(180deg, #ffffff 0%, #f8faf9 100%);
    padding-top: 2rem;
}

.hero-badge {
    background: rgba(30, 217, 128, 0.1) !important;
    color: #16C170 !important;
}

.hero-title {
    font-family: 'Source Code Pro', monospace;
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.15;
    color: #1a1a1a;
}

.hero-subtitle {
    font-size: 1.125rem;
    color: #666;
    max-width: 400px;
    margin: 0 auto;
}

.dashboard-wrapper {
    max-width: 900px;
    margin: 0 auto;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
}

.dashboard-image {
    width: 100%;
    display: block;
}

/* Features Section */
.features-section {
    background: #f8faf9;
}

.features-parallax {
    min-height: auto !important;
}

.section-title {
    font-family: 'Source Code Pro', monospace;
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.2;
    color: #1a1a1a;
}

.section-subtitle {
    font-size: 1rem;
    color: #666;
    max-width: 500px;
}

.pricing-description {
    font-size: 1rem;
    color: #666;
    margin-bottom: 2rem;
    display: block;
    width: 100%;
    text-align: center;
}


.feature-title {
    font-weight: 600;
    font-size: 0.95rem;
    color: #1a1a1a;
}

.feature-text {
    font-size: 0.875rem;
    color: #666;
    line-height: 1.6;
}

.feature-image {
    max-width: 100%;
    height: auto;
    max-height: 450px;
    object-fit: contain;
}

.feature-column-image {
    max-width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: contain;
}

/* Feature List Card */
.feature-list-card {
    background: #fff;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.feature-list-item {
    display: flex;
    gap: 1rem;
}

.feature-icon-wrapper {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 12px;
    background: rgba(30, 217, 128, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #16C170;
}

.feature-list-title {
    font-weight: 600;
    font-size: 1rem;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
}

.feature-list-text {
    font-size: 0.875rem;
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* Logos Section */
.logos-section {
    background: #fff;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.partner-logo {
    max-height: 32px;
    opacity: 0.5;
    filter: grayscale(100%);
    transition: all 0.3s ease;
}

.partner-logo:hover {
    opacity: 1;
    filter: grayscale(0%);
}

/* Testimonials Section */
.testimonials-section {
    background: #fff;
    padding: 4rem 0;
}

.testimonial-carousel {
    background: transparent;
}

.testimonial-carousel :deep(.v-carousel__controls) {
    padding: 1rem 0;
}

.testimonial-carousel :deep(.v-carousel__controls .v-btn) {
    color: #ccc;
    width: 12px;
    height: 12px;
}

.testimonial-carousel :deep(.v-carousel__controls .v-btn--active) {
    color: #16C170;
}

.testimonial-carousel :deep(.v-carousel__controls .v-btn .v-icon) {
    font-size: 8px;
}

.testimonial-carousel :deep(.v-window__container) {
    padding: 0 80px;
}

.carousel-arrow {
    background: #fff !important;
    border: 1px solid #e0e0e0 !important;
    position: absolute !important;
    z-index: 1;
}

.carousel-arrow:hover {
    background: #f5f5f5 !important;
}

.carousel-arrow-prev {
    left: 0 !important;
}

.carousel-arrow-next {
    right: 0 !important;
}

.testimonial-content {
    text-align: center;
    padding: 3rem 2rem;
    background: #fff;
}

.testimonial-text {
    font-size: 1.125rem;
    font-weight: 500;
    color: #1a1a1a;
    line-height: 1.7;
    max-width: 700px;
    margin: 0 auto;
}

.testimonial-author {
    display: flex;
    align-items: center;
    justify-content: center;
}

.author-info {
    min-width: 150px;
}

.author-name {
    font-weight: 600;
    font-size: 1rem;
    color: #1a1a1a;
}

.author-role {
    font-size: 0.85rem;
    color: #888;
}

.company-logo {
    height: 28px;
    opacity: 0.7;
}

/* Pricing Section */
.pricing-section {
    background: linear-gradient(180deg, #f8faf9 0%, #e8f5ee 100%);
}

.billing-toggle {
    background: #fff !important;
    border: 1px solid #e0e0e0;
    padding: 4px;
}

.billing-toggle .v-btn {
    text-transform: none !important;
    font-weight: 500;
    min-width: 80px;
}

.billing-toggle .v-btn--active {
    background: #16C170 !important;
    color: #fff !important;
}

.pricing-card {
    background: #fff;
}

.pricing-card-featured {
    background: linear-gradient(135deg, #16C170 0%, #1ED980 100%);
    color: #fff;
}

.plan-name {
    font-size: 0.95rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.pricing-card-featured .plan-name {
    color: rgba(255, 255, 255, 0.9);
}

.plan-price {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 2px;
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 700;
    color: #16C170;
}

.pricing-card-featured .price-amount {
    color: #fff;
}

.price-period {
    font-size: 1rem;
    color: #888;
}

.pricing-card-featured .price-period {
    color: rgba(255, 255, 255, 0.7);
}

.price-savings {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.8);
    margin-top: 0.25rem;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}

.feature-item {
    display: flex;
    align-items: center;
    font-size: 0.875rem;
}

.feature-item.limitation {
    color: #999;
}

.pricing-card-featured .feature-item {
    color: rgba(255, 255, 255, 0.95);
}

/* Responsive */
@media (max-width: 960px) {
    .hero-title {
        font-size: 2.25rem;
    }

    .section-title {
        font-size: 1.75rem;
    }

    .feature-image,
    .feature-column-image {
        max-height: 300px;
        margin-top: 2rem;
    }
}

@media (max-width: 600px) {
    .hero-title {
        font-size: 1.75rem;
    }

    .section-title {
        font-size: 1.5rem;
    }

    .testimonial-carousel :deep(.v-window__container) {
        padding: 0 50px;
    }

    .testimonial-content {
        padding: 1rem;
    }

    .testimonial-author {
        flex-wrap: wrap;
    }

    .company-logo {
        display: none;
    }
}
</style>
