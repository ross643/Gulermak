@use('Illuminate\Support\Facades\Vite')
<!doctype html>
<html @php(language_attributes())>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,900;1,300;1,400;1,500;1,600;1,700;1,900&display=swap" rel="stylesheet">
	@php(do_action('get_header'))
	@vite(['resources/styles/critical.scss'])
	@vite(['resources/styles/navigation/nav-style-' . wp_get_global_settings(['custom', 'navigationStyle']) . '.scss'])
	@vite(['resources/styles/app.scss'])
	@vite(['resources/styles/footer/footer-style-' . wp_get_global_settings(['custom', 'footerStyle']) . '.scss'])
	@php(wp_head())
</head>

<body @php(body_class())>
	@php(wp_body_open())

	<a class="sr-only focus:not-sr-only" href="#main">
		{{ __('Skip to content', 'sage') }}
	</a>

	@include('sections.header')

	<main id="main" class="main">
		@yield('content')
	</main>

	{{-- @hasSection('sidebar')
		<aside class="sidebar">
			@yield('sidebar')
		</aside>
	@endif --}}

	@include('sections.footer')

	@vite(['resources/js/navigation/nav-style-' . wp_get_global_settings(['custom', 'navigationStyle']) . '.js', 'resources/js/app.js'])
	@php(do_action('get_footer'))
	@php(wp_footer())
</body>

</html>
