/**
 * CHF Theme — Frontend JavaScript
 * Vanilla ES2022+, no jQuery dependency.
 * Provides scroll reveal, counter animations, back-to-top,
 * header scroll behavior, and Elementor integration.
 *
 * @package CHF
 * @since 5.0.0
 */

(() => {
  'use strict';

  /* ========================================================================
     Configuration
     ======================================================================== */

  /** @type {boolean} Whether the user prefers reduced motion */
  const prefersReducedMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  /** @type {number} Scroll threshold (px) before header gets .scrolled class */
  const HEADER_SCROLL_THRESHOLD = 60;

  /** @type {number} Scroll threshold (px) before back-to-top button appears */
  const BACK_TO_TOP_THRESHOLD = 400;

  /** @type {number} Default counter animation duration in ms */
  const COUNTER_DURATION = 1800;

  /* ========================================================================
     Utilities
     ======================================================================== */

  /**
   * Debounce a function by the given delay.
   * @param {Function} fn - The function to debounce
   * @param {number} delay - Delay in milliseconds
   * @returns {Function} Debounced function
   */
  function debounce(fn, delay) {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  }

  /**
   * Format a number with commas (e.g. 1234567 -> "1,234,567").
   * @param {number} n - The number to format
   * @returns {string} Formatted string
   */
  function formatNumber(n) {
    return n.toLocaleString('en-US');
  }

  /**
   * Ease-out exponential curve.
   * @param {number} t - Progress from 0 to 1
   * @returns {number} Eased value
   */
  function easeOutExpo(t) {
    return t === 1 ? 1 : 1 - 2 ** (-10 * t);
  }

  /* ========================================================================
     1. Scroll Reveal Observer
     ======================================================================== */

  /**
   * Initialize IntersectionObserver for .reveal elements.
   * Adds .visible class when elements scroll into view.
   */
  function initScrollReveal() {
    if (prefersReducedMotion) {
      // Make everything visible immediately
      document.querySelectorAll('.reveal').forEach((el) => {
        el.classList.add('visible');
      });
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px',
      }
    );

    document.querySelectorAll('.reveal:not(.visible)').forEach((el) => {
      observer.observe(el);
    });
  }

  /* ========================================================================
     2. Smooth Scroll for Anchor Links
     ======================================================================== */

  /**
   * Attach smooth-scroll behavior to all same-page anchor links.
   * Respects prefers-reduced-motion by using instant scroll.
   */
  function initSmoothScroll() {
    document.addEventListener('click', (e) => {
      const link = e.target.closest('a[href^="#"]:not([href="#"])');
      if (!link) return;

      const targetId = link.getAttribute('href');
      const target = document.querySelector(targetId);
      if (!target) return;

      e.preventDefault();

      target.scrollIntoView({
        behavior: prefersReducedMotion ? 'instant' : 'smooth',
        block: 'start',
      });

      // Move focus to target for accessibility
      if (target.getAttribute('tabindex') === null) {
        target.setAttribute('tabindex', '-1');
      }
      target.focus({ preventScroll: true });
    });
  }

  /* ========================================================================
     3. Back to Top Button
     ======================================================================== */

  /**
   * Create and manage a back-to-top button.
   * Appears after scrolling past BACK_TO_TOP_THRESHOLD.
   */
  function initBackToTop() {
    // Create the button element
    const btn = document.createElement('button');
    btn.className = 'back-to-top';
    btn.setAttribute('aria-label', 'Back to top');
    btn.innerHTML = `<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
      <path d="M10 16V4M10 4L4 10M10 4L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>`;

    // Style the button
    Object.assign(btn.style, {
      position: 'fixed',
      bottom: '24px',
      right: '24px',
      zIndex: '999',
      width: '44px',
      height: '44px',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      background: 'var(--green)',
      color: 'var(--white)',
      border: 'none',
      borderRadius: '50%',
      cursor: 'pointer',
      opacity: '0',
      visibility: 'hidden',
      transform: 'translateY(12px)',
      transition: 'opacity 0.3s, visibility 0.3s, transform 0.3s, background-color 0.2s',
      boxShadow: '0 4px 16px rgba(0,0,0,0.12)',
    });

    document.body.appendChild(btn);

    // Show/hide based on scroll position
    let visible = false;

    function updateVisibility() {
      const shouldShow = window.scrollY > BACK_TO_TOP_THRESHOLD;
      if (shouldShow === visible) return;
      visible = shouldShow;

      btn.style.opacity = shouldShow ? '1' : '0';
      btn.style.visibility = shouldShow ? 'visible' : 'hidden';
      btn.style.transform = shouldShow ? 'translateY(0)' : 'translateY(12px)';
    }

    window.addEventListener('scroll', () => {
      requestAnimationFrame(updateVisibility);
    }, { passive: true });

    // Hover state
    btn.addEventListener('mouseenter', () => {
      btn.style.backgroundColor = 'var(--green-dark)';
    });
    btn.addEventListener('mouseleave', () => {
      btn.style.backgroundColor = 'var(--green)';
    });

    // Click handler
    btn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: prefersReducedMotion ? 'instant' : 'smooth',
      });
    });
  }

  /* ========================================================================
     4. Header Scroll Behavior
     ======================================================================== */

  /**
   * Add .scrolled class to header element when scrolled past threshold.
   * Uses requestAnimationFrame for performant scroll handling.
   */
  function initHeaderScroll() {
    const header = document.getElementById('nav') ||
                   document.querySelector('.site-header') ||
                   document.querySelector('header');

    if (!header) return;

    let ticking = false;
    let lastScrolled = false;

    function onScroll() {
      if (ticking) return;

      ticking = true;
      requestAnimationFrame(() => {
        const isScrolled = window.scrollY > HEADER_SCROLL_THRESHOLD;

        if (isScrolled !== lastScrolled) {
          header.classList.toggle('scrolled', isScrolled);
          lastScrolled = isScrolled;
        }

        ticking = false;
      });
    }

    window.addEventListener('scroll', onScroll, { passive: true });

    // Check initial state
    onScroll();
  }

  /* ========================================================================
     5. Counter Animation for Stat Numbers
     ======================================================================== */

  /**
   * Animate stat numbers from 0 to their target value.
   * Observes elements with data-target or data-count attributes.
   * Supports selectors: .stat-number[data-target], [data-count], .counter[data-target].
   * Supports suffixes: K, M, +, %, or custom via data-suffix.
   */
  function initCounters() {
    /** @type {string} Unified selector covering all counter markup patterns */
    const COUNTER_SELECTOR = '.stat-number[data-target], [data-count], .counter[data-target]';

    /**
     * Read the numeric target from an element's dataset.
     * Checks data-target first, falls back to data-count.
     * @param {HTMLElement} el
     * @returns {number}
     */
    function getTarget(el) {
      return parseFloat(el.dataset.target ?? el.dataset.count);
    }

    if (prefersReducedMotion) {
      // Show final values immediately
      document.querySelectorAll(COUNTER_SELECTOR).forEach((el) => {
        const target = getTarget(el);
        if (isNaN(target)) return;

        const suffix = el.dataset.suffix ?? '';
        el.textContent = formatNumber(target) + suffix;
      });
      return;
    }

    const counterObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;

          const el = entry.target;
          const target = getTarget(el);
          if (isNaN(target)) return;

          const suffix = el.dataset.suffix ?? '';
          const isDecimal = !Number.isInteger(target);
          const start = performance.now();

          /**
           * Animation frame loop for counter.
           * @param {number} now - Current timestamp from rAF
           */
          function animate(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / COUNTER_DURATION, 1);
            const eased = easeOutExpo(progress);
            const current = eased * target;

            if (isDecimal) {
              el.textContent = current.toFixed(1) + suffix;
            } else {
              el.textContent = formatNumber(Math.round(current)) + suffix;
            }

            if (progress < 1) {
              requestAnimationFrame(animate);
            }
          }

          requestAnimationFrame(animate);
          counterObserver.unobserve(el);
        });
      },
      { threshold: 0.3 }
    );

    document
      .querySelectorAll(
        COUNTER_SELECTOR.split(', ')
          .map((s) => `${s}:not(.counted)`)
          .join(', ')
      )
      .forEach((el) => {
        counterObserver.observe(el);
      });
  }

  /* ========================================================================
     6. Elementor Frontend Hooks
     ======================================================================== */

  /**
   * Re-initialize observers when Elementor loads new widgets dynamically.
   * Hooks into elementor/frontend/init and popup events.
   */
  function initElementorHooks() {
    // Wait for Elementor frontend to be available
    if (typeof window.elementorFrontend === 'undefined') {
      // Elementor not loaded yet — listen for its init event
      document.addEventListener('elementor/frontend/init', onElementorInit);
      return;
    }

    onElementorInit();
  }

  /**
   * Called when Elementor frontend initializes.
   * Sets up hooks for widget rendering and popup display.
   */
  function onElementorInit() {
    const ef = window.elementorFrontend;
    if (!ef?.hooks) return;

    // Re-run observers after any widget renders
    ef.hooks.addAction(
      'frontend/element_ready/global',
      () => {
        initScrollReveal();
        initCounters();
      }
    );

    // Re-run after popup shows
    if (ef.hooks.addAction) {
      ef.hooks.addAction(
        'frontend/element_ready/popup',
        () => {
          initScrollReveal();
          initCounters();
        }
      );
    }

    // Listen for Elementor popup:show event
    document.addEventListener('elementor/popup/show', () => {
      // Small delay to let popup DOM render
      setTimeout(() => {
        initScrollReveal();
        initCounters();
      }, 100);
    });
  }

  /* ========================================================================
     7. Resize Handler
     ======================================================================== */

  /**
   * Handle window resize events with debouncing.
   * Recalculates layout-dependent values.
   */
  function initResizeHandler() {
    const onResize = debounce(() => {
      // Update CSS custom property for viewport height (mobile browsers)
      document.documentElement.style.setProperty(
        '--vh',
        `${window.innerHeight * 0.01}px`
      );
    }, 150);

    window.addEventListener('resize', onResize, { passive: true });

    // Set initial value
    onResize();
  }

  /* ========================================================================
     Bootstrap
     ======================================================================== */

  /**
   * Initialize all modules when the DOM is ready.
   */
  function init() {
    initScrollReveal();
    initSmoothScroll();
    initBackToTop();
    initHeaderScroll();
    initCounters();
    initElementorHooks();
    initResizeHandler();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    // DOM already ready (deferred script or late load)
    init();
  }
})();
