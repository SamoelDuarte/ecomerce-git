@extends('user.layout')

@php
    $selLang = \App\Models\User\Language::where([
        ['code', request()->input('language')],
        ['user_id', Auth::guard('web')->user()->id],
    ])->first();
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Códigos Digitais') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.item.index') . '?language=' . $selLang->code }}">{{ __('Itens') }}</a>
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
                <a href="#">{{ __('Códigos Digitais') }}</a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title row">
                <div class="col-lg-7">
                    {{ __('Lista de Códigos') }}
                </div>
                <div class="col-lg-4 offset-lg-1 text-right">
                    <a class="btn btn-secondary btn-sm text-white"
                        href="{{ route('user.item.index') . '?language=' . $selLang->code }}">{{ __('Voltar') }}</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($codes->isEmpty())
                <div class="alert alert-warning text-center">
                    {{ __('Nenhum código encontrado para este produto.') }}
                </div>
            @else
                <div class="row">
                    <div class="col-md-10">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Nome') }}</th>
                                        <th>{{ __('Código') }}</th>
                                        <th>{{ __('Preço') }}</th>
                                        <th>{{ __('Usado') }}</th>
                                        <th>{{ __('Data de Uso') }}</th>
                                        <th>{{ __('Pedido') }}</th>
                                        <th>{{ __('Ações') }}</th> {{-- novo --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($codes as $key => $code)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $code->name }}</td>
                                            <td>{{ $code->code }}</td>
                                            <td>{{ $userBs->base_currency_symbol }}{{ number_format($code->price, 2, ',', '.') }}
                                            </td>
                                            <td>
                                                @if ($code->is_used)
                                                    <span class="badge badge-danger">{{ __('Sim') }}</span>
                                                @else
                                                    <span class="badge badge-success">{{ __('Não') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $code->used_at ? $code->used_at->format('d/m/Y H:i') : '-' }}</td>
                                            <td>
                                                @if ($code->order_id)
                                                    <a
                                                        href="{{ route('user.orders.details', $code->order_id) }}">#{{ $code->order_id }}</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{-- Botão de deletar --}}
                                                <form action="{{ route('user.item.code.delete', $code->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Tem certeza que deseja excluir este código?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{-- Botão para adicionar manualmente --}}
                        <div class="mt-4">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCodeModal">
                                <i class="fas fa-plus"></i> {{ __('Adicionar Código Manualmente') }}
                            </button>
                        </div>

                        {{-- Upload por planilha --}}
                        <div id="codeUploadSection" class="mt-5">
                            <div class="form-group">
                                <label for="codeExcelInput">
                                    {{ __('Importar Planilha de Códigos') }}
                                    <span class="text-danger">**</span>
                                </label>
                                <input type="file" class="form-control" name="codeExcelInput" id="codeExcelInput"
                                    accept=".xlsx,.csv">

                                <div id="codeImportResult" class="mt-3 d-none">
                                    <div class="alert alert-info">
                                        <p><strong>Total de Códigos:</strong> <span id="totalCodes">0</span></p>
                                        <p><strong>Variações encontradas:</strong></p>
                                        <ul id="variationList" class="mb-0"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-3 d-none" id="sendCodesBtn">
                            <i class="fas fa-upload"></i> Enviar Códigos
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de Adicionar Código Manualmente --}}
    <div class="modal fade" id="addCodeModal" tabindex="-1" role="dialog" aria-labelledby="addCodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('user.item.code.store', $item_id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCodeModalLabel">{{ __('Adicionar Código Manualmente') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Fechar') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        {{-- Nome do Código --}}
                        <div class="form-group">
                            <label for="codeName">{{ __('Nome') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="codeName" class="form-control" required>
                        </div>

                        {{-- Código --}}
                        <div class="form-group">
                            <label for="codeValue">{{ __('Código') }} <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="codeValue" class="form-control" required>
                        </div>

                        {{-- Preço --}}
                        <div class="form-group">
                            <label for="codePrice">{{ __('Preço') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" id="codePrice" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Cancelar') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Salvar Código') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection



@section('scripts')
    {{-- Importar SheetJS --}}
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <script>
        let parsedCodes = []; // armazenar para envio posterior

        document.getElementById('codeExcelInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                let rows;
                const isCSV = file.name.endsWith('.csv');

                if (isCSV) {
                    const workbook = XLSX.read(e.target.result, {
                        type: 'binary'
                    });
                    const sheet = workbook.Sheets[workbook.SheetNames[0]];
                    rows = XLSX.utils.sheet_to_json(sheet, {
                        header: 1
                    });
                } else {
                    const workbook = XLSX.read(new Uint8Array(e.target.result), {
                        type: 'array'
                    });
                    const sheet = workbook.Sheets[workbook.SheetNames[0]];
                    rows = XLSX.utils.sheet_to_json(sheet, {
                        header: 1
                    });
                }

                const dataRows = rows.slice(1);
                parsedCodes = [];
                const variations = {};
                let total = 0;

                dataRows.forEach(row => {
                    const variation = row[0]?.toString().trim() || '';
                    const code = row[1]?.toString().trim() || '';
                    const value = row[2]?.toString().trim() || '';

                    if (variation && code && value) {
                        total++;
                        parsedCodes.push({
                            variation,
                            code,
                            price: value
                        });
                        variations[variation] = (variations[variation] || 0) + 1;
                    }
                });

                // Mostrar resumo
                document.getElementById('codeImportResult').classList.remove('d-none');
                document.getElementById('sendCodesBtn').classList.remove('d-none');
                document.getElementById('totalCodes').innerText = total;

                const ul = document.getElementById('variationList');
                ul.innerHTML = '';
                Object.entries(variations).forEach(([variation, count]) => {
                    const li = document.createElement('li');
                    li.innerText = `${variation} → ${count} código(s)`;
                    ul.appendChild(li);
                });
            };

            if (file.name.endsWith('.csv')) {
                reader.readAsBinaryString(file);
            } else {
                reader.readAsArrayBuffer(file);
            }
        });

        document.getElementById('sendCodesBtn').addEventListener('click', function() {
            if (!parsedCodes.length) return alert('Nenhum código para enviar.');

            const itemId = {{ $item_id }}; // você já tem essa variável na view

            fetch("{{ route('user.item.code.import') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        codes: parsedCodes
                    })
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        alert('Códigos importados com sucesso!');
                        location.reload(); // atualiza página
                    } else {
                        alert('Erro: ' + (res.message || 'Não foi possível importar.'));
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Erro ao enviar códigos.');
                });
        });
    </script>
@endsection
