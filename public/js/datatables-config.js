/**
 * DataTables Configuration - Reusable
 * This file contains the default configuration for all DataTables instances
 */

(function() {
    'use strict';

    // Get current locale
    function getLocale() {
        let locale = 'es';
        const localeMeta = document.querySelector('meta[name="app-locale"]');
        if (localeMeta) {
            locale = localeMeta.getAttribute('content');
        } else if (document.documentElement.lang) {
            locale = document.documentElement.lang;
        } else if (document.documentElement.getAttribute('lang')) {
            locale = document.documentElement.getAttribute('lang');
        }
        return (locale === 'en' || locale.startsWith('en')) ? 'en' : 'es';
    }

    // Get base URL
    function getBaseUrl() {
        return window.location.origin || '';
    }

    // Default DataTables configuration
    window.getDataTablesConfig = function(customOptions = {}) {
        const locale = getLocale();
        const baseUrl = getBaseUrl();
        
        const defaultConfig = {
            language: {
                url: locale === 'en' 
                    ? baseUrl + '/js/datatables/i18n/en.json'
                    : baseUrl + '/js/datatables/i18n/es-ES.json',
                paginate: {
                    first: '<<',
                    previous: '<',
                    next: '>',
                    last: '>>'
                }
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, locale === 'es' ? "Todos" : "All"]],
            responsive: true,
            order: [],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row mt-2"<"col-6"i><"col-6"p>>',
            autoWidth: false,
            scrollX: false,
            pagingType: 'full_numbers', // Show page numbers with first/last buttons
            drawCallback: function() {
                // Re-initialize tooltips after DataTables redraw (Bootstrap 4 - jQuery)
                $('[data-toggle="tooltip"]').tooltip('dispose');
                $('[data-toggle="tooltip"]').tooltip();
            }
        };

        // Merge with custom options
        return Object.assign({}, defaultConfig, customOptions);
    };

    // Initialize all DataTables with class 'datatable'
    window.initDataTables = function() {
        if (typeof $ === 'undefined' || !$.fn.DataTable) {
            return;
        }

        $('.datatable').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                const config = window.getDataTablesConfig();
                $(this).DataTable(config);
            }
        });
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.initDataTables);
    } else {
        window.initDataTables();
    }
})();

