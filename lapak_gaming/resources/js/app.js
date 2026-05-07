import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

Alpine.plugin(focus);
window.Alpine = Alpine;

// ─── Navbar scroll shadow ─────────────────────────────
const navbar = document.querySelector('.navbar-gaming');
if (navbar) {
    const onScroll = () => {
        navbar.classList.toggle('scrolled', window.scrollY > 10);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

// ─── Countdown timers ────────────────────────────────
document.querySelectorAll('[data-countdown]').forEach(el => {
    const endTime = new Date(el.dataset.countdown).getTime();
    const tick = () => {
        const now = Date.now();
        const diff = Math.max(0, endTime - now);
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        const pad = n => String(n).padStart(2, '0');
        const hEl = el.querySelector('[data-h]');
        const mEl = el.querySelector('[data-m]');
        const sEl = el.querySelector('[data-s]');
        if (hEl) hEl.textContent = pad(h);
        if (mEl) mEl.textContent = pad(m);
        if (sEl) sEl.textContent = pad(s);
        if (diff > 0) requestAnimationFrame(tick);
    };
    tick();
});

// ─── Toast System ────────────────────────────────────
window.showToast = function (message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const icons = {
        success: `<svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`,
        error:   `<svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`,
        info:    `<svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    };
    const colors = {
        success: 'border-green-500/30 bg-green-950/80',
        error:   'border-red-500/30 bg-red-950/80',
        info:    'border-cyan-500/30 bg-cyan-950/80',
    };

    const toast = document.createElement('div');
    toast.className = `flex items-center gap-3 rounded-xl border px-4 py-3 text-sm font-medium text-white backdrop-blur-xl shadow-xl ${colors[type] || colors.info} transition-all duration-300 translate-y-0 opacity-100`;
    toast.innerHTML = `${icons[type] || icons.info}<span>${message}</span>`;

    container.appendChild(toast);

    // animate out after 3.5s
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 350);
    }, 3500);
};

// ─── Intersection Observer – slide-up on scroll ──────
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('[data-reveal]').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(el);
});

// ─── Init Alpine ─────────────────────────────────────
Alpine.start();

// ─── Mobile Drawer ─────────────────────────────
window.openDrawer = function () {
    const drawer = document.getElementById('mobile-drawer');
    if (drawer) {
        drawer.classList.remove('-translate-x-full');
    }
};

window.closeDrawer = function () {
    const drawer = document.getElementById('mobile-drawer');
    if (drawer) {
        drawer.classList.add('-translate-x-full');
    }
};

// ─── Dropdown System ───────────────────────────
window.toggleDropdown = function (id) {
    const dropdown = document.getElementById(id);

    if (!dropdown) return;

    // close others
    document.querySelectorAll('.dropdown-panel').forEach(el => {
        if (el.id !== id) {
            el.classList.add('hidden');
        }
    });

    dropdown.classList.toggle('hidden');
};

// close dropdown when clicking outside
document.addEventListener('click', function (e) {
    if (!e.target.closest('[onclick*="toggleDropdown"]') &&
        !e.target.closest('.dropdown-panel')) {

        document.querySelectorAll('.dropdown-panel').forEach(el => {
            el.classList.add('hidden');
        });
    }
});

window.toggleDropdown = function(id) {
    const dropdown = document.getElementById(id);

    document.querySelectorAll('.dropdown-panel').forEach(el => {
        if (el.id !== id) {
            el.classList.add('hidden');
        }
    });

    dropdown.classList.toggle('hidden');
};

window.openDrawer = function() {
    document.getElementById('mobile-drawer').classList.remove('-translate-x-full');
};

window.closeDrawer = function() {
    document.getElementById('mobile-drawer').classList.add('-translate-x-full');
};

document.addEventListener('click', function(e) {
    if (!e.target.closest('[onclick*="toggleDropdown"]') &&
        !e.target.closest('.dropdown-panel')) {

        document.querySelectorAll('.dropdown-panel').forEach(el => {
            el.classList.add('hidden');
        });
    }
});

window.openDrawer = function() {
    document.getElementById('mobile-drawer')
        .classList.remove('-translate-x-full');

    document.getElementById('drawerOverlay')
        .classList.add('active');
};

window.closeDrawer = function() {
    document.getElementById('mobile-drawer')
        .classList.add('-translate-x-full');

    document.getElementById('drawerOverlay')
        .classList.remove('active');
};

