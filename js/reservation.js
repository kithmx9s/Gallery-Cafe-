// reservation.js
// Handles fetching stats and sending reservation via AJAX

document.addEventListener("DOMContentLoaded", () => {
  const totalTables = 50;
  const statsDate = document.getElementById('stats-date');
  const statsTime = document.getElementById('stats-time');
  const reservedCountEl = document.getElementById('reserved-count');
  const availableCountEl = document.getElementById('available-count');
  const totalTablesEl = document.getElementById('total-tables');
  const refreshBtn = document.getElementById('refresh-stats');
  const form = document.getElementById('reservation-form');

  totalTablesEl.textContent = totalTables;

  // fetch stats for selected date/time
  async function fetchStats(){
    const d = statsDate.value;
    const t = statsTime.value;
    try {
      const res = await fetch(`get_reservation_stats.php?date=${encodeURIComponent(d)}&time=${encodeURIComponent(t)}`);
      const data = await res.json();
      const reserved = parseInt(data.reserved) || 0;
      reservedCountEl.textContent = reserved;
      availableCountEl.textContent = Math.max(0, totalTables - reserved);
    } catch(err){
      console.error(err);
    }
  }

  // initial
  fetchStats();

  refreshBtn.addEventListener('click', (e) => {
    e.preventDefault();
    fetchStats();
  });

  // Poll every 20s for near-realtime (optional)
  setInterval(fetchStats, 20000);

  // Form submit via AJAX
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    // disable submit while sending
    const submitBtn = form.querySelector('.submit-btn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Reserving...';

    try {
      const res = await fetch('process_reservation.php', {
        method: 'POST',
        body: formData
      });
      const data = await res.json();
      if(data.success){
        // Show success message with ref
        alert(`Reservation Successful! Your Reference Number is - ${data.ref_display}`);
        // reset form lightly
        form.reset();
        statsDate.value = (new Date()).toISOString().split('T')[0];
        statsTime.value = (new Date()).toTimeString().slice(0,5);
        fetchStats();
      } else {
        alert('Error: ' + (data.error || 'Could not make reservation'));
      }
    } catch(err){
      console.error(err);
      alert('Network error, try again.');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Reserve Table';
    }
  });

});
