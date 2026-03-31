import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import CarsCatalog from './components/CarsCatalog';
import ReservationPriceCalculator from './components/ReservationPriceCalculator';

function initProfessionalMotion() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const body = document.body;

    if (reduceMotion) {
        body.classList.add('motion-reduced');
        return;
    }

    body.classList.add('motion-enabled');

    const targets = document.querySelectorAll(
        '[data-animate="reveal"], .hero-section, .feature-card, .panel-card, .auth-card, .car-card, .admin-panel, .admin-stat-card, .alert'
    );

    let delayStep = 0;
    targets.forEach((element) => {
        if (!element.hasAttribute('data-animate')) {
            element.setAttribute('data-animate', 'reveal');
        }

        if (!element.hasAttribute('data-animate-delay')) {
            element.setAttribute('data-animate-delay', String(delayStep * 60));
            delayStep = (delayStep + 1) % 6;
        }
    });

    const revealImmediately = () => {
        targets.forEach((element) => {
            element.style.setProperty('--motion-delay', '0ms');
            element.classList.add('is-visible');
        });
    };

    if (!('IntersectionObserver' in window)) {
        revealImmediately();
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const delay = entry.target.getAttribute('data-animate-delay') || '0';
                    entry.target.style.setProperty('--motion-delay', `${delay}ms`);
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -6% 0px' }
    );

    targets.forEach((element) => observer.observe(element));

    window.setTimeout(() => {
        const hiddenTargets = Array.from(targets).filter((element) => !element.classList.contains('is-visible'));
        if (hiddenTargets.length > 0) {
            revealImmediately();
        }
    }, 1200);
}

const carsCatalogRoot = document.getElementById('cars-catalog-root');
if (carsCatalogRoot) {
    const detailsBaseUrl = carsCatalogRoot.dataset.detailsUrl || '/car';
    const carsDataScript = document.getElementById('cars-catalog-data');
    let cars = [];

    try {
        if (carsDataScript?.textContent) {
            cars = JSON.parse(carsDataScript.textContent);
        } else if (carsCatalogRoot.dataset.cars) {
            cars = JSON.parse(carsCatalogRoot.dataset.cars);
        }

        if (!Array.isArray(cars)) {
            cars = [];
        }
    } catch (error) {
        console.error('Cars catalog data parsing failed:', error);
        cars = [];
    }

    createRoot(carsCatalogRoot).render(<CarsCatalog cars={cars} detailsBaseUrl={detailsBaseUrl} />);
    requestAnimationFrame(initProfessionalMotion);
}

const reservationPricingRoot = document.getElementById('reservation-pricing-root');
if (reservationPricingRoot) {
    const pricePerDay = parseFloat(reservationPricingRoot.dataset.pricePerDay || '0');
    const dateStartInputId = reservationPricingRoot.dataset.dateStartInputId || 'date_debut';
    const dateEndInputId = reservationPricingRoot.dataset.dateEndInputId || 'date_fin';
    const citySelectId = reservationPricingRoot.dataset.citySelectId || 'city_id';

    createRoot(reservationPricingRoot).render(
        <ReservationPriceCalculator
            pricePerDay={pricePerDay}
            dateStartInputId={dateStartInputId}
            dateEndInputId={dateEndInputId}
            citySelectId={citySelectId}
        />
    );
    requestAnimationFrame(initProfessionalMotion);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProfessionalMotion);
} else {
    initProfessionalMotion();
}
