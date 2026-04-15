import './bootstrap';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.flatpickr.l10ns.id = Indonesian;

const DATE_FIELDS_SELECTOR = 'input[type="date"], input[type="datetime-local"]';
const SELECT_FIELDS_SELECTOR = 'select:not([multiple]):not([size])';
const FILE_FIELDS_SELECTOR = 'input[type="file"]';
const PASSWORD_TOGGLE_SELECTOR = '.js-password-toggle[data-target]';
let dropdownEventsBound = false;

function shouldSkipReusableDropdown(selectEl) {
	if (!selectEl) {
		return true;
	}

	if (selectEl.classList.contains('flatpickr-monthDropdown-months')) {
		return true;
	}

	if (selectEl.closest('.flatpickr-calendar')) {
		return true;
	}

	if (selectEl.classList.contains('rn-select-ignore')) {
		return true;
	}

	return false;
}

function closeAllCustomDropdowns(exceptDropdown = null) {
	document.querySelectorAll('.custom-dropdown.open').forEach((dropdownEl) => {
		if (dropdownEl !== exceptDropdown) {
			dropdownEl.classList.remove('open');
		}
	});
}

function bindGlobalDropdownEvents() {
	if (dropdownEventsBound) {
		return;
	}

	document.addEventListener('click', () => {
		closeAllCustomDropdowns();
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			closeAllCustomDropdowns();
		}
	});

	dropdownEventsBound = true;
}

function buildReusableDropdown(selectEl) {
	const parentEl = selectEl.parentElement;
	if (!parentEl) {
		return;
	}

	const shellEl = document.createElement('div');
	shellEl.className = 'rn-select-shell';

	parentEl.insertBefore(shellEl, selectEl);
	shellEl.appendChild(selectEl);

	selectEl.classList.add('rn-select', 'rn-select-native');
	selectEl.dataset.rnDropdownReady = 'true';

	const dropdownEl = document.createElement('div');
	dropdownEl.className = 'custom-dropdown rn-select-dropdown';

	const triggerEl = document.createElement('button');
	triggerEl.type = 'button';
	triggerEl.className = 'custom-dropdown-trigger';

	const textEl = document.createElement('span');
	textEl.className = 'custom-dropdown-text';

	const chevronEl = document.createElement('span');
	chevronEl.className = 'chevron';
	chevronEl.setAttribute('aria-hidden', 'true');
	chevronEl.innerHTML = '&#9662;';

	triggerEl.appendChild(textEl);
	triggerEl.appendChild(chevronEl);

	const menuEl = document.createElement('div');
	menuEl.className = 'custom-dropdown-menu';

	const optionEls = Array.from(selectEl.options);

	optionEls.forEach((optionEl, index) => {
		if (optionEl.hidden) {
			return;
		}

		const itemEl = document.createElement('div');
		itemEl.className = 'custom-dropdown-item';
		itemEl.dataset.index = String(index);
		itemEl.textContent = optionEl.textContent ?? '';

		if (optionEl.disabled) {
			itemEl.classList.add('is-disabled');
		} else {
			itemEl.addEventListener('click', () => {
				selectEl.selectedIndex = index;
				selectEl.dispatchEvent(new Event('change', { bubbles: true }));
				dropdownEl.classList.remove('open');
			});
		}

		menuEl.appendChild(itemEl);
	});

	dropdownEl.appendChild(triggerEl);
	dropdownEl.appendChild(menuEl);
	shellEl.appendChild(dropdownEl);

	const syncFromSelect = () => {
		const selectedIndex = selectEl.selectedIndex >= 0 ? selectEl.selectedIndex : 0;
		const selectedOptionEl = optionEls[selectedIndex];

		textEl.textContent = selectedOptionEl?.textContent?.trim() || 'Pilih Opsi';
		textEl.classList.toggle('is-placeholder', !selectedOptionEl || selectedOptionEl.value === '');

		menuEl.querySelectorAll('.custom-dropdown-item').forEach((itemEl) => {
			itemEl.classList.toggle('selected', itemEl.dataset.index === String(selectedIndex));
		});

		if (selectEl.disabled) {
			dropdownEl.classList.add('is-disabled');
			triggerEl.disabled = true;
		} else {
			dropdownEl.classList.remove('is-disabled');
			triggerEl.disabled = false;
		}
	};

	triggerEl.addEventListener('click', (event) => {
		event.preventDefault();
		event.stopPropagation();

		if (selectEl.disabled) {
			return;
		}

		const willOpen = !dropdownEl.classList.contains('open');
		closeAllCustomDropdowns(dropdownEl);
		dropdownEl.classList.toggle('open', willOpen);
	});

	selectEl.addEventListener('change', syncFromSelect);

	if (selectEl.form) {
		selectEl.form.addEventListener('reset', () => {
			setTimeout(syncFromSelect, 0);
		});
	}

	syncFromSelect();
}

