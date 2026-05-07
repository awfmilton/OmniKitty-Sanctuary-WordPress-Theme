/**
 * OmniKitty Child Theme - Interactive features
 *
 * - Mobile menu toggle
 * - IntersectionObserver-driven scroll animations
 * - Smooth-scroll anchor links
 * - Client-side form validation
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initMobileMenu();
        initScrollAnimations();
        initSmoothScroll();
        initFormValidation();
    });

    /* ----------------------------------------------------------------
     * Mobile menu toggle
     * ---------------------------------------------------------------- */
    function initMobileMenu() {
        var toggle = document.querySelector('.menu-toggle');
        var nav    = document.querySelector('.main-navigation');
        if (!toggle || !nav) return;

        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('toggled');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        // Close on outside click.
        document.addEventListener('click', function (e) {
            if (!nav.contains(e.target) && !toggle.contains(e.target) && nav.classList.contains('toggled')) {
                nav.classList.remove('toggled');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on Escape.
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('toggled')) {
                nav.classList.remove('toggled');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });
    }

    /* ----------------------------------------------------------------
     * Scroll animations (.fade-in -> .is-visible)
     * ---------------------------------------------------------------- */
    function initScrollAnimations() {
        var els = document.querySelectorAll('.fade-in');
        if (!els.length) return;

        // Respect reduced-motion preference.
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            els.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }

        if (!('IntersectionObserver' in window)) {
            els.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        els.forEach(function (el) { observer.observe(el); });
    }

    /* ----------------------------------------------------------------
     * Smooth scroll for in-page anchor links
     * ---------------------------------------------------------------- */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                var href = link.getAttribute('href');
                if (!href || href === '#' || href.length < 2) return;

                var target;
                try {
                    target = document.querySelector(href);
                } catch (err) {
                    return;
                }
                if (!target) return;

                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Move focus for keyboard users.
                target.setAttribute('tabindex', '-1');
                target.focus({ preventScroll: true });
            });
        });
    }

    /* ----------------------------------------------------------------
     * Lightweight client-side form validation
     * ---------------------------------------------------------------- */
    function initFormValidation() {
        document.querySelectorAll('.omnikitty-form, .newsletter-form').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                var firstInvalid = null;

                form.querySelectorAll('[required]').forEach(function (field) {
                    field.classList.remove('has-error');

                    var value = (field.value || '').trim();
                    var invalid = !value;

                    if (!invalid && field.type === 'email') {
                        invalid = !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    }

                    if (invalid) {
                        field.classList.add('has-error');
                        field.setAttribute('aria-invalid', 'true');
                        if (!firstInvalid) firstInvalid = field;
                    } else {
                        field.removeAttribute('aria-invalid');
                    }
                });

                if (firstInvalid) {
                    e.preventDefault();
                    firstInvalid.focus();
                }
            });
        });
    }
})();
