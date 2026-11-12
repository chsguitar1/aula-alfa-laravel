<!doctype html>
<html lang="pt-BR">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Dashboard</title>
    @vite('resources/scss/app.scss')

    <script>
      (() => {
        'use strict';
        const root = document.documentElement;

        if (root.getAttribute('data-lte-color-mode') === 'off') {
          return;
        }

        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
          stored = localStorage.getItem(STORAGE_KEY);
        } catch {
          // localStorage pode não estar disponível.
        }
        const authored = root.getAttribute('data-bs-theme');
        let resolved = 'light';
        if (stored === 'dark' || stored === 'light') {
          resolved = stored;
        } else if (authored === 'dark' || authored === 'light') {
          resolved = authored;
        } else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) {
          resolved = 'dark';
        }
        root.setAttribute('data-bs-theme', resolved);
        root.style.colorScheme = resolved;
        if (resolved !== authored) {
          root.setAttribute('data-lte-theme-resolved', '');
        }
      })();
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind."
    />
    <meta name="supported-color-schemes" content="light dark" />

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
  </head>
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    @vite('resources/js/app.js')
    <div class="app-wrapper">
      @include('parts.header')
      @include('parts.sidebar')
      <main class="app-main">
        @include('parts.content-header')
        <div class="app-content">
          <div class="container-fluid">
            @yield('content')
          </div>
        </div>
      </main>
      @include('parts.footer')
    </div>

    <!-- OverlayScrollbars -->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>

    <!-- Gráficos acompanham o modo de cor -->
    <script>
      (() => {
        'use strict';
        const mode = () =>
          document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
        globalThis.Apex ||= {};
        const apex = globalThis.Apex;
        apex.theme = { mode: mode() };
        apex.chart = Object.assign(apex.chart || {}, { background: 'transparent' });
        new MutationObserver(() => {
          const next = mode();
          apex.theme = { mode: next };
          const instances = apex._chartInstances || [];
          for (const { chart } of instances) {
            chart.updateOptions({ theme: { mode: next } }, false, false);
          }
        }).observe(document.documentElement, {
          attributes: true,
          attributeFilter: ['data-bs-theme'],
        });
      })();
    </script>

    <!-- sortablejs -->
    <script
      src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
      crossorigin="anonymous"
    ></script>
    <script>
      const sortableArea = document.querySelector('.connectedSortable');
      if (sortableArea) {
        new Sortable(sortableArea, {
          group: 'shared',
          handle: '.card-header',
        });

        document.querySelectorAll('.connectedSortable .card-header').forEach((cardHeader) => {
          cardHeader.style.cursor = 'move';
        });
      }
    </script>

    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
    <script>
      const revenueChartElement = document.querySelector('#revenue-chart');
      if (revenueChartElement) {
        const sales_chart_options = {
          series: [
            { name: 'Digital Goods', data: [28, 48, 40, 19, 86, 27, 90] },
            { name: 'Electronics', data: [65, 59, 80, 81, 56, 55, 40] },
          ],
          chart: {
            id: 'revenue-chart',
            height: 300,
            type: 'area',
            toolbar: { show: false },
          },
          legend: { show: false },
          colors: ['#0d6efd', '#20c997'],
          dataLabels: { enabled: false },
          stroke: { curve: 'smooth' },
          xaxis: {
            type: 'datetime',
            categories: [
              '2023-01-01',
              '2023-02-01',
              '2023-03-01',
              '2023-04-01',
              '2023-05-01',
              '2023-06-01',
              '2023-07-01',
            ],
          },
          tooltip: { x: { format: 'MMMM yyyy' } },
        };

        new ApexCharts(revenueChartElement, sales_chart_options).render();
      }
    </script>

    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>
    <script>
      if (document.querySelector('#world-map')) {
        new jsVectorMap({
          selector: '#world-map',
          map: 'world',
        });
      }

      const sparklineData = {
        '#sparkline-1': [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
        '#sparkline-2': [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
        '#sparkline-3': [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
      };

      Object.entries(sparklineData).forEach(([selector, data], index) => {
        const element = document.querySelector(selector);
        if (!element) {
          return;
        }

        new ApexCharts(element, {
          series: [{ data }],
          chart: {
            id: `sparkline-${index + 1}`,
            type: 'area',
            height: 50,
            sparkline: { enabled: true },
          },
          stroke: { curve: 'straight' },
          fill: { opacity: 0.3 },
          yaxis: { min: 0 },
          colors: ['#DCE6EC'],
        }).render();
      });
    </script>
  </body>
</html>