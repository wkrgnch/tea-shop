
(function () {
    const slides = document.getElementById("slides");
    const dots = document.getElementById("dots");
    if (!slides || !dots) return;

    const list = Array.from(slides.querySelectorAll(".slide"));
    let i = 0;

    function renderDots() {
        dots.innerHTML = "";
        list.forEach((_, idx) => {
        const b = document.createElement("button");
        b.type = "button";
        b.className = "dot" + (idx === i ? " dot-on" : "");
        b.addEventListener("click", () => go(idx));
        dots.appendChild(b);
        });
    }

    function go(idx) {
        i = (idx + list.length) % list.length;
        slides.style.transform = `translateX(${-i * 100}%)`;
        renderDots();
    }

    renderDots();
    setInterval(() => go(i + 1), 4500);
})();

//переключение режимов магазина (таблица или карточки)
(function () {
    const viewTable = document.getElementById("viewTable");
    const viewCards = document.getElementById("viewCards");
    const btnTable = document.getElementById("btnTable");
    const btnCards = document.getElementById("btnCards");
    if (!viewTable || !viewCards || !btnTable || !btnCards) return;

    btnTable.addEventListener("click", () => {
        viewTable.classList.remove("hidden");
        viewCards.classList.add("hidden");
    });

    btnCards.addEventListener("click", () => {
        viewCards.classList.remove("hidden");
        viewTable.classList.add("hidden");
    });

      // На маленьких экранах сразу показываем карточки
    if (window.innerWidth < 860) {
    viewCards.classList.remove("hidden");
    viewTable.classList.add("hidden");
    }

})();

// добавить или убрать из списка желаний
(function () {
    const buttons = document.querySelectorAll(".js-wish");
    if (!buttons.length) return;

    buttons.forEach(btn => {
        btn.addEventListener("click", async () => {
        const productId = btn.getAttribute("data-product-id");
        if (!productId) return;

        btn.disabled = true;

        const form = new FormData();
        form.append("product_id", productId);

        try {
            const r = await fetch("api/wishlist_toggle.php", { method: "POST", body: form });
            const data = await r.json();

            if (!data.ok) {
            alert("Нужно войти, чтобы пользоваться списком покупок.");
            btn.disabled = false;
            return;
        }

        btn.setAttribute("data-in", data.inList ? "1" : "0");
        btn.textContent = data.inList ? "Убрать" : "В список";

        const wc = document.getElementById("wishCount");
        if (wc) wc.textContent = String(data.count);

        // Если убрали на странице account.php — перезагрузим, чтобы список обновился
        if (location.pathname.includes("account.php") && !data.inList) location.reload();
        } catch (e) {
            alert("Ошибка сети.");
        } finally {
            btn.disabled = false;
        }
        });
    });
})();
