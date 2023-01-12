import './bootstrap';

import {createApp} from "vue";

import Alpine from 'alpinejs';
import MeteringPoint from "../views/components/meteringpoints/MeteringPoint.vue";
import Paginate from "vuejs-paginate-next";
import {
    Button,
    HasError,
    AlertError,
    AlertErrors,
    AlertSuccess
} from 'vform/src/components/bootstrap5'

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});
app.component('meteringpoint', MeteringPoint);
app.component('pagination', Paginate);
app.component(Button.name, Button);
app.component('has-error', HasError);
app.component(AlertError.name, AlertError);
app.component(AlertErrors.name, AlertErrors);
app.component(AlertSuccess.name, AlertSuccess);
app.mount('#app');
