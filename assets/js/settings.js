// assets/js/settings.js

document.addEventListener('DOMContentLoaded', () => {
    fetchRate(); // Fetch current global rate
    fetchStations(); // Fetch list for management
    startClock();

    // Event Listeners
    document.getElementById('updateRateForm').addEventListener('submit', handleUpdateRate);
    document.getElementById('addStationForm').addEventListener('submit', handleAddStation);
});

function startClock() {
    setInterval(() => {
        const now = new Date();
        const el = document.getElementById('server-clock');
        if(el) el.textContent = now.toLocaleTimeString();
    }, 1000);
}

// 1. Fetch Global Rate
async function fetchRate() {
    // In our schema, we have a 'rates' table. We'll add an endpoint to get/set this.
    // For now, we simulate or fetch from a new action we'll add to computers.php
    try {
        const res = await fetch('../api/computers.php?action=get_global_rate');
        const json = await res.json();
        if (json.success) {
            document.getElementById('global-rate-input').value = json.rate;
        }
    } catch (e) { console.error("Rate fetch failed", e); }
}

// 2. Fetch Stations for List
async function fetchStations() {
    const list = document.getElementById('settings-station-list');
    try {
        const res = await fetch('../api/computers.php?v=' + Date.now());
        const json = await res.json();
        
        if (json.success) {
            if (json.data.length === 0) {
                list.innerHTML = '<div class="text-center text-gray-500 text-xs py-4">No stations found.</div>';
                return;
            }

            list.innerHTML = json.data.map(pc => `
                <div class="flex items-center justify-between bg-slate-800/50 p-3 rounded border border-white/5 group hover:bg-white/[0.02] transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 rounded-full ${pc.status === 'Available' ? 'bg-emerald-500' : (pc.status === 'Maintenance' ? 'bg-amber-500' : 'bg-blue-500')}"></div>
                        <span class="text-sm font-mono font-bold text-gray-300">${escapeHtml(pc.computer_name)}</span>
                        <span class="text-[10px] text-gray-500 uppercase tracking-wider border border-white/10 px-1 rounded">${pc.status}</span>
                    </div>
                    
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                         <span class="text-xs text-gray-500 font-mono mr-2">₱${parseFloat(pc.current_rate || pc.default_rate).toFixed(2)}/hr</span>
                        ${pc.status === 'Available' ? `
                        <button onclick="deleteStation(${pc.id})" class="text-red-400 hover:text-red-300 hover:bg-red-500/10 p-1.5 rounded transition" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>` : `<span class="text-[10px] text-gray-600 cursor-not-allowed"><i class="fas fa-trash-alt"></i></span>`}
                    </div>
                </div>
            `).join('');
        }
    } catch (e) {
        list.innerHTML = '<div class="text-red-400 text-xs text-center">Error loading stations.</div>';
    }
}

// 3. Handle Update Rate
async function handleUpdateRate(e) {
    e.preventDefault();
    const rate = document.getElementById('global-rate-input').value;
    
    try {
        const res = await fetch('../api/computers.php?action=update_global_rate', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ rate: rate })
        });
        const json = await res.json();
        if (json.success) {
            // Show toast or alert
            alert("Global Rate Updated Successfully!");
            fetchRate();
        } else {
            alert(json.message);
        }
    } catch (err) { alert("Error updating rate"); }
}

// 4. Handle Add Station
async function handleAddStation(e) {
    e.preventDefault();
    const name = document.getElementById('new-station-name').value;
    
    try {
        const res = await fetch('../api/computers.php?action=add_computer', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ name: name })
        });
        const json = await res.json();
        if (json.success) {
            document.getElementById('addStationForm').reset();
            fetchStations(); // Refresh list
        } else {
            alert(json.message);
        }
    } catch (err) { alert("Error adding station"); }
}

// 5. Handle Delete Station
async function deleteStation(id) {
    if(!confirm("Are you sure you want to delete this station?")) return;
    
    try {
        const res = await fetch('../api/computers.php?action=delete_computer', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id: id })
        });
        const json = await res.json();
        if (json.success) {
            fetchStations();
        } else {
            alert(json.message);
        }
    } catch (err) { alert("Error deleting station"); }
}

// Helper (reused)
function escapeHtml(text) {
    if (!text) return text;
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
