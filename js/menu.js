const buttons = document.querySelectorAll(".category-btn");
const grids = document.querySelectorAll(".country-block");

function showCategory(country) {
    grids.forEach(g => g.classList.remove("active"));
    document.getElementById(country).classList.add("active");
}

buttons.forEach(btn => {
    btn.addEventListener("click", () => {
        buttons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        showCategory(btn.dataset.category);
    });
});

// Show default category on load
showCategory("srilankan");
