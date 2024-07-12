import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js';
import '../css/app.css'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BookingLayout from '@/Layouts/BookingLayout.vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

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
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)

    app.component('VueDatePicker', VueDatePicker);

    app.mount(el);

  },
})

