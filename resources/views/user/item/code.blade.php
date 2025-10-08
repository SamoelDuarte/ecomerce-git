@extends('user.layout')

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
            <a href="{{ route('user.item.index') . '?language=' . ($selLang ? $selLang->code : 'pt') }}">{{ __('Itens') }}</a>
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
                    href="{{ route('user.item.index') . '?language=' . ($selLang ? $selLang->code : 'pt') }}">{{ __('Voltar') }}</a>
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
                                <th>{{ __('Código') }}</th>
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
                                <td>{{ $code->code }}</td>
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
                                        href="{{ route('user.item.details', $code->order_id) }}">#{{ $code->order_id }}</a>
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
                        <i class="fas fa-plus"></i> {{ __('Adicionar Manualmente') }}
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

                       

                        {{-- Feedback de validação --}}
                        <div id="file-validation-feedback" class="mt-2"></div>

                        <div id="codeImportResult" class="mt-3 d-none">
                            <div class="alert alert-info">
                                <p><strong>Total de Códigos:</strong> <span id="totalCodes">0</span></p>
                                <p><strong>Códigos encontrados:</strong></p>
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
                    {{-- Código --}}
                    <div class="form-group">
                        <label for="codeValue">{{ __('Código') }} <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="codeValue" class="form-control" required>
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

    function showValidationError(message) {
        const feedbackDiv = document.getElementById('file-validation-feedback');
        if (feedbackDiv) {
            feedbackDiv.innerHTML = `<div class="alert alert-danger">${message}</div>`;
        } else {
            alert(message);
        }
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

        // Preparar códigos para envio
        parsedCodes = [];
        dataRows.forEach(row => {
            const code = row[0] !== undefined && row[0] !== null ? row[0].toString().trim() : '';
            if (code) {
                parsedCodes.push({ code });
            }
        });

        // Atualiza na tela
        document.getElementById('totalCodes').innerText = total;

        // Não há variações, apenas mostra o total de códigos
        const ul = document.getElementById('variationList');
        ul.innerHTML = '<li>Códigos únicos encontrados: ' + total + '</li>';

        // Mostra o resultado e o botão de enviar
        document.getElementById('codeImportResult').classList.remove('d-none');
        document.getElementById('sendCodesBtn').classList.remove('d-none');
    }

    document.getElementById('codeExcelInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Debug completo do arquivo
        console.log('=== DEBUG ARQUIVO SELECIONADO ===');
        console.log('Nome:', file.name);
        console.log('Tipo MIME:', file.type);
        console.log('Tamanho:', file.size, 'bytes');

        // Validação inicial do tipo de arquivo
        const validExtensions = ['.csv', '.xls', '.xlsx'];
        const fileExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

        if (!validExtensions.includes(fileExtension)) {
            const errorMsg = `Arquivo "${file.name}" não é suportado. Use apenas arquivos CSV (.csv) ou Excel (.xls, .xlsx)`;
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
                    showValidationError('Arquivo vazio ou corrompido. Por favor, use o modelo CSV fornecido.');
                    return;
                }

                // Validação do cabeçalho - ACEITA APENAS UMA COLUNA
                const header = rows[0];
                
                if (!header || header.length < 1) {
                    showValidationError('Arquivo não possui o cabeçalho correto. O arquivo deve ter 1 coluna: codigo.');
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
                const dataRows = rows.slice(1).filter(row => row && row.length > 0 && row.some(cell => cell !== null && cell !== undefined && cell.toString().trim() !== ''));

                if (dataRows.length === 0) {
                    showValidationError('Nenhum dado encontrado no arquivo. Por favor, adicione códigos ao arquivo CSV.');
                    return;
                }

                // Validação detalhada dos dados - ADAPTADA PARA UMA COLUNA
                const validationResult = validateCsvDataOneColumn(dataRows);

                if (!validationResult.isValid) {
                    showValidationError(`Encontrados erros no arquivo:\n\n${validationResult.errors.join('\n')}\n\nPor favor, corrija os erros e tente novamente.`);
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
                showValidationError('Erro ao processar o arquivo. Verifique se o arquivo não está corrompido e tente novamente.');
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
            reader.readAsBinaryString(file);  // Excel como binary
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