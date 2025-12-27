// assets/js/dashboard.js
let stations = [];
let serverTimeOffset = 0; 
let timerInterval;

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchStations();
    setInterval(fetchStations, 5000);
    timerInterval = setInterval(updateTimers, 1000);
    
    // Server clock
    setInterval(() => {
        const now = new Date(Date.now() + serverTimeOffset);
        document.getElementById('server-clock').textContent = now.toLocaleTimeString();
    }, 1000);
    
    // Wire up events
    document.getElementById('startForm').addEventListener('submit', handleStartSubmit);
});

async function fetchStations() {
    try {
        const res = await fetch('../api/stations.php');
        const json = await res.json();
        
        if (json.success) {
            stations = json.data;
            const serverDate = new Date(json.server_time); 
            serverTimeOffset = serverDate.getTime() - Date.now();
            
            renderStations();
            updateStats();
        }
    } catch (e) {
        console.error("Failed to fetch stations", e);
    }
}

function renderStations() {
    const grid = document.getElementById('stations-grid');
    grid.innerHTML = '';
    
    stations.forEach(station => {
        const isOccupied = station.status === 'Occupied';
        
        // Dynamic Cyber Styling
        const borderClass = isOccupied ? 'border-red-500/50 shadow-[0_0_15px_rgba(239,68,68,0.3)]' : 'border-emerald-500/50 shadow-[0_0_15px_rgba(16,185,129,0.2)]';
        const bgClass = isOccupied ? 'bg-slate-800/80' : 'bg-slate-800/40 hover:bg-slate-800/60';
        const iconColor = isOccupied ? 'text-red-500' : 'text-emerald-500';
        
        // Timer Logic
        let timerHtml = '';
        if (isOccupied && station.start_time) {
            timerHtml = `<div class="text-3xl font-mono my-4 font-bold text-white tracking-widest timer" data-start="${station.start_time}">--:--:--</div>`;
        } else {
            timerHtml = `<div class="text-3xl font-mono my-4 font-bold text-gray-600 tracking-widest">00:00:00</div>`;
        }

        // Action Button
        let btnHtml = '';
        let userDisplay = '';
        
        if (station.status === 'Available') {
            userDisplay = `<div class="text-xs text-gray-500 font-mono italic mb-2">SYSTEM STANDBY</div>`;
            btnHtml = `
                <button onclick="openStartModal(${station.id}, '${station.station_name}')" 
                    class="w-full py-2 rounded bg-emerald-600/20 text-emerald-400 border border-emerald-500/50 hover:bg-emerald-600 hover:text-white transition uppercase text-xs font-bold tracking-wider shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                    <i class="fas fa-power-off mr-1"></i> Initialize
                </button>`;
        } else if (station.status === 'Occupied') {
             userDisplay = `
                <div class="mb-2">
                    <div class="text-[10px] text-gray-400 uppercase tracking-widest">Operator</div>
                    <div class="text-cyan-400 font-bold truncate font-mono"><i class="fas fa-user-circle mr-1"></i> ${station.customer_name}</div>
                </div>
             `;
            btnHtml = `
                <button onclick="endSession(${station.session_id}, '${station.customer_name}')" 
                    class="w-full py-2 rounded bg-red-600/20 text-red-400 border border-red-500/50 hover:bg-red-600 hover:text-white transition uppercase text-xs font-bold tracking-wider shadow-[0_0_10px_rgba(220,38,38,0.2)]">
                    <i class="fas fa-stop mr-1"></i> Terminate
                </button>`;
        } else {
            btnHtml = `<button disabled class="w-full py-2 rounded bg-gray-700 text-gray-400 cursor-not-allowed uppercase text-xs font-bold tracking-wider">Maintenance</button>`;
        }

        const html = `
            <div class="glass-panel p-6 rounded-xl relative transition-all duration-300 hover:-translate-y-1 border ${borderClass} ${bgClass} group flex flex-col justify-between h-auto min-h-[250px]">
                <div>
                    <div class="flex justify-between items-start border-b border-gray-700/50 pb-2 mb-2">
                        <h3 class="font-bold text-lg text-white font-mono uppercase tracking-widest">${station.station_name}</h3>
                        <div class="${iconColor} animate-pulse"><i class="fas fa-circle text-[8px]"></i></div>
                    </div>
                </div>
                
                <div class="text-center flex-1 flex flex-col justify-center">
                    ${timerHtml}
                    ${userDisplay}
                </div>
                
                <div class="mt-2">
                    ${btnHtml}
                </div>

                <!-- Corner Decorations -->
                <div class="absolute top-0 left-0 w-2 h-2 border-t border-l border-white/20 rounded-tl-lg"></div>
                <div class="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-white/20 rounded-br-lg"></div>
            </div>
        `;
        grid.insertAdjacentHTML('beforeend', html);
    });
    
    updateTimers();
}

