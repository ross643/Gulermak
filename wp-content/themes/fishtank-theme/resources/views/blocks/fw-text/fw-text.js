import Swiper from 'swiper';
// // import { Navigation, Pagination } from 'swiper/modules';
// // import 'swiper/swiper-bundle.css';
// // init Swiper:

// // after window load
window.addEventListener('load', function () {
	// check if the element exists
	const swiperElement = document.querySelector('.swiper1');
	if (swiperElement) {
		// init Swiper:
		const swiper = new Swiper('.swiper', {
			// configure Swiper to use modules
			// modules: [Navigation, Pagination],
		});
	}
});
