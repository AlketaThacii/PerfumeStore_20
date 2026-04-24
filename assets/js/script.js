document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("theme-toggle");

    if (!toggleBtn) return;

    // kontrollo a ka theme të ruajtur
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark");
        toggleBtn.textContent = "☀️";
    }

    toggleBtn.addEventListener("click", () => {
        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark")) {
            toggleBtn.textContent = "☀️";
            localStorage.setItem("theme", "dark");
        } else {
            toggleBtn.textContent = "🌙";
            localStorage.setItem("theme", "light");
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".add-to-cart-form").forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("add_to_cart.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                document.getElementById("cart-count").innerText = data.count;
            });
        });
    });
});
