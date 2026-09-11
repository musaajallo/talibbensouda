import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

Alpine.plugin(focus);
window.Alpine = Alpine;
Alpine.start();

// ── Scroll reveal + count-up ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const reveals  = document.querySelectorAll('[data-reveal]');
    const counters = document.querySelectorAll('[data-count]');

    const io = new IntersectionObserver((entries) => {
        entries.forEach(({ isIntersecting, target }) => {
            if (!isIntersecting) return;

            if (target.hasAttribute('data-reveal')) {
                const delay = parseInt(target.dataset.revealDelay || 0);
                setTimeout(() => target.classList.add('is-visible'), delay);
            }

            if (target.hasAttribute('data-count')) {
                countUp(target);
            }

            io.unobserve(target);
        });
    }, { threshold: 0.25 });

    [...reveals, ...counters].forEach(el => io.observe(el));
});

function countUp(el) {
    const target   = parseInt(el.dataset.count);
    const duration = 1800;
    const fps      = 60;
    const steps    = duration / (1000 / fps);
    let   current  = 0;

    const timer = setInterval(() => {
        // Ease-out: faster at start, slows at end
        const progress = current / target;
        const increment = (target / steps) * (1 - progress * 0.6);
        current = Math.min(current + increment, target);
        el.textContent = Math.round(current).toLocaleString();
        if (current >= target) clearInterval(timer);
    }, 1000 / fps);
}
