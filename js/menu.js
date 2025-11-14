// Hardcoded items (until DB setup)
const menuData = {
    srilankan: [
        { name: "Kottu", price: "LKR 450", category: "Sri Lankan" },
        { name: "Rice & Curry", price: "LKR 350", category: "Sri Lankan" },
        { name: "String Hoppers", price: "LKR 200", category: "Sri Lankan" },
        { name: "Hoppers", price: "LKR 180", category: "Sri Lankan" }
    ],
    indian: [
        { name: "Naan", price: "LKR 150", category: "Indian" },
        { name: "Chicken Tikka", price: "LKR 600", category: "Indian" }
    ],
    chinese: [
        { name: "Fried Rice", price: "LKR 550", category: "Chinese" },
        { name: "Chow Mein", price: "LKR 500", category: "Chinese" }
    ],
    beverage: [
        { name: "Milkshake", price: "LKR 300", category: "Beverage" },
        { name: "Iced Coffee", price: "LKR 250", category: "Beverage" }
    ],
    dessert: [
        { name: "Watalappan", price: "LKR 200", category: "Dessert" }
    ]
};

// Inject items into grid
function loadItems(category) {
    const container = document.getElementById("items-container");
    container.innerHTML = "";

    menuData[category].forEach(item => {
        container.innerHTML += `
            <div class="item-card">
                <div class="item-img">Sample Image</div>
                <h3>${item.name}</h3>
                <p>${item.price}</p>
                <small>${item.category}</small>
                <button class="add-btn">Add to Cart</button>
            </div>
        `;
    });
}

// Initial load
loadItems("srilankan");

// Category Switching
document.querySelectorAll(".category-btn").forEach(btn => {
    btn.addEventListener("click", () => {

        document.querySelector(".category-btn.active").classList.remove("active");
        btn.classList.add("active");

        const cat = btn.getAttribute("data-category");
        loadItems(cat);
    });
});
