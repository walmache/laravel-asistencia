/**
 * DataTables 2.3.5 Configuration - Compatible with Bootstrap 5
 *
 * Configuración usando SOLO clases de Bootstrap 5
 * - Tamaño pequeño (sm) en todos los componentes
 * - Bordes dark en inputs
 * - Paginación alineada a la derecha
 * - Espaciado con clases Bootstrap (mb-3, mt-3)
 */

(function() {
    'use strict';

    // Obtener locale actual
    function getLocale() {
        let locale = 'es';
        const localeMeta = document.querySelector('meta[name="app-locale"]');
        if (localeMeta) {
            locale = localeMeta.getAttribute('content');
        } else if (document.documentElement.lang) {
            locale = document.documentElement.lang;
        }
        return (locale === 'en' || locale.startsWith('en')) ? 'en' : 'es';
    }

    // Obtener URL base
    function getBaseUrl() {
        return window.location.origin || '';
    }

    // Configuración por defecto de DataTables
    window.getDataTablesConfig = function(customOptions = {}) {
        const locale = getLocale();
        const baseUrl = getBaseUrl();
        
        const defaultConfig = {
            language: {
                url: locale === 'en' 
                    ? baseUrl + '/js/datatables/i18n/en.json'
                    : baseUrl + '/js/datatables/i18n/es-ES.json',
                paginate: {
                    first: '«',
                    previous: '‹',
                    next: '›',
                    last: '»'
                },
                emptyTable: locale === 'es' 
                    ? 'No hay datos disponibles en la tabla'
                    : 'No data available in table'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, locale === 'es' ? "Todos" : "All"]],
            responsive: true,
            order: [],
            // DOM con clases Bootstrap 5 para espaciado y alineación
            dom: '<"row mb-3"<"col-sm-12 col-md-6 d-flex align-items-center"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>' +
                 '<"row"<"col-12"t>>' +
                 '<"row mt-3"<"col-sm-12 col-md-5 d-flex align-items-center small"i><"col-sm-12 col-md-7 d-flex justify-content-end"p>>',
            autoWidth: false,
            scrollX: false,
            pagingType: 'full_numbers',
            deferRender: true,
            drawCallback: function() {
                applyBootstrapClasses();
            },
            initComplete: function() {
                applyBootstrapClasses();
            }
        };

        return Object.assign({}, defaultConfig, customOptions);
    };

    // Aplicar clases de Bootstrap 5 a los componentes de DataTables
    function applyBootstrapClasses() {
        // Select de "Show entries" - tamaño SM con borde oscuro
        // Bootstrap form-select-sm ya tiene el padding correcto para el ícono dropdown
        document.querySelectorAll('div.dt-length select, .dataTables_length select').forEach(function(el) {
            el.classList.add('form-select', 'form-select-sm');
            el.style.borderColor = '#212529';
            el.style.width = 'auto';
            el.style.display = 'inline-block';
        });
        
        // Input de búsqueda - tamaño SM con borde oscuro
        document.querySelectorAll('div.dt-search input, .dataTables_filter input').forEach(function(el) {
            el.classList.add('form-control', 'form-control-sm');
            el.style.borderColor = '#212529';
            el.style.width = 'auto';
            el.style.display = 'inline-block';
        });
        
        // Labels - texto pequeño
        document.querySelectorAll('div.dt-length label, div.dt-search label, .dataTables_length label, .dataTables_filter label').forEach(function(el) {
            el.classList.add('small');
        });
        
        // Paginación - tamaño pequeño (pagination-sm)
        document.querySelectorAll('div.dt-paging .pagination, .dataTables_paginate .pagination').forEach(function(el) {
            el.classList.add('pagination-sm', 'mb-0');
        });
        
        // Info - texto pequeño
        document.querySelectorAll('div.dt-info, .dataTables_info').forEach(function(el) {
            el.classList.add('small');
        });
        
        // Wrapper - texto pequeño base
        document.querySelectorAll('.dt-container, .dataTables_wrapper').forEach(function(el) {
            el.classList.add('small');
        });
    }

    // Inicializar todas las tablas con clase 'datatable'
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

    // Auto-inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.initDataTables);
    } else {
        window.initDataTables();
    }
})();

