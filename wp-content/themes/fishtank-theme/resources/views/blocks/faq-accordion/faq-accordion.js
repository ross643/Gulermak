document.addEventListener('DOMContentLoaded', () => {
	document
		.querySelectorAll('[data-faq-accordion-trigger]')
		.forEach((trigger) => {
			trigger.addEventListener('click', () => {
				const expanded =
					trigger.getAttribute('aria-expanded') === 'true';
				const panel = trigger.getAttribute('aria-controls')
					? document.getElementById(
							trigger.getAttribute('aria-controls')
						)
					: null;

				if (!panel) return;

				trigger.setAttribute('aria-expanded', !expanded);

				if (expanded) {
					panel.classList.remove('faq-accordion__panel--open');
				} else {
					panel.classList.add('faq-accordion__panel--open');
				}
			});
		});
});
