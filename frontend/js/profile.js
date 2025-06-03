document.addEventListener("DOMContentLoaded", function () {
  const usernameInput = document.getElementById("username");
  const emailInput = document.getElementById("email");
  const phoneInput = document.getElementById("noTelp");
  const birthdayInput = document.getElementById("birthday");
  const zipcodeInput = document.getElementById("zipcode");
  const levelInputs = document.querySelectorAll('input[name="level"]');

  const sessionCheckboxes = document.querySelectorAll('input[name="session[]"]');
  const dayCheckboxes = document.querySelectorAll('input[name="day[]"]');
  const goalCheckboxes = document.querySelectorAll('input[name="goal[]"]');

  const saveBtn = document.getElementById("save-profile-btn");

  // fetch profile
  fetch("http://localhost/StrongU_Project/backend/get_profile.php")
    .then((res) => res.json())
    .then((data) => {
      usernameInput.value = data.username;
      emailInput.value = data.email;
      phoneInput.value = data.noTelp;
      birthdayInput.value = data.birthday;
      zipcodeInput.value = data.zipcode;

      if (data.level) {
        levelInputs.forEach(radio => {
          if (radio.value === data.level) radio.checked = true;
        });
      }

      const checkList = (dataArr, inputList) => {
        inputList.forEach(input => {
          if (dataArr.includes(parseInt(input.value))) input.checked = true;
        });
      };

      checkList(data.session, sessionCheckboxes);
      checkList(data.day, dayCheckboxes);
      checkList(data.goal, goalCheckboxes);
    });

  // Save profile
  saveBtn.addEventListener("click", function () {
    const level = Array.from(levelInputs).find(r => r.checked)?.value;
    if (!level) return alert("Please select a level.");

    const getCheckedValues = (name) =>
      Array.from(document.querySelectorAll(`input[name="${name}[]"]:checked`)).map(cb => parseInt(cb.value));

    const updatedData = {
      username: usernameInput.value.trim(),
      email: emailInput.value.trim(),
      noTelp: phoneInput.value.trim(),
      birthday: birthdayInput.value,
      zipcode: zipcodeInput.value.trim(),
      level: level,
      session: getCheckedValues("session"),
      day: getCheckedValues("day"),
      goal: getCheckedValues("goal")
    };

    if (!/^\d{5}$/.test(updatedData.zipcode)) {
      alert("Zipcode harus 5 digit angka.");
      return;
    }

    fetch("http://localhost/StrongU_Project/backend/update.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updatedData)
    })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          alert("Profile berhasil diperbarui.");
        } else {
          alert(res.error || "Gagal update profile.");
        }
      });
  });

  // Tombol navigasi
  document.querySelector("[data-tab='saved-profile']").addEventListener("click", () => {
    window.location.href = "program_taken.html";
  });

  document.querySelector("[data-tab='logout-profile']").addEventListener("click", () => {
    window.location.href = "signin.html";
  });
});
