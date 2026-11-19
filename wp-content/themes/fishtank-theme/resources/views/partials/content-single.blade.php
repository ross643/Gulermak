<article @php(post_class('h-entry'))>
	<header class="container--lg">
		<h1 class="entry-title">
			{!! $title !!}
		</h1>
		@include('partials.entry-meta')
	</header>
	@php(the_content())
	{{-- @php(comments_template()) --}}
</article>
