<section @include('partials.block-attributes')>
	<div class="inner container--{{ isset($fields['container_size']) ? $fields['container_size'] : 'md' }}">
		<InnerBlocks allowedBlocks="{{ WYSIWYG }}" template="{{ $template }}" class="wysiwyg" />
	</div>
	<!-- Slider main container -->
	<div class="swiper1">
		<!-- Additional required wrapper -->
		<div class="swiper-wrapper">
			<!-- Slides -->
			<div class="swiper-slide">Slide 1</div>
			<div class="swiper-slide">Slide 2</div>
			<div class="swiper-slide">Slide 3</div>
		</div>
	</div>

</section>
