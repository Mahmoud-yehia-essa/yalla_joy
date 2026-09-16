/**
 * Table Sticky & Top Horizontal Scrollbar System
 * Yalla Joye Admin Panel
 * - Creates a Top Horizontal Scrollbar above wide tables.
 * - Creates a Floating Sticky Horizontal Scrollbar accompanying the user while scrolling down vertically.
 * - Full bi-directional synchronization with DataTables and responsive layouts.
 */

(function () {
    'use strict';

    // Global floating scrollbar element
    let globalFloatingBar = null;
    let activeTableResponsive = null;
    let isSyncingScroll = false;

    // Create the global floating scrollbar once
    function createFloatingBar() {
        if (globalFloatingBar) return globalFloatingBar;

        const floatingBar = document.createElement('div');
        floatingBar.className = 'table-floating-scrollbar';
        floatingBar.setAttribute('dir', 'rtl');
        floatingBar.innerHTML = `
            <button type="button" class="table-floating-scroll-btn scroll-right-btn" title="تحريك لليمين">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            <div class="table-floating-scroll-label">
                <i class="fa-solid fa-arrows-left-right"></i>
                <span>تحريك الجدول:</span>
            </div>
            <div class="table-floating-scroll-track" dir="rtl">
                <div class="table-floating-scroll-inner"></div>
            </div>
            <button type="button" class="table-floating-scroll-btn scroll-left-btn" title="تحريك لليسار">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        `;

        document.body.appendChild(floatingBar);

        const track = floatingBar.querySelector('.table-floating-scroll-track');
        const btnRight = floatingBar.querySelector('.scroll-right-btn');
        const btnLeft = floatingBar.querySelector('.scroll-left-btn');

        // Floating track scroll listener
        track.addEventListener('scroll', function () {
            if (isSyncingScroll || !activeTableResponsive) return;
            isSyncingScroll = true;
            activeTableResponsive.scrollLeft = track.scrollLeft;

            const topScroll = activeTableResponsive._topScrollContainer;
            if (topScroll) {
                topScroll.scrollLeft = track.scrollLeft;
            }
            isSyncingScroll = false;
        });

        // Quick scroll buttons
        btnRight.addEventListener('click', function () {
            if (!activeTableResponsive) return;
            activeTableResponsive.scrollBy({ left: 250, behavior: 'smooth' });
        });

        btnLeft.addEventListener('click', function () {
            if (!activeTableResponsive) return;
            activeTableResponsive.scrollBy({ left: -250, behavior: 'smooth' });
        });

        globalFloatingBar = floatingBar;
        return floatingBar;
    }

    // Initialize top scrollbar for a specific table container
    function initTableScrollbars(container) {
        const table = container.querySelector('table');
        if (!table) return;

        // Check if top scrollbar container already exists
        let topContainer = container.previousElementSibling;
        let isNewTopContainer = false;

        if (!topContainer || !topContainer.classList.contains('table-top-scrollbar-container')) {
            topContainer = document.createElement('div');
            topContainer.className = 'table-top-scrollbar-container';
            topContainer.setAttribute('dir', 'rtl');
            topContainer.innerHTML = `
                <div class="table-top-scrollbar-label">
                    <i class="fa-solid fa-arrows-left-right"></i>
                    <span>تمرير أفقي:</span>
                </div>
                <div class="table-top-scrollbar" dir="rtl">
                    <div class="table-top-scrollbar-inner"></div>
                </div>
            `;
            container.parentNode.insertBefore(topContainer, container);
            isNewTopContainer = true;
        }

        const topScroll = topContainer.querySelector('.table-top-scrollbar');
        const topInner = topContainer.querySelector('.table-top-scrollbar-inner');
        container._topScrollContainer = topScroll;

        function updateDimensions() {
            const scrollWidth = table.scrollWidth || container.scrollWidth;
            const clientWidth = container.clientWidth;
            const hasOverflow = scrollWidth > clientWidth + 5;

            if (hasOverflow) {
                topContainer.style.display = 'flex';
                topInner.style.width = scrollWidth + 'px';
                topScroll.scrollLeft = container.scrollLeft;
            } else {
                topContainer.style.display = 'none';
            }

            // Also update floating bar if active
            if (activeTableResponsive === container && globalFloatingBar) {
                const floatingInner = globalFloatingBar.querySelector('.table-floating-scroll-inner');
                const floatingTrack = globalFloatingBar.querySelector('.table-floating-scroll-track');
                if (floatingInner) floatingInner.style.width = scrollWidth + 'px';
                if (floatingTrack) floatingTrack.scrollLeft = container.scrollLeft;
            }
        }

        // Attach sync listeners only once
        if (isNewTopContainer) {
            topScroll.addEventListener('scroll', function () {
                if (isSyncingScroll) return;
                isSyncingScroll = true;
                container.scrollLeft = topScroll.scrollLeft;

                if (globalFloatingBar && activeTableResponsive === container) {
                    const track = globalFloatingBar.querySelector('.table-floating-scroll-track');
                    if (track) track.scrollLeft = topScroll.scrollLeft;
                }
                isSyncingScroll = false;
            });

            container.addEventListener('scroll', function () {
                if (isSyncingScroll) return;
                isSyncingScroll = true;
                topScroll.scrollLeft = container.scrollLeft;

                if (globalFloatingBar && activeTableResponsive === container) {
                    const track = globalFloatingBar.querySelector('.table-floating-scroll-track');
                    if (track) track.scrollLeft = container.scrollLeft;
                }
                isSyncingScroll = false;
            });
        }

        container._updateDimensions = updateDimensions;
        updateDimensions();
    }

    // Check all tables and manage floating bar state
    function checkFloatingBarState() {
        const containers = document.querySelectorAll('.table-responsive');
        let currentInViewContainer = null;
        const viewportHeight = window.innerHeight;

        containers.forEach(function (container) {
            const table = container.querySelector('table');
            if (!table) return;

            const scrollWidth = table.scrollWidth || container.scrollWidth;
            const clientWidth = container.clientWidth;
            const hasOverflow = scrollWidth > clientWidth + 5;

            if (!hasOverflow) return;

            const rect = container.getBoundingClientRect();
            // Table is in view if top is above bottom of screen and bottom is below top of screen
            // AND the bottom scrollbar of the table is currently below the visible screen viewport
            const isInView = rect.top < viewportHeight - 60 && rect.bottom > 120;
            const isBottomScrollbarOutOfView = rect.bottom > viewportHeight - 20;

            if (isInView && isBottomScrollbarOutOfView) {
                currentInViewContainer = container;
            }
        });

        const floatingBar = createFloatingBar();

        if (currentInViewContainer) {
            activeTableResponsive = currentInViewContainer;
            const table = currentInViewContainer.querySelector('table');
            const scrollWidth = table ? table.scrollWidth : currentInViewContainer.scrollWidth;

            const floatingInner = floatingBar.querySelector('.table-floating-scroll-inner');
            const floatingTrack = floatingBar.querySelector('.table-floating-scroll-track');

            if (floatingInner) floatingInner.style.width = scrollWidth + 'px';
            if (floatingTrack && !isSyncingScroll) {
                isSyncingScroll = true;
                floatingTrack.scrollLeft = currentInViewContainer.scrollLeft;
                isSyncingScroll = false;
            }

            floatingBar.classList.add('is-visible');
        } else {
            floatingBar.classList.remove('is-visible');
            activeTableResponsive = null;
        }
    }

    // Main initialization function
    function initAll() {
        const containers = document.querySelectorAll('.table-responsive');
        containers.forEach(initTableScrollbars);
        checkFloatingBarState();
    }

    // Event listeners
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    window.addEventListener('scroll', checkFloatingBarState, { passive: true });
    window.addEventListener('resize', function () {
        document.querySelectorAll('.table-responsive').forEach(function (container) {
            if (container._updateDimensions) container._updateDimensions();
        });
        checkFloatingBarState();
    }, { passive: true });

    // Integrate with jQuery / DataTables
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function () {
            initAll();

            jQuery(document).on('draw.dt', function () {
                setTimeout(initAll, 60);
            });
        });
    }

    // Support dynamic updates (e.g. collapsing coins row or changing content)
    const observer = new MutationObserver(function () {
        document.querySelectorAll('.table-responsive').forEach(function (container) {
            if (container._updateDimensions) container._updateDimensions();
        });
        checkFloatingBarState();
    });

    if (document.body) {
        observer.observe(document.body, { childList: true, subtree: true });
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            observer.observe(document.body, { childList: true, subtree: true });
        });
    }

    window.refreshTableStickyScrollbars = initAll;
})();
