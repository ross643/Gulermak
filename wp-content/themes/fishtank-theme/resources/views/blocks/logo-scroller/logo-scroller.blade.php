<section @include('partials.block-attributes')>
	<div class="inner container--{{ isset($fields['container_size']) ? $fields['container_size'] : 'md' }}">
		<header class="container--md">
			@if (isset($fields['title']) && !empty($fields['title']))
				<h2 class="wp-block-fishtank-logo-slider__title">{!! $fields['title'] !!}</h2>
			@endif
			{{-- @if ($fields['description'])
				<div class="wp-block-fishtank-logo-slider__description">{!! $fields['description'] !!}</div>
			@endif --}}
			{{-- @if ($fields['show_slider_navigation'])
				<div class="slider-nav">
					<button data-block="{{ $block['id'] }}" class="logo-slider-btn logo-slider-btn--prev">@svg('arrow--left')</button>
					<button data-block="{{ $block['id'] }}" class="logo-slider-btn logo-slider-btn--next">@svg('arrow--right')</button>
				</div>
			@endif --}}
		</header>
		<div class="marquee-wrapper" id="marquee-wrapper-{{ $block['id'] }}">
			@foreach (['top_slider', 'bottom_slider'] as $slider)
				@if (isset($fields[$slider]) && is_array($fields[$slider]) && count($fields[$slider]) > 0)
					<div class="marquee {{ $loop->first ? '' : 'marquee-ltr' }}">
						@for ($i = 0; $i < 2; $i++)
							<ul class="marquee__group" style="animation-duration: {{ $fields['scroller_speed'] ?? 30 }}s;" {{ $i != 0 ? 'inert' : '' }}>
								@foreach ($fields[$slider] as $logo)
									@if (!is_array($logo))
										@continue
									@endif

									<li>
										@php
											$linkRaw = $logo['link'] ?? null;
											$linkUrl = is_array($linkRaw) ? $linkRaw['url'] ?? '' : (is_string($linkRaw) ? $linkRaw : '');
										@endphp
										@if (!empty($linkUrl))
											<a href="{{ $linkUrl }}" target="{{ $logo['open_in_a_new_window'] ? '_blank' : '_self' }}" rel="noopener noreferrer" class="logo-wrap">
												{!! wp_get_attachment_image($logo['logo'] ?? 0, 'full') !!}
											</a>
										@else
											<span class="logo-wrap">
												{!! wp_get_attachment_image($logo['logo'] ?? 0, 'full') !!}
											</span>
										@endif
									</li>
								@endforeach
							</ul>
						@endfor
					</div>
				@endif
			@endforeach
		</div>
	</div>
</section>
