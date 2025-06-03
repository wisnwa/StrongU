document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const targetTab = this.getAttribute("data-tab");

            // Hapus class 'active' dari semua tombol dan semua tab
            tabButtons.forEach(btn => btn.classList.remove("active"));
            tabContents.forEach(tab => tab.classList.remove("active"));

            // Tambahkan 'active' ke tombol yang diklik dan tab target
            this.classList.add("active");
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add("active");
                targetContent.scrollIntoView({ behavior: "smooth" }); // opsional: auto scroll ke tab
            }
        });
    });
});