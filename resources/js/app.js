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
const CONFIRMABLE_FORM_SELECTOR = 'form[data-confirm-message]';
const INLINE_CONFIRM_SELECTOR = 'form[onsubmit*="confirm("], button[onclick*="confirm("], a[onclick*="confirm("]';
let dropdownEventsBound = false;
let confirmModalBound = false;
let confirmModalResolver = null;

function decodeHtmlEntities(text) {
	if (!text) {
		return '';
	}

	const textarea = document.createElement('textarea');
	textarea.innerHTML = text;
	return textarea.value;
}

function ensureConfirmModal() {
	let modalEl = document.getElementById('rnConfirmModal');
	if (modalEl) {
		return modalEl;
	}

	modalEl = document.createElement('div');
	modalEl.id = 'rnConfirmModal';
	modalEl.className = 'rn-confirm-modal';
	modalEl.innerHTML = `
		<div class="rn-confirm-modal__backdrop" data-rn-confirm-backdrop></div>
		<div class="rn-confirm-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="rnConfirmTitle">
			<div class="rn-confirm-modal__icon" aria-hidden="true">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M3 6h18" />
					<path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
					<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
					<path d="M10 11v6" />
					<path d="M14 11v6" />
				</svg>
			</div>
			<h3 id="rnConfirmTitle" class="rn-confirm-modal__title">Konfirmasi Aksi</h3>
			<p class="rn-confirm-modal__message" id="rnConfirmMessage">Apakah kamu yakin ingin melanjutkan aksi ini?</p>
			<div class="rn-confirm-modal__actions">
				<button type="button" class="rn-confirm-btn" data-rn-confirm-cancel>Batal</button>
				<button type="button" class="rn-confirm-btn rn-confirm-btn--danger" data-rn-confirm-submit>Ya, Lanjutkan</button>
			</div>
		</div>
	`;

	document.body.appendChild(modalEl);
	return modalEl;
}

function closeConfirmModal(confirmed) {
	const modalEl = document.getElementById('rnConfirmModal');
	if (!modalEl) {
		return;
	}

	modalEl.classList.remove('is-open');
	document.body.classList.remove('rn-confirm-open');

	if (typeof confirmModalResolver === 'function') {
		confirmModalResolver(confirmed);
		confirmModalResolver = null;
	}
}

function openConfirmModal({
	title = 'Konfirmasi Aksi',
	message = 'Apakah kamu yakin ingin melanjutkan aksi ini?',
	confirmText = 'Ya, Lanjutkan',
	cancelText = 'Batal',
} = {}) {
	const modalEl = ensureConfirmModal();

	const titleEl = modalEl.querySelector('#rnConfirmTitle');
	const messageEl = modalEl.querySelector('#rnConfirmMessage');
	const cancelBtnEl = modalEl.querySelector('[data-rn-confirm-cancel]');
	const submitBtnEl = modalEl.querySelector('[data-rn-confirm-submit]');

	if (titleEl) {
		titleEl.textContent = title;
	}

	if (messageEl) {
		messageEl.textContent = message;
	}

	if (cancelBtnEl) {
		cancelBtnEl.textContent = cancelText;
	}

	if (submitBtnEl) {
		submitBtnEl.textContent = confirmText;
	}

	modalEl.classList.add('is-open');
	document.body.classList.add('rn-confirm-open');

	return new Promise((resolve) => {
		confirmModalResolver = resolve;
	});
}

function extractInlineConfirmMessage(handlerText = '') {
	const matches = handlerText.match(/confirm\((['"])(.*?)\1\)/);
	if (!matches) {
		return null;
	}

	return decodeHtmlEntities(matches[2]);
}

function bindConfirmModalEvents() {
	if (confirmModalBound) {
		return;
	}

	const modalEl = ensureConfirmModal();
	const backdropEl = modalEl.querySelector('[data-rn-confirm-backdrop]');
	const cancelBtnEl = modalEl.querySelector('[data-rn-confirm-cancel]');
	const submitBtnEl = modalEl.querySelector('[data-rn-confirm-submit]');

	if (backdropEl) {
		backdropEl.addEventListener('click', () => closeConfirmModal(false));
	}

	if (cancelBtnEl) {
		cancelBtnEl.addEventListener('click', () => closeConfirmModal(false));
	}

	if (submitBtnEl) {
		submitBtnEl.addEventListener('click', () => closeConfirmModal(true));
	}

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			const currentModalEl = document.getElementById('rnConfirmModal');
			if (currentModalEl?.classList.contains('is-open')) {
				event.preventDefault();
				closeConfirmModal(false);
			}
		}
	});

	document.addEventListener('submit', async (event) => {
		const formEl = event.target;
		if (!(formEl instanceof HTMLFormElement)) {
			return;
		}

		const hasDataConfirm = formEl.matches(CONFIRMABLE_FORM_SELECTOR);
		const onsubmitText = formEl.getAttribute('onsubmit') ?? '';
		const inlineMessage = extractInlineConfirmMessage(onsubmitText);

		if (!hasDataConfirm && !inlineMessage) {
			return;
		}

		if (formEl.dataset.rnConfirmBypass === 'true') {
			delete formEl.dataset.rnConfirmBypass;
			return;
		}

		event.preventDefault();
		event.stopPropagation();

		if (inlineMessage) {
			formEl.removeAttribute('onsubmit');
		}

		const confirmed = await openConfirmModal({
			title: formEl.dataset.confirmTitle || 'Konfirmasi Aksi',
			message: formEl.dataset.confirmMessage || inlineMessage || 'Apakah kamu yakin ingin melanjutkan aksi ini?',
			confirmText: formEl.dataset.confirmConfirmText || 'Ya, Lanjutkan',
			cancelText: formEl.dataset.confirmCancelText || 'Batal',
		});

		if (!confirmed) {
			return;
		}

		formEl.dataset.rnConfirmBypass = 'true';
		if (typeof formEl.requestSubmit === 'function') {
			formEl.requestSubmit();
		} else {
			formEl.submit();
		}
	}, true);

	document.addEventListener('click', async (event) => {
		const clickableEl = event.target instanceof Element
			? event.target.closest(INLINE_CONFIRM_SELECTOR)
			: null;

		if (!clickableEl || clickableEl instanceof HTMLFormElement) {
			return;
		}

		if (clickableEl.dataset.rnConfirmBypass === 'true') {
			delete clickableEl.dataset.rnConfirmBypass;
			return;
		}

		const inlineMessage = extractInlineConfirmMessage(clickableEl.getAttribute('onclick') ?? '');
		if (!inlineMessage) {
			return;
		}

		event.preventDefault();
		event.stopPropagation();

		clickableEl.removeAttribute('onclick');

		const confirmed = await openConfirmModal({
			title: 'Konfirmasi Aksi',
			message: inlineMessage,
			confirmText: 'Ya, Lanjutkan',
			cancelText: 'Batal',
		});

		if (!confirmed) {
			return;
		}

		if (clickableEl instanceof HTMLAnchorElement && clickableEl.href) {
			window.location.href = clickableEl.href;
			return;
		}

		if (clickableEl instanceof HTMLButtonElement && clickableEl.form) {
			clickableEl.dataset.rnConfirmBypass = 'true';
			if (typeof clickableEl.form.requestSubmit === 'function') {
				clickableEl.form.requestSubmit(clickableEl);
			} else {
				clickableEl.form.submit();
			}
		}
	}, true);

	confirmModalBound = true;
}

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
	bindConfirmModalEvents();
}

Alpine.start();

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initGlobalFormEnhancements);
} else {
	initGlobalFormEnhancements();
}
