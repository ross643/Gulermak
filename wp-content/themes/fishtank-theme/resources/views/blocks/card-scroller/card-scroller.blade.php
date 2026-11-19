<section @include('partials.block-attributes')>
	<div class="inner container--{{ isset($fields['container_size']) ? $fields['container_size'] : 'md' }}">
		<div class="inner__left">
			<InnerBlocks allowedBlocks="{{ WYSIWYG }}" template="{{ $template }}" class="wysiwyg" />
		</div>
		<div class="inner__right">
			@if (isset($fields['cards']))
				<div class="cards">
					@foreach ($fields['cards'] as $card)
						<div class="card">
							<h3 class="card__title">{{ $card['title'] }}</h3>
							<div class="card__content wysiwyg">
								{!! $card['content'] !!}
							</div>
							@if (isset($card['link']['url']))
								<div class="card__link">
									<a href="{{ $card['link']['url'] }}" target="{{ $card['link']['target'] }}">
										{{ $card['link']['title'] }}
										@svg('arrow--right')
									</a>
								</div>
							@endif
						</div>
					@endforeach
				</div>
			@endif

		</div>
	</div>
</section>
