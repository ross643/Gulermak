@if ($menu->nav_items)
	<ul class="menu">
		@foreach ($menu->nav_items as $item)
			<li class="{{ trim(implode(' ', $item->classes)) }}{{ isset($item->children) ? ' menu-item--has-children' : '' }}">
				<a class="menu-link" href="{{ $item->url }}" aria-expanded="false">
					{!! $item->title !!}
					@if (!empty($item->children))
						@svg('chevron-down')
					@endif
				</a>
				@if (!empty($item->children))
					@if (!empty($item->acf_fields['dropdown_menu']))
						@php
							$subnav = $item->acf_fields['dropdown_menu'];
						@endphp
						<div class="subnav">
							<div class="subnav__inner container--large">
								<div class="subnav__content">
									@if (!empty($subnav['submenu_title']))
										<h2 class="subnav__content-heading">{!! $subnav['submenu_title'] !!}</h2>
									@endif
									@if (!empty($subnav['submenu_description']))
										<p class="subnav__content-description">{!! $subnav['submenu_description'] !!}</p>
									@endif
									@if (!empty($subnav['submenu_link']))
										<div class="subnav__content-btn wp-block-button is-style-primary">
											<a href="{{ $subnav['submenu_link']['url'] }}" target="_blank" class="wp-block-button__link" aria-label="{{ $subnav['submenu_link']['title'] }}">
												<span>Read More</span>
											</a>
										</div>
									@endif
								</div>
								<ul class="submenu">
									@foreach ($item->children as $subitem)
										<li class="submenu-item {{ trim(implode(' ', $subitem->classes)) }}">
											<a href="{{ $subitem->url }}" class="submenu-link" title="{!! $subitem->title !!}">
												{!! $subitem->title !!}
											</a>
										</li>
									@endforeach
								</ul>
								@if (!empty($subnav['image']))
									<picture>
										<source media="(min-width: 1400px)" srcset="{{ $subnav['image']['url'] }}">
										<source media="(min-width: 768px)" srcset="{{ $subnav['image']['sizes']['large'] }}">
										<img srcset="{{ $subnav['image']['sizes']['medium_large'] }}" alt="{!! $subnav['image']['alt'] !!}" class="subnav__img" loading="lazy">
									</picture>
								@else
									<img class="subnav__img" src="https://placehold.co/350x200/5b5b5b/FFFFFF/png">
								@endif
							</div>
						</div>
					@endif
				@endif
			</li>
		@endforeach
	</ul>
	@if (THEME_OPTIONS['nav-style'] === 1)
		@isset($menu->acf_fields['call_to_action']['text'])
			<div class="cta">
				<a href="{{ $menu->acf_fields['call_to_action']['link_type'] == 'link' ? $menu->acf_fields['call_to_action']['link'] : $menu->acf_fields['call_to_action']['custom_link'] }}" class="btn btn--primary" {{ $menu->acf_fields['call_to_action']['open_in_new_tab'] ? 'target="_blank"' : '' }}>
					{!! $menu->acf_fields['call_to_action']['text'] !!}
				</a>
			</div>
		@endisset
	@endif
@endif
