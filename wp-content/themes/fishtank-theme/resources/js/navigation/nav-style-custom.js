export function NavJS() {
	const burgerTrigger = document.querySelector('.burger-menu');

	// toggle the class on click
	burgerTrigger.addEventListener('click', () => {
		document.body.classList.toggle('mobile-nav-open');
		burgerTrigger.setAttribute(
			'aria-expanded',
			document.body.classList.contains('mobile-nav-open')
		);
	});
}
