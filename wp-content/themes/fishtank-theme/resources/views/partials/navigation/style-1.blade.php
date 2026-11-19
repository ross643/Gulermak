@php
	/**
	 * This is the primary navigation template file for Style 1
	 *
	 * Controller: Navigation.php
	 */
@endphp
<div class="pre-navigation">
	<p class="container--lg text-align--center">
		Lipsome ipsum dolor, sit amet consectetur adipisicing <a href="#">elit</a>.
	</p>
</div>
<header class="site-header">
	<nav class="navigation navigation--style-1 primary-navigation container--lg" aria-label="Primary Navigation">
		<a href="{{ home_url() }}" title="{!! get_bloginfo('name') !!}" class="site-logo">
			<span class="sr-only">{!! get_bloginfo('name') !!}</span>
			@svg('fishtank-logo', ['class' => 'full-logo'])
			@svg('fishtank-ident', ['class' => 'ident-logo'])
		</a>
		@if ($menu->nav_items)
			<ul class="menu">
				@foreach ($menu->nav_items as $item)
					<li class="{{ trim(implode(' ', $item->classes)) }}{{ isset($item->children) ? ' menu-item--has-children' : '' }}">
						<a class="menu-link" href="{{ $item->url }}" aria-expanded="false">
							{!! $item->title !!}
						</a>
					</li>
				@endforeach
			</ul>
		@endif
		<div class="cta text-align--right">
			@isset($menu->acf_fields['call_to_action']['text'])
				<a href="{{ $menu->acf_fields['call_to_action']['link_type'] == 'link' ? $menu->acf_fields['call_to_action']['link'] : $menu->acf_fields['call_to_action']['custom_link'] }}" class="btn btn--primary" {{ $menu->acf_fields['call_to_action']['open_in_new_tab'] ? 'target="_blank"' : '' }}>
					{!! $menu->acf_fields['call_to_action']['text'] !!}
				</a>
			@endisset
			<button class="burger-menu" aria-label="Main Menu">
				@svg('burger', ['width' => 40, 'height' => 40])
				<span class="sr-only">Menu</span>
			</button>
		</div>
	</nav>
</header>
<aside class="mobile-navigation">
	<div class="container--lg">
		@if ($menu->nav_items)
			<ul class="menu">
				@foreach ($menu->nav_items as $item)
					<li class="{{ trim(implode(' ', $item->classes)) }}{{ isset($item->children) ? ' menu-item--has-children' : '' }}">
						<a class="menu-link" href="{{ $item->url }}" aria-expanded="false">
							{!! $item->title !!}
						</a>
					</li>
				@endforeach
				@isset($menu->acf_fields['call_to_action']['text'])
					<li class="cta">
						<a href="{{ $menu->acf_fields['call_to_action']['link_type'] == 'link' ? $menu->acf_fields['call_to_action']['link'] : $menu->acf_fields['call_to_action']['custom_link'] }}" class="btn btn--primary" {{ $menu->acf_fields['call_to_action']['open_in_new_tab'] ? 'target="_blank"' : '' }}>
							{!! $menu->acf_fields['call_to_action']['text'] !!}
						</a>
					</li>
				@endisset
				<li class="socials">
					{{-- This uses DASHICONS CSS enqueued in setup.php from WP core and needs replacing with proper SVGs  --}}
					<a href="#" class="dashicons dashicons-facebook-alt"></a>
					<a href="#" class="dashicons dashicons-linkedin"></a>
					<a href="#" class="dashicons dashicons-instagram"></a>
					<a href="#" class="dashicons dashicons-youtube"></a>
				</li>
			</ul>
		@endif
	</div>
</aside>
