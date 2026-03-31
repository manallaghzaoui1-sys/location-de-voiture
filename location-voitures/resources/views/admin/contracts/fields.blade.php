@extends('layouts.admin')

@section('title', 'Champs contrat PDF')

@section('content')
<div class="admin-panel contract-fields-page">
    <div class="admin-panel-head d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Configuration des champs du contrat</h5>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('admin.contract-fields.reset') }}" onsubmit="return confirm('Reinitialiser tous les champs par defaut ?');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-rotate-left"></i> Reset defaut
                </button>
            </form>
            <button type="button" class="btn btn-sm btn-outline-primary" id="add-custom-field-btn">
                <i class="fas fa-plus"></i> Ajouter champ
            </button>
        </div>
    </div>

    <p class="text-muted mt-2 mb-3">
        Activez ou desactivez les champs, changez leurs libelles, ajoutez des champs personnalises, puis enregistrez.
    </p>

    <form method="POST" action="{{ route('admin.contract-fields.update') }}">
        @csrf
        @method('PUT')

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <small class="text-muted">Utilisez les fleches pour changer l ordre d affichage des champs dans le PDF.</small>
            <button class="btn btn-primary btn-sm">Enregistrer</button>
        </div>

        <small class="text-muted d-block mb-2">Astuce: sur petit ecran, faites un scroll horizontal dans le tableau.</small>

        <div class="table-responsive">
            <table class="table align-middle" id="contract-fields-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 56px;">#</th>
                        <th style="min-width: 160px;">Libelle</th>
                        <th style="min-width: 170px;">Source</th>
                        <th style="min-width: 160px;">Section</th>
                        <th style="min-width: 180px;">Valeur manuelle (optionnel)</th>
                        <th class="text-center" style="width: 90px;">Actif</th>
                        <th class="text-center" style="width: 120px;">Ordre</th>
                        <th class="text-center" style="width: 80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fields as $index => $field)
                        <tr>
                            <td class="text-center text-muted fw-bold row-order">{{ $index + 1 }}</td>
                            <td>
                                <input type="hidden" name="fields[{{ $index }}][id]" value="{{ $field['id'] }}">
                                <input type="text" name="fields[{{ $index }}][label]" class="form-control" value="{{ $field['label'] }}" maxlength="120" required>
                            </td>
                            <td>
                                <select name="fields[{{ $index }}][source]" class="form-select source-select">
                                    @foreach($sources as $sourceKey => $sourceLabel)
                                        <option value="{{ $sourceKey }}" @selected($field['source'] === $sourceKey)>{{ $sourceLabel }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="fields[{{ $index }}][section]" class="form-select">
                                    @foreach($sections as $sectionKey => $sectionLabel)
                                        <option value="{{ $sectionKey }}" @selected($field['section'] === $sectionKey)>{{ $sectionLabel }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                       name="fields[{{ $index }}][value]"
                                       class="form-control custom-value-input"
                                       value="{{ $field['value'] ?? '' }}"
                                       maxlength="300"
                                       placeholder="Si rempli, remplace la valeur automatique">
                            </td>
                            <td class="text-center">
                                <input class="form-check-input" type="checkbox" name="fields[{{ $index }}][enabled]" value="1" @checked($field['enabled'])>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary move-up-btn" title="Monter">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary move-down-btn" title="Descendre">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @error('fields')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="d-flex flex-wrap justify-content-end gap-2">
            <a href="{{ route('admin.reservations') }}" class="btn btn-outline-secondary">Retour reservations</a>
            <button class="btn btn-primary">Enregistrer la configuration</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const tableBody = document.querySelector('#contract-fields-table tbody');
        const addButton = document.getElementById('add-custom-field-btn');
        const sources = @json($sources);
        const sections = @json($sections);

        function sourceOptions(selected) {
            return Object.entries(sources)
                .map(([key, label]) => `<option value="${key}" ${selected === key ? 'selected' : ''}>${label}</option>`)
                .join('');
        }

        function sectionOptions(selected) {
            return Object.entries(sections)
                .map(([key, label]) => `<option value="${key}" ${selected === key ? 'selected' : ''}>${label}</option>`)
                .join('');
        }

        function bindRowEvents(row) {
            row.querySelector('.remove-row-btn').addEventListener('click', function () {
                row.remove();
                reindexRows();
            });

            row.querySelector('.move-up-btn').addEventListener('click', function () {
                const previous = row.previousElementSibling;
                if (previous) {
                    tableBody.insertBefore(row, previous);
                    reindexRows();
                }
            });

            row.querySelector('.move-down-btn').addEventListener('click', function () {
                const next = row.nextElementSibling;
                if (next) {
                    tableBody.insertBefore(next, row);
                    reindexRows();
                }
            });
        }

        function reindexRows() {
            tableBody.querySelectorAll('tr').forEach((row, index) => {
                const orderCell = row.querySelector('.row-order');
                if (orderCell) {
                    orderCell.textContent = String(index + 1);
                }

                row.querySelectorAll('input, select').forEach((el) => {
                    const name = el.getAttribute('name');
                    if (!name) {
                        return;
                    }
                    el.setAttribute('name', name.replace(/fields\[\d+]/, `fields[${index}]`));
                });
            });
        }

        addButton.addEventListener('click', function () {
            const index = tableBody.querySelectorAll('tr').length;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="text-center text-muted fw-bold row-order">${index + 1}</td>
                <td>
                    <input type="hidden" name="fields[${index}][id]" value="">
                    <input type="text" name="fields[${index}][label]" class="form-control" value="Nouveau champ" maxlength="120" required>
                </td>
                <td>
                    <select name="fields[${index}][source]" class="form-select source-select">
                        ${sourceOptions('custom')}
                    </select>
                </td>
                <td>
                    <select name="fields[${index}][section]" class="form-select">
                        ${sectionOptions('parties')}
                    </select>
                </td>
                <td>
                    <input type="text" name="fields[${index}][value]" class="form-control custom-value-input" maxlength="300" placeholder="Texte libre du champ" value="" />
                </td>
                <td class="text-center">
                    <input class="form-check-input" type="checkbox" name="fields[${index}][enabled]" value="1" checked>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary move-up-btn" title="Monter">
                            <i class="fas fa-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary move-down-btn" title="Descendre">
                            <i class="fas fa-arrow-down"></i>
                        </button>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
            bindRowEvents(row);
            reindexRows();
        });

        tableBody.querySelectorAll('tr').forEach((row) => {
            bindRowEvents(row);
        });
    })();
</script>
@endpush
