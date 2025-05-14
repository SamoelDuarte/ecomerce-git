<!-- show limit check Modal -->
@if ($currPackage)
  <div class="modal fade" id="limitModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title" id="exampleModalLabel">
            <h4 class="m-0">{{ __('All Limits') }}</h4>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="list-group limit-modal">
            <!-- item categories check -->
            <li class="list-group-item border">
              <div class="d-flex justify-content-between">
                <span>
                  @if ($totalCat > $catLimit)
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                  @endif
                  {{ __('Categories Left') }} :
                  @if ($catLimit < 999999)
                    @if ($canAddCat == 0)
                      <span class="mx-2 d-inline-block text-danger">{{ __('Limit is over') }}</span>
                    @elseif($totalCat > $catLimit)
                      <span class="mx-2 d-inline-block text-danger">
                        {{ __('Down Graded') }}
                      </span>
                    @endif
                  @endif
                </span>

                @if ($catLimit < 999999)
                  <span
                    class="badge @if ($totalCat > $catLimit) badge-danger @elseif($canAddCat == 0) badge-warning @else badge-primary @endif badge-sm">
                    @if ($totalCat > $catLimit)
                      0
                    @else
                      {{ $canAddCat }}
                    @endif

                  </span>
                @else
                  <span class="d-inline-block badge badge-success badge-pill">
                    {{ __('Unlimited') }}</span>
                @endif
              </div>
              @if ($totalCat > $catLimit)
                <span class="text-danger">{{ __('You need to delete') }}
                  {{ $totalCat - $catLimit }}
                  {{ __('categories') }}</span>
              @endif
            </li>
            <!-- item sub-categories check -->
            <li class="list-group-item border">
              <div class="d-flex justify-content-between">
                <span>
                  @if ($totalSubcat > $subcatLimit)
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                  @endif
                  {{ __('Subcategories Left') }} :
                  @if ($subcatLimit < 999999)
                    @if ($canAddSubcat == 0)
                      <span class="mx-2 d-inline-block text-danger">{{ __('Limit is over') }}</span>
                    @elseif($totalSubcat > $subcatLimit)
                      <span class="mx-2 d-inline-block text-danger">
                        {{ __('Down Graded') }}
                      </span>
                    @endif
                  @endif
                </span>

                @if ($subcatLimit < 999999)
                  <span
                    class="badge @if ($totalSubcat > $subcatLimit) badge-danger @elseif($canAddSubcat == 0) badge-warning @else badge-primary @endif badge-sm">
                    @if ($totalSubcat > $subcatLimit)
                      0
                    @else
                      {{ $canAddSubcat }}
                    @endif

                  </span>
                @else
                  <span class="d-inline-block badge badge-success badge-pill">
                    {{ __('Unlimited') }}</span>
                @endif
              </div>
              @if ($totalSubcat > $subcatLimit)
                <span class="text-danger">{{ __('You need to delete') }}
                  {{ $totalSubcat - $subcatLimit }}
                  {{ __('subcategories') }}</span>
              @endif
            </li>
            <!-- item limit check -->
            <li class="list-group-item border">
              <div class="d-flex justify-content-between">
                <span>
                  @if ($totalItem > $itemLimit)
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                  @endif
                  {{ __('Items Left') }} :
                  @if ($itemLimit < 999999)
                    @if ($canAddItem == 0)
                      <span class="mx-2 d-inline-block text-danger">{{ __('Limit is over') }}</span>
                    @elseif($totalItem > $itemLimit)
                      <span class="mx-2 d-inline-block text-danger">
                        {{ __('Down Graded') }}
                      </span>
                    @endif
                  @endif
                </span>

                @if ($itemLimit < 999999)
                  <span
                    class="badge @if ($totalItem > $itemLimit) badge-danger @elseif($canAddItem == 0) badge-warning @else badge-primary @endif badge-sm">
                    @if ($totalItem > $itemLimit)
                      0
                    @else
                      {{ $canAddItem }}
                    @endif

                  </span>
                @else
                  <span class="d-inline-block badge badge-success badge-pill">
                    {{ __('Unlimited') }}</span>
                @endif
              </div>
              @if ($totalItem > $itemLimit)
                <span class="text-danger">{{ __('You need to delete') }}
                  {{ $totalItem - $itemLimit }}
                  {{ __('items') }}</span>
              @endif
            </li>
            <!-- order limit check -->
            <li class="list-group-item border">
              <div class="d-flex justify-content-between">
                <span>
                  @if ($totalOrder > $orderLimit)
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                  @endif
                  {{ __('Orders Left') }} :
                  @if ($orderLimit < 999999)
                    @if ($canAddOrder == 0)
                      <span class="mx-2 d-inline-block text-danger">{{ __('Limit is over') }}</span>
                    @elseif($totalOrder > $orderLimit)
                      <span class="mx-2 d-inline-block text-danger">
                        {{ __('Down Graded') }}
                      </span>
                    @endif
                  @endif
                </span>

                @if ($orderLimit < 999999)
                  <span
                    class="badge @if ($totalOrder > $orderLimit) badge-danger @elseif($canAddOrder == 0) badge-warning @else badge-primary @endif badge-sm">
                    @if ($totalOrder > $orderLimit)
                      0
                    @else
                      {{ $canAddOrder }}
                    @endif

                  </span>
                @else
                  <span class="d-inline-block badge badge-success badge-pill">
                    {{ __('Unlimited') }}</span>
                @endif
              </div>
              @if ($totalOrder > $orderLimit)
                <span class="text-danger">{{ __('You need to delete') }}
                  {{ $totalOrder - $orderLimit }}
                  {{ __('orders') }}</span>
              @endif
            </li>
            <!-- blog post limit check -->
            @if (!is_null($package->post_limit))
              <li class="list-group-item border">
                <div class="d-flex justify-content-between">
                  <span>
                    @if ($totalBlog > $blogLimit)
                      <i class="fas fa-exclamation-triangle text-danger"></i>
                    @endif
                    {{ __('Post Left') }} :
                    @if ($blogLimit < 999999)
                      @if ($canAddBlog == 0)
                        <span class="mx-2 d-inline-block text-danger">{{ __('Limit is over') }}</span>
                      @elseif($totalBlog > $blogLimit)
                        <span class="mx-2 d-inline-block text-danger">
                          {{ __('Down Graded') }}
                        </span>
                      @endif
                    @endif
                  </span>

                  @if ($blogLimit < 999999)
                    <span
                      class="badge @if ($totalBlog > $blogLimit) badge-danger @elseif($canAddBlog == 0) badge-warning @else badge-primary @endif badge-sm">
                      @if ($totalBlog > $blogLimit)
                        0
                      @else
                        {{ $canAddBlog }}
                      @endif

                    </span>
                  @else
                    <span class="d-inline-block badge badge-success badge-pill">
                      {{ __('Unlimited') }}</span>
                  @endif
                </div>
                @if ($totalBlog > $blogLimit)
                  <span class="text-danger">{{ __('You need to delete') }}
                    {{ $totalBlog - $blogLimit }}
                    {{ __('post') }}</span>
                @endif
              </li>
            @endif
            <!-- language limit check -->
            <li class="list-group-item border">
              <div class="d-flex justify-content-between">
                <span>
                  @if ($totalLang > $langLimit)
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                  @endif
                  {{ __('Additional Languages Left') }} :
                  @if ($langLimit < 999999)
                    @if ($canAddLang == 0)
                      <span class="mx-2 d-inline-block text-danger">{{ __('Limit is over') }}</span>
                    @elseif($totalLang > $langLimit)
                      <span class="mx-2 d-inline-block text-danger">
                        {{ __('Down Graded') }}
                      </span>
                    @endif
                  @endif
                </span>

                @if ($langLimit < 999999)
                  <span
                    class="badge @if ($totalLang > $langLimit) badge-danger @elseif($canAddLang == 0) badge-warning @else badge-primary @endif badge-sm">
                    @if ($totalLang > $langLimit)
                      0
                    @else
                      {{ $canAddLang }}
                    @endif

                  </span>
                @else
                  <span class="d-inline-block badge badge-success badge-pill">
                    {{ __('Unlimited') }}</span>
                @endif
              </div>
              @if ($totalLang > $langLimit)
                <span class="text-danger">{{ __('You need to delete') }}
                  {{ $totalLang - $langLimit }}
                  {{ __('language') }}</span>
              @endif
            </li>
            <!-- custom page limit check -->
            @if (!is_null($package->number_of_custom_page))
              <li class="list-group-item border">
                <div class="d-flex justify-content-between">
                  <span>
                    @if ($totalCustomPage > $pageLimit)
                      <i class="fas fa-exclamation-triangle text-danger"></i>
                    @endif
                    {{ __('Additional Page Left') }} :
                    @if ($pageLimit < 999999)
                      @if ($canAddPage == 0)
                        <span class="mx-2 d-inline-block text-danger">{{ __('Limit is over') }}</span>
                      @elseif($totalCustomPage > $pageLimit)
                        <span class="mx-2 d-inline-block text-danger">
                          {{ __('Down Graded') }}
                        </span>
                      @endif
                    @endif
                  </span>

                  @if ($pageLimit < 999999)
                    <span
                      class="badge @if ($totalCustomPage > $pageLimit) badge-danger @elseif($canAddPage == 0) badge-warning @else badge-primary @endif badge-sm">
                      @if ($totalCustomPage > $pageLimit)
                        0
                      @else
                        {{ $canAddPage }}
                      @endif

                    </span>
                  @else
                    <span class="d-inline-block badge badge-success badge-pill">
                      {{ __('Unlimited') }}</span>
                  @endif
                </div>
                @if ($totalCustomPage > $pageLimit)
                  <span class="text-danger">{{ __('You need to delete') }}
                    {{ $totalCustomPage - $pageLimit }}
                    {{ __('additional page') }}</span>
                @endif
              </li>
            @endif


            <!-- other features -->
            @if ($permissions != null)
              @foreach ($permissions as $feature)
                @php
                  $feature = str_replace('_', ' ', $feature);
                @endphp
                <li class="list-group-item border">
                  <div class="d-flex justify-content-between">
                    <span>
                      {{ __($feature) }}
                      :
                    </span>

                    <span class="badge badge-success badge-pill">
                      {{ __('Enabled') }}</span>
                  </div>
                </li>
              @endforeach
            @endif
          </ul>
        </div>
      </div>
    </div>
  </div>
@endif
