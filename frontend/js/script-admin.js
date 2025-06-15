document.addEventListener("DOMContentLoaded", function () {
    const adminMain = document.getElementById("admin-main");
    if (!adminMain) return;

    // --- Deklarasi Variabel ---
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");
    let allUsersData = [];
    let allPTsData = [];

    // --- Fungsi Render ---
    function renderTable(tbodyId, data, type) {
        const tableBody = document.getElementById(tbodyId);
        tableBody.innerHTML = '';
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="6">No data available.</td></tr>`;
            return;
        }
        data.forEach(item => {
            const row = `
                <tr>
                    <td>${type.toUpperCase()}${String(item.userID).padStart(3, '0')}</td>
                    <td><img src="${item.profile_pict_url}" alt="Profile" class="table-pict"/></td>
                    <td>${item.username}</td>
                    <td>${item.email}</td>
                    <td>${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                    <td>
                        <div class="action-btns">
                            <button class="delete-icon" data-id="${item.userID}" data-name="${item.username}" title="Delete">🗑️</button>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    }

    // --- Logika Utama ---
    function initializeDashboard() {
        fetch('http://localhost/StrongU_Project/backend/get_admin_data.php')
        .then(res => res.json())
        .then(data => {
            // Isi Info Admin
            document.getElementById('admin-username').textContent = data.adminInfo.username;
            document.getElementById('admin-profile-pict').src = data.adminInfo.profile_pict_url;

            // Isi Statistik
            document.getElementById('total-users').textContent = data.stats.total_users;
            document.getElementById('total-trainers').textContent = data.stats.total_trainers;
            document.getElementById('today-income').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(data.stats.today_income)}`;
            
            // Simpan data dan render tabel
            allUsersData = data.allUsers;
            allPTsData = data.allPTs;
            renderTable('user-table-body', allUsersData, 'user');
            renderTable('pt-table-body', allPTsData, 'pt');
        })
        .catch(error => console.error('Error initializing dashboard:', error));
    }

    // --- Event Listeners ---
    // Navigasi Tab
    tabButtons.forEach(button => {
        button.addEventListener("click", function () {
            const targetTab = this.dataset.tab;
            tabButtons.forEach(btn => btn.classList.remove("active"));
            tabContents.forEach(tab => tab.classList.remove("active"));
            this.classList.add("active");
            document.getElementById(targetTab).classList.add("active");
        });
    });

    // Search
    document.getElementById('user-search-input').addEventListener('input', e => {
        const term = e.target.value.toLowerCase();
        const filtered = allUsersData.filter(u => u.username.toLowerCase().includes(term) || u.email.toLowerCase().includes(term));
        renderTable('user-table-body', filtered, 'user');
    });

    document.getElementById('pt-search-input').addEventListener('input', e => {
        const term = e.target.value.toLowerCase();
        const filtered = allPTsData.filter(pt => pt.username.toLowerCase().includes(term) || pt.email.toLowerCase().includes(term));
        renderTable('pt-table-body', filtered, 'pt');
    });

    // Delete (Event Delegation)
    document.querySelector('.admin-content').addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-icon')) {
            const userId = e.target.dataset.id;
            const userName = e.target.dataset.name;
            if (confirm(`Are you sure you want to delete user: ${userName} (ID: ${userId})? This action cannot be undone.`)) {
                fetch('http://localhost/StrongU_Project/backend/delete_user.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ userID: userId })
                })
                .then(res => res.json())
                .then(response => {
                    alert(response.message);
                    if (response.success) {
                        initializeDashboard(); // Muat ulang semua data
                    }
                });
            }
        }
    });

    // Panggil fungsi inisialisasi
    initializeDashboard();
});