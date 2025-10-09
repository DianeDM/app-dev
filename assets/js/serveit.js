/* ServeIT Landing Interactions */
(function () {
  'use strict';

  const on = (el, evt, fn, opts) => el && el.addEventListener(evt, fn, opts || false);
  const qs = (sel, root) => (root || document).querySelector(sel);
  const qsa = (sel, root) => Array.from((root || document).querySelectorAll(sel));

  function smoothScrollInit() {
    qsa('a[href^="#"]').forEach(anchor => {
      on(anchor, 'click', function (e) {
        const href = this.getAttribute('href');
        if (!href || href.length <= 1) return;
        const target = qs(href);
        if (!target) return;
        e.preventDefault();
        const navbar = qs('.navbar');
        const navbarHeight = navbar ? navbar.offsetHeight : 0;
        const top = target.getBoundingClientRect().top + window.scrollY - navbarHeight - 10;
        window.scrollTo({ top, behavior: 'smooth' });
      });
    });
  }

  function navbarScrollEffect() {
    const navbar = qs('.navbar');
    if (!navbar) return;
    const apply = () => {
      if (window.scrollY > 50) {
        navbar.style.background = 'linear-gradient(135deg, rgba(44, 90, 160, 0.95) 0%, rgba(30, 58, 138, 0.95) 100%)';
        navbar.style.backdropFilter = 'blur(10px)';
        navbar.style.boxShadow = '0 10px 30px rgba(0,0,0,0.25)';
      } else {
        navbar.style.background = 'linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%)';
        navbar.style.backdropFilter = 'none';
        navbar.style.boxShadow = 'var(--shadow)';
      }
    };
    apply();
    on(window, 'scroll', apply, { passive: true });
  }

  function intersectionReveal() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('fade-in');
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    qsa('.service-card, .announcement-card, .about-card').forEach(card => observer.observe(card));
  }

  function autodismissAlerts() {
    setTimeout(() => {
      qsa('.alert').forEach(el => {
        const api = bootstrap.Alert.getOrCreateInstance(el);
        api.close();
      });
    }, 5000);
  }

  function backToTopButton() {
    let btn = qs('#backToTop');
    if (!btn) {
      btn = document.createElement('button');
      btn.id = 'backToTop';
      btn.className = 'back-to-top';
      btn.setAttribute('aria-label', 'Back to top');
      btn.innerHTML = '<i class="fas fa-arrow-up"></i>';
      document.body.appendChild(btn);
    }
    const toggle = () => {
      if (window.scrollY > 350) btn.classList.add('show');
      else btn.classList.remove('show');
    };
    on(window, 'scroll', toggle, { passive: true });
    on(btn, 'click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    toggle();
  }

  function themeToggle() {
    const storageKey = 'serveit-theme';
    const root = document.documentElement;
    const current = localStorage.getItem(storageKey);
    if (current === 'light') root.setAttribute('data-theme', 'light');

    let btn = qs('#themeToggle');
    if (!btn) {
      btn = document.createElement('button');
      btn.id = 'themeToggle';
      btn.className = 'btn-theme-toggle ms-2';
      btn.innerHTML = '<i class="fas fa-sun"></i>';
      const nav = qs('.navbar .container');
      if (nav) nav.appendChild(btn);
    }
    const updateIcon = () => {
      const isLight = root.getAttribute('data-theme') === 'light';
      btn.innerHTML = isLight ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
    };
    on(btn, 'click', () => {
      const isLight = root.getAttribute('data-theme') === 'light';
      if (isLight) {
        root.removeAttribute('data-theme');
        localStorage.removeItem(storageKey);
      } else {
        root.setAttribute('data-theme', 'light');
        localStorage.setItem(storageKey, 'light');
      }
      updateIcon();
    });
    updateIcon();
  }

  function init() {
    smoothScrollInit();
    navbarScrollEffect();
    intersectionReveal();
    autodismissAlerts();
    backToTopButton();
    themeToggle();
  }

  if (document.readyState === 'loading') {
    on(document, 'DOMContentLoaded', init);
  } else {
    init();
  }
})();
