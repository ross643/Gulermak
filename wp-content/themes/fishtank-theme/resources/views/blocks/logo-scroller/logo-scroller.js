window.addEventListener('load', function () {
	// --- PART 1: SETUP BUTTONS ---
	// We only want the buttons to toggle classes. We do NOT put animation logic here.

	const fastForwardButtons = document.querySelectorAll(
		'.logo-slider-btn--next'
	);
	const rewindButtons = document.querySelectorAll('.logo-slider-btn--prev');

	// Helper function to handle the click logic
	function setupButton(btn, stateClass) {
		const theBlock = document.getElementById(btn.dataset.block);
		// Safety check in case block ID is wrong
		if (!theBlock) return;

		const boxes = theBlock.querySelectorAll('.marquee__group');

		btn.addEventListener('click', () => {
			// Add the specific state class (boosting or reversing)
			boxes.forEach((box) => {
				// Remove conflicting class first to prevent getting stuck
				box.classList.remove('boosting', 'reversing');
				box.classList.add(stateClass);
			});

			// Remove class after 0.5 seconds (as per your code)
			setTimeout(() => {
				boxes.forEach((box) => box.classList.remove(stateClass));
			}, 500);
		});
	}

	// Initialize Next Buttons
	fastForwardButtons.forEach((btn) => setupButton(btn, 'boosting'));

	// Initialize Prev Buttons
	rewindButtons.forEach((btn) => setupButton(btn, 'reversing'));

	// --- PART 2: THE GLOBAL PHYSICS ENGINE ---
	// One loop to rule them all. This runs forever and handles every box on the page.

	function gameLoop() {
		// Select ALL marquee groups on the entire page
		// (This ensures we catch every box in every block)
		const allBoxes = document.querySelectorAll('.marquee__group');

		allBoxes.forEach((box) => {
			const anim = box.getAnimations()[0];

			if (anim) {
				let targetSpeed = 1; // Default Normal Speed

				// Check State
				if (box.classList.contains('boosting')) {
					targetSpeed = 20; // Fast Forward
				} else if (box.classList.contains('reversing')) {
					targetSpeed = -20; // Fast Backward
				}

				// Smooth Interpolation
				const currentSpeed = anim.playbackRate || 1;

				// "0.05" is the friction.
				// If 20 is too fast and feels jerky, lower 0.05 to 0.02 to make it ramp up slower
				const newSpeed =
					currentSpeed + (targetSpeed - currentSpeed) * 0.05;

				anim.playbackRate = newSpeed;
			}
		});

		requestAnimationFrame(gameLoop);
	}

	// Start the engine once
	gameLoop();
});
