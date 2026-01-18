// Events data
const events = [
    {
        id: 1,
        title: "Wine Tasting Evening",
        image: "https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?w=600",
        date: "January 25, 2026",
        time: "7:00 PM - 10:00 PM",
        shortDescription: "Join us for an exclusive wine tasting experience featuring premium selections from around the world.",
        fullDescription: "Indulge in an unforgettable evening of wine appreciation at Gallery Café. Our sommelier will guide you through a curated selection of premium wines from renowned vineyards across the globe. Each wine will be paired with artisanal cheeses and gourmet appetizers, carefully chosen to complement and enhance the tasting experience. Whether you're a wine connoisseur or simply enjoy discovering new flavors, this event promises to delight your palate and expand your wine knowledge. Limited seats available - reserve your spot today!"
    },
    {
        id: 2,
        title: "Chef's Special Dinner",
        image: "https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600",
        date: "February 1, 2026",
        time: "6:30 PM - 9:30 PM",
        shortDescription: "Experience a multi-course culinary journey crafted by our award-winning chef.",
        fullDescription: "Embark on a gastronomic adventure with our Chef's Special Dinner, where culinary artistry meets exceptional ingredients. Our award-winning chef has designed an exquisite seven-course tasting menu that showcases seasonal ingredients and innovative cooking techniques. Each course is meticulously prepared and beautifully presented, transforming your dining experience into a work of art. The evening includes wine pairings selected by our sommelier to perfectly complement each dish. This is more than a meal - it's a celebration of fine dining at its best. Dress code: Smart casual."
    },
    {
        id: 3,
        title: "Live Jazz Night",
        image: "https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=600",
        date: "February 8, 2026",
        time: "8:00 PM - 11:00 PM",
        shortDescription: "Enjoy smooth jazz performances while savoring our signature cocktails and appetizers.",
        fullDescription: "Let the soulful sounds of live jazz transport you to a world of rhythm and relaxation at Gallery Café. Featuring talented local jazz musicians, this evening promises an enchanting atmosphere filled with classic standards and contemporary improvisations. As you sip on our signature cocktails and sample from our special appetizer menu, you'll be immersed in the timeless elegance of live jazz performance. The intimate setting of our café creates the perfect ambiance for this sophisticated evening of music and fine refreshments. Whether you're a jazz enthusiast or simply looking for a memorable night out, this event offers the perfect blend of culture, cuisine, and entertainment."
    },
    {
        id: 4,
        title: "Art Exhibition Opening",
        image: "https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?w=600",
        date: "February 15, 2026",
        time: "5:00 PM - 9:00 PM",
        shortDescription: "Celebrate local artists with an evening of art, wine, and sophisticated conversation.",
        fullDescription: "Gallery Café is proud to host the opening reception of our latest art exhibition, featuring works by talented local artists. This special evening brings together art lovers, collectors, and creative minds in a celebration of visual artistry. Browse stunning paintings, sculptures, and mixed media pieces while enjoying complimentary wine and hors d'oeuvres. Meet the artists, learn about their creative process, and perhaps find the perfect piece to add to your collection. The exhibition will remain on display for the following month, but the opening night offers a unique opportunity to experience the art in an festive, social atmosphere. All artwork will be available for purchase."
    }
];

// Populate events grid
function populateEvents() {
    const eventsGrid = document.getElementById('eventsGrid');
    
    events.forEach(event => {
        const eventCard = document.createElement('div');
        eventCard.className = 'event-card';
        eventCard.innerHTML = `
            <div class="event-image">
                <img src="${event.image}" alt="${event.title}">
            </div>
            <div class="event-info">
                <h3>${event.title}</h3>
                <div class="event-datetime">
                    <div class="datetime-item">
                        <i class="fa-solid fa-calendar"></i>
                        <span>${event.date}</span>
                    </div>
                    <div class="datetime-item">
                        <i class="fa-solid fa-clock"></i>
                        <span>${event.time}</span>
                    </div>
                </div>
                <p class="event-description">${event.shortDescription}</p>
                <button class="view-more-btn" onclick="openModal(${event.id})">
                    View More <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        `;
        
        eventsGrid.appendChild(eventCard);
    });
}

// Open modal
function openModal(eventId) {
    const event = events.find(e => e.id === eventId);
    if (!event) return;
    
    document.getElementById('modalImage').src = event.image;
    document.getElementById('modalTitle').textContent = event.title;
    document.getElementById('modalDate').textContent = event.date;
    document.getElementById('modalTime').textContent = event.time;
    document.getElementById('modalDescription').textContent = event.fullDescription;
    
    const modalOverlay = document.getElementById('modalOverlay');
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Close modal
function closeModal() {
    const modalOverlay = document.getElementById('modalOverlay');
    modalOverlay.classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Event listeners
document.getElementById('backBtn').addEventListener('click', closeModal);

document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Initialize
populateEvents();