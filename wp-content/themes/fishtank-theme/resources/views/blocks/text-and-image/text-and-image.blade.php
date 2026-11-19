<section @include('partials.block-attributes')>
	<div class="inner container--{{ isset($fields['container_size']) ? $fields['container_size'] : 'md' }}">
		<figure class="inner__img">
			@isset($fields['image']['url'])
				<img src="{{ $fields['image']['url'] }}" alt="{{ $fields['image']['alt'] }}" />
			@endisset
		</figure>
		<div class="inner__text">
			<InnerBlocks allowedBlocks="{{ WYSIWYG }}" template="{{ $template }}" class="wysiwyg" />
		</div>
	</div>
</section>
