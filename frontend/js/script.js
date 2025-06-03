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

            fetch("http://localhost/StrongU_Project/backend/signin.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    if (usernameError) usernameError.textContent = "";
                    if (passwordError) passwordError.textContent = "";
                    if (data.role === "admin") {
                        window.location.href = "../admin/dashboard.html";
                    } else {
                        window.location.href = "enter_zipcode.html";
                    }
                } else if (data.field === "username") {
                    if (usernameError) usernameError.textContent = data.message;
                    if (passwordError) passwordError.textContent = "";
                } else if (data.field === "password") {
                    if (passwordError) passwordError.textContent = data.message;
                    if (usernameError) usernameError.textContent = "";
                } else {
                    alert("Terjadi kesalahan: " + data.message);
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                alert("Terjadi kesalahan jaringan.");
            });
        });
    }

    // ============ FORM: SIGN UP ============
    const signupForm = document.getElementById("signup-form");
    if (signupForm) {
        const successPopup = document.getElementById("success-popup");
        const emailError = document.getElementById("email-error");
        const phoneError = document.getElementById("phone-error");

        if (successPopup) successPopup.style.display = "none";
        if (emailError) emailError.textContent = "";
        if (phoneError) phoneError.textContent = "";

        signupForm.addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(signupForm);

            for (let [key, value] of formData.entries()) {
                console.log(key, "=", value);
            }

            fetch("http://localhost/StrongU_Project/backend/signup.php", {
                method: "POST",
                body: formData,
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                console.log("Server response:", data);
                if (data.status === "success") {
                    alert("Berhasil daftar!");
                    signupForm.reset();
                    if (successPopup) successPopup.style.display = "block";
                } else if (data.field === "email") {
                    if (emailError) emailError.textContent = data.message;
                } else if (data.field === "phone") {
                    if (phoneError) phoneError.textContent = data.message;
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                alert("Terjadi kesalahan jaringan.");
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

    // ============ PROFILE ============
    // Ambil semua referensi elemen HTML
    const emailInput = document.getElementById("email");
    const phoneInput = document.getElementById("phone");
    const birthdayInput = document.getElementById("birthday");
    const zipcodeInput = document.getElementById("zipcode");
    const levelInputs = document.querySelectorAll('input[name="level"]');
    const sessionCheckboxes = document.querySelectorAll('input[name="session"]');
    const dayCheckboxes = document.querySelectorAll('input[name="day"]');
    const goalCheckboxes = document.querySelectorAll('input[name="goal"]');
    const saveBtn = document.getElementById("save-profile-btn");

    // Elemen UI untuk menampilkan data
    const sidebarUsername = document.getElementById("sidebar-username");
    const sidebarEmail = document.getElementById("sidebar-email");
    const sidebarPhone = document.getElementById("sidebar-phone");
    const profileNameText = document.getElementById("profile-name-text");

    // Input fields untuk mode edit
    const profileNameInput = document.getElementById("profile-name-input");
    const emailView = document.getElementById("email-view") || document.createElement("span");
    const phoneView = document.getElementById("phone-view") || document.createElement("span");
    const birthdayView = document.getElementById("birthday-view") || document.createElement("span");
    const zipcodeView = document.getElementById("zipcode-view") || document.createElement("span");

    // Fungsi: Aktifkan mode edit saat klik pada teks
    function enableEditMode(elementView, elementInput, currentValue) {
    elementView.addEventListener("click", () => {
        elementView.style.display = "none";
        elementInput.style.display = "inline-block";
        elementInput.value = currentValue;
        elementInput.focus();

        // Keluar dari mode edit saat input blur
        elementInput.addEventListener("blur", () => {
        const newValue = elementInput.value.trim();
        if (newValue !== "") {
            elementView.textContent = newValue;
            elementView.style.display = "inline-block";
            elementInput.style.display = "none";
        }
        });

        // Juga keluar saat tekan Enter
        elementInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            elementInput.blur();
        }
        });
    });
    }

    // Aktifkan mode edit untuk masing-masing field
    enableEditMode(profileNameText, profileNameInput, profileNameText.textContent);
    enableEditMode(emailView, emailInput, emailInput.value);
    enableEditMode(phoneView, phoneInput, phoneInput.value);
    enableEditMode(birthdayView, birthdayInput, birthdayInput.value);
    enableEditMode(zipcodeView, zipcodeInput, zipcodeInput.value);

    // Fetch profil dari API
    fetch("http://localhost/StrongU_Project/backend/get_profile.php")
    .then((res) => res.json())
    .then((data) => {
        // Update elemen UI
        sidebarUsername.textContent = data.username;
        profileNameText.textContent = data.username;
        sidebarEmail.textContent = data.email;
        sidebarPhone.textContent = data.noTelp;
        emailView.textContent = data.email;
        phoneView.textContent = data.noTelp;
        birthdayView.textContent = data.birthday;
        zipcodeView.textContent = data.zipcode;


        // Update input form
        emailInput.value = data.email;
        phoneInput.value = data.noTelp;
        birthdayInput.value = data.birthday;
        zipcodeInput.value = data.zipcode;

        // Update radio button
        levelInputs.forEach((radio) => {
        if (radio.value.toLowerCase() === data.level?.toLowerCase()) {
            radio.checked = true;
        }
        });

        // Fungsi centang checkbox
        const checkList = (dataArr, inputList) => {
        inputList.forEach((input) => {
            const value = parseInt(input.value);
            if (!isNaN(value) && dataArr.includes(value)) {
            input.checked = true;
            }
        });
        };

        checkList(data.session, sessionCheckboxes);
        checkList(data.day, dayCheckboxes);
        checkList(data.goal, goalCheckboxes);
    })
    .catch((error) => console.error("Error fetching profile:", error));

    // Event listener untuk tombol Save
    saveBtn.addEventListener("click", function () {
    const level = Array.from(levelInputs).find((r) => r.checked)?.value;
    if (!level) return alert("Please select a level.");

    const getCheckedValues = (name) =>
        Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map((cb) =>
        parseInt(cb.value)
        );

    const updatedData = {
        username: profileNameText.textContent,
        email: emailInput.value.trim(),
        noTelp: phoneInput.value.trim(),
        birthday: birthdayInput.value,
        zipcode: zipcodeInput.value.trim(),
        level: level,
        session: getCheckedValues("session"),
        day: getCheckedValues("day"),
        goal: getCheckedValues("goal"),
    };

    if (!/^\d{5}$/.test(updatedData.zipcode)) {
        alert("Zipcode harus 5 digit angka.");
        return;
    }

    fetch("http://localhost/StrongU_Project/backend/update_profile.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(updatedData),
    })
        .then((res) => res.json())
        .then((res) => {
        if (res.success) {
            alert("Profile berhasil diperbarui.");

            // Update juga tampilan UI setelah update
            sidebarUsername.textContent = updatedData.username;
            profileNameText.textContent = updatedData.username;
            sidebarEmail.textContent = updatedData.email;
            sidebarPhone.textContent = updatedData.noTelp;
        } else {
            alert(res.error || "Gagal update profile.");
        }
        })
        .catch((error) => console.error("Error updating profile:", error));
    });

    // Tombol navigasi
    document.querySelector("[data-tab='saved-profile']").addEventListener("click", () => {
    window.location.href = "program_taken.html";
    });
    document.querySelector("[data-tab='logout-profile']").addEventListener("click", () => {
    window.location.href = "signin.html";
    });
    
    // ============ CHATBOX ============
    const trainerChats = document.querySelectorAll(".trainer-chat");
    const searchChatInput = document.getElementById("searchChatInput");

    trainerChats.forEach(chat => {
        const hasUnread = true;
        if (hasUnread) {
            chat.querySelector(".unread-indicator").style.display = "block";
        }
    });

    if (searchChatInput) {
        searchChatInput.addEventListener("input", function () {
            const filter = searchChatInput.value.toLowerCase();
            trainerChats.forEach(chat => {
                const name = chat.getAttribute("data-trainer").toLowerCase();
                if (name.includes(filter)) {
                    chat.style.display = "flex";
                } else {
                    chat.style.display = "none";
                }
            });
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
