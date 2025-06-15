document.addEventListener("DOMContentLoaded", function () {
    const adminMain = document.getElementById("admin-main");
    if (!adminMain) return;

    // --- Deklarasi Variabel Global untuk Data & Grafik ---
    let allUsersData = [], allPTsData = [], allTransactionsData = [];
    let visitorChartInstance = null;
    let incomeChartInstance = null; // Tambahkan ini

    // --- Fungsi Render Tampilan ---
    function renderTable(tbodyId, data, type) {
        const tableBody = document.getElementById(tbodyId);
        if (!tableBody) return;
        tableBody.innerHTML = '';
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center;">No data available.</td></tr>`;
            return;
        }
        data.forEach(item => {
            const row = `
                <tr>
                    <td>${type.toUpperCase()}${String(item.userID).padStart(3, '0')}</td>
                    <td><img src="${item.profile_pict_url}" alt="${item.username}" class="table-pict"/></td>
                    <td>${item.username}</td>
                    <td>${item.email}</td>
                    <td>${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                    <td><div class="action-btns"><button class="delete-icon" data-id="${item.userID}" data-name="${item.username}" title="Delete">🗑️</button></div></td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function renderTransactionTable(transactions) {
        const tableBody = document.getElementById('transaction-table-body');
        if (!tableBody) return;
        tableBody.innerHTML = '';
        transactions.forEach(t => {
            const row = `
                <tr>
                    <td>PAY${String(t.paymentID).padStart(4, '0')}</td>
                    <td>${new Date(t.paidAt).toLocaleString('id-ID')}</td>
                    <td>${t.buyerName}</td>
                    <td>${t.trainerName}</td>
                    <td>Rp ${new Intl.NumberFormat('id-ID').format(t.totalPayment)}</td>
                    <td><span class="status ${t.status}">${t.status}</span></td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function renderAddPtTable(users) {
        const tableBody = document.getElementById('add-pt-table-body');
        if(!tableBody) return;
        tableBody.innerHTML = '';
        users.forEach(user => {
            const row = `
                <tr>
                    <td>USER${String(user.userID).padStart(3, '0')}</td>
                    <td><img src="${user.profile_pict_url}" alt="${user.username}" class="table-pict"/></td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td>
                        <select class="role-switcher" data-id="${user.userID}">
                            <option value="user" selected>User</option>
                            <option value="pt">Personal Trainer</option>
                        </select>
                    </td>
                </tr>`;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

        // --- Fungsi Render ---
    function renderChart(canvasId, period, type) {
        // Panggil PHP dengan tipe yang benar
        fetch(`http://localhost/StrongU_Project/backend/get_chart_data.php?type=${type}&period=${period}`)
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            // Tentukan instance chart mana yang akan di-update/dihancurkan
            let chartInstance = (type === 'visitor') ? visitorChartInstance : incomeChartInstance;
            if (chartInstance) {
                chartInstance.destroy();
            }

            let datasets = [];
            let yAxisOptions = {}; // Obyek untuk opsi sumbu Y dinamis

            if (type === 'visitor') {
                datasets.push({
                    label: 'New Users', data: data.values, borderColor: '#0392FB',
                    backgroundColor: 'rgba(3, 146, 251, 0.1)', fill: true, tension: 0.4
                });
                // Atur skala sumbu Y untuk Visitor
                const maxVisitor = Math.max(...data.values);
                yAxisOptions = {
                    beginAtZero: true,
                    // Jika data maksimal kecil, buat langkahnya 1
                    ticks: {
                        stepSize: maxVisitor < 10 ? 1 : undefined,
                        precision: 0 // Tidak ada desimal
                    }
                };
            } else { // type 'income'
                datasets.push({
                    label: 'Total Income', data: data.income, borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)', fill: true, tension: 0.4
                });
                datasets.push({
                    label: 'Profit (Admin Fees)', data: data.profit, borderColor: '#f39c12',
                    backgroundColor: 'rgba(243, 156, 18, 0.1)', fill: true, tension: 0.4
                });
                // Atur format sumbu Y untuk Rupiah
                yAxisOptions = {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            if (value >= 1000000) return 'Rp ' + value / 1000000 + ' Jt';
                            if (value >= 1000) return 'Rp ' + value / 1000 + ' rb';
                            return 'Rp ' + value;
                        }
                    }
                };
            }

            // Buat instance chart baru
            const newChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: { labels: data.labels, datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Penting agar chart mengisi wrapper
                    scales: {
                        y: yAxisOptions // Terapkan opsi sumbu Y dinamis di sini
                    }
                }
            });

            // Simpan instance baru
            if (type === 'visitor') visitorChartInstance = newChart;
            else incomeChartInstance = newChart;
        });
    }

    // --- Logika Utama Inisialisasi ---
    function initializeDashboard() {
        fetch('http://localhost/StrongU_Project/backend/get_admin_data.php')
        .then(res => res.json())
        .then(data => {
            document.getElementById('admin-username').textContent = data.adminInfo.username;
            document.getElementById('admin-profile-pict').src = data.adminInfo.profile_pict_url;
            document.getElementById('total-users').textContent = data.stats.total_users;
            document.getElementById('total-trainers').textContent = data.stats.total_trainers;
            document.getElementById('today-income').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(data.stats.today_income)}`;
            
            allUsersData = data.allUsers;
            allPTsData = data.allPTs;
            allTransactionsData = data.allTransactions;

            renderTable('user-table-body', allUsersData, 'user');
            renderTable('pt-table-body', allPTsData, 'pt');
            renderTransactionTable(allTransactionsData);
            renderAddPtTable(allUsersData); // Tampilkan semua user di awal

            // Render grafik awal
            renderChart('visitor-chart', 30, 'visitor');
            renderChart('income-chart', 30, 'income');

            // Isi form setting admin
            document.getElementById('admin-username-input').value = data.adminInfo.username;
            document.getElementById('admin-email-input').value = data.adminInfo.email; // Pastikan PHP mengirim email
            document.getElementById('admin-notelp-input').value = data.adminInfo.noTelp; // Pastikan PHP mengirim noTelp
        });
    }

    const adminSettingForm = document.getElementById('admin-setting-form');
    if (adminSettingForm) {
        adminSettingForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Kumpulkan data dari form setting admin
            const adminData = {
                username: document.getElementById('admin-username-input').value,
                email: document.getElementById('admin-email-input').value,
                noTelp: document.getElementById('admin-notelp-input').value
            };

            // Kirim data ke skrip update_profile.php yang sudah ada
            fetch('http://localhost/StrongU_Project/backend/update_profile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(adminData)
            })
            .then(res => res.json())
            .then(response => {
                alert(response.message || 'Terjadi kesalahan.');
                if (response.success) {
                    // Perbarui juga nama di sidebar secara langsung
                    document.getElementById('admin-username').textContent = adminData.username;
                }
            })
            .catch(error => {
                console.error('Error updating admin settings:', error);
                alert('Terjadi kesalahan jaringan.');
            });
        });
    }

    // --- Event Listeners ---
    // Navigasi Tab
    document.querySelectorAll(".tab-button").forEach(button => {
        button.addEventListener("click", function () {
            document.querySelectorAll(".tab-button").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll(".tab-content").forEach(tab => tab.classList.remove("active"));
            this.classList.add("active");
            document.getElementById(this.dataset.tab).classList.add("active");
        });
    });

    // Search & Filter
    document.getElementById('user-search-input').addEventListener('input', e => { /* ... */ });
    document.getElementById('pt-search-input').addEventListener('input', e => { /* ... */ });
    document.getElementById('transaction-search-input').addEventListener('input', e => { /* ... */ });
    document.getElementById('add-pt-search-input').addEventListener('input', e => { /* ... */ });
    document.getElementById('visitor-period-filter').addEventListener('change', function() { renderVisitorChart(this.value); });

    // Aksi (Delete, Ganti Role, Simpan Setting)
    document.querySelector('.admin-content').addEventListener('click', function (e) { /* ... (Logika Delete) ... */ });
    document.getElementById('add-pt-table-body').addEventListener('change', function(e) { /* ... (Logika Ganti Role) ... */ });
    document.getElementById('admin-setting-form').addEventListener('submit', function(e){
        e.preventDefault();
        alert('Fitur simpan setting admin akan dihubungkan ke update_profile.php');
        // Logika fetch ke update_profile.php bisa ditambahkan di sini
    });

    // Filter Grafik
    document.getElementById('visitor-period-filter').addEventListener('change', function() { renderChart('visitor-chart', this.value, 'visitor'); });
    document.getElementById('income-period-filter').addEventListener('change', function() { renderChart('income-chart', this.value, 'income'); });

    // Panggil Inisialisasi
    initializeDashboard();
});