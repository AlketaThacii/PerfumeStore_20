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

    function showToast(message, type = "success") {
        let toast = document.getElementById("toast");
        if (!toast) return;

        toast.innerText = message;
        toast.className = "toast show " + type;

        setTimeout(() => {
            toast.className = "toast";
        }, 2500);
    }

    document.querySelectorAll(".add-to-cart-form").forEach(form => {

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            let button = this.querySelector("button");

            if (button.disabled) return;
            button.disabled = true;

            let formData = new FormData(this);

            fetch("/PerfumeStore_20/add_to_cart.php", { 
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                let cartCount = document.getElementById("cart-count");
                if (cartCount) {
                    cartCount.innerText = data.count;
                }
                let productName = formData.get("name");

                showToast(productName + " added to cart 🛒", "success");

                button.disabled = false;
            })
            .catch((err) => {
                console.error(err);
                showToast("Error adding product ❌", "error");
                button.disabled = false;
            });

        });

    });

});