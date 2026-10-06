import './bootstrap';

const storeMenuToggle = document.querySelector('[data-store-menu-toggle]');
const storeNavigation = document.querySelector('#store-navigation');

if (storeMenuToggle && storeNavigation) {
	const closeStoreMenu = () => {
		storeNavigation.classList.remove('is-open');
		storeMenuToggle.setAttribute('aria-expanded', 'false');
		storeMenuToggle.setAttribute('aria-label', 'Buka menu navigasi');
	};

	storeMenuToggle.addEventListener('click', () => {
		const isOpen = storeNavigation.classList.toggle('is-open');
		storeMenuToggle.setAttribute('aria-expanded', String(isOpen));
		storeMenuToggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
	});

	storeNavigation.querySelectorAll('a').forEach((link) => {
		link.addEventListener('click', closeStoreMenu);
	});

	window.addEventListener('resize', () => {
		if (window.innerWidth >= 768) closeStoreMenu();
	});
}

const notificationConfig = document.querySelector('#admin-order-notification-config');

if (notificationConfig && notificationConfig.dataset.ordersEnabled !== '0') {
	const storageKey = 'admin-last-order-id';
	const toggle = document.querySelector('#admin-notification-toggle');
	const label = document.querySelector('#admin-notification-label');
	const count = document.querySelector('#admin-notification-count');
	const toast = document.querySelector('#admin-order-toast');
	const toastText = document.querySelector('#admin-order-toast-text');
	let soundEnabled = false;
	let audioContext;
	let toastTimer;

	const formatTotal = (total) => new Intl.NumberFormat('id-ID', {
		style: 'currency', currency: 'IDR', maximumFractionDigits: 0,
	}).format(total);

	const playNotificationSound = () => {
		if (!soundEnabled) return;
		audioContext ??= new AudioContext();
		const oscillator = audioContext.createOscillator();
		const gain = audioContext.createGain();
		oscillator.frequency.setValueAtTime(880, audioContext.currentTime);
		oscillator.frequency.exponentialRampToValueAtTime(660, audioContext.currentTime + 0.16);
		gain.gain.setValueAtTime(0.12, audioContext.currentTime);
		gain.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.22);
		oscillator.connect(gain).connect(audioContext.destination);
		oscillator.start();
		oscillator.stop(audioContext.currentTime + 0.22);
	};

	const showOrderNotification = (order) => {
		count.textContent = Number(count.textContent || 0) + 1;
		count.classList.remove('hidden');
		toastText.textContent = `${order.number} dari ${order.customer} (${formatTotal(order.total)})`;
		toast.classList.remove('hidden');
		clearTimeout(toastTimer);
		toastTimer = setTimeout(() => toast.classList.add('hidden'), 8000);
		playNotificationSound();
		if ('Notification' in window && Notification.permission === 'granted') {
			new Notification('Pesanan baru masuk', { body: `${order.number} dari ${order.customer}` });
		}
	};

	const checkNewOrders = async (initial = false) => {
		const storedId = Number(localStorage.getItem(storageKey) || 0);
		const response = await fetch(`${notificationConfig.dataset.url}?since=${storedId}`, {
			headers: { Accept: 'application/json' },
		});
		if (!response.ok) return;
		const data = await response.json();
		const orders = data.orders || [];
		if (orders.length) {
			localStorage.setItem(storageKey, String(orders[orders.length - 1].id));
			if (!initial) orders.forEach(showOrderNotification);
		}
	};

	toggle?.addEventListener('click', async () => {
		soundEnabled = !soundEnabled;
		if (soundEnabled) {
			audioContext ??= new AudioContext();
			await audioContext.resume();
			playNotificationSound();
			if ('Notification' in window && Notification.permission === 'default') {
				await Notification.requestPermission();
			}
			toggle.setAttribute('aria-pressed', 'true');
			label.textContent = 'Notifikasi aktif';
		} else {
			toggle.setAttribute('aria-pressed', 'false');
			label.textContent = 'Aktifkan notifikasi';
		}
	});

	checkNewOrders(!localStorage.getItem(storageKey)).catch(() => {});
	window.setInterval(() => checkNewOrders().catch(() => {}), 10000);
}

const storeSettingsConfig = document.querySelector('#store-settings-config');
const paymentSelect = document.querySelector('#payment_method');

if (storeSettingsConfig && paymentSelect) {
	const transferEnabled = storeSettingsConfig.dataset.transferEnabled === '1';
	const codEnabled = storeSettingsConfig.dataset.codEnabled === '1';

	[...paymentSelect.options].forEach((option) => {
		if ((!transferEnabled && ['bank_transfer', 'qris'].includes(option.value)) || (!codEnabled && option.value === 'cod')) {
			option.remove();
		}
	});

	if (!paymentSelect.value && paymentSelect.options.length) {
		paymentSelect.value = paymentSelect.options[0].value;
	}

	if (transferEnabled && storeSettingsConfig.dataset.bankName && storeSettingsConfig.dataset.accountNumber) {
		const bankInfo = document.createElement('p');
		bankInfo.className = 'mt-2 text-xs leading-5 text-slate-500';
		bankInfo.textContent = `Transfer ke ${storeSettingsConfig.dataset.bankName} ${storeSettingsConfig.dataset.accountNumber} a.n. ${storeSettingsConfig.dataset.accountHolder || 'pemilik toko'}.`;
		paymentSelect.insertAdjacentElement('afterend', bankInfo);
	}
}
