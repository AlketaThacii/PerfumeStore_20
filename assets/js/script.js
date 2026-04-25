document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.getElementById("theme-toggle");

    if (toggleBtn) {
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
    }
});

