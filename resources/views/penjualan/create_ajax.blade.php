<form action="{{ url('/penjualan/store_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kasir</label>
                    <input value="{{ Auth::user()->user_id }}" type="hidden" name="user_id" id="user_id"
                        class="form-control" required>
                    <input type="text" class="form-control" value="{{ Auth::user()->nama }}" readonly>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Pembeli</label>
                    <input value="" type="text" name="pembeli" id="pembeli" class="form-control"
                        placeholder="Nama Pembeli" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Barang, Harga, dan Jumlah</label>
                    <div id="barang-wrapper">
                        <div class="form-row mb-2 barang-row">
                            <div class="col-5">
                                <select name="barang[]" class="form-control select-barang" required>
                                    <option value="">Pilih Barang</option>
                                    @foreach ($barang as $b)
                                        <option value="{{ $b->barang_id }}" data-harga="{{ $b->harga_jual }}"
                                            data-stok="{{ $b->stok->sum('stok_sisa') }}">
                                            {{ $b->barang_nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-2">
                                <input type="number" name="stok[]" id="stok" class="form-control stok"
                                    placeholder="Stok" readonly required>
                            </div>
                            <div class="col-2">
                                <input type="number" name="harga[]" id="harga" class="form-control input-harga"
                                    placeholder="Harga" min="0" readonly required>
                            </div>
                            <div class="col-2">
                                <input type="number" name="jumlah[]" id="jumlah" class="form-control"
                                    placeholder="Jumlah" min="1" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="tambah-barang" class="btn btn-sm btn-success">
                        + Tambah Barang
                    </button>

                    <hr style="height:2px;border-width:0;color:rgb(194, 194, 194);background-color:rgb(182, 182, 182)">
                    <div class="form-row mb-2 justify-content-end">
                        <div class="col-3 align-self-center">
                            <div class="text-right font-weight-bold">Total </div>
                        </div>
                        <div class="col-5">
                            <div class="form-control bg-light text-right" id="total-harga">

                            </div>
                        </div>

                        <small id="error-barang" class="error-text form-text text-danger"></small>
                        <small id="error-harga" class="error-text form-text text-danger"></small>
                        <small id="error-jumlah" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                user_id: {
                    required: true,
                    number: true,
                },
                pembeli: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                barang: {
                    required: true,
                    number: true,
                },
                harga: {
                    required: true,
                },
                jumlah: {
                    required: true,
                    number: true,
                    min: 1
                },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPenjualan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
    // Tambah barang baru
    $('#tambah-barang').click(function() {
        let semuaTerisi = true;

        $('.barang-row').each(function() {
            const barang = $(this).find('select[name="barang[]"]').val();
            const harga = $(this).find('input[name="harga[]"]').val();
            const jumlah = $(this).find('input[name="jumlah[]"]').val();

            if (!barang || !harga || !jumlah) {
                semuaTerisi = false;
                return false;
            }
        });

        if (!semuaTerisi) {
            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi Data',
                text: 'Harap isi semua field Barang, Harga, dan Jumlah sebelum menambah barang baru.'
            });
            return;
        }

        let barangRow = `
            <div class="form-row mb-2 barang-row">
                <div class="col-5">
                    <select name="barang[]" class="form-control select-barang" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->barang_id }}" data-harga="{{ $b->harga_jual }}"
                                data-stok="{{ $b->stok->sum('stok_sisa') }}">
                                {{ $b->barang_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <input type="number" name="stok[]" id="stok" class="form-control stok"
                        placeholder="Stok" readonly required>
                </div>
                <div class="col-2">
                    <input type="number" name="harga[]" id="harga" class="form-control input-harga"
                        placeholder="Harga" min="0" readonly required>
                </div>
                <div class="col-2">
                    <input type="number" name="jumlah[]" id="jumlah" class="form-control"
                        placeholder="Jumlah" min="1" required>
                </div>
            </div>
            `;
        $('#barang-wrapper').append(barangRow);
        updateTotal();
    });

    // Hitung total harga
    function updateTotal() {
        let total = 0;
        $('.barang-row').each(function() {
            const harga = parseInt($(this).find('.input-harga').val()) || 0;
            const jumlah = parseInt($(this).find('input[name="jumlah[]"]').val()) || 0;
            total += harga * jumlah;
        });
        $('#total-harga').text('Rp ' + total.toLocaleString('id-ID'));
    }
    // Update total harga saat harga atau jumlah berubah
    $(document).on('input', 'input[name="jumlah[]"]', function() {
        updateTotal();
    });

    $(document).on('click', '.hapus-barang', function() {
        $(this).closest('.barang-row').remove();
        updateTotal();
    });
    $(document).on('change', '.select-barang', function() {
        const stok = $(this).find(':selected').data('stok');
        const harga = $(this).find(':selected').data('harga');

        $(this).closest('.barang-row').find('.stok').val(stok);
        $(this).closest('.barang-row').find('.input-harga').val(harga);
        // $(this).closest('.barang-row').find('.input-harga').val('Rp ' + harga.toLocaleString('id-ID')); //value plain(X), show rupiah(O)
        updateTotal();
    });
    $(document).on('change', 'total-harga', function() {
        const harga = $(this).find(':selected').data('harga');
        let total = 0;
        $('.barang-row').each(function() {
            const harga = $(this).find('.input-harga').val();
            const jumlah = $(this).find('input[name="jumlah[]"]').val();
            total += harga * jumlah;
        });
        $('#total-harga').text('Rp ' + total.toLocaleString('id-ID'));
    });
    $(document).ready(function() {
        updateTotal();
    });
</script>
