<a href="{{ get_field('link_type') == 'link' ? get_field('link') : get_field('custom_link') }}" {!! get_block_wrapper_attributes() !!} id="{{ $block['id'] }}" {{ get_field('open_in_new_tab') ? 'target="_blank"' : '' }}>
	{{ get_field('text') }}
</a>