function updateTimers() {
    const timers = document.querySelectorAll('.timer');
    const now = Date.now() + serverTimeOffset;
    
    timers.forEach(el => {
        const start = new Date(el.dataset.start).getTime();
        const diff = now - start;
        
        if (diff >= 0) {
            const hrs = Math.floor(diff / 3600000);
            const mins = Math.floor((diff % 3600000) / 60000);
            const secs = Math.floor((diff % 60000) / 1000);
            
            el.textContent = 
                (hrs < 10 ? '0' : '') + hrs + ':' + 
                (mins < 10 ? '0' : '') + mins + ':' + 
                (secs < 10 ? '0' : '') + secs;
        }
    });
}

function updateStats() {
    const total = stations.length;
    let occ = 0;
    stations.forEach(s => {
        if(s.status === 'Occupied') occ++;
    });
    
    if(document.getElementById('stat-available')) 
        document.getElementById('stat-available').textContent = (total - occ);
    if(document.getElementById('stat-occupied'))
        document.getElementById('stat-occupied').textContent = occ;
    
    // Waitlist placeholder or fetch if planned
    if(document.getElementById('stat-waiting'))
        document.getElementById('stat-waiting').textContent = '--'; 
}

// Modals
function openStartModal(id, name) {
    document.getElementById('start-station-id').value = id;
    document.getElementById('modal-station-name').textContent = `TARGET: ${name}`;
    document.getElementById('startModal').classList.remove('hidden');
    document.getElementById('start-customer').focus();
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    if(id === 'startModal') document.getElementById('startForm').reset();
}
window.closeModal = closeModal; // Make global

// Start Session Submit
async function handleStartSubmit(e) {
    e.preventDefault();
    const stationId = document.getElementById('start-station-id').value;
    const customerName = document.getElementById('start-customer').value;
    
    try {
        const res = await fetch('../api/stations.php?action=start', {
            method: 'POST',
            body: JSON.stringify({ station_id: stationId, customer_name: customerName })
        });
        const json = await res.json();
        
        if(json.success) {
            closeModal('startModal');
            fetchStations();
        } else {
            alert(json.message);
        }
    } catch(err) {
        alert("Error starting session");
    }
}

// Open Stop Confirmation
function endSession(sessionId, customerName) {
    document.getElementById('stop-session-id').value = sessionId;
    document.getElementById('stop-customer-name').textContent = customerName || 'Customer';
    document.getElementById('stopConfirmModal').classList.remove('hidden');
}
window.endSession = endSession; // Make global

// Actual API Call
async function processStopSession() {
    const sessionId = document.getElementById('stop-session-id').value;
    closeModal('stopConfirmModal');
    
    try {
        const res = await fetch('../api/stations.php?action=end', {
            method: 'POST',
            body: JSON.stringify({ session_id: sessionId })
        });
        const json = await res.json();
        
        if(json.success) {
            document.getElementById('end-fee').textContent = '$' + parseFloat(json.fee).toFixed(2);
            document.getElementById('endModal').classList.remove('hidden');
        } else {
            alert(json.message);
        }
    } catch(err) {
        alert("Error ending session");
    }
}
window.processStopSession = processStopSession; // Make global
