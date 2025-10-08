@extends('user.layout')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
    <style>
        .tags-input-container {
            position: relative;
        }

        .tag-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .tag-suggestion-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .tag-suggestion-item:hover,
        .tag-suggestion-item.selected {
            background-color: #f8f9fa;
        }

        .tag-suggestion-item:last-child {
            border-bottom: none;
        }

        .selected-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            min-height: 30px;
        }

        .tag-bubble {
            display: inline-flex;
            align-items: center;
            background-color: #007bff;
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 12px;
            white-space: nowrap;
        }

        .tag-remove {
            margin-left: 5px;
            cursor: pointer;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .tag-remove:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .tag-create-new {
            color: #28a745;
            font-weight: 500;
        }

        .tag-create-new:before {
            content: "+ ";
        }
    </style>
@endsection
@section('content')
    @php
        $type = request()->input('type');
    @endphp
    <div class="page-header">
        <h4 class="page-title">{{ __('Add Item') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
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
                <a href="#">{{ __('Items') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Item') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Add Item') }}</div>
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
                            <div class="px-2">
                                <label for="" class="mb-2"><strong>{{ __('Slider Images') }} <span
                                            class="text-danger">**</span></strong></label>
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
                                    <strong>{{ __('Recommended Image size : 800x800') }}</strong>
                                </p>
                                <p class="em text-danger mb-0" id="err_slider_images"></p>
                            </div>
                            <form id="itemForm" class="" action="{{ route('user.item.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="type" value="{{ request()->input('type') }}">
                                <input type="hidden" name="language" value={{ request()->input('language') }}>
                                <div id="sliders"></div>

                                {{-- START: Featured Image --}}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2 pl-0">
                                                <label for="image"><strong>{{ __('Thumbnail Image') }} <span
                                                            class="text-danger">**</span></strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                                                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                    class="cropped-thumbnail-image">
                                            </div>

                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                                        </div>
                                    </div>
                                    {{-- END: Featured Image --}}

                                    @if ($type == 'fisico')
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">{{ __('Stock') }}</label>
                                                <input type="number" value="0" id="productStock" class="form-control"
                                                    name="stock" min="0" placeholder="{{ __('Enter Stock') }}">
                                                <p class="mb-0 text-warning">
                                                    {{ __('If the item has variations, then set the stocks in the variations page') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($type == 'digital')
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">{{ __('Type') }} <span
                                                        class="text-danger">**</span></label>
                                                <select name="file_type" class="form-control" id="fileType"
                                                    onchange="toggleFileUpload()">
                                                    <option value="upload" selected>{{ __('File Upload') }}</option>
                                                    <option value="link">{{ __('File Download Link') }}</option>
                                                    <option value="code">Código</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-8" id="fileUploadContainer">
                                            <div id="downloadFile" class="form-group">
                                                <label for="">{{ __('Downloadable File') }} <span
                                                        class="text-danger">**</span></label>
                                                <br>

                                                <input name="download_file" type="file" class="form-control">



                                                {{-- Resumo do arquivo selecionado --}}
                                                <div id="file-summary" class="mt-2" style="display: none;">
                                                    <div class="alert alert-info">
                                                        <i class="fa fa-file-text"></i>
                                                        <span id="file-lines-count">0</span> linhas encontradas no arquivo
                                                        <small class="d-block text-muted">
                                                            (1 linha de cabeçalho + <span id="data-lines-count">0</span>
                                                            linhas de dados)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="downloadLink" class="form-group d-none">
                                                <label for="">{{ __('Downloadable Link') }} <span
                                                        class="text-danger">**</span></label>
                                                <input name="download_link" type="text" class="form-control">
                                            </div>
                                            <div id="codeUploadSection" class="d-none">
                                                <div class="form-group">
                                                    <label for="codeExcelInput">
                                                        {{ __('Importar Planilha de Códigos') }}
                                                        <span class="text-danger">**</span>
                                                    </label>
                                                    <input type="file" class="form-control" name="codeExcelInput"
                                                        id="codeExcelInput" accept=".xlsx,.csv,.xls">
                                                    <small class="form-text text-muted">
                                                        <a href="{{ asset('modelo_codigos_correto.csv') }}" download
                                                            class="text-primary">
                                                            <i class="fa fa-download"></i> Baixar modelo CSV de exemplo
                                                        </a>
                                                    </small>

                                                    {{-- Feedback da validação do arquivo --}}
                                                    <div id="file-validation-feedback" class="mt-2"></div>

                                                    <div id="codeImportResult" class="mt-3 d-none">
                                                        <div class="alert alert-info">
                                                            <p><strong>Total de Códigos:</strong> <span
                                                                    id="totalCodes">0</span></p>
                                                            <p><strong>Variações encontradas:</strong></p>
                                                            <ul id="variationList" class="mb-0"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($type == 'fisico')
                                        <!-- SKU -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">SKU do Produto <span
                                                        class="text-danger">**</span></label>
                                                <input type="text" class="form-control" name="sku"
                                                    value="{{ rand(1000000, 9999999) }}"
                                                    placeholder="Digite o SKU do produto">
                                            </div>
                                        </div>

                                        <!-- Peso -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Peso (kg) <span
                                                        class="text-danger">**</span></label>
                                                <input type="number" step="any" min="0.00" class="form-control"
                                                    name="weight" value="{{ old('weight') }}" placeholder="Ex: 1">
                                            </div>
                                        </div>

                                        <!-- Comprimento -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Comprimento (cm) <span
                                                        class="text-danger">**</span></label>
                                                <input type="number" step="1" min="0" class="form-control"
                                                    name="length" value="{{ old('length') }}" placeholder="Ex: 20">
                                            </div>
                                        </div>

                                        <!-- Largura -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Largura (cm) <span
                                                        class="text-danger">**</span></label>
                                                <input type="number" step="1" min="0" class="form-control"
                                                    name="width" value="{{ old('width') }}" placeholder="Ex: 15">
                                            </div>
                                        </div>

                                        <!-- Altura -->
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">Altura (cm) <span
                                                        class="text-danger">**</span></label>
                                                <input type="number" step="1" min="0" class="form-control"
                                                    name="height" value="{{ old('height') }}" placeholder="Ex: 10">
                                            </div>
                                        </div>
                                    @endif


                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Status') }} <span
                                                    class="text-danger">**</span></label>
                                            <select class="form-control" name="status">
                                                <option value="" selected disabled>
                                                    Selecionar Status
                                                </option>
                                                <option value="1">Visível</option>
                                                <option value="0">Oculto</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 price-group">
                                        <div class="form-group">
                                            <label for="">{{ __('Current Price') }} ({{ $currency->symbol }})
                                                <span class="text-danger">**</span></label>
                                            <input type="number" class="form-control" name="current_price"
                                                value="" step="any" min="0.01"
                                                placeholder="{{ __('Enter Current Price') }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 price-group">
                                        <div class="form-group">
                                            <label for="">{{ __('Previous Price') }}
                                                ({{ $currency->symbol }})</label>
                                            <input type="number" class="form-control" name="previous_price"
                                                value="" min="0.01" step="any"
                                                placeholder="{{ __('Enter Previous Price') }}">
                                        </div>
                                    </div>



                                    <input hidden id="subcatGetterForItem" value="{{ route('user.item.subcatGetter') }}">
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                                            <label>{{ __('Category') }} <span class="text-danger">**</span></label>
                                            <select data-code="{{ $lang->code }}" name="category"
                                                class="form-control getSubCategory">
                                                <option value="" disabled selected>
                                                    {{ __('Select Category') }}
                                                </option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                                            <label>{{ __('Subcategory') }}</label>
                                            <select data-code="{{ $lang->code }}" name="subcategory"
                                                id="{{ $lang->code }}_subcategory" class="form-control">
                                                <option value="" selected>
                                                    {{ __('Select Subcategory') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Campo de Tags -->
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('Tags') }}</label>
                                            <div class="tags-input-container">
                                                <input type="text" id="tagInput" class="form-control"
                                                    placeholder="Digite para buscar ou criar tags..." autocomplete="off">
                                                <div id="tagSuggestions" class="tag-suggestions"></div>
                                                <div id="selectedTags" class="selected-tags mt-2"></div>
                                                <input type="hidden" name="tags" id="tagsData">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
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
                                                <div
                                                    class="version-body {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                    <div class="row">
                                                        @php
                                                            $product_labels = App\Models\User\Label::where([
                                                                ['user_id', Auth::guard('web')->user()->id],
                                                                ['language_id', $language->id],
                                                            ])->get();
                                                        @endphp
                                                        <div class="col-lg-8">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Title') }} <span
                                                                        class="text-danger">**</span></label>
                                                                <input type="text"
                                                                    class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                                                    name="{{ $language->code }}_title"
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
                                                                    @foreach ($product_labels as $product_label)
                                                                        <option value="{{ $product_label->id }}">
                                                                            {{ $product_label->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Summary') }} <span
                                                                        class="text-danger">**</span></label>
                                                                <textarea class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                                                    name="{{ $language->code }}_summary" placeholder="{{ __('Enter Summary') }}" rows="8"></textarea>
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
                                                                    name="{{ $language->code }}_description" placeholder="{{ __('Enter Description') }}" data-height="300"></textarea>
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
                                                                    placeholder="{{ __('Enter Meta Description') }}"></textarea>
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
                                    class="btn btn-success">{{ __('Submit') }}</button>
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
                    <h2>{{ __('Thumbnail') }}*</h2>
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
                            <div role="button" class="btn btn-primary btn-sm  fw-bold upload-btn">
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
    <script>
        // ===== TAGS FUNCTIONALITY =====
        document.addEventListener('DOMContentLoaded', function() {
            const tagInput = document.getElementById('tagInput');
            const tagSuggestions = document.getElementById('tagSuggestions');
            const selectedTags = document.getElementById('selectedTags');
            const tagsData = document.getElementById('tagsData');

            let currentTags = [];
            let currentSuggestionIndex = -1;
            let searchTimeout;

            function updateTagsData() {
                tagsData.value = JSON.stringify(currentTags);
            }

            function addTag(tag) {
                // Verifica se a tag já existe
                if (currentTags.some(t => t.name.toLowerCase() === tag.name.toLowerCase())) {
                    return;
                }

                currentTags.push(tag);
                renderTags();
                updateTagsData();
                tagInput.value = '';
                hideSuggestions();
            }

            function removeTag(index) {
                currentTags.splice(index, 1);
                renderTags();
                updateTagsData();
            }

            function renderTags() {
                selectedTags.innerHTML = '';
                currentTags.forEach((tag, index) => {
                    const tagElement = document.createElement('div');
                    tagElement.className = 'tag-bubble';
                    tagElement.innerHTML = `
                    ${tag.name}
                    <span class="tag-remove" onclick="removeTag(${index})">×</span>
                `;
                    selectedTags.appendChild(tagElement);
                });
            }

            function showSuggestions(suggestions, searchTerm) {
                // Só mostrar sugestões se houver sugestões existentes
                if (suggestions.length === 0) {
                    hideSuggestions();
                    return;
                }

                tagSuggestions.innerHTML = '';
                currentSuggestionIndex = -1;

                // Adicionar apenas sugestões existentes (sem opção de criar nova)
                suggestions.forEach((suggestion, index) => {
                    const item = document.createElement('div');
                    item.className = 'tag-suggestion-item';
                    item.textContent = suggestion.name;
                    item.addEventListener('click', () => addTag(suggestion));
                    tagSuggestions.appendChild(item);
                });

                tagSuggestions.style.display = 'block';
            }

            function hideSuggestions() {
                tagSuggestions.style.display = 'none';
                currentSuggestionIndex = -1;
            }

            function searchTags(term) {
                if (term.length < 1) {
                    hideSuggestions();
                    return;
                }

                fetch(`{{ route('user.item.searchTags') }}?term=${encodeURIComponent(term)}`)
                    .then(response => response.json())
                    .then(data => {
                        showSuggestions(data, term);
                    })
                    .catch(error => {
                        console.error('Erro ao buscar tags:', error);
                        hideSuggestions();
                    });
            }

            // Event listeners
            tagInput.addEventListener('input', function() {
                const term = this.value.trim();

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchTags(term);
                }, 300); // Debounce de 300ms
            });

            tagInput.addEventListener('keydown', function(e) {
                const suggestions = tagSuggestions.querySelectorAll('.tag-suggestion-item');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    currentSuggestionIndex = Math.min(currentSuggestionIndex + 1, suggestions.length - 1);
                    updateSuggestionSelection(suggestions);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    currentSuggestionIndex = Math.max(currentSuggestionIndex - 1, -1);
                    updateSuggestionSelection(suggestions);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (currentSuggestionIndex >= 0 && suggestions[currentSuggestionIndex]) {
                        suggestions[currentSuggestionIndex].click();
                    } else if (this.value.trim()) {
                        // Criar nova tag
                        addTag({
                            id: null,
                            name: this.value.trim(),
                            slug: this.value.trim().toLowerCase().replace(/\s+/g, '-')
                        });
                    }
                } else if (e.key === ' ' || e.key === 'Spacebar') {
                    // Criar tag automaticamente quando pressionar espaço
                    const currentText = this.value.trim();
                    if (currentText) {
                        e.preventDefault(); // Impede o espaço de ser adicionado
                        addTag({
                            id: null,
                            name: currentText,
                            slug: currentText.toLowerCase().replace(/\s+/g, '-')
                        });
                    }
                } else if (e.key === 'Escape') {
                    hideSuggestions();
                }
            });

            function updateSuggestionSelection(suggestions) {
                suggestions.forEach((item, index) => {
                    item.classList.toggle('selected', index === currentSuggestionIndex);
                });
            }

            // Fechar sugestões ao clicar fora
            document.addEventListener('click', function(e) {
                if (!tagInput.contains(e.target) && !tagSuggestions.contains(e.target)) {
                    hideSuggestions();
                }
            });

            // Tornar funções globais para uso nos elementos
            window.removeTag = removeTag;
        });
    </script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <script>
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
            const feedbackDiv = document.getElementById('file-validation-feedback');
            if (feedbackDiv) {
                feedbackDiv.innerHTML = `
                <div class="alert alert-success">
                    ✅ <strong>Arquivo validado com sucesso!</strong><br>
                    • Total de códigos processados: ${validationResult.totalCodes}<br>
                    • Códigos únicos encontrados: ${validationResult.validLines}<br>
                </div>
            `;
            }
        }

        // ...existing code...

        // ...existing code...
        // Event listener principal
        document.getElementById('codeExcelInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Debug completo do arquivo
            console.log('=== DEBUG ARQUIVO SELECIONADO ===');
            console.log('Nome:', file.name);
            console.log('Tipo MIME:', file.type);
            console.log('Tamanho:', file.size, 'bytes');
            console.log('Última modificação:', file.lastModified);

            // Validação inicial do tipo de arquivo
            const validExtensions = ['.csv', '.xls', '.xlsx'];
            const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

            console.log('Arquivo selecionado:', file.name);
            console.log('Extensão detectada:', fileExtension);
            console.log('Extensões válidas:', validExtensions);

            if (!validExtensions.includes(fileExtension)) {
                const errorMsg =
                    `Arquivo "${file.name}" não é suportado. Extensão detectada: "${fileExtension}". Use apenas arquivos CSV (.csv) ou Excel (.xls, .xlsx)`;
                console.error(errorMsg);
                alert(errorMsg);
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
                    console.log('Tipo de arquivo:', isCSV ? 'CSV' : 'Excel');
                    console.log('Dados lidos (primeiros 200 chars):', data.substring(0, 200));

                    if (isCSV) {
                        // Para CSV, lê como texto e processa diretamente
                        workbook = XLSX.read(data, {
                            type: 'string'
                        });
                        const sheetName = workbook.SheetNames[0];
                        const sheet = workbook.Sheets[sheetName];
                        rows = XLSX.utils.sheet_to_json(sheet, {
                            header: 1,
                            defval: ''
                        });
                        console.log('Linhas processadas do CSV:', rows);
                        console.log('Primeira linha (cabeçalho):', rows[0]);
                        console.log('Total de linhas:', rows.length);
                    } else {
                        // Se for Excel, lê como binary
                        const binary = new Uint8Array(e.target.result);
                        workbook = XLSX.read(binary, {
                            type: 'array'
                        });
                        const sheetName = workbook.SheetNames[0];
                        const sheet = workbook.Sheets[sheetName];
                        rows = XLSX.utils.sheet_to_json(sheet, {
                            header: 1,
                            defval: ''
                        });
                    }

                    // Validação básica do arquivo
                    if (!rows || rows.length === 0) {
                        showValidationError(
                            'Arquivo vazio ou corrompido. Por favor, use o modelo CSV fornecido.');
                        return;
                    }

                    // Validação do cabeçalho - ACEITA APENAS UMA COLUNA
                    const header = rows[0];

                    if (!header || header.length < 1) {
                        showValidationError(
                            'Arquivo não possui o cabeçalho correto. O arquivo deve ter 1 coluna: codigo.');
                        return;
                    }

                    // Normalizar cabeçalho para comparação (remove acentos, espaços, converte para minúsculas)
                    function normalizeHeader(text) {
                        if (!text || text === null || text === undefined) return '';
                        return text.toString()
                            .toLowerCase()
                            .trim()
                            .normalize('NFD')
                            .replace(/[\u0300-\u036f]/g, '') // Remove acentos
                            .replace(/[^a-z0-9]/g, ''); // Remove caracteres especiais
                    }

                    const normalizedHeader = header.map(h => normalizeHeader(h));

                    // Aceitar diferentes variações do cabeçalho (APENAS 1 COLUNA)
                    const validHeaderNames = ['codigo', 'code', 'key', 'chave', 'nome'];

                    const headerValid = validHeaderNames.includes(normalizedHeader[0]);

                    if (!headerValid) {
                        console.warn('Cabeçalho não reconhecido:', header);
                        console.warn('Cabeçalho normalizado:', normalizedHeader);
                        showValidationError(`Formato de arquivo inválido!\n\n` +
                            `✅ Cabeçalho esperado (primeira linha):\n` +
                            `   • codigo\n` +
                            `   • code\n` +
                            `   • key\n` +
                            `   • chave\n` +
                            `   • nome\n\n` +
                            `❌ Cabeçalho encontrado: ${header[0] || 'vazio'}\n\n` +
                            `DICA: O arquivo deve ter apenas UMA coluna com os códigos.`);
                        return;
                    }

                    // Remove cabeçalho para processar apenas os dados
                    const dataRows = rows.slice(1).filter(row => row && row.length > 0 && row.some(cell =>
                        cell !== null && cell !== undefined && cell.toString().trim() !== ''));

                    if (dataRows.length === 0) {
                        showValidationError(
                            'Nenhum dado encontrado no arquivo. Por favor, adicione códigos ao arquivo CSV.'
                            );
                        return;
                    }

                    // Validação detalhada dos dados - ADAPTADA PARA UMA COLUNA
                    const validationResult = validateCsvDataOneColumn(dataRows);

                    if (!validationResult.isValid) {
                        showValidationError(
                            `Encontrados erros no arquivo:\n\n${validationResult.errors.join('\n')}\n\nPor favor, corrija os erros e tente novamente.`
                            );
                        return;
                    }

                    // Sucesso - mostrar resumo da validação
                    showValidationSuccess(validationResult);

                    // Continuar com o processamento original
                    processValidFileOneColumn(dataRows, validationResult);

                    // Adicionar flag para indicar que o arquivo foi validado com sucesso
                    document.getElementById('codeExcelInput').setAttribute('data-validated', 'true');

                } catch (error) {
                    console.error('Erro ao processar arquivo:', error);
                    showValidationError(
                        'Erro ao processar o arquivo. Verifique se o arquivo não está corrompido e tente novamente.'
                        );
                    // Remover flag se houver erro
                    document.getElementById('codeExcelInput').removeAttribute('data-validated');
                }
            };

            reader.onerror = function() {
                showValidationError('Erro ao ler o arquivo. Tente novamente.');
            };

            // Ler arquivo de acordo com o tipo
            const isCSV = file.name.endsWith('.csv');
            if (isCSV) {
                // Tentar diferentes encodings para CSV
                reader.readAsText(file, 'UTF-8');
            } else {
                reader.readAsBinaryString(file); // Excel como binary
            }
        });

        // Função para validar dados com apenas uma coluna
        function validateCsvDataOneColumn(dataRows) {
            const result = {
                isValid: true,
                errors: [],
                validLines: 0,
                totalLines: dataRows.length,
                totalCodes: 0,
                duplicateCodes: []
            };

            const seenCodes = new Set();

            dataRows.forEach((row, index) => {
                const lineNumber = index + 2; // +2 porque removemos o cabeçalho e index começa em 0
                const code = row[0] !== undefined && row[0] !== null ? row[0].toString().trim() : '';

                // Validar campo obrigatório
                if (!code) {
                    result.errors.push(`Linha ${lineNumber}: Código não pode estar vazio`);
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

                result.validLines++;
                result.totalCodes++;
            });

            return result;
        }

        function processValidFileOneColumn(dataRows, validationResult) {
            const total = validationResult.totalCodes;

            if (total === 0) {
                alert('Nenhum código válido encontrado no arquivo.');
                document.getElementById('codeExcelInput').value = '';
                return;
            }

            // Atualiza na tela
            document.getElementById('totalCodes').innerText = total;

            // Não há variações, apenas mostra o total de códigos
            const ul = document.getElementById('variationList');
            ul.innerHTML = '<li>Códigos únicos encontrados: ' + total + '</li>';

            // Mostra o resultado
            document.getElementById('codeImportResult').classList.remove('d-none');
        }
        // ...existing code...
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
                    const lines = content.split('\n').filter(line => line.trim() !==
                    ''); // Remove linhas vazias
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

        // toggleFileUpload function
        function toggleFileUpload() {
            console.log('toggleFileUpload chamada!'); // Debug

            let fileType = document.getElementById('fileType');
            if (!fileType) {
                console.log('Elemento fileType não encontrado!');
                return;
            }

            let fileTypeValue = fileType.value;
            console.log('Tipo selecionado:', fileTypeValue); // Debug

            let downloadFile = document.getElementById('downloadFile');
            let downloadLink = document.getElementById('downloadLink');
            let codeUploadSection = document.getElementById('codeUploadSection');
            let fileUploadContainer = document.getElementById('fileUploadContainer');


            if (fileTypeValue === 'upload') {
                // Mostrar upload de arquivo, ocultar link e códigos
                if (fileUploadContainer) fileUploadContainer.classList.remove('d-none');
                if (downloadFile) downloadFile.classList.remove('d-none');
                if (downloadLink) downloadLink.classList.add('d-none');
                if (codeUploadSection) codeUploadSection.classList.add('d-none');

            } else if (fileTypeValue === 'link') {
                // Mostrar link, ocultar upload de arquivo e códigos
                if (fileUploadContainer) fileUploadContainer.classList.remove('d-none');
                if (downloadFile) downloadFile.classList.add('d-none');
                if (downloadLink) downloadLink.classList.remove('d-none');
                if (codeUploadSection) codeUploadSection.classList.add('d-none');

            } else if (fileTypeValue === 'code') {
                // Mostrar seção de códigos, ocultar upload de arquivo e link
                if (fileUploadContainer) fileUploadContainer.classList.remove('d-none'); // Manter container visível!
                if (downloadFile) downloadFile.classList.add('d-none');
                if (downloadLink) downloadLink.classList.add('d-none');
                if (codeUploadSection) codeUploadSection.classList.remove('d-none'); // Mostrar códigos

            }
        }

        function downloadCodeTemplate() {
            // Create workbook and worksheet
            const workbook = XLSX.utils.book_new();
            const ws_data = [
                ['codigo'], // Header - apenas uma coluna
                ['ABC123'], // Sample data
                ['XYZ789'],
                ['ENT456']
            ];

            const worksheet = XLSX.utils.aoa_to_sheet(ws_data);

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Códigos');

            // Save file
            XLSX.writeFile(workbook, 'modelo_codigos.xlsx');
        }

        // Executar ao carregar a página para configurar estado inicial
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('fileType')) {
                toggleFileUpload();
            }
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
    @php
        $test = $languages->pluck('code')->toArray();
    @endphp

    <script src="{{ asset('assets/user/js/dropzone-slider.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugin/cropper.js') }}"></script>
    <script src="{{ asset('assets/user/js/cropper-init.js') }}"></script>
@endsection
