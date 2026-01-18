document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('reservationForm');
    const btn = document.getElementById('submitBtn');

    if (!form || !btn) return;

    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.innerHTML = '<span>Processing...</span>';
    });
});