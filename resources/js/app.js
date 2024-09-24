import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import { createORM } from 'pinia-orm'
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import AdminLayout from 'Layouts/AdminLayout.vue'
import BookingLayout from 'Layouts/BookingLayout.vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import '../css/app.css';

import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';

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
    const app = createApp({
      render: () => h(App, props),
      setup() {
      }
    })
      .use(plugin)
      .use(ZiggyVue)
      .use(createPinia().use(createORM()))
      .use(PrimeVue, {
        theme: {
          preset: Aura,
          options: {
            darkModeSelector: '.dark-mode',
            // cssLayer: {
            //     name: 'primevue',
            //     // order: 'tailwind-base, primevue, tailwind-utilities'
            // }
          }
        }
      })
    app.component('VueDatePicker', VueDatePicker);

    app.mount(el);
  },
})

