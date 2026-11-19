document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('[data-expanding-service-cards]').forEach((rail) => {
		const cards = rail.querySelectorAll('[data-expanding-service-cards-card]');

		const activate = (index) => {
			cards.forEach((card, i) => {
				const active = i === index;
				const panel = card.querySelector('.expanding-service-cards__card-expanded');

				card.classList.toggle('is-active', active);
				card.setAttribute('aria-selected', active ? 'true' : 'false');
				card.setAttribute('tabindex', active ? '0' : '-1');

				if (panel) {
					if (active) {
						panel.removeAttribute('hidden');
					} else {
						panel.setAttribute('hidden', '');
					}
				}
			});
		};

		cards.forEach((card, index) => {
			card.addEventListener('click', (event) => {
				if (event.target.closest('a')) {
					return;
				}
				activate(index);
			});

			card.addEventListener('keydown', (event) => {
				if (event.key === 'Enter' || event.key === ' ') {
					event.preventDefault();
					activate(index);
				}
			});
		});
	});
});
