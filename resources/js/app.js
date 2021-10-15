require('./bootstrap');

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect'
Alpine.plugin(intersect)

window.Alpine = Alpine;
Alpine.start();

var Turbolinks = require("turbolinks")
Turbolinks.start();

window.dayjs = require('dayjs');