function initReusableDropdowns() {
	bindGlobalDropdownEvents();

	document.querySelectorAll(SELECT_FIELDS_SELECTOR).forEach((selectEl) => {
		if (selectEl.dataset.rnDropdownReady === 'true') {
			return;
		}

		if (shouldSkipReusableDropdown(selectEl)) {
			selectEl.dataset.rnDropdownReady = 'true';
			return;
		}

		if (selectEl.closest('.rn-select-shell')) {
			return;
		}

		buildReusableDropdown(selectEl);
	});
}

function initUnifiedSelectStyle() {
	document.querySelectorAll(SELECT_FIELDS_SELECTOR).forEach((selectEl) => {
		if (selectEl.dataset.rnSelectReady === 'true') {
			return;
		}

		if (shouldSkipReusableDropdown(selectEl)) {
			selectEl.dataset.rnSelectReady = 'true';
			return;
		}

		selectEl.classList.add('rn-select');
		selectEl.dataset.rnSelectReady = 'true';
	});
}

function buildDateConfig(inputEl) {
	const isDateTime = inputEl.type === 'datetime-local';

	const config = {
		locale: 'id',
		allowInput: true,
		disableMobile: true,
		prevArrow: '<span aria-hidden="true">&#10094;</span>',
		nextArrow: '<span aria-hidden="true">&#10095;</span>',
	};

	if (isDateTime) {
		config.enableTime = true;
		config.time_24hr = true;
		config.dateFormat = 'Y-m-d\\TH:i';
		config.altInput = true;
		config.altFormat = 'd/m/Y H:i';
	} else {
		config.dateFormat = 'Y-m-d';
		config.altInput = true;
		config.altFormat = 'd/m/Y';
	}

	if (inputEl.min) {
		config.minDate = inputEl.min;
	}

	if (inputEl.max) {
		config.maxDate = inputEl.max;
	}

	return config;
}

function initGlobalDatePickers() {
	if (typeof window.flatpickr !== 'function') {
		return;
	}

	document.querySelectorAll(DATE_FIELDS_SELECTOR).forEach((inputEl) => {
		if (inputEl.dataset.rnDateReady === 'true') {
			return;
		}

		const placeholderText = inputEl.type === 'datetime-local'
			? 'dd/mm/yyyy hh:mm'
			: 'dd/mm/yyyy';

		if (!inputEl.getAttribute('placeholder')) {
			inputEl.setAttribute('placeholder', placeholderText);
		}

		const pickerInstance = window.flatpickr(inputEl, buildDateConfig(inputEl));
		const styledInputs = [inputEl, pickerInstance.altInput].filter(Boolean);

		styledInputs.forEach((dateFieldEl) => {
			if (!dateFieldEl.getAttribute('placeholder')) {
				dateFieldEl.setAttribute('placeholder', placeholderText);
			}

			dateFieldEl.classList.add('rn-date-input');
		});

		inputEl.dataset.rnDateReady = 'true';
	});
}

