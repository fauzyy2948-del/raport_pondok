@extends('layouts.app')
@section('title', 'Absensi Santri')
@section('page-title', 'Absensi Santri')
@section('breadcrumb')
    <li class="breadcrumb-item active">Absensi</li>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('ustadz.absensi.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-4">
                <label class="form-label">Jadwal / Kelas</label>
                <select name="jadwal_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Pilih Jadwal</option>
                    @foreach($jadwals as $j)
                        <option value="{{ $j->id }}" {{ request('jadwal_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->kelas->nama_kelas }} - {{ $j->mapel->nama_mapel }} ({{ substr($j->jam_mulai, 0, 5) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@if(request('jadwal_id'))
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                Input Absensi Kelas {{ $jadwalTerpilih->kelas->nama_kelas }} 
                | Tanggal: {{ \Carbon\Carbon::parse(request('tanggal', date('Y-m-d')))->format('d M Y') }}
            </h5>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scanQrModal">
                    <i class="bi bi-qr-code-scan me-1"></i> Scan QR Absensi
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <form action="{{ route('ustadz.absensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="jadwal_id" value="{{ request('jadwal_id') }}">
                <input type="hidden" name="kelas_id" value="{{ $jadwalTerpilih->kelas_id }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Santri</th>
                                <th>Status Kehadiran</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santris as $s)
                                @php
                                    $absen = $s->absensi->first();
                                    $status = $absen ? strtolower($absen->status) : 'hadir'; // Default hadir
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $s->user->name }}</div>
                                        <input type="hidden" name="absensi[{{ $s->id }}][santri_id]" value="{{ $s->id }}">
                                    </td>
                                    <td>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="absensi[{{ $s->id }}][status]" id="status_{{ $s->id }}_hadir" value="Hadir" {{ $status == 'hadir' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success" for="status_{{ $s->id }}_hadir">Hadir</label>

                                            <input type="radio" class="btn-check" name="absensi[{{ $s->id }}][status]" id="status_{{ $s->id }}_izin" value="Izin" {{ $status == 'izin' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-info" for="status_{{ $s->id }}_izin">Izin</label>

                                            <input type="radio" class="btn-check" name="absensi[{{ $s->id }}][status]" id="status_{{ $s->id }}_sakit" value="Sakit" {{ $status == 'sakit' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning" for="status_{{ $s->id }}_sakit">Sakit</label>

                                            <input type="radio" class="btn-check" name="absensi[{{ $s->id }}][status]" id="status_{{ $s->id }}_alfa" value="Alfa" {{ $status == 'alfa' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger" for="status_{{ $s->id }}_alfa">Alfa</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="absensi[{{ $s->id }}][keterangan]" class="form-control" placeholder="Opsional..." value="{{ $absen->keterangan ?? '' }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Data santri tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($santris->count() > 0)
                    <div class="card-footer text-end bg-white">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Absensi</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-calendar2-check display-4 d-block mb-3"></i>
        <h4>Pilih Jadwal Mengajar</h4>
        <p>Silakan pilih tanggal dan jadwal mengajar untuk menginput absensi santri.</p>
    </div>
@endif

<!-- Modal Scan QR Code -->
<div class="modal fade" id="scanQrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="scanQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--border-radius);">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-600" id="scanQrModalLabel">
                    <i class="bi bi-qr-code-scan me-2"></i> Scan QR Code Absensi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="btn-close-scanner-modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info py-2" style="font-size:12px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Arahkan kamera ke QR Code santri (berisi NISN) atau ketik NISN secara manual.
                </div>
                
                <!-- Scanner Viewport -->
                <div class="position-relative bg-light border rounded overflow-hidden mb-3 text-center d-flex align-items-center justify-content-center" style="min-height: 250px;">
                    <div id="reader" style="width: 100%;"></div>
                </div>

                <!-- Input Manual & Parameter -->
                <div class="row g-2 mb-3">
                    <div class="col-7">
                        <label class="form-label font-bold" style="font-size:11px;">Input Manual NISN</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="manual_nisn" class="form-control" placeholder="Ketik NISN..." style="border-radius: 8px 0 0 8px;">
                            <button class="btn btn-primary" type="button" id="btn-submit-manual" style="border-radius: 0 8px 8px 0;">Absen</button>
                        </div>
                    </div>
                    <div class="col-5">
                        <label class="form-label" style="font-size:11px;">Status Kehadiran</label>
                        <select id="scan_status" class="form-select form-select-sm" style="border-radius: 8px;">
                            <option value="Hadir" selected>Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alfa">Alfa</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" style="font-size:11px;">Keterangan Tambahan (Opsional)</label>
                    <input type="text" id="scan_keterangan" class="form-control form-control-sm" placeholder="Catatan..." style="border-radius: 8px;">
                </div>

                <!-- Status Feedback -->
                <div id="scan-feedback" class="d-none alert py-2 px-3 text-center mb-3" style="font-size: 13px;"></div>

                <!-- Recently Scanned -->
                <div>
                    <h6 class="fw-600 border-bottom pb-1 mb-2" style="font-size:12px;">Daftar Pindai Sesi Ini</h6>
                    <div id="scan-history" class="overflow-auto" style="max-height: 120px; font-size:12px;">
                        <div class="text-muted text-center py-2 italic" id="empty-history-text">Belum ada pemindaian di sesi ini.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Selesai</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        let html5QrcodeScanner = null;
        
        // Buat synthesizer audio untuk bunyi bip & buzzer
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        
        function playSuccessBeep() {
            try {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(800, audioCtx.currentTime); // 800 Hz
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15); // bunyi 150ms
            } catch(e) {
                console.error("Audio error", e);
            }
        }
        
        function playErrorBuzzer() {
            try {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(150, audioCtx.currentTime); // 150 Hz
                gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.4); // bunyi 400ms
            } catch(e) {
                console.error("Audio error", e);
            }
        }

        // Tampilkan feedback scan
        function showFeedback(success, message) {
            const fb = $('#scan-feedback');
            fb.removeClass('d-none alert-success alert-danger');
            if (success) {
                fb.addClass('alert-success').text(message);
                playSuccessBeep();
            } else {
                fb.addClass('alert-danger').text(message);
                playErrorBuzzer();
            }
            fb.hide().fadeIn(150);
        }

        // Kirim absensi via AJAX
        function submitAttendance(nisn) {
            const status = $('#scan_status').val();
            const keterangan = $('#scan_keterangan').val();
            const jadwalId = "{{ request('jadwal_id') }}";
            const tanggal = "{{ request('tanggal', date('Y-m-d')) }}";
            
            if (!nisn || !nisn.trim()) return;
            
            // Nonaktifkan tombol submit manual jika dipicu manual
            $('#btn-submit-manual').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('ustadz.absensi.scan-qr') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    jadwal_id: jadwalId,
                    tanggal: tanggal,
                    nisn: nisn.trim(),
                    status: status,
                    keterangan: keterangan
                },
                success: function(response) {
                    $('#btn-submit-manual').prop('disabled', false);
                    $('#manual_nisn').val('');
                    
                    if (response.success) {
                        showFeedback(true, response.message);
                        
                        // Update tabel utama secara dinamis
                        const santriId = response.santri_id;
                        const statusLower = response.status.toLowerCase();
                        
                        // Centang tombol radio di tabel utama
                        $(`#status_${santriId}_${statusLower}`).prop('checked', true);
                        
                        // Isi keterangan di tabel utama
                        $(`input[name="absensi[${santriId}][keterangan]"]`).val(response.keterangan);
                        
                        // Update daftar riwayat scan sesi ini
                        addHistory(response.nama, response.status, response.keterangan);
                    } else {
                        showFeedback(false, response.message || 'Gagal menyimpan absensi.');
                    }
                },
                error: function(xhr) {
                    $('#btn-submit-manual').prop('disabled', false);
                    let errMsg = 'Terjadi kesalahan sistem.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    showFeedback(false, errMsg);
                }
            });
        }

        function addHistory(nama, status, keterangan) {
            $('#empty-history-text').addClass('d-none');
            
            let badgeClass = 'bg-success';
            if (status.toLowerCase() === 'izin') badgeClass = 'bg-info text-dark';
            else if (status.toLowerCase() === 'sakit') badgeClass = 'bg-warning text-dark';
            else if (status.toLowerCase() === 'alfa') badgeClass = 'bg-danger';

            const time = new Date().toLocaleTimeString('id', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            
            const itemHtml = `
                <div class="d-flex justify-content-between align-items-center border-bottom py-1">
                    <div>
                        <strong>${nama}</strong> <span class="badge ${badgeClass} ms-1" style="font-size: 10px;">${status}</span>
                        ${keterangan ? `<div class="text-muted small" style="font-size: 10px;">Ket: ${keterangan}</div>` : ''}
                    </div>
                    <small class="text-muted">${time}</small>
                </div>
            `;
            $('#scan-history').prepend(itemHtml);
        }

        // Submit manual dengan tombol atau menekan Enter
        $('#btn-submit-manual').click(function() {
            submitAttendance($('#manual_nisn').val());
        });
        
        $('#manual_nisn').keypress(function(e) {
            if (e.which === 13) {
                submitAttendance($(this).val());
            }
        });

        // Event listener saat modal scanner dibuka
        $('#scanQrModal').on('shown.bs.modal', function () {
            // Bersihkan feedback
            $('#scan-feedback').addClass('d-none').text('');
            
            // Inisialisasi scanner
            html5QrcodeScanner = new Html5Qrcode("reader");
            
            const config = { 
                fps: 10, 
                qrbox: { width: 200, height: 200 },
                aspectRatio: 1.0
            };
            
            // Jalankan scanner dengan kamera belakang
            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                config,
                function(decodedText, decodedResult) {
                    // Ketika QR Code terbaca
                    // Kita pause dulu scanner agar tidak menumpuk request
                    html5QrcodeScanner.pause(true);
                    
                    submitAttendance(decodedText);
                    
                    // Lanjutkan pemindaian setelah jeda 2 detik
                    setTimeout(() => {
                        if (html5QrcodeScanner && html5QrcodeScanner.getState() === 3) { // 3 = PAUSED
                            html5QrcodeScanner.resume();
                        }
                    }, 2000);
                },
                function(error) {
                    // Abaikan error per frame
                }
            ).catch(err => {
                console.error("Gagal memulai scanner: ", err);
                $('#reader').html(`<div class="text-danger p-3 small"><i class="bi bi-exclamation-triangle"></i> Gagal membuka kamera. Pastikan izin kamera diaktifkan atau gunakan input manual di bawah.</div>`);
            });
        });

        // Event listener saat modal scanner ditutup
        $('#scanQrModal').on('hidden.bs.modal', function () {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    html5QrcodeScanner = null;
                }).catch(err => {
                    console.error("Gagal menghentikan scanner: ", err);
                });
            }
        });
    });
</script>
@endpush
@endsection
