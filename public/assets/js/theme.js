/**
 * MoveFlow — Theme Manager (Dark/Light Mode)
 */
(function() {
    'use strict';

    const STORAGE_KEY = 'moveflow-theme';
    const DARK = 'dark';
    const LIGHT = 'light';

    /**
     * Get the saved theme from localStorage or system preference
     */
    function getPreferredTheme() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved === DARK || saved === LIGHT) return saved;

        // Check OS-level preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return DARK;
        }
        return LIGHT;
    }

    /**
     * Apply theme to the document
     */
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        updateToggleButtons(theme);
    }

    /**
     * Toggle between light and dark
     */
    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme') || LIGHT;
        const next = current === DARK ? LIGHT : DARK;
        applyTheme(next);
    }

    /**
     * Update all toggle button states
     */
    function updateToggleButtons(theme) {
        document.querySelectorAll('.theme-toggle, .sidebar-theme-toggle').forEach(btn => {
            const textEl = btn.querySelector('.theme-toggle-text');
            if (textEl) {
                textEl.textContent = theme === DARK ? 'Modo Claro' : 'Modo Escuro';
            }
        });
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        // Bind all toggle buttons
        document.querySelectorAll('.theme-toggle, .sidebar-theme-toggle').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                toggleTheme();
            });
        });

        // Listen for OS theme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                // Only follow OS if user hasn't manually chosen
                if (!localStorage.getItem(STORAGE_KEY)) {
                    applyTheme(e.matches ? DARK : LIGHT);
                }
            });
        }
    }

    // Apply theme ASAP (before DOMContentLoaded) to prevent flash
    applyTheme(getPreferredTheme());

    // Bind buttons after DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose globally for programmatic use
    window.MoveFlowTheme = {
        toggle: toggleTheme,
        set: applyTheme,
        get: () => document.documentElement.getAttribute('data-theme') || LIGHT
    };
})();
