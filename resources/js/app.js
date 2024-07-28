import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BookingLayout from '@/Layouts/BookingLayout.vue'
import VueDatePicker from '@vuepic/vue-datepicker';

import '@vuepic/vue-datepicker/dist/main.css';
import '../css/app.css';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })

    let page = pages[`./Pages/${name}.vue`];

    const defaultLayout = {
      Booking: BookingLayout,
      Admin: AdminLayout,
    }[name.split('/')[0]];

    // apply default layout
    page.default.layout = page.default.layout || defaultLayout

    return page
  },
  setup({ el, App, props, plugin }) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const app = createApp({
      render: () => h(App, props),
      setup() {
        // provide('csrfToken', csrfToken);
      }
    })
      .use(plugin)
      .use(ZiggyVue)
      .use(createPinia())

    app.component('VueDatePicker', VueDatePicker);

    app.mount(el);
  },
})

