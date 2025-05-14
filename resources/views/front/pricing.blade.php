@extends('front.layout')

@section('pagename')
  - {{ $pageHeading ?? __('Pricing') }}
@endsection

@section('meta-description', !empty($seo) ? $seo->pricing_meta_description : '')
@section('meta-keywords', !empty($seo) ? $seo->pricing_meta_keywords : '')

@section('breadcrumb-title')
  {{ $pageHeading ?? __('Pricing') }}
@endsection
@section('breadcrumb-link')
  {{ $pageHeading ?? __('Pricing') }}
@endsection

@section('content')

  <!-- Pricing Start -->
  <section class="pricing-area pt-120 pb-120">
    <div class="container">
      <div class="row">
        <div class="col-12">
          @if (count($terms) > 1)
            <div class="nav-tabs-navigation text-center" data-aos="fade-up">
              <ul class="nav nav-tabs">
                @foreach ($terms as $term)
                  <li class="nav-item">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                      data-bs-target="#{{ __("$term") }}" type="button">{{ __("$term") }}</button>
                  </li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="tab-content">
            @foreach ($terms as $term)
              <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} " id="{{ __("$term") }}">
                <div class="row">
                  @php
                    $packages = \App\Models\Package::where('status', '1')->where('term', strtolower($term))->get();
                  @endphp
                  @foreach ($packages as $package)
                    @php
                      $pFeatures = json_decode($package->features);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                      <div class="card mb-30 {{ $package->recommended == '1' ? 'active' : '' }}" data-aos="fade-up"
                        data-aos-delay="100">
                        <div class="d-flex align-items-center mb-4">
                          <div class="icon primary"><i class="{{ $package->icon }}"></i></div>
                          <div class="label">
                            <h3>{{ __($package->title) }}</h3>

                            @if ($package->recommended == '1')
                              <span>{{ __('Popular') }}</span>
                            @endif
                          </div>
                        </div>
                        <div class="d-flex align-items-center">
                          <span
                            class="price">{{ $package->price != 0 && $be->base_currency_symbol_position == 'left' ? $be->base_currency_symbol : '' }}{{ $package->price == 0 ? __('Free') : $package->price }}{{ $package->price != 0 && $be->base_currency_symbol_position == 'right' ? $be->base_currency_symbol : '' }}</span>
                          <span class="period">/ {{ __("$package->term") }}</span>

                        </div>
                        <h5>{{ __("What's Included") }}</h5>
                        <div class="list-item-area mb-20">
                          <ul class="item-list list-unstyled p-0 toggle-list" data-toggle-list="amenitiesToggle"
                            data-toggle-show="7">
                            <li class=""><i class="fal fa-check"></i>{{ __('Categories Limit') . ' :' }}
                              {{ $package->categories_limit != '999999' ? $package->categories_limit : __('Unlimited') }}
                            </li>
                            <li class=""><i class="fal fa-check"></i>{{ __('Subcategories Limit') . ' :' }}
                              {{ $package->subcategories_limit != '999999' ? $package->subcategories_limit : __('Unlimited') }}
                            </li>
                            <li class=""><i class="fal fa-check"></i>{{ __('Products Limit') . ' :' }}
                              {{ $package->product_limit != '999999' ? $package->product_limit : __('Unlimited') }}</li>

                            <li class=""><i class="fal fa-check"></i>{{ __('Orders Limit') . ' :' }}
                              {{ $package->order_limit != '999999' ? $package->order_limit : __('Unlimited') }}</li>

                            <li class=""><i class="fal fa-check"></i>{{ __('Additional Languages') . ' :' }}
                              {{ $package->language_limit != '999999' ? $package->language_limit : __('Unlimited') }}
                            </li>

                            @if (is_array($pFeatures) && in_array('Blog', $pFeatures))
                              <li class=""><i class="fal fa-check"></i>{{ __('Posts Limit') . ':' }}
                                {{ $package->post_limit != '999999' ? $package->post_limit : __('Unlimited') }}</li>
                            @else
                              <li class="disabled"><i class="fal fa-times"></i>{{ __('Blog') }}
                              </li>
                            @endif

                            @if (is_array($pFeatures) && in_array('Custom Page', $pFeatures))
                              <li class=""><i class="fal fa-check"></i>{{ __('Custom Pages Limit') . ' :' }}
                                {{ $package->number_of_custom_page != '999999' ? $package->number_of_custom_page : __('Unlimited') }}
                              </li>
                            @else
                              <li class="disabled"><i class="fal fa-times"></i>{{ __('Custom Page') }}
                              </li>
                            @endif

                            @php
                              $hideFeatures = ['Posts Limit', 'Blog', 'Custom Page'];
                            @endphp
                            @foreach ($allPfeatures as $feature)
                              @if (!in_array($feature, $hideFeatures))
                                <li
                                  class="{{ is_array($pFeatures) && in_array($feature, $pFeatures) ? '' : 'disabled' }}">
                                  <i
                                    class="{{ is_array($pFeatures) && in_array($feature, $pFeatures) ? 'fal fa-check' : 'fal fa-times' }}"></i>{{ __("$feature") }}
                                </li>
                              @endif
                            @endforeach

                          </ul>
                          <span class="show-more font-sm" data-toggle-btn="toggleListBtn">{{ __('Show More') }} +</span>
                        </div>

                        <div class="d-flex align-items-center">
                          @if ($package->is_trial === '1' && $package->price != 0)
                            <a href="{{ route('front.register.view', ['status' => 'trial', 'id' => $package->id]) }}"
                              class="btn secondary-btn">{{ __('Trial') }}</a>
                          @endif

                          @if ($package->price == 0)
                            <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $package->id]) }}"
                              class="btn secondary-btn">{{ __('Signup') }}</a>
                          @else
                            <a href="{{ route('front.register.view', ['status' => 'regular', 'id' => $package->id]) }}"
                              class="btn primary-btn">{{ __('Purchase') }}</a>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endforeach

                </div>
              </div>
            @endforeach

          </div>
        </div>
      </div>
    </div>
    <!-- Bg Overlay -->
    <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-2.png') }}" alt="Bg">
    <img class="lazyload bg-overlay" src="{{ asset('assets/front/images/shadow-bg-1.png') }}" alt="Bg">
    <!-- Bg Shape -->
    <div class="shape">
      <img class="lazyload shape-1" src="{{ asset('assets/front/images/shape/shape-6.png') }}" alt="Shape">
      <img class="lazyload shape-2" src="{{ asset('assets/front/images/shape/shape-7.png') }}" alt="Shape">
      <img class="lazyload shape-3" src="{{ asset('assets/front/images/shape/shape-3.png') }}" alt="Shape">
      <img class="lazyload shape-4" src="{{ asset('assets/front/images/shape/shape-4.png') }}" alt="Shape">
      <img class="lazyload shape-5" src="{{ asset('assets/front/images/shape/shape-5.png') }}" alt="Shape">
      <img class="lazyload shape-6" src="{{ asset('assets/front/images/shape/shape-11.png') }}" alt="Shape">
    </div>
  </section>
  <!-- Pricing End -->

@endsection
