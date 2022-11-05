@extends('App')


@section('content-header', 'Item')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Code', 'Jenis', 'Merk', 'Nama Barang', 'Satuan', 'Status', 'Aksi']">
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->code }}</td>
                                    <td>{{ $row->kind->label }}</td>
                                    <td>{{ $row->merk->label }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->unit->label }}</td>
                                    <td>{{ $row->status == 1 ? "Tersedia" : "Tidak tersedia" }}</td>
                                    <td>
                                        <a
                                            href="{{ route('item.show', $row->id) }}"
                                            class="btn btn-warning"
                                            title="Ubah"><i class="fas fa-pencil-alt"></i></a>

                                        <form style=" display:inline!important;" method="POST" action="{{ route('item.destroy', $row->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{ $data->links() }}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'xl'">
        <form style="width: 100%" action="{{ route('item.store') }}" method="POST">
            @csrf
            @method('POST')

            <input type="hidden" name="stock_id" value="{{ app('request')->input('stock_id') }}">

            <x-row>
                <x-in-select
                    :label="'Pilih Jenis'"
                    :placeholder="'Pilih Jenis'"
                    :col="6"
                    :name="'kind_id'"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Pilih Merk'"
                    :placeholder="'Pilih Merk'"
                    :col="6"
                    :name="'merk_id'"
                    :required="true"></x-in-select>
                <x-in-select
                    :label="'Pilih Satuan'"
                    :placeholder="'Pilih Satuan'"
                    :col="6"
                    :name="'unit_id'"
                    :required="true"></x-in-select>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama'"
                    :col="6"
                    :name="'name'"
                    :required="true"></x-in-text>
                <x-in-text
                    :label="'Kode'"
                    :placeholder="'Masukkan Kode Barang'"
                    :col="6"
                    :name="'code'"
                    :required="true"></x-in-text>
            </x-row>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </form>
    </x-modal>
@endsection

@push('js')
    <input type="hidden" id="url-categories" value="{{ route('select2.categories') }}">

    <script>
        $(function() {
            $('#kind_id').select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: {
                    id: '',
                    text: 'Pilih Jenis'
                },
                ajax: {
                    url: $('#url-categories').val(),
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        let query = {
                            category: 'kinds',
                            keyword: params.term
                        }

                        return query;
                    },
                    processResults: function (data) {
                        let x = $.map(data, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.name
                            };
                        });

                        return {
                            results: x
                        };
                    },
                    cache: false
                }
            });

            $('#merk_id').select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: {
                    id: '',
                    text: 'Pilih Merk'
                },
                ajax: {
                    url: $('#url-categories').val(),
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        let query = {
                            category_id: $('#kind_id').val(),
                            category: 'merks',
                            keyword: params.term
                        }

                        return query;
                    },
                    processResults: function (data) {
                        let x = $.map(data, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.name
                            };
                        });

                        return {
                            results: x
                        };
                    },
                    cache: false
                }
            });

            $('#unit_id').select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: {
                    id: '',
                    text: 'Pilih Satuan'
                },
                ajax: {
                    url: $('#url-categories').val(),
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        let query = {
                            category: 'units',
                            keyword: params.term
                        }

                        return query;
                    },
                    processResults: function (data) {
                        let x = $.map(data, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.name
                            };
                        });

                        return {
                            results: x
                        };
                    },
                    cache: false
                }
            });

            // on change kind
            $('#kind_id').on('change', function() {
                $('#merk_id').val(null).trigger('change');
            });
        });
    </script>
@endpush