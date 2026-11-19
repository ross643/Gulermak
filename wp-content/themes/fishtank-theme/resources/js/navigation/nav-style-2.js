document.addEventListener('DOMContentLoaded', function () {
	/**
	 * Toggle the mobile navigation menu
	 * @param {HTMLElement} burgerTrigger - The burger menu button element
	 */
	const burgerTrigger = document.querySelector('.burger-menu');
	burgerTrigger.addEventListener('click', () => {
		document.body.classList.toggle('mobile-nav-open');
		burgerTrigger.setAttribute(
			'aria-expanded',
			document.body.classList.contains('mobile-nav-open')
		);
	});

	/**
	 * Toggle the sub-navigation menu
	 * @param {HTMLElement} subNavTrigger - The sub-navigation menu button element
	 */

	const subNavTriggers = document.querySelectorAll(
		'.menu-item--has-children'
	);

	subNavTriggers.forEach((trigger) => {
		// Toggle the sub-navigation on click
		trigger.onclick = function (event) {
			event.stopPropagation();
			subNavTriggers.forEach((trigger) => {
				if (trigger != this) {
					trigger.classList.remove('menu-item--is-active');
				}
			});
			this.classList.toggle('menu-item--is-active');
		};

		// Prevent clicks inside the sub-navigation from closing it
		const subNav = trigger.querySelector('.subnav');
		if (subNav) {
			subNav.onclick = function (event) {
				event.stopPropagation();
			};
		}
	});
	// Close the sub-navigation when clicking outside
	document.onclick = function (event) {
		if (
			!event.target.closest('.menu-item--has-children')
			// && !event.target.closest('.burger-menu')
		) {
			subNavTriggers.forEach((trigger) => {
				trigger.classList.remove('menu-item--is-active');
			});
			// document.body.classList.remove('mobile-nav-open');
			// burgerTrigger.setAttribute('aria-expanded', 'false');
		}
	};

	// Close the sub-navigation when scrolling more than 100px
	let lastScrollPosition = window.scrollY;
	window.addEventListener('scroll', () => {
		const currentScrollPosition = window.scrollY;
		if (Math.abs(currentScrollPosition - lastScrollPosition) > 200) {
			subNavTriggers.forEach((trigger) => {
				trigger.classList.remove('menu-item--is-active');
			});
		}
		lastScrollPosition = currentScrollPosition;
	});
});
