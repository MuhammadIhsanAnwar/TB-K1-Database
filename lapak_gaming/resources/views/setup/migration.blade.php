<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database - Lapak Gaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .migration-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }

        .migration-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .migration-header h1 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .migration-header p {
            color: #999;
            font-size: 14px;
        }

        .step-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }

        .step-number {
            min-width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
        }

        .step-text h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .step-text p {
            margin: 5px 0 0 0;
            font-size: 13px;
            color: #666;
        }

        .btn-migrate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            transition: transform 0.3s ease;
            margin-top: 20px;
        }

        .btn-migrate:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-migrate:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .console-output {
            background: #1e1e1e;
            color: #0ecb81;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 15px;
            min-height: 150px;
            max-height: 300px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 20px 0;
            display: none;
        }

        .console-output.show {
            display: block;
        }

        .console-output .error {
            color: #ff6b6b;
        }

        .console-output .success {
            color: #51cf66;
        }

        .spinner {
            display: none;
        }

        .spinner.show {
            display: inline-block;
        }

        .alert-container {
            display: none;
            margin-bottom: 20px;
        }

        .alert-container.show {
            display: block;
        }

        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 13px;
            color: #555;
        }

        .progress-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            padding: 10px;
        }

        .progress-step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
        }

        .progress-step.done {
            background: #d4edda;
            color: #155724;
            border-radius: 8px;
        }

        .progress-step-number {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            background: #e9ecef;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .progress-step.active .progress-step-number {
            background: white;
            color: #667eea;
        }

        .progress-step.done .progress-step-number {
            background: #28a745;
            color: white;
        }

        .progress-step-label {
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="migration-container">
        <div class="migration-header">
            <h1>🎮 Lapak Gaming</h1>
            <p>Setup Database & Administrator</p>
        </div>

        <div class="progress-indicator">
            <div class="progress-step active" id="step-migrate">
                <div class="progress-step-number">1</div>
                <div class="progress-step-label">Migrate</div>
            </div>
            <div class="progress-step" id="step-admin">
                <div class="progress-step-number">2</div>
                <div class="progress-step-label">Setup Admin</div>
            </div>
        </div>

        <div class="info-box">
            <strong>ℹ️ Info:</strong><br>
            Halaman ini akan membuat semua tabel database yang diperlukan untuk aplikasi Lapak Gaming.
        </div>

        <div class="alert-container" id="alertContainer"></div>

        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-text">
                <h6>Setup Database</h6>
                <p>Klik tombol di bawah untuk menjalankan migrasi database</p>
            </div>
        </div>

        <div class="console-output" id="consoleOutput"></div>

        <button class="btn btn-migrate" id="migrateBtn" onclick="runMigration()">
            <i class="bi bi-arrow-repeat me-2"></i> Jalankan Migrasi Database
        </button>

        <div class="spinner-border text-primary mt-3" id="spinner" role="status" style="display: none;">
            <span class="visually-hidden">Loading...</span>
        </div>

        <button class="btn btn-success mt-3" id="nextBtn" style="display: none; width: 100%;" onclick="goToSetup()">
            <i class="bi bi-arrow-right me-2"></i> Lanjut ke Setup Admin
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const consoleOutput = document.getElementById('consoleOutput');
        const migrateBtn = document.getElementById('migrateBtn');
        const nextBtn = document.getElementById('nextBtn');
        const spinner = document.getElementById('spinner');
        const alertContainer = document.getElementById('alertContainer');

        function addOutput(message, type = 'info') {
            const line = document.createElement('div');
            line.className = type;
            line.textContent = `$ ${message}`;
            consoleOutput.appendChild(line);
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
        }

        function runMigration() {
            consoleOutput.classList.add('show');
            migrateBtn.disabled = true;
            nextBtn.style.display = 'none';
            spinner.style.display = 'block';
            alertContainer.classList.remove('show');

            addOutput('Menjalankan migrasi database...');

            fetch('{{ route("setup.migrate.run") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    spinner.style.display = 'none';

                    if (data.success) {
                        addOutput('✓ Migrasi berhasil dijalankan!', 'success');
                        addOutput('', 'success');
                        addOutput(data.output, 'success');

                        // Update progress indicator
                        document.getElementById('step-migrate').classList.remove('active');
                        document.getElementById('step-migrate').classList.add('done');
                        document.getElementById('step-admin').classList.add('active');

                        nextBtn.style.display = 'block';

                        // Show success alert
                        showAlert('✅ Database berhasil dibuat! Silakan lanjut ke tahap setup admin.', 'success');
                    } else {
                        addOutput('❌ Migrasi gagal!', 'error');
                        addOutput(data.message, 'error');
                        migrateBtn.disabled = false;
                        showAlert('❌ ' + data.message, 'danger');
                    }
                })
                .catch(error => {
                    spinner.style.display = 'none';
                    migrateBtn.disabled = false;
                    addOutput('❌ Error: ' + error.message, 'error');
                    showAlert('❌ Terjadi kesalahan: ' + error.message, 'danger');
                });
        }

        function goToSetup() {
            window.location.href = '{{ route("setup.index") }}';
        }

        function showAlert(message, type) {
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.classList.add('show');
        }

        // Cek status database saat halaman pertama kali dimuat
        window.addEventListener('load', function () {
            fetch('{{ route("setup.migrate.status") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.tables_exist) {
                        addOutput('✓ Tabel database sudah ada', 'success');
                        document.getElementById('step-migrate').classList.remove('active');
                        document.getElementById('step-migrate').classList.add('done');
                        document.getElementById('step-admin').classList.add('active');
                        nextBtn.style.display = 'block';
                        showAlert('✅ Database sudah siap! Silakan lanjut ke setup admin.', 'success');
                    } else {
                        addOutput('ℹ Tabel database belum dibuat', 'info');
                    }
                });
        });
    </script>
</body>
</html>
