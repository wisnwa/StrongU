document.addEventListener("DOMContentLoaded", function () {
// ============ FORM: SIGN IN ============
    const signinForm = document.getElementById("signin-form");
    if (signinForm) {
        const usernameError = document.getElementById("username-error");
        const passwordError = document.getElementById("password-error");

        // Pastikan span error selalu kosong saat form dimuat
        if (usernameError) usernameError.textContent = "";
        if (passwordError) passwordError.textContent = "";

        signinForm.addEventListener("submit", function(event) {
            event.preventDefault();

            var username = signinForm.username.value.trim();
            var password = signinForm.password.value.trim();

            const formData = new FormData();
            formData.append("username", username);
            formData.append("password", password);

            fetch("http://localhost/StrongU_Project/backend/signin.php", { // Pastikan URL ini benar
                method: "POST",
                body: formData
            })
            .then(res => {
                if (!res.ok) { // Periksa apakah respons HTTP tidak sukses (misal: 404, 500)
                    return res.json().then(errData => {
                        throw new Error(errData.message || "Network response was not ok");
                    });
                }
                return res.json();
            })
            .then(data => {
                if (data.status === "success") {
                    if (usernameError) usernameError.textContent = "";
                    if (passwordError) passwordError.textContent = "";

                    if (data.role === "admin") {
                        window.location.href = "../admin/dashboard.html";
                    } else if (data.role === "user") {
                        // Logika baru berdasarkan kolom 'level'
                        if (data.level !== null && data.level !== undefined && data.level !== '') {
                            window.location.href = "home.html"; // Jika level ada, ke home.html
                        } else {
                            window.location.href = "enter_zipcode.html"; // Jika level null, ke enter_zipcode.html
                        }
                    } else {
                        // Handle role lain jika ada, atau default redirect
                        alert("Role tidak dikenali. Mengarahkan ke halaman utama.");
                        window.location.href = "welcome.html"; // Atau halaman default lainnya
                    }
                } else if (data.field === "username") {
                    if (usernameError) usernameError.textContent = data.message;
                    if (passwordError) passwordError.textContent = "";
                } else if (data.field === "password") {
                    if (passwordError) passwordError.textContent = data.message;
                    if (usernameError) usernameError.textContent = "";
                } else {
                    // Menampilkan pesan error umum dari server jika ada
                    alert("Terjadi kesalahan: " + (data.message || "Respons tidak diketahui"));
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                alert("Terjadi kesalahan jaringan atau server: " + err.message);
            });
        });
    }

    // ============ FORM: SIGN UP ============
    const signupForm = document.getElementById("signup-form");
    if (signupForm) {
        const successPopup = document.getElementById("success-popup");
        const emailInput = document.getElementById("email"); // Ambil elemen input email
        const emailError = document.getElementById("email-error");
        const phoneError = document.getElementById("phone-error");

        if (successPopup) successPopup.style.display = "none";
        if (emailError) emailError.textContent = "";
        if (phoneError) phoneError.textContent = "";

        signupForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Selalu cegah submit default dulu

            const emailValue = emailInput.value.trim(); // Ambil nilai email

            // Validasi email harus diakhiri dengan @gmail.com
            if (!emailValue.endsWith("@gmail.com")) {
                if (emailError) emailError.textContent = "Email harus menggunakan domain @gmail.com";
                return; // Hentikan proses jika email tidak valid
            } else {
                if (emailError) emailError.textContent = ""; // Kosongkan error jika valid
            }

            const formData = new FormData(signupForm);

            // Anda bisa menghapus loop console.log ini jika sudah tidak diperlukan untuk debugging
            // for (let [key, value] of formData.entries()) {
            //     console.log(key, "=", value);
            // }

            fetch("http://localhost/StrongU_Project/backend/signup.php", { // Pastikan URL ini benar
                method: "POST",
                body: formData,
                credentials: 'same-origin' // Pastikan Anda memahami implikasi credentials
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(errData => {
                        throw new Error(errData.message || "Network response was not ok");
                    });
                }
                return res.json();
            })
            .then(data => {
                console.log("Server response:", data);
                if (data.status === "success") {
                    alert("Berhasil daftar!");
                    signupForm.reset();
                    if (emailError) emailError.textContent = ""; // Pastikan error dikosongkan
                    if (phoneError) phoneError.textContent = ""; // Pastikan error dikosongkan
                    if (successPopup) successPopup.style.display = "block";
                } else if (data.field === "email") {
                    if (emailError) emailError.textContent = data.message;
                } else if (data.field === "phone") {
                    if (phoneError) phoneError.textContent = data.message;
                } else {
                    // Menampilkan pesan error umum dari server jika ada dan belum ditangani spesifik
                    alert("Error: " + (data.message || "Terjadi kesalahan saat pendaftaran."));
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                alert("Terjadi kesalahan jaringan atau server: " + err.message);
            });
        });
    }

    // ============ FORM: ZIPCODE ============
    const zipcodeForm = document.getElementById("zipcode-form");
    if (zipcodeForm) {
        zipcodeForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const zipcode = zipcodeForm.zipcode.value.trim();

            if (!/^\d{5}$/.test(zipcode)) {
                alert("Zipcode harus terdiri dari 5 angka.");
                return;
            }

            const formData = new FormData();
            formData.append("zipcode", zipcode);

            fetch("http://localhost/StrongU_Project/backend/zipcode.php", {
                method: "POST",
                body: formData,
                credentials: "include" // penting agar session terbawa
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "enter_birthday.html";
                } else {
                    alert("Gagal menyimpan zipcode: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan jaringan.");
            });
        });
    }


    // ============ FORM: BIRTHDAY ============
    const birthdayForm = document.getElementById("birthday-form");

    if (birthdayForm) {
        birthdayForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const birthday = birthdayForm.birthday.value;

            if (!birthday) {
                alert("Tanggal lahir harus diisi.");
                return;
            }

            const inputDate = new Date(birthday);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Buat pastikan cuma tanggal yg dibandingkan

            if (inputDate > today) {
                alert("Tanggal lahir tidak sesuai");
                return;
            }

            const formData = new FormData();
            formData.append("birthday", birthday);

            fetch("http://localhost/StrongU_Project/backend/birthday.php", {
                method: "POST",
                body: formData,
                credentials: "include" // biar session login kebawa
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "choose_session.html";
                } else {
                    alert("Gagal menyimpan birthday: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan jaringan.");
            });
        });
    }


    // ============ FORM: SESSION ============
    const sessionOptions = document.querySelectorAll(".session-option");
    const sessionForm = document.getElementById("session-form");

    // Buat toggle efek klik
    sessionOptions.forEach(option => {
        option.addEventListener("click", function () {
            const checkbox = this.querySelector("input");
            checkbox.checked = !checkbox.checked;
            this.classList.toggle("selected");
        });
    });

    if (sessionForm) {
        sessionForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const checkedSessions = document.querySelectorAll('input[name="session"]:checked');

            if (checkedSessions.length === 0) {
                alert("Please select at least one session.");
                return;
            }

            const formData = new FormData();
            checkedSessions.forEach(cb => {
                formData.append("sessions[]", cb.value); // Kirim array
            });

            fetch("http://localhost/StrongU_Project/backend/session.php", {
                method: "POST",
                body: formData,
                credentials: "include"
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "choose_day.html";
                } else {
                    alert("Gagal menyimpan sesi: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan jaringan.");
            });
        });
    }

    // ============ FORM: DAY ============
    const dayOptions = document.querySelectorAll(".day-option");
    const dayForm = document.getElementById("day-form");

    dayOptions.forEach(option => {
        option.addEventListener("click", function () {
            const checkbox = this.querySelector("input");
            checkbox.checked = !checkbox.checked;
            this.classList.toggle("selected");
        });
    });

    if (dayForm) {
        dayForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const checkedDays = document.querySelectorAll('input[name="day"]:checked');
            if (checkedDays.length === 0) {
                alert("Please select at least one day.");
                return;
            }

            const selectedValues = Array.from(checkedDays).map(cb => cb.value);

            const formData = new FormData();
            formData.append("days", JSON.stringify(selectedValues)); // kirim array sebagai string JSON

            fetch("http://localhost/StrongU_Project/backend/day.php", {
                method: "POST",
                body: formData,
                credentials: "include"
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "choose_goal.html";
                } else {
                    alert("Gagal menyimpan hari: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan jaringan.");
            });
        });
    }

    // ============ FORM: GOAL ============
    const goalOptions = document.querySelectorAll(".goal-option");
    const goalForm = document.getElementById("goal-form");

    goalOptions.forEach(option => {
        option.addEventListener("click", function () {
            const checkbox = this.querySelector("input");
            checkbox.checked = !checkbox.checked;
            this.classList.toggle("selected", checkbox.checked);
        });
    });

    if (goalForm) {
        goalForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const checkedGoals = document.querySelectorAll('input[name="goal"]:checked');
            if (checkedGoals.length === 0) {
                alert("Please select at least one goal.");
                return;
            }

            const selectedValues = Array.from(checkedGoals).map(cb => cb.value);

            const formData = new FormData();
            formData.append("goals", JSON.stringify(selectedValues)); // Kirim array sebagai string JSON

            fetch("http://localhost/StrongU_Project/backend/goal.php", {
                method: "POST",
                body: formData,
                credentials: "include"
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "choose_level.html"; 
                } else {
                    alert("Gagal menyimpan tujuan: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan jaringan.");
            });
        });
    }


    // ============ FORM: LEVEL ============
    const levelOptions = document.querySelectorAll(".level-option");
    const levelForm = document.getElementById("level-form");

    // Toggle visual dan centang radio button
    levelOptions.forEach(option => {
        option.addEventListener("click", function () {
            const radioInput = this.querySelector("input");

            // Hanya centang jika bukan sudah dicentang
            if (!radioInput.checked) {
                levelOptions.forEach(opt => {
                    opt.classList.remove("selected");
                    opt.querySelector("input").checked = false;
                });

                radioInput.checked = true;
                this.classList.add("selected");
            }
        });
    });

    // Submit form
    if (levelForm) {
        levelForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const selectedRadio = document.querySelector('input[name="level"]:checked');

            if (!selectedRadio) {
                alert("Please select a level.");
                return;
            }

            const selectedValue = selectedRadio.value;

            const formData = new FormData();
            formData.append("level", selectedValue);

            fetch("http://localhost/StrongU_Project/backend/level.php", {
                method: "POST",
                body: formData,
                credentials: "include"
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "home.html";
                } else {
                    alert("Gagal menyimpan level: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan jaringan.");
            });
        });
    }

    
    
    // ============ TAB NAVIGATION ============
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    function changeTab(event) {
        tabButtons.forEach(button => button.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        const clickedButton = event.currentTarget;
        clickedButton.classList.add('active');

        const tabId = clickedButton.getAttribute('data-tab');
        const activeContent = document.getElementById(tabId);
        if (activeContent) activeContent.classList.add('active');
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', changeTab);
    });

    // ============ PROFILE CARD SELECTION ============
    const sessionCards = document.querySelectorAll('.session-card');
    const continueBtn = document.getElementById('continueBtn');
    let selectedSession = null;

    sessionCards.forEach(card => {
        card.addEventListener('click', () => {
            sessionCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            selectedSession = card.dataset.session;
            continueBtn.disabled = false;
        });
    });

    if (continueBtn) {
        continueBtn.addEventListener('click', () => {
            if (selectedSession) {
                localStorage.setItem('selectedSession', selectedSession);
                window.location.href = 'payment.html';
            }
        });
    }

    // ============ PAYMENT METHOD SELECTION ============
    const methodOptions = document.querySelectorAll(".method-option");
    const confirmBtn = document.getElementById("confirm-btn");

    methodOptions.forEach(option => {
        option.addEventListener("click", () => {
            methodOptions.forEach(opt => opt.classList.remove("selected"));
            option.classList.add("selected");
            const radio = option.querySelector("input[type='radio']");
            if (radio) radio.checked = true;
            confirmBtn.disabled = false;
            confirmBtn.classList.add("enabled");
        });
    });

    if (confirmBtn) {
        confirmBtn.addEventListener("click", () => {
            if (!confirmBtn.disabled) {
                alert("Metode pembayaran dipilih. Lanjut ke proses berikutnya.");
            }
        });
    }

    // ============ PROFILE PAGE ============
    const profileMain = document.getElementById("profile-main");
    if (profileMain) {

        // =================================================
        // BAGIAN 1: DEKLARASI SEMUA ELEMEN (VARIABEL)
        // =================================================

        // --- Form dan Tombol Utama ---
        const editProfileForm = document.getElementById("editProfileForm");
        const saveBtn = document.getElementById("save-profile-btn");

        // --- Gambar ---
        const changePictureBtn = document.getElementById("changePictureBtn");
        const deletePictureBtn = document.getElementById("deletePictureBtn");
        const profilePictureInput = document.getElementById("profile-picture-input");
        const sidebarProfileImg = document.getElementById("sidebar-profile-img");
        const editProfilePicture = document.getElementById("edit-profile-picture");
        const defaultProfilePic = '../images/profile-pict.png'; // Ganti jika path default Anda berbeda

        // --- Mode Lihat & Edit (Text Fields) ---
        const usernameView = document.getElementById("username-view"), usernameInput = document.getElementById("username");
        const emailView = document.getElementById("email-view"), emailInput = document.getElementById("email");
        const phoneView = document.getElementById("phone-view"), phoneInput = document.getElementById("noTelp");
        const birthdayView = document.getElementById("birthday-view"), birthdayInput = document.getElementById("birthday");
        const zipcodeView = document.getElementById("zipcode-view"), zipcodeInput = document.getElementById("zipcode");

        // --- Navigasi Tab ---
        const tabButtons = document.querySelectorAll(".tab-button-profile");
        const tabContents = document.querySelectorAll(".tab-content");
        const trainerSettingTab = document.getElementById("trainer-setting-tab");

        // =================================================
        // BAGIAN 2: DEKLARASI SEMUA FUNGSI
        // =================================================

        /**
         * Mengaktifkan mode "klik-untuk-edit" pada pasangan elemen span (lihat) dan input (edit).
         * @param {HTMLElement} view - Elemen span untuk mode lihat.
         * @param {HTMLElement} input - Elemen input untuk mode edit.
         */
        function enableEditMode(view, input) {
            if (!view || !input) return;
            view.addEventListener("click", () => {
                view.style.display = "none";
                input.style.display = "block";
                input.focus();
            });
            input.addEventListener("blur", () => {
                view.textContent = input.value.trim();
                view.style.display = "block";
                input.style.display = "none";
            });
            input.addEventListener("keypress", e => {
                if (e.key === "Enter") {
                    e.preventDefault(); // Mencegah form tersubmit saat menekan enter di input
                    input.blur();
                }
            });
        }

        /**
         * Memuat semua data profil dari server dan mengisi form.
         */
        function loadProfileData() {
            saveBtn.disabled = true;
            saveBtn.textContent = "Loading...";

            fetch("http://localhost/StrongU_Project/backend/get_profile.php")
                .then(res => res.ok ? res.json() : Promise.reject('Gagal memuat profil'))
                .then(data => {
                    if (data.error) { throw new Error(data.error); }

                    // Mengisi semua data ke form
                    const imageUrl = data.profile_pict_url || defaultProfilePic;
                    sidebarProfileImg.src = imageUrl;
                    editProfilePicture.src = imageUrl;

                    document.getElementById("sidebar-username").textContent = data.username;
                    document.getElementById("sidebar-email").textContent = data.email;
                    document.getElementById("sidebar-phone").textContent = data.noTelp;
                    
                    usernameView.textContent = data.username;
                    emailView.textContent = data.email;
                    phoneView.textContent = data.noTelp;
                    birthdayView.textContent = data.birthday;
                    zipcodeView.textContent = data.zipcode;
                    
                    usernameInput.value = data.username;
                    emailInput.value = data.email;
                    phoneInput.value = data.noTelp;
                    birthdayInput.value = data.birthday;
                    zipcodeInput.value = data.zipcode;
                    
                    if (data.level) {
                        const levelRadio = document.querySelector(`input[name="level"][value="${data.level}"]`);
                        if (levelRadio) levelRadio.checked = true;
                    }

                    const checkBoxes = (name, ids) => {
                        if (!Array.isArray(ids)) return;
                        document.querySelectorAll(`input[name="${name}[]"]`).forEach(cb => {
                            cb.checked = ids.includes(parseInt(cb.value));
                        });
                    };
                    
                    checkBoxes('session', data.session);
                    checkBoxes('day', data.day);
                    checkBoxes('goal', data.goal);
                    
                    // Menampilkan tab khusus 'Trainer' jika rolenya 'pt'
                    if (data.role === 'pt' && trainerSettingTab) {
                        trainerSettingTab.style.display = 'block';
                    }

                    saveBtn.disabled = false;
                    saveBtn.textContent = "Save Profile";
                })
                .catch(error => {
                    console.error("Error fetching profile:", error);
                    alert("Gagal memuat data profil. Periksa koneksi dan coba lagi.");
                    saveBtn.textContent = "Gagal Memuat";
                });
        }
        
        /**
         * Menangani logika perpindahan antar tab.
         * @param {Event} event - Event klik dari tombol tab.
         */
        function handleTabClick(event) {
            const clickedButton = event.currentTarget;
            const targetTabId = clickedButton.dataset.tab;

            if (targetTabId === 'logout-profile') {
                if (confirm("Anda yakin ingin keluar?")) window.location.href = "signin.html";
                return;
            }
            if (targetTabId === 'saved-profile') {
                window.location.href = "program_taken.html";
                return;
            }

            tabButtons.forEach(button => button.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            clickedButton.classList.add('active');
            const targetContent = document.getElementById(targetTabId);
            if (targetContent) targetContent.classList.add('active');
        }

        // =================================================
        // BAGIAN 3: MENJALANKAN FUNGSI & EVENT LISTENERS
        // =================================================

        // Terapkan mode edit ke setiap field
        enableEditMode(usernameView, usernameInput);
        enableEditMode(emailView, emailInput);
        enableEditMode(phoneView, phoneInput);
        enableEditMode(birthdayView, birthdayInput);
        enableEditMode(zipcodeView, zipcodeInput);
        
        // Panggil fungsi untuk memuat data saat halaman dibuka
        loadProfileData();

        // Tambahkan event listener untuk setiap tombol tab
        tabButtons.forEach(button => button.addEventListener("click", handleTabClick));

        // --- Event Listener untuk Fitur Gambar Profil ---

        // 1. UPLOAD OTOMATIS SAAT GAMBAR DIPILIH
        changePictureBtn.addEventListener("click", () => profilePictureInput.click());
        profilePictureInput.addEventListener("change", function() {
            if (!this.files || this.files.length === 0) return;
            const file = this.files[0];
            const formData = new FormData();
            formData.append('profile_picture', file);

            const reader = new FileReader();
            reader.onload = e => {
                sidebarProfileImg.src = e.target.result;
                editProfilePicture.src = e.target.result;
            };
            reader.readAsDataURL(file);

            fetch("http://localhost/StrongU_Project/backend/upload_picture.php", {
                method: "POST",
                body: formData,
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || data.error);
                if (data.success && data.new_picture_url) {
                    sidebarProfileImg.src = data.new_picture_url;
                    editProfilePicture.src = data.new_picture_url;
                } else if (!data.success) {
                    loadProfileData(); // Kembalikan ke foto lama jika upload gagal
                }
            })
            .catch(error => {
                console.error("Upload error:", error);
                alert("Gagal mengunggah gambar.");
                loadProfileData();
            });
        });

        // 2. HAPUS GAMBAR PROFIL
        deletePictureBtn.addEventListener("click", function() {
            if (!confirm("Anda yakin ingin menghapus foto profil?")) return;

            fetch("http://localhost/StrongU_Project/backend/delete_picture.php", {
                method: "POST"
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || data.error);
                if (data.success) {
                    sidebarProfileImg.src = defaultProfilePic;
                    editProfilePicture.src = defaultProfilePic;
                }
            })
            .catch(error => console.error("Delete error:", error));
        });

        // --- Event Listener untuk Simpan Data Teks ---
        editProfileForm.addEventListener("submit", function (event) {
            event.preventDefault();

            // Pastikan semua input yang tersembunyi memiliki nilai terbaru dari span-nya
            document.querySelectorAll('.edit-mode').forEach(input => {
                if (input.style.display === 'none') {
                    const viewId = input.id.replace(/([a-zA-Z]+)/, '$1-view');
                    const viewElement = document.getElementById(viewId);
                    if (viewElement) input.value = viewElement.textContent;
                }
            });

            // Kumpulkan semua data teks dari form
            const updatedData = {
                username: usernameInput.value,
                email: emailInput.value,
                noTelp: phoneInput.value,
                birthday: birthdayInput.value,
                zipcode: zipcodeInput.value,
                level: document.querySelector('input[name="level"]:checked')?.value || null,
                session: Array.from(document.querySelectorAll('input[name="session[]"]:checked')).map(cb => parseInt(cb.value)),
                day: Array.from(document.querySelectorAll('input[name="day[]"]:checked')).map(cb => parseInt(cb.value)),
                goal: Array.from(document.querySelectorAll('input[name="goal[]"]:checked')).map(cb => parseInt(cb.value))
            };

            // Kirim sebagai JSON ke skrip PHP yang HANYA mengurus data teks
            fetch("http://localhost/StrongU_Project/backend/update_profile.php", {
                method: "POST",
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(updatedData),
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || data.error);
                if (data.success) {
                    // Perbarui tampilan di sidebar jika berhasil
                    document.getElementById("sidebar-username").textContent = updatedData.username;
                    document.getElementById("sidebar-email").textContent = updatedData.email;
                    document.getElementById("sidebar-phone").textContent = updatedData.noTelp;
                }
            })
            .catch(error => console.error("Update error:", error));
        });
    }

    // ============ SEND MESSAGE ============
    const sendBtn = document.getElementById('send-btn');
    if (sendBtn) {
        sendBtn.addEventListener('click', function () {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();

            if (message !== "") {
                const chatBox = document.getElementById('chat-container');
                const msgDiv = document.createElement('div');
                msgDiv.classList.add('chat-message', 'user-msg');
                msgDiv.textContent = message;
                chatBox.appendChild(msgDiv);
                input.value = "";
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    }

    // ============ FUNCTION: openChatbox ============
    window.openChatbox = function () {
        window.location.href = "chatbox.html";
    };
});
