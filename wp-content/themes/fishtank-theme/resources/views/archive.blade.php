@extends('layouts.app')

@section('content')
	@if (!empty($blocks))
		@foreach ($blocks as $block)
			@if ($block['blockName'] === 'fishtank/query-loop')
				@if (have_posts())
					<section class="container--lg">
						<ul>
							@while (have_posts())
								@php
									the_post();
								@endphp
								<li @php post_class() @endphp>
									<a href="{{ get_the_permalink() }}">
										{!! get_the_title() !!}
										{!! has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), 'thumbnail') : '' !!}
									</a>
								</li>
							@endwhile
						</ul>
					</section>
				@else
					<p>No posts found</p>
				@endif
			@else
				{!! render_block($block) !!}
			@endif
		@endforeach
	@endif
@endsection
