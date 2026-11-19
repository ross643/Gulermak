<section @include('partials.block-attributes')>
	<div class="faq-accordion inner {{ container_class($fields) }}">
		@if (!empty($fields['faq_items']) && is_array($fields['faq_items']))
			<div class="faq-accordion__list" role="list">
				@foreach ($fields['faq_items'] as $index => $item)
					@if (empty($item['question']) && empty($item['answer']))
						@continue
					@endif
					<div class="faq-accordion__item" role="listitem">
						<h3 class="faq-accordion__heading">
							<button type="button" class="faq-accordion__trigger" aria-expanded="false" aria-controls="faq-accordion-panel-{{ $block['id'] }}-{{ $index }}" id="faq-accordion-trigger-{{ $block['id'] }}-{{ $index }}" data-faq-accordion-trigger>
								<span class="faq-accordion__question">{{ esc_html($item['question'] ?? '') }}</span>
								<span class="faq-accordion__icon" aria-hidden="true">
									@svg('arrow--right')
								</span>
							</button>
						</h3>
						<div class="faq-accordion__panel" id="faq-accordion-panel-{{ $block['id'] }}-{{ $index }}" role="region" aria-labelledby="faq-accordion-trigger-{{ $block['id'] }}-{{ $index }}">
							<div class="faq-accordion__content">
								{!! $item['answer'] ?? '' !!}
							</div>
						</div>
					</div>
				@endforeach
			</div>

			@if (!empty($fields['include_faq_schema']))
				@php
					$schema_main_entity = array_map(
					    function ($item) {
					        return [
					            '@type' => 'Question',
					            'name' => wp_strip_all_tags($item['question'] ?? ''),
					            'acceptedAnswer' => [
					                '@type' => 'Answer',
					                'text' => wp_strip_all_tags($item['answer'] ?? ''),
					            ],
					        ];
					    },
					    array_filter($fields['faq_items'], function ($item) {
					        return !empty($item['question']) || !empty(wp_strip_all_tags($item['answer'] ?? ''));
					    }),
					);
					$faq_schema = [
					    '@context' => 'https://schema.org',
					    '@type' => 'FAQPage',
					    'mainEntity' => array_values($schema_main_entity),
					];
				@endphp
				<script type="application/ld+json">{!! wp_json_encode($faq_schema) !!}</script>
			@endif
		@endif
	</div>
</section>
