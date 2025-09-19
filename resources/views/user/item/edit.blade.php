@extends('user.layout')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
@endsection
@includeIf('user.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Edit Item') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Shop Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Products') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                    href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">{{ __('Items') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ truncateString($title, 35) ?? '-' }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Item') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Item') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-9 m-auto">
                            <div class="alert alert-danger pb-1 d-none" id="postErrors">
                                <ul></ul>
                            </div>
                            {{-- Slider images upload start --}}
                            <div class="px-2">
                                <label for="" class="mb-2"><strong>{{ __('Slider Images') }}
                                        <span class="text-danger">**</span></strong></label>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped" id="imgtable">
                                            @if (!is_null($item->sliders))
                                                @foreach ($item->sliders as $key => $img)
                                                    <tr class="trdb" id="trdb{{ $key }}">
                                                        <td>
                                                            <div class="thumbnail">
                                                                <img class="width-150"
                                                                    src="{{ asset('assets/front/img/user/items/slider-images/' . $img->image) }}"
                                                                    alt="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger pull-right rmvbtndb"
                                                                onclick="rmvdbimg({{ $key }},{{ $img->id }})">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <form action="{{ route('user.item.slider') }}" id="my-dropzone"
                                    enctype="multipart/form-data" class="dropzone create">
                                    <div class="dz-message">
                                        {{ __('Drag and drop files here to upload') }}
                                    </div>
                                    @csrf
                                    <div class="fallback">
                                    </div>
                                </form>
                                <p class="text-warning">
                                    <strong>{{ __('Recommended Image Size : 800 x 800') }}</strong>
                                </p>
                                @if ($errors->has('image'))
                                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('image') }}</p>
                                @endif
                            </div>
                            {{-- Slider images upload end --}}

                            <form id="itemForm" class="" action="{{ route('user.item.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                
                                {{-- Tipo do Produto --}}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="type">{{ __('Product Type') }} <span class="text-danger">**</span></label>
                                            <select name="type" id="productType" class="form-control" onchange="toggleProductFields()">
                                                <option value="fisico" {{ $item->type == 'fisico' ? 'selected' : '' }}>{{ __('Physical Product') }}</option>
                                                <option value="digital" {{ $item->type == 'digital' ? 'selected' : '' }}>{{ __('Digital Product') }}</option>
                                            </select>
                                            <small class="text-muted">{{ __('Select the product type to show relevant fields (weight, dimensions, etc.)') }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="sliders"></div>
                                {{-- thumbnail image start --}}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2 pl-0">
                                                <label for="">{{ __('Thumbnail Image') }} <span
                                                        class="text-danger">**</span></label>
                                            </div>

                                            <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                                                <img src="{{ isset($item->thumbnail) ? asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="cropped-thumbnail-image">
                                            </div>

                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                                        </div>
                                    </div>
                                    {{-- thumbnail image end --}}
                                    
                                    {{-- Campos específicos para cada tipo de produto --}}
                                    
                                    {{-- Campos para Produtos Digitais - controlados por JavaScript --}}
                                    @php
                                        $hasCodes = \App\Models\User\DigitalProductCode::where('user_item_id', $item->id)->count() > 0;
                                    @endphp
                                    
                                    @if (!$hasCodes)
                                        <div class="col-lg-4" data-product-type="digital" style="display: none;">
                                            <div class="form-group">
                                                <label for="">{{ __('Type') }} <span class="text-danger">**</span></label>
                                                <select name="file_type" class="form-control" id="fileType" onchange="toggleFileUpload();">
                                                    <option value="upload" {{ !empty($item->download_file) ? 'selected' : '' }}>
                                                        {{ __('File Upload') }}
                                                    </option>
                                                    <option value="link" {{ !empty($item->download_link) ? 'selected' : '' }}>
                                                        {{ __('File Download Link') }}
                                                    </option>
                                                    <option value="code">{{ __('Códigos') }}</option>
                                                </select>
                                            </div>
                                            <button type="button" id="downloadTemplateBtn" class="btn btn-info mt-2 d-none" onclick="downloadCodeTemplate()">
                                                <i class="fa fa-download"></i> Modelo de Planilha de Códigos
                                            </button>
                                        </div>
                                        
                                        <div class="col-lg-4" data-product-type="digital" style="display: none;">
                                            <div id="downloadFile" class="form-group {{ !empty($item->download_link) ? 'd-none' : '' }}">
                                                <label for="">{{ __('Downloadable File') }} <span class="text-danger">**</span></label>
                                                <br>
                                                <input name="download_file" type="file" class="form-control">
                                                <p class="mb-0 text-warning">{{ __('Only zip file is allowed.') }}</p>
                                                
                                                <!-- Botão para baixar modelo CSV -->
                                                <div class="mt-2">
                                                    <a href="{{ route('user.item.download.csv.model') }}" 
                                                       class="btn btn-info btn-sm" 
                                                       download="modeloDigital.csv">
                                                        <i class="fa fa-download"></i> {{ __('Baixar Modelo CSV') }}
                                                    </a>
                                                </div>

                                                {{-- Resumo do arquivo selecionado --}}
                                                <div id="file-summary" class="mt-2" style="display: none;">
                                                    <div class="alert alert-info">
                                                        <i class="fa fa-file-text"></i> 
                                                        <span id="file-lines-count">0</span> linhas encontradas no arquivo
                                                        <small class="d-block text-muted">
                                                            (1 linha de cabeçalho + <span id="data-lines-count">0</span> linhas de dados)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="downloadLink" class="form-group {{ !empty($item->download_link) ? '' : 'd-none' }}">
                                                <label for="">{{ __('Downloadable Link') }} <span class="text-danger">**</span></label>
                                                <input name="download_link" type="text" class="form-control" value="{{ $item->download_link }}">
                                            </div>
                                            
                                            <div id="codeUploadSection" class="mt-3 d-none">
                                                <div class="form-group">
                                                    <label for="codeExcelInput">
                                                        {{ __('Importar Planilha de Códigos') }}
                                                        <span class="text-danger">**</span>
                                                    </label>
                                                    <input type="file" class="form-control" name="codeExcelInput"
                                                        id="codeExcelInput" accept=".xlsx,.csv">

                                                    {{-- Feedback da validação do arquivo --}}
                                                    <div id="file-validation-feedback" class="mt-2"></div>

                                                    <div id="codeImportResult" class="mt-3 d-none">
                                                        <div class="alert alert-info">
                                                            <p><strong>Total de Códigos:</strong> <span id="totalCodes">0</span></p>
                                                            <p><strong>Variações encontradas:</strong></p>
                                                            <ul id="variationList" class="mb-0"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-4" data-product-type="digital" style="display: none;">
                                            <div class="form-group">
                                                <label>{{ __('Gerenciamento de Códigos') }}</label>
                                                <div>
                                                    <a class="btn btn-secondary btn-sm"
                                                       href="{{ route('user.item.codes', $item->id) . '?language=' . request()->input('language') }}">
                                                        <span class="btn-label">Gerenciar Código</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Status') }} <span
                                                    class="text-danger">**</span></label>
                                            <select class="form-control" name="status">
                                                <option value="" selected disabled>
                                                    {{ __('Select Status') }}</option>
                                                <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>
                                                    {{ __('Show') }}
                                                </option>
                                                <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>
                                                    {{ __('Hide') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>





                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for=""> {{ __('Current Price') }}
                                                ({{ $currency->symbol }}) <span class="text-danger">**</span></label>
                                            <input type="number" class="form-control" name="current_price"
                                                min="0.01" value="{{ $item->current_price }}" step="any"
                                                placeholder="{{ __('Enter Current Price') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Previous Price') }} (
                                                {{ $currency->symbol }}
                                                )</label>
                                            <input type="number" class="form-control" name="previous_price"
                                                min="0.01" value="{{ $item->previous_price }}" step="any"
                                                placeholder="{{ __('Enter Previous Price') }}">
                                        </div>
                                    </div>

                                    {{-- Campos para Produtos Físicos - sempre disponíveis --}}
                                    <div class="col-lg-4" data-product-type="fisico" style="display: none;">
                                        <div class="form-group">
                                            <label for="">{{ __('Stock') }} <span class="text-danger">**</span></label>
                                            <input type="number" class="form-control" name="stock"
                                                placeholder="{{ __('Enter Stock') }}" min="0" required
                                                value="{{ $item->stock ?? 0 }}">
                                            <p class="mb-0 text-warning">
                                                {{ __('If the item has variations, then set the stocks in the variations page') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4" data-product-type="fisico" style="display: none;">
                                        <div class="form-group">
                                            <label for="sku">SKU do Produto <span class="text-danger">**</span></label>
                                            <input type="number" class="form-control" name="sku"
                                                placeholder="Digite o SKU do produto" value="{{ $item->sku }}" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-2" data-product-type="fisico" style="display: none;">
                                        <div class="form-group">
                                            <label for="weight">Peso (kg) <span class="text-danger">**</span></label>
                                            <input type="number" step="any" min="0.00" class="form-control"
                                                name="weight" placeholder="Peso em kg" value="{{ $item->weight }}" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-2" data-product-type="fisico" style="display: none;">
                                        <div class="form-group">
                                            <label for="length">Comprimento (cm) <span class="text-danger">**</span></label>
                                            <input type="number" step="1" min="0" class="form-control"
                                                name="length" placeholder="Comprimento em cm"
                                                value="{{ $item->length }}" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-2" data-product-type="fisico" style="display: none;">
                                        <div class="form-group">
                                            <label for="width">Largura (cm) <span class="text-danger">**</span></label>
                                            <input type="number" step="1" min="0" class="form-control"
                                                name="width" placeholder="Largura em cm"
                                                value="{{ $item->width }}" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-2" data-product-type="fisico" style="display: none;">
                                        <div class="form-group">
                                            <label for="height">Altura (cm) <span class="text-danger">**</span></label>
                                            <input type="number" step="1" min="0" class="form-control"
                                                name="height" placeholder="Altura em cm"
                                                value="{{ $item->height }}" required>
                                        </div>
                                    </div>


                                    @php
                                        // Verificar se $lang existe, senão usar idioma padrão
                                        if (!$lang) {
                                            $lang = App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)
                                                                           ->where('is_default', 1)
                                                                           ->first();
                                        }
                                        
                                        $postData = $lang ? $lang->itemInfo()->where('item_id', $item->id)->first() : null;

                                        $categories = $lang ? App\Models\User\UserItemCategory::where('language_id', $lang->id)
                                            ->where('user_id', Auth::guard('web')->user()->id)
                                            ->where('status', 1)
                                            ->orderBy('name', 'asc')
                                            ->get() : collect();
                                    @endphp
                                    <input hidden id="subcatGetterForItem" value="{{ route('user.item.subcatGetter') }}">
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $lang && $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                                            <label>{{ __('Category') }} <span class="text-danger">**</span></label>
                                            <select data-code="{{ $lang ? $lang->code : 'pt' }}" name="category"
                                                class="form-control getSubCategory">
                                                <option value="">{{ __('Select Category') }}
                                                </option>
                                                @foreach ($categories as $category)
                                                    <option @selected(@$postData->category_id == $category->id) value="{{ $category->id }}">
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-warning" data-tooltip="tooltip"
                                                data-bs-placement="top"
                                                title="{{ __('After changing the category, you must re-add item variations; otherwise, variations from the previous category may be displayed incorrectly.') }}">
                                                {{ __('Changing the category may affect your product variations.') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $lang && $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                                            <label>{{ __('Subcategory') }}</label>
                                            <select data-code="{{ $lang ? $lang->code : 'pt' }}" name="subcategory"
                                                id="{{ $lang ? $lang->code : 'pt' }}_subcategory" class="form-control">
                                                <option value="">
                                                    {{ __('Select Subcategory') }}</option>
                                                @php
                                                    $sub_categories = collect();
                                                    if ($postData && $lang) {
                                                        $sub_categories = App\Models\User\UserItemSubCategory::where(
                                                            'language_id',
                                                            $lang->id,
                                                        )
                                                            ->where('user_id', Auth::guard('web')->user()->id)
                                                            ->where('category_id', $postData->category_id)
                                                            ->get();
                                                    }
                                                @endphp

                                                @foreach ($sub_categories as $sub)
                                                    <option @selected(@$postData->subcategory_id == $sub->id) value="{{ $sub->id }}">
                                                        {{ $sub->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>





                                </div>
                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        @php
                                            $postData = $language ? $language->itemInfo()->where('item_id', $item->id)->first() : null;
                                        @endphp
                                        <div class="version">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                                        data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . ' ' . __('Language') }}
                                                        {{ $language->is_default == 1 ? __('(Default)') : '' }}
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div class="version-body {{ $language->rtl == 1 ? 'rtl text-right' : '' }}"
                                                    id="app{{ $language->code }}">
                                                    <div class="row">
                                                        <div class="col-lg-8">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Title') }} <span
                                                                        class="text-danger">**</span></label>
                                                                <input type="text"
                                                                    class="form-control {{ $language->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                                                    name="{{ $language->code }}_title"
                                                                    value="{{ is_null($postData) ? '' : $postData->title }}"
                                                                    placeholder="{{ __('Enter Title') }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Product Label') }}</label>
                                                                <select name="{{ $language->code }}_label_id"
                                                                    class="form-control">
                                                                    <option value="" selected>
                                                                        {{ __('Select product label') }}
                                                                    </option>

                                                                    @php
                                                                        $product_labels = App\Models\User\Label::where([
                                                                            ['user_id', Auth::guard('web')->user()->id],
                                                                            ['language_id', $language->id],
                                                                        ])->get();
                                                                    @endphp

                                                                    @foreach ($product_labels as $product_label)
                                                                        <option
                                                                            {{ $product_label->id == @$postData->label_id ? 'selected' : '' }}
                                                                            value="{{ $product_label->id }}">
                                                                            {{ $product_label->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>
                                                                    {{ __('Summary') }} <span
                                                                        class="text-danger">**</span>
                                                                </label>
                                                                <textarea class="form-control {{ $language->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                                                    name="{{ $language->code }}_summary" placeholder="{{ __('Enter Summary') }}" rows="8">{{ is_null($postData) ? '' : $postData->summary }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Description') }} <span
                                                                        class="text-danger">**</span></label>
                                                                <textarea id="{{ $language->code }}_PostContent" class="form-control summernote"
                                                                    name="{{ $language->code }}_description" placeholder="{{ __('Enter Description') }}" data-height="300">{{ is_null($postData) ? '' : $postData->description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Keywords') }}</label>
                                                                <input class="form-control"
                                                                    name="{{ $language->code }}_meta_keywords"
                                                                    placeholder="{{ __('Enter Meta Keywords') }}"
                                                                    value="{{ is_null($postData) ? '' : $postData->meta_keywords }}"
                                                                    data-role="tagsinput">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Meta Description') }}</label>
                                                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                                                    placeholder="{{ __('Enter Meta Description') }}">{{ is_null($postData) ? '' : $postData->meta_description }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            @php $currLang = $language; @endphp
                                                            @foreach ($languages as $lang)
                                                                @continue($lang->id == $currLang->id)
                                                                <div class="form-check py-0">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $lang->id }}', event)">
                                                                        <span
                                                                            class="form-check-sign">{{ __('Clone for') }}
                                                                            <strong
                                                                                class="text-capitalize text-secondary">{{ $lang->name }}</strong>
                                                                            {{ __('language') }}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" form="itemForm"
                                    class="btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- thumbnail --}}
    <p class="d-none" id="blob_image"></p>
    <div class="modal fade" id="thumbnail-image-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h2>{{ __('Thumbnail') }} <span class="text-danger">**</span></h2>
                    <button role="button" class="close btn btn-secondary mr-2 destroy-cropper d-none text-white"
                        data-dismiss="modal" aria-label="Close">
                        {{ __('Crop') }}
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @php
                            $d_none = 'none';
                        @endphp
                        <div class="thumb-preview" style="background: {{ $d_none }}">
                            <img src="{{ asset('assets/admin/img/noimage.jpg') }}"
                                data-no_image="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                class="uploaded-thumbnail-img" id="image">
                        </div>
                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="thumbnail-input" name="thumbnail-image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- thumbnail end --}}
@endsection

@section('scripts')
    {{-- Importar SheetJS --}}
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <script>
        document.getElementById('codeExcelInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validação inicial do tipo de arquivo
            const validExtensions = ['.csv', '.xls', '.xlsx'];
            const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
            
            if (!validExtensions.includes(fileExtension)) {
                alert('Arquivo deve ser um arquivo CSV (.csv) ou Excel (.xls, .xlsx)');
                document.getElementById('codeExcelInput').value = '';
                return;
            }

            // Mostrar feedback de processamento
            const feedbackDiv = document.getElementById('file-validation-feedback');
            if (feedbackDiv) {
                feedbackDiv.innerHTML = '<div class="alert alert-info">🔄 Validando arquivo, aguarde...</div>';
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                try {
                    const data = e.target.result;
                    let workbook;
                    let rows;

                    // Detecta tipo de arquivo
                    const isCSV = file.name.endsWith('.csv');

                    if (isCSV) {
                        // Se for CSV, lê direto como texto
                        workbook = XLSX.read(data, {
                            type: 'binary'
                        });
                        const sheetName = workbook.SheetNames[0];
                        const sheet = workbook.Sheets[sheetName];
                        rows = XLSX.utils.sheet_to_json(sheet, {
                            header: 1
                        });
                    } else {
                        // Se for Excel
                        const binary = new Uint8Array(e.target.result);
                        workbook = XLSX.read(binary, {
                            type: 'array'
                        });
                        const sheetName = workbook.SheetNames[0];
                        const sheet = workbook.Sheets[sheetName];
                        rows = XLSX.utils.sheet_to_json(sheet, {
                            header: 1
                        });
                    }

                    // Validação básica do arquivo
                    if (!rows || rows.length === 0) {
                        showValidationError('Arquivo vazio ou corrompido. Por favor, use o modelo CSV fornecido.');
                        return;
                    }

                    // Validação do cabeçalho
                    const header = rows[0];
                    const expectedHeaders = ['nome', 'codigo', 'valor'];
                    
                    if (!header || header.length < 3) {
                        showValidationError('Arquivo não possui o cabeçalho correto. Por favor, use o modelo CSV fornecido.');
                        return;
                    }
                    
                    // Verificar se o cabeçalho está correto
                    const headerValid = expectedHeaders.every((expectedHeader, index) => {
                        const actualHeader = header[index] ? header[index].toString().toLowerCase().trim() : '';
                        return actualHeader === expectedHeader;
                    });

                    if (!headerValid) {
                        showValidationError(`Formato de arquivo inválido!\n\n` +
                            `✅ Cabeçalho esperado: ${expectedHeaders.join(', ')}\n` +
                            `❌ Cabeçalho encontrado: ${header.join(', ')}\n\n` +
                            `Por favor, baixe e use o modelo CSV fornecido.`);
                        return;
                    }

                    // Remove cabeçalho para processar apenas os dados
                    const dataRows = rows.slice(1).filter(row => row && row.length > 0 && row.some(cell => cell !== null && cell !== undefined && cell.toString().trim() !== ''));

                    if (dataRows.length === 0) {
                        showValidationError('Nenhum dado encontrado no arquivo. Por favor, adicione códigos ao arquivo CSV seguindo o modelo.');
                        return;
                    }

                    // Validação detalhada dos dados
                    const validationResult = validateCsvData(dataRows);
                    
                    if (!validationResult.isValid) {
                        showValidationError(`Encontrados erros no arquivo:\n\n${validationResult.errors.join('\n')}\n\nPor favor, corrija os erros e tente novamente.`);
                        return;
                    }

                    // Sucesso - mostrar resumo da validação
                    showValidationSuccess(validationResult);

                    // Continuar com o processamento original
                    processValidFile(dataRows, validationResult);

                } catch (error) {
                    console.error('Erro ao processar arquivo:', error);
                    showValidationError('Erro ao processar o arquivo. Verifique se o arquivo não está corrompido e tente novamente.');
                }
            };

            reader.onerror = function() {
                showValidationError('Erro ao ler o arquivo. Tente novamente.');
            };

            reader.readAsBinaryString(file);
        });

        // Event listener para o campo download_file para mostrar resumo das linhas
        document.querySelector('input[name="download_file"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const summaryDiv = document.getElementById('file-summary');
            
            if (!file) {
                summaryDiv.style.display = 'none';
                return;
            }

            // Verificar se é arquivo CSV
            if (!file.name.toLowerCase().endsWith('.csv')) {
                summaryDiv.style.display = 'none';
                return;
            }

            // Mostrar loading
            summaryDiv.style.display = 'block';
            summaryDiv.innerHTML = `
                <div class="alert alert-info">
                    <i class="fa fa-spinner fa-spin"></i> Analisando arquivo...
                </div>
            `;

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const content = e.target.result;
                    const lines = content.split('\n').filter(line => line.trim() !== ''); // Remove linhas vazias
                    const totalLines = lines.length;
                    const dataLines = totalLines > 0 ? totalLines - 1 : 0; // Subtrai o cabeçalho

                    // Verificar se tem pelo menos o cabeçalho
                    if (totalLines === 0) {
                        summaryDiv.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i> Arquivo vazio
                            </div>
                        `;
                        return;
                    }

                    if (totalLines === 1) {
                        summaryDiv.innerHTML = `
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i> 
                                Apenas cabeçalho encontrado (nenhum dado)
                            </div>
                        `;
                        return;
                    }

                    // Mostrar resumo completo
                    summaryDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fa fa-file-text"></i> 
                            <strong>${totalLines} linhas</strong> encontradas no arquivo
                            <small class="d-block text-muted mt-1">
                                📋 1 linha de cabeçalho + 📊 ${dataLines} linhas de dados
                            </small>
                        </div>
                    `;

                } catch (error) {
                    summaryDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> 
                            Erro ao ler o arquivo. Verifique se é um CSV válido.
                        </div>
                    `;
                }
            };

            reader.onerror = function() {
                summaryDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-circle"></i> 
                        Erro ao ler o arquivo.
                    </div>
                `;
            };

            reader.readAsText(file);
        });

        function validateCsvData(dataRows) {
            const result = {
                isValid: true,
                errors: [],
                validLines: 0,
                totalLines: dataRows.length,
                variations: {},
                duplicateCodes: []
            };

            const seenCodes = new Set();

            dataRows.forEach((row, index) => {
                const lineNumber = index + 2; // +2 porque removemos o cabeçalho e index começa em 0
                const variation = row[0] !== undefined && row[0] !== null ? row[0].toString().trim() : '';
                const code = row[1] !== undefined && row[1] !== null ? row[1].toString().trim() : '';
                const value = row[2] !== undefined && row[2] !== null ? row[2].toString().trim() : '';

                // Validar campos obrigatórios
                if (!variation) {
                    result.errors.push(`Linha ${lineNumber}: Nome da variação não pode estar vazio`);
                    result.isValid = false;
                    return;
                }

                if (!code) {
                    result.errors.push(`Linha ${lineNumber}: Código não pode estar vazio`);
                    result.isValid = false;
                    return;
                }

                if (!value) {
                    result.errors.push(`Linha ${lineNumber}: Valor não pode estar vazio`);
                    result.isValid = false;
                    return;
                }

                // Validar se o valor é numérico
                const numericValue = parseFloat(value.replace(',', '.'));
                if (isNaN(numericValue)) {
                    result.errors.push(`Linha ${lineNumber}: Valor "${value}" deve ser numérico (ex: 10.50)`);
                    result.isValid = false;
                    return;
                }

                if (numericValue < 0) {
                    result.errors.push(`Linha ${lineNumber}: Valor não pode ser negativo`);
                    result.isValid = false;
                    return;
                }

                // Verificar códigos duplicados
                if (seenCodes.has(code.toLowerCase())) {
                    result.errors.push(`Linha ${lineNumber}: Código "${code}" já existe no arquivo`);
                    result.duplicateCodes.push(code);
                    result.isValid = false;
                    return;
                }
                seenCodes.add(code.toLowerCase());

                // Contar por variação
                result.variations[variation] = (result.variations[variation] || 0) + 1;
                result.validLines++;
            });

            return result;
        }

        function showValidationError(message) {
            const feedbackDiv = document.getElementById('file-validation-feedback');
            if (feedbackDiv) {
                feedbackDiv.innerHTML = `<div class="alert alert-danger">❌ ${message.replace(/\n/g, '<br>')}</div>`;
            } else {
                alert(message);
            }
            document.getElementById('codeExcelInput').value = '';
        }

        function showValidationSuccess(validationResult) {
            const variationsList = Object.entries(validationResult.variations)
                .map(([variation, count]) => `• ${variation}: ${count} código(s)`)
                .join('<br>');

            const feedbackDiv = document.getElementById('file-validation-feedback');
            if (feedbackDiv) {
                feedbackDiv.innerHTML = `
                    <div class="alert alert-success">
                        ✅ <strong>Arquivo validado com sucesso!</strong><br>
                        📊 <strong>Resumo:</strong><br>
                        • Total de linhas processadas: ${validationResult.totalLines}<br>
                        • Linhas válidas: ${validationResult.validLines}<br>
                        • Cabeçalho: removido automaticamente<br><br>
                        📋 <strong>Variações encontradas:</strong><br>
                        ${variationsList}
                    </div>
                `;
            }
        }

        function processValidFile(dataRows, validationResult) {
            const variations = validationResult.variations;
            let total = validationResult.validLines;

            if (total === 0) {
                alert('Nenhum código válido encontrado no arquivo.');
                document.getElementById('codeExcelInput').value = '';
                return;
            }

            // Atualiza na tela
            document.getElementById('codeImportResult').classList.remove('d-none');
            document.getElementById('totalCodes').innerText = total;

            const ul = document.getElementById('variationList');
            ul.innerHTML = '';

            Object.entries(variations).forEach(([variation, count]) => {
                const li = document.createElement('li');
                li.innerText = `${variation} → ${count} código(s)`;
                ul.appendChild(li);
            });
        }
    </script>
    
    <script>
        // Função para alternar campos baseado no tipo do produto
        function toggleProductFields() {
            const productType = document.getElementById('productType').value;
            
            // Selecionar elementos de produto físico e digital
            const physicalFields = document.querySelectorAll('[data-product-type="fisico"]');
            const digitalFields = document.querySelectorAll('[data-product-type="digital"]');
            
            // Controlar campos físicos
            physicalFields.forEach(field => {
                const inputs = field.querySelectorAll('input, select, textarea');
                if (productType === 'fisico') {
                    field.style.display = 'block';
                    field.classList.remove('d-none');
                    // Ativar validação required para campos físicos
                    inputs.forEach(input => {
                        if (input.name && (input.name === 'stock' || input.name === 'sku' || 
                            input.name === 'weight' || input.name === 'length' || 
                            input.name === 'width' || input.name === 'height')) {
                            input.setAttribute('required', 'required');
                        }
                    });
                } else {
                    field.style.display = 'none';
                    field.classList.add('d-none');
                    // Desativar validação required para campos físicos quando ocultos
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            });
            
            // Controlar campos digitais
            digitalFields.forEach(field => {
                const inputs = field.querySelectorAll('input, select, textarea');
                if (productType === 'digital') {
                    field.style.display = 'block';
                    field.classList.remove('d-none');
                    // Ativar validação required para campos digitais se necessário
                    inputs.forEach(input => {
                        if (input.name && (input.name.includes('file_type') || input.name.includes('download_'))) {
                            input.setAttribute('required', 'required');
                        }
                    });
                } else {
                    field.style.display = 'none';
                    field.classList.add('d-none');
                    // Desativar validação required para campos digitais quando ocultos
                    inputs.forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            });
        }
        
        // Executar ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            toggleProductFields();
        });
    </script>
    
    <script>
        "use strict";
        const currUrl = "{{ url()->current() }}";
        const fullUrl = "{!! url()->full() !!}";
        const uploadSliderImage = "{{ route('user.item.slider') }}";
        const rmvSliderImage = "{{ route('user.item.slider-remove') }}";
        const rmvDbSliderImage = "{{ route('user.item.db-slider-remove') }}";
    </script>
    <script src="{{ asset('assets/user/js/dropzone-slider.js') }}"></script>
    <script src="{{ asset('assets/user/js/custom.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugin/cropper.js') }}"></script>
    <script src="{{ asset('assets/user/js/cropper-init.js') }}"></script>
@endsection