function resolveUploadPreviewContainer(inputEl) {
	const explicitTarget = inputEl.dataset.previewTarget;
	if (explicitTarget) {
		return document.querySelector(explicitTarget);
	}

	const profileWrap = inputEl.closest('.account-profile-photo-wrap');
	if (profileWrap) {
		return profileWrap.querySelector('.account-profile-photo-preview');
	}

	const controlWrap = inputEl.closest('div') || inputEl.parentElement;
	if (!controlWrap) {
		return null;
	}

	let previewEl = controlWrap.querySelector('.rn-upload-preview');
	if (!previewEl) {
		previewEl = document.createElement('div');
		previewEl.className = 'rn-upload-preview';

		const hintEl = controlWrap.querySelector('small');
		if (hintEl) {
			hintEl.insertAdjacentElement('beforebegin', previewEl);
		} else {
			inputEl.insertAdjacentElement('afterend', previewEl);
		}
	}

	return previewEl;
}

function restoreOriginalPreview(previewEl) {
	if (!previewEl) {
		return;
	}

	if (previewEl.dataset.rnOriginalHtml !== undefined) {
		previewEl.innerHTML = previewEl.dataset.rnOriginalHtml;
	}

	if (previewEl.classList.contains('rn-upload-preview')) {
		previewEl.classList.remove('is-visible');
	}
}

function renderImagePreview(inputEl, file) {
	const previewEl = resolveUploadPreviewContainer(inputEl);
	if (!previewEl) {
		return;
	}

	if (previewEl.dataset.rnOriginalHtml === undefined) {
		previewEl.dataset.rnOriginalHtml = previewEl.innerHTML;
	}

	if (!file || !file.type.startsWith('image/')) {
		restoreOriginalPreview(previewEl);
		return;
	}

	const reader = new FileReader();
	reader.onload = (event) => {
		previewEl.innerHTML = `
			<img src="${event.target?.result}" alt="Preview file upload">
			<div class="rn-upload-preview__meta">${file.name}</div>
		`;

		if (previewEl.classList.contains('rn-upload-preview')) {
			previewEl.classList.add('is-visible');
		}
	};
	reader.readAsDataURL(file);
}

function initUploadPreviews() {
	document.querySelectorAll(FILE_FIELDS_SELECTOR).forEach((inputEl) => {
		if (inputEl.dataset.rnPreviewReady === 'true') {
			return;
		}

		inputEl.addEventListener('change', () => {
			const selectedFile = inputEl.files?.[0] ?? null;
			renderImagePreview(inputEl, selectedFile);
		});

		inputEl.dataset.rnPreviewReady = 'true';
	});
}

function initPasswordToggles() {
	document.querySelectorAll(PASSWORD_TOGGLE_SELECTOR).forEach((buttonEl) => {
		if (buttonEl.dataset.rnPasswordReady === 'true') {
			return;
		}

		buttonEl.addEventListener('click', () => {
			const targetId = buttonEl.dataset.target;
			if (!targetId) {
				return;
			}

			const inputEl = document.getElementById(targetId);
			if (!inputEl) {
				return;
			}

			const eyeOpenEl = buttonEl.querySelector('.eye-open');
			const eyeClosedEl = buttonEl.querySelector('.eye-closed');
			const shouldShowText = inputEl.type === 'password';

			inputEl.type = shouldShowText ? 'text' : 'password';

			if (eyeOpenEl && eyeClosedEl) {
				eyeOpenEl.style.display = shouldShowText ? 'none' : 'block';
				eyeClosedEl.style.display = shouldShowText ? 'block' : 'none';
			}

			buttonEl.setAttribute(
				'aria-label',
				shouldShowText ? 'Sembunyikan password' : 'Tampilkan password'
			);
		});

		buttonEl.dataset.rnPasswordReady = 'true';
	});
}

function initGlobalFormEnhancements() {
	initUnifiedSelectStyle();
	initReusableDropdowns();
	initGlobalDatePickers();
	initUploadPreviews();
	initPasswordToggles();
}

Alpine.start();

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initGlobalFormEnhancements);
} else {
	initGlobalFormEnhancements();
}
