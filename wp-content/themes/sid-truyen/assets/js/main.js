document.addEventListener('DOMContentLoaded', function () {
    // Top-level variables
    const html = document.documentElement;
    const body = document.body;

    /* -------------------------------------------------------------------------- */
    /*                                 DARK MODE                                  */
    /* -------------------------------------------------------------------------- */
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    if (themeToggleBtn) {
        // Initial Icon State
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
            html.classList.add('dark');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
            html.classList.remove('dark');
        }

        // Toggle Logic
        themeToggleBtn.addEventListener('click', function () {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    html.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    html.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (html.classList.contains('dark')) {
                    html.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    }

    /* -------------------------------------------------------------------------- */
    /*                            TYPOGRAPHY CONTROLS                             */
    /* -------------------------------------------------------------------------- */
    // Only run on chapter reading pages
    const contentArea = document.querySelector('.chapter-content');
    if (contentArea) {
        // Increase Font
        const btnIncrease = document.getElementById('font-increase');
        const btnDecrease = document.getElementById('font-decrease');
        const btnReset = document.getElementById('font-reset');

        let currentFontSize = localStorage.getItem('font-size') ? parseInt(localStorage.getItem('font-size')) : 18; // Default 18px (rem equivalent usually)

        const updateFontSize = (size) => {
            contentArea.style.fontSize = size + 'px';
            localStorage.setItem('font-size', size);
        };

        // Apply saved preference
        if (localStorage.getItem('font-size')) {
            updateFontSize(currentFontSize);
        }

        if (btnIncrease) {
            btnIncrease.addEventListener('click', () => {
                currentFontSize += 1;
                if (currentFontSize > 30) currentFontSize = 30;
                updateFontSize(currentFontSize);
            });
        }

        if (btnDecrease) {
            btnDecrease.addEventListener('click', () => {
                currentFontSize -= 1;
                if (currentFontSize < 14) currentFontSize = 14;
                updateFontSize(currentFontSize);
            });
        }

        if (btnReset) {
            btnReset.addEventListener('click', () => {
                currentFontSize = 18;
                updateFontSize(currentFontSize);
            });
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                              SEARCH MODAL                                  */
    /* -------------------------------------------------------------------------- */
    const searchToggle = document.getElementById('search-toggle');
    const searchModal = document.getElementById('search-modal');
    const searchClose = document.getElementById('search-close');

    if (searchToggle && searchModal) {
        searchToggle.addEventListener('click', () => {
            searchModal.classList.remove('hidden');
            const searchInput = searchModal.querySelector('input[type="search"]');
            if (searchInput) searchInput.focus();
        });

        if (searchClose) {
            searchClose.addEventListener('click', () => {
                searchModal.classList.add('hidden');
            });
        }

        // Close on backdrop click
        searchModal.addEventListener('click', (e) => {
            if (e.target === searchModal) {
                searchModal.classList.add('hidden');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                searchModal.classList.add('hidden');
            }
        });
    }

    /* -------------------------------------------------------------------------- */
    /*                     BACK TO TOP & SHARE BUTTONS                            */
    /* -------------------------------------------------------------------------- */
    const backToTopBtn = document.getElementById('back-to-top');

    if (backToTopBtn) {
        // Show/hide buttons based on scroll position
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                if (backToTopBtn) {
                    backToTopBtn.classList.remove('opacity-0', 'invisible');
                    backToTopBtn.classList.add('opacity-100', 'visible');
                }

            } else {
                if (backToTopBtn) {
                    backToTopBtn.classList.add('opacity-0', 'invisible');
                    backToTopBtn.classList.remove('opacity-100', 'visible');
                }

            }
        });

        // Scroll to top when clicked
        if (backToTopBtn) {
            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }


    }
});
