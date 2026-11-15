import 'bootstrap';
import 'admin-lte';
import { OverlayScrollbars, ClickScrollPlugin } from 'overlayscrollbars';

OverlayScrollbars.plugin(ClickScrollPlugin);

const sidebarWrapper = document.querySelector('.sidebar-wrapper');
if (sidebarWrapper && window.innerWidth > 992) {
    OverlayScrollbars(sidebarWrapper, {
        scrollbars: {
            theme: 'os-theme-light',
            autoHide: 'leave',
            clickScroll: true,
        },
    });
}
