@if (!empty($block['data']['is_preview']))
	@includeIf('partials.block-preview', ['block' => $block])
@else
	<section {!! !$is_preview ? get_block_wrapper_attributes() : '' !!}>
		<div class="container--1515">
			Query Loop Placeholder
		</div>
	</section>
@endif
