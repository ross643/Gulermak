<section @include('partials.block-attributes')>
	<div class="expanding-service-cards inner {{ container_class($fields) }}">
		@if (!empty($fields['section_heading']) || !empty($fields['section_intro']))
			<header class="expanding-service-cards__header">
				@if (!empty($fields['section_heading']))
					<h2 class="expanding-service-cards__heading">{{ esc_html($fields['section_heading']) }}</h2>
				@endif
				@if (!empty($fields['section_intro']))
					<p class="expanding-service-cards__intro">{{ esc_html($fields['section_intro']) }}</p>
				@endif
			</header>
		@endif

		@if (!empty($fields['cards']) && is_array($fields['cards']))
			<div
				class="expanding-service-cards__rail"
				data-expanding-service-cards
				role="tablist"
				aria-label="{{ esc_attr(__('Service categories', 'sage')) }}"
			>
				@foreach ($fields['cards'] as $index => $card)
					@php
						$cid = $block['id'] . '-' . $index;
						$tab_id = 'expanding-card-tab-' . $cid;
						$panel_id = 'expanding-card-panel-' . $cid;
						$link = $card['button_link'] ?? [];
						$has_link = ! empty($link['url']);
					@endphp
					<div
						id="{{ esc_attr($tab_id) }}"
						class="expanding-service-cards__card {{ $loop->first ? 'is-active' : '' }}"
						data-expanding-service-cards-card
						role="tab"
						aria-selected="{{ $loop->first ? 'true' : 'false' }}"
						aria-controls="{{ esc_attr($panel_id) }}"
						tabindex="{{ $loop->first ? '0' : '-1' }}"
					>
						<div class="expanding-service-cards__card-collapsed">
							@if (!empty($card['card_title']))
								<span class="expanding-service-cards__card-label">{{ esc_html($card['card_title']) }}</span>
							@endif
							<span class="expanding-service-cards__card-icon" aria-hidden="true">
								@svg('lightning', ['class' => 'expanding-service-cards__lightning'])
							</span>
						</div>

						<div
							id="{{ esc_attr($panel_id) }}"
							class="expanding-service-cards__card-expanded"
							role="tabpanel"
							aria-labelledby="{{ esc_attr($tab_id) }}"
							@unless ($loop->first)
								hidden
							@endunless
						>
							@if (!empty($card['card_title']))
								<h3 class="expanding-service-cards__card-heading">{{ esc_html($card['card_title']) }}</h3>
							@endif

							@if (!empty($card['card_subheading']))
								<p class="expanding-service-cards__card-sub">{{ esc_html($card['card_subheading']) }}</p>
							@endif

							@if (!empty($card['card_description']))
								<div class="expanding-service-cards__card-body">
									{!! wpautop(esc_html($card['card_description'])) !!}
								</div>
							@endif

							@if ($has_link && !empty($card['button_text']))
								<a
									class="expanding-service-cards__cta"
									href="{{ esc_url($link['url']) }}"
									@if (!empty($link['target']))
										target="{{ esc_attr($link['target']) }}"
									@endif
									@if (!empty($link['target']) && $link['target'] === '_blank')
										rel="noopener noreferrer"
									@endif
									@if (!empty($link['title']))
										title="{{ esc_attr($link['title']) }}"
									@endif
								>
									@svg('lightning', ['class' => 'expanding-service-cards__cta-icon'])
									<span class="expanding-service-cards__cta-text">{{ esc_html($card['button_text']) }}</span>
								</a>
							@endif
						</div>
					</div>
				@endforeach
			</div>
		@endif
	</div>
</section>
