<div class="row d-none" id="skeleton-loader">
    <!-- Skeleton Loader -->
    {{-- for list style --}}
    @if (Session::has('view_type') && Session::get('view_type') == 'list')
      <div class="col-12">
        <div class="product-default product-list-card product-column radius-md mb-30">
          <!-- Skeleton for Product Image -->
          <figure class="product-img skeleton skeleton-img"></figure>

          <!-- Skeleton for Product Details -->
          <div class="product-details skeleton_details">
            <h3 class="skeleton-category skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <h3 class="product-title skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <p class="text skeleton skeleton-text"></p>
          </div>

          <!-- Skeleton for Product Action -->
          <div class="product-action">
            <!-- Skeleton for Product Price -->
            <div class="product-price">
              <span class="new-price skeleton skeleton-price"></span>
            </div>

            <!-- Skeleton for Buttons -->
            <div class="btn-icon-group">
              <a href="javascript:void(0)"
                class="btn btn-md btn-primary radius-sm mb-20 skeleton skeleton-btn-icon skeleton skeleton-add-to-cart"></a>
              <div>
                <button type="button" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></button>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
              </div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-12">
        <div class="product-default product-list-card product-column radius-md mb-30">
          <!-- Skeleton for Product Image -->
          <figure class="product-img skeleton skeleton-img"></figure>

          <!-- Skeleton for Product Details -->
          <div class="product-details skeleton_details">
            <h3 class="skeleton-category skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <h3 class="product-title skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <p class="text skeleton skeleton-text"></p>
          </div>

          <!-- Skeleton for Product Action -->
          <div class="product-action">
            <!-- Skeleton for Product Price -->
            <div class="product-price">
              <span class="new-price skeleton skeleton-price"></span>
            </div>

            <!-- Skeleton for Buttons -->
            <div class="btn-icon-group">
              <a href="javascript:void(0)"
                class="btn btn-md btn-primary radius-sm mb-20 skeleton skeleton-btn-icon skeleton skeleton-add-to-cart"></a>
              <div>
                <button type="button" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></button>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
              </div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-12">
        <div class="product-default product-list-card product-column radius-md mb-30">
          <!-- Skeleton for Product Image -->
          <figure class="product-img skeleton skeleton-img"></figure>

          <!-- Skeleton for Product Details -->
          <div class="product-details skeleton_details">
            <h3 class="skeleton-category skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <h3 class="product-title skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <p class="text skeleton skeleton-text"></p>
          </div>

          <!-- Skeleton for Product Action -->
          <div class="product-action">
            <!-- Skeleton for Product Price -->
            <div class="product-price">
              <span class="new-price skeleton skeleton-price"></span>
            </div>

            <!-- Skeleton for Buttons -->
            <div class="btn-icon-group">
              <a href="javascript:void(0)"
                class="btn btn-md btn-primary radius-sm mb-20 skeleton skeleton-btn-icon skeleton skeleton-add-to-cart"></a>
              <div>
                <button type="button" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></button>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
              </div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-12">
        <div class="product-default product-list-card product-column radius-md mb-30">
          <!-- Skeleton for Product Image -->
          <figure class="product-img skeleton skeleton-img"></figure>

          <!-- Skeleton for Product Details -->
          <div class="product-details skeleton_details">
            <h3 class="skeleton-category skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <h3 class="product-title skeleton">
              <a href="javascript:void(0)" class="skeleton skeleton-title"></a>
            </h3>

            <p class="text skeleton skeleton-text"></p>
          </div>

          <!-- Skeleton for Product Action -->
          <div class="product-action">
            <!-- Skeleton for Product Price -->
            <div class="product-price">
              <span class="new-price skeleton skeleton-price"></span>
            </div>

            <!-- Skeleton for Buttons -->
            <div class="btn-icon-group">
              <a href="javascript:void(0)"
                class="btn btn-md btn-primary radius-sm mb-20 skeleton skeleton-btn-icon skeleton skeleton-add-to-cart"></a>
              <div>
                <button type="button" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></button>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
                <a href="javascript:void(0)" class="btn btn-icon radius-sm skeleton skeleton-btn-icon"></a>
              </div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
    @else
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30 ">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>
            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>

            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>

            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>

            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>

            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>

            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>

            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
      <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="product-default product-center radius-xl mb-30">
          <div class="product-skeleton">
            <figure class="product-img">
              <div class="skeleton skeleton-img mb-1"></div>

            </figure>
            <div class="product-details skeleton_details">
              <div class="skeleton skeleton-category"></div>
              <div class="skeleton skeleton-title"></div>
              <div class="d-flex justify-content-center align-items-center">
              </div>
              <div class="skeleton skeleton-ratings"></div>
              <div class="skeleton skeleton-price"></div>
            </div>
            <div class="btn-icon-group btn-inline d-flex justify-content-center">
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
              <div class="skeleton skeleton-btn-icon"></div>
            </div>
          </div>
        </div> <!-- product-default -->
      </div>
    @endif
</div>
