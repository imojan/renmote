import './bootstrap';

import Alpine from 'alpinejs';

// Flatpickr date picker
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';
window.flatpickr = flatpickr;
window.flatpickrIndonesian = Indonesian;

window.Alpine = Alpine;

Alpine.start();
