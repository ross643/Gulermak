@php
	/**
	 * This is the primary navigation template file for Style 2
	 * Controller: Navigation.php
	 */
@endphp

<div class="pre-navigation">
	<p class="container--lg text-align--center">
		Lipsome ipsum dolor, sit amet consectetur adipisicing <a href="#">elit</a>.
	</p>
</div>
<header class="site-header">
	<nav class="navigation navigation--style-2 primary-navigation container--lg" aria-label="Primary Navigation">
		<a href="{{ home_url() }}" title="{!! get_bloginfo('name') !!}" class="site-logo">
			<span class="sr-only">{!! get_bloginfo('name') !!}</span>
			@include('SVG::fishtank-logo', ['class' => 'full-logo'])
			@includeIf('SVG::fishtank-ident', ['class' => 'ident-logo'])
		</a>
		@if ($menu->nav_items)
			<ul class="menu">
				@foreach ($menu->nav_items as $item)
					@php
						$item->classes = array_merge($item->classes, ['menu-item']);
						if (isset($item->children)) {
						    $item->classes[] = 'menu-item--has-children';
						}
					@endphp
					<li class="{{ trim(implode(' ', $item->classes)) }}" @isset($item->children) aria-expanded="false" @endisset>
						<a class="menu-link" href="{{ empty($item->children) ? $item->url : '#!' }}">
							{!! $item->title !!}
							@if (!empty($item->children))
								@includeIf('SVG::chevron-down', ['color' => '#000', 'width' => 14])
							@endif
						</a>
						@if (!empty($item->children))
							<div class="subnav">
								<div class="subnav__inner container--lg">
									<div class="subnav__inner--left">
										<h4>{!! $item->attr_title !!}</h4>
										<p>{!! $item->description !!}</p>
									</div>
									<div class="subnav__inner--right">
										@if (!empty($item->children))
											<ul class="subnav__menu">
												@foreach ($item->children as $child)
													<li class="{{ trim(implode(' ', $child->classes)) }}">
														<a href="{{ $child->url }}">
															{!! $child->title !!}
														</a>
													</li>
												@endforeach
											</ul>
										@endif
									</div>
								</div>
							</div>
						@endif
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
				@includeIf('SVG::burger', ['width' => 40, 'height' => 40])
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
					@php
						$item->classes = array_merge($item->classes, ['menu-item']);
						if (isset($item->children)) {
						    $item->classes[] = 'menu-item--has-children';
						}
					@endphp
					<li class="{{ trim(implode(' ', $item->classes)) }}" @isset($item->children) aria-expanded="false" @endisset>
						<a class="menu-link" href="{{ empty($item->children) ? $item->url : '#!' }}" title="{!! $item->title !!}" aria-expanded="false">
							{!! $item->title !!}
							@if (!empty($item->children))
								@includeIf('SVG::chevron-down', ['color' => '#000', 'width' => 14])
							@endif
						</a>
						@if (!empty($item->children))
							<div class="subnav">
								<ul>
									@foreach ($item->children as $child)
										<li class="{{ trim(implode(' ', $child->classes)) }}" title="{{ $child->title }}">
											<a href="{{ $child->url }}">
												{!! $child->title !!}
											</a>
										</li>
									@endforeach
								</ul>
							</div>
						@endif
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
					{{-- @DEV: Obviously replace these with SVG icons from design if needed  --}}
					<a href="#" class="socials__facebook" title="Facebook">
						<span class="sr-only">Facebook</span>
						<img src="{{ placeholder(40, 40, 'FB') }}" alt="">
					</a>
					<a href="#" class="socials__twitter" title="Twitter">
						<span class="sr-only">Twitter</span>
						<img src="{{ placeholder(40, 40, 'X') }}" alt="">
					</a>
					<a href="#" class="socials__instagram" title="Instagram">
						<span class="sr-only">Instagram</span>
						<img src="{{ placeholder(40, 40, 'IG') }}" alt="">
					</a>
					<a href="#" class="socials__linkedin" title="LinkedIn">
						<span class="sr-only">LinkedIn</span>
						<img src="{{ placeholder(40, 40, 'LI') }}" alt="">
					</a>
					<a href="#" class="socials__youtube" title="YouTube">
						<span class="sr-only">YouTube</span>
						<img src="{{ placeholder(40, 40, 'YT') }}" alt="">
					</a>
				</li>
			</ul>

		@endif
	</div>
</aside>
