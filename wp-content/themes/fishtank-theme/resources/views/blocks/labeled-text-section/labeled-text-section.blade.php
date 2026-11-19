<section @include('partials.block-attributes')>
	<div class="labeled-text-section inner {{ container_class($fields) }}">
		@if (!empty($fields['show_divider']))
			<hr class="labeled-text-section__divider" aria-hidden="true" />
		@endif

		<div class="labeled-text-section__layout">
			<h2 class="labeled-text-section__label" {!! acf_inline_text_editing_attrs('label') !!}>
				{!! $fields['label'] ?? '' !!}
			</h2>
			<div class="labeled-text-section__main">
				@if (!empty($fields['intro']))
					<p class="labeled-text-section__intro">{!! $fields['intro'] !!}</p>
				@endif

				@if (!empty($fields['body']))
					<div class="labeled-text-section__body">
						{!! $fields['body'] !!}
					</div>
				@endif
			</div>
		</div>
	</div>
</section>
