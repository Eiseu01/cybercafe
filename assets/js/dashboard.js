// assets/js/dashboard.js
let computers = [];
let currentRate = 20.00; // Default fallback if API fails
let serverTimeOffset = 0; 
let timerInterval;

// XSS Protection Helper
function escapeHtml(text) {
    if (!text) return text;
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    // Clear stats immediately to prevent stale data flash
    const statIds = ['stat-occupied', 'stat-available', 'stat-maintenance', 'stat-waiting', 'stat-revenue'];
    statIds.forEach(id => {
        if(document.getElementById(id)) document.getElementById(id).textContent = '--';
    });

    fetchComputers();
    fetchDashboardStats();
    
    setInterval(fetchComputers, 5000); // 5s for realtime status
    setInterval(fetchDashboardStats, 15000); // 15s for stats
    
    timerInterval = setInterval(updateTimers, 200); // 5fps for smooth timer
    
    // Server clock
    setInterval(() => {
        const now = new Date(Date.now() + serverTimeOffset);
        if(document.getElementById('server-clock')) document.getElementById('server-clock').textContent = now.toLocaleTimeString();
    }, 1000);
    
    // Wire up events
    const startForm = document.getElementById('startForm');
    if(startForm) startForm.addEventListener('submit', handleStartSubmit);

    // Mobile Sidebar Toggle
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    
    if (mobileBtn && sidebar) {
        mobileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('-translate-x-full');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !mobileBtn.contains(e.target) && !sidebar.classList.contains('-translate-x-full')) {
               sidebar.classList.add('-translate-x-full');
            }
        });
    }
});

async function fetchDashboardStats() {
    if (!document.getElementById('stat-revenue')) return; // Exit if not on dashboard page
    
    try {
        const res = await fetch('../api/dashboard_stats.php');
        const json = await res.json();
        
        if (json.success) {
            const data = json.data;
            
            // 1. Revenue
            const revEl = document.getElementById('stat-revenue');
            if (revEl) revEl.textContent = '₱' + parseFloat(data.revenue_today).toLocaleString(undefined, {minimumFractionDigits: 2});
            
            // 2. Counts
            if(document.getElementById('stat-occupied')) document.getElementById('stat-occupied').textContent = data.pc_occupied;
            if(document.getElementById('stat-available')) document.getElementById('stat-available').textContent = data.pc_available;
            if(document.getElementById('stat-maintenance')) document.getElementById('stat-maintenance').textContent = data.pc_maintenance;
            if(document.getElementById('stat-waiting')) document.getElementById('stat-waiting').textContent = data.waitlist_count !== undefined ? data.waitlist_count : '--';

            // Bar widths (Visual only)
            const occPct = (data.pc_occupied / data.pc_total) * 100;
            const avlPct = (data.pc_available / data.pc_total) * 100;
            
            if(document.getElementById('bar-occupied')) document.getElementById('bar-occupied').style.width = `${occPct}%`;
            if(document.getElementById('bar-available')) document.getElementById('bar-available').style.width = `${avlPct}%`;

            // 3. Feed
            const feedContainer = document.getElementById('feed-container');
            if (feedContainer && data.recent_transactions) {
                // Slice to top 8 to fit perfectly no scroll
                const limitedTx = data.recent_transactions.slice(0, 8);
                feedContainer.innerHTML = limitedTx.map(tx => `
                    <div class="flex-1 flex items-center justify-between px-3 bg-slate-800/50 rounded border border-white/5 hover:bg-cyan-900/10 transition group mb-1 last:mb-0 min-h-[40px]">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded bg-cyan-900/40 flex items-center justify-center text-cyan-400 border border-cyan-500/30 group-hover:shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                                <i class="fas fa-receipt text-xs"></i>
                            </div>
                            <div>
                                <div class="text-xs text-white font-bold font-mono tracking-wide uppercase">${escapeHtml(tx.customer_name)}</div>
                                <div class="text-[10px] text-cyan-500/50 font-mono">${escapeHtml(tx.computer_name || 'COUNTER')} • ${new Date(tx.time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                            </div>
                        </div>
                        <div class="text-cyan-400 font-bold text-xs font-mono tracking-widest">+₱${parseFloat(tx.amount).toFixed(2)}</div>
                    </div>
                `).join('');
                if (data.recent_transactions.length === 0) feedContainer.innerHTML = '<div class="text-center text-gray-500 text-xs py-4 font-mono">NO DATA STREAM</div>';
            }

            // 4. Leaderboard
            const lbContainer = document.getElementById('leaderboard-container');
            if(lbContainer && data.top_customers) {
                lbContainer.innerHTML = data.top_customers.map((c, i) => {
                    let rankColor = "bg-slate-700/50 text-gray-400";
                    if(i===0) rankColor = "bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 shadow-[0_0_10px_rgba(234,179,8,0.2)]";
                    if(i===1) rankColor = "bg-gray-400/20 text-gray-300 border border-gray-400/30";
                    if(i===2) rankColor = "bg-amber-700/20 text-amber-600 border border-amber-700/30";

                    return `
                    <div class="flex-1 flex items-center justify-between px-2 hover:bg-white/[0.02] transition border-b border-white/5 last:border-0 min-h-[40px]">
                        <div class="flex items-center space-x-3">
                            <div class="${rankColor} font-bold w-6 h-6 rounded flex items-center justify-center text-[10px] font-mono shadow-inner shrink-0">#${i+1}</div>
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(c.customer_name)}&background=random&color=fff&size=32" class="rounded w-6 h-6 opacity-80 border border-white/10 shrink-0">
                            <span class="text-xs text-gray-300 font-bold tracking-wide uppercase font-mono truncate max-w-[100px]">${escapeHtml(c.customer_name)}</span>
                        </div>
                        <div class="text-cyan-500 text-xs font-bold font-mono tracking-widest shrink-0">₱${parseFloat(c.total_spent).toLocaleString()}</div>
                    </div>
                `}).join('');
            }
        }
    } catch(e) { console.error("Stats fetch error", e); }
}

async function fetchComputers() {
    try {
        const res = await fetch('../api/computers.php?v=' + Date.now());
        const json = await res.json();
        
        if (json.success) {
            computers = json.data;
            
            // Sync Rate (Global default is just for new additions or fallback in UI)
            // But now every computer has its own rate, so global 'currentRate' is less relevant
            // except maybe for 'Add Computer' defaults.
            
            const serverDate = new Date(json.server_time); 
            serverTimeOffset = serverDate.getTime() - Date.now();
            
            renderComputers();
            
            // Update Waitlist Count
            const waitingEl = document.getElementById('stat-waiting');
            if (waitingEl) {
                waitingEl.textContent = json.waiting_count !== undefined ? json.waiting_count : '--';
            }

            updateStats();
        }
    } catch (e) {
        console.error("Failed to fetch computers", e);
    }
}

function renderComputers() {
    const grid = document.getElementById('stations-grid');
    if (!grid) return; 
    
    // remove spinner if it exists
    const loader = document.getElementById('initial-loader');
    if(loader) loader.remove();

    // Create a set of active IDs to handle deletions if needed (though usually fixed count)
    const activeIds = new Set();

    computers.forEach(pc => {
        activeIds.add(pc.id);
        activeIds.add(pc.id);
        const cardId = `station-card-${pc.id}`;
        let existingCard = document.getElementById(cardId);
        
        const isOccupied = pc.status === 'Occupied';
        const displayRate = pc.current_rate || pc.default_rate || currentRate;
        
        // Determine if we need to re-render
        let shouldRender = true;
        
        if (existingCard) {
            const currentStatus = existingCard.dataset.status;
            const currentCustomer = existingCard.dataset.customer;
            const currentName = existingCard.dataset.name;
            const currentRate = parseFloat(existingCard.dataset.rate || 0);
            
            // Optimization: Only re-render if something meaningful changed
            if (currentStatus === pc.status && 
                currentName === pc.computer_name && 
                Math.abs(currentRate - parseFloat(displayRate)) < 0.01) {
                
                if (pc.status === 'Occupied' && currentCustomer === pc.customer_name) {
                    shouldRender = false;
                } else if (pc.status !== 'Occupied') {
                    shouldRender = false;
                }
            }
        }

        if (!shouldRender) return; // SKIP RENDER -> SMOOTH TIMER

        // Admin Controls (Top Right - Delete Only)
        let adminTopControls = '';
        let adminBottomBtn = '';
        
        if (typeof USER_ROLE !== 'undefined' && (USER_ROLE === 'admin' || USER_ROLE === 'Admin')) { // Show for admin only
            adminTopControls = `
            <div class="mr-2">
                <button onclick="openDeleteComputerModal(${pc.id}, '${escapeHtml(pc.computer_name)}')" class="w-8 h-8 rounded-full flex items-center justify-center bg-white/5 text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition cursor-pointer" title="Delete Terminal">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </div>`;

            // Blue Edit Button for footer
            adminBottomBtn = `
                <button onclick="openEditComputerModal(${pc.id}, '${escapeHtml(pc.computer_name)}')" class="btn w-full mt-2 bg-blue-600/20 text-blue-400 hover:bg-blue-600 hover:text-white border border-blue-500/30 text-xs uppercase tracking-widest shadow-none transition-all">
                    <i class="fas fa-cog mr-1"></i> Configure
                </button>
            `;
        }
        
        // Modern Flat Styling
        let cardBase = "card p-0 flex flex-col justify-between h-full min-h-[260px] overflow-hidden group hover:shadow-glow border-transparent";
        let statusColor = "bg-brand-surface";
        let statusBadge = "";
        
        // Timer Logic
        let timerHtml = '';

        if (isOccupied && pc.start_time) {
            statusColor = "bg-brand-surface"; 
            statusBadge = `<span class="badge bg-emerald-500/10 text-emerald-400 border-emerald-500/20">Occupied</span>`;
            
            timerHtml = `
                <div class="my-6 text-center">
                    <div class="text-4xl font-bold text-gray-300 tracking-tight timer mb-1" data-start="${pc.start_time}">Syncing...</div>
                    <div class="text-xs font-bold text-brand-secondary uppercase tracking-wider mb-2">Session Duration</div>
                    <div class="inline-block px-3 py-1 bg-white/5 rounded-lg text-sm font-medium text-emerald-400 live-price" data-start="${pc.start_time}" data-rate="${displayRate}">₱0.00</div>
                </div>`;
        } else {
            statusBadge = `<span class="badge bg-brand-secondary/10 text-brand-secondary border-brand-secondary/20">Available</span>`;
            timerHtml = `
            <div class="my-6 text-center opacity-50">
                <div class="text-4xl font-bold text-brand-secondary tracking-tight mb-1">--:--</div>
                <div class="text-xs font-bold text-brand-secondary uppercase tracking-wider">Ready to Start</div>
            </div>`;
        }

        // Action Button
        let btnHtml = '';
        let userDisplay = '';
        
        if (pc.status === 'Available') {
            btnHtml = `
                <button onclick="openStartModal(${pc.id}, '${escapeHtml(pc.computer_name)}')" 
                    class="btn btn-secondary w-full justify-center group-hover:bg-brand-primary group-hover:text-white group-hover:border-transparent">
                    <i class="fas fa-power-off mr-1"></i> Start Session
                </button>`;
        } else if (pc.status === 'Occupied') {
             userDisplay = `
                <div class="flex items-center justify-center space-x-2 mb-4 bg-brand-primary/10 py-1.5 mx-6 rounded-lg">
                    <i class="fas fa-user-circle text-brand-primary"></i>
                    <span class="text-sm font-medium text-gray-300 truncate max-w-[120px]">${escapeHtml(pc.customer_name)}</span>
                </div>
             `;
            btnHtml = `
                <button onclick="endSession(${pc.session_id}, '${escapeHtml(pc.customer_name)}')" 
                    class="btn btn-danger w-full justify-center bg-red-500/10 hover:bg-red-600 border-red-500/20 text-red-400 hover:text-white">
                    End Session
                </button>`;
        }
        
        // Maintenance
        if (pc.status === 'Maintenance') {
             statusBadge = `<span class="badge bg-amber-500/10 text-amber-500 border-amber-500/20">Maintenance</span>`;
             timerHtml = `<div class="my-6 text-center text-amber-500/50 font-bold text-xl uppercase tracking-widest">Offline</div>`;
             btnHtml = `<button disabled class="btn w-full bg-white/5 text-gray-500 cursor-not-allowed border-transparent">Unavailable</button>`;
        }

        const html = `
            <div id="${cardId}" data-status="${pc.status}" data-customer="${escapeHtml(pc.customer_name || '')}" data-name="${escapeHtml(pc.computer_name)}" data-rate="${displayRate}" class="${cardBase} ${statusColor}">
                
                <!-- Card Header -->
                <div class="p-5 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                    <h3 class="font-bold text-lg text-gray-300 tracking-wide">${escapeHtml(pc.computer_name)}</h3>
                    <div class="flex items-center gap-2">
                        ${adminTopControls}
                        ${statusBadge}
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="flex-1 flex flex-col justify-center relative pt-2">
                     <div class="text-center mb-[-1rem] z-10 relative">
                        <span class="inline-block px-2 py-0.5 rounded bg-white/5 text-[10px] font-bold font-mono text-gray-500 border border-white/5">
                            ₱${parseFloat(displayRate).toFixed(2)} / HR
                        </span>
                     </div>
                     ${timerHtml}
                     ${userDisplay}
                </div>
                
                <!-- Card Footer -->
                <div class="p-5 border-t border-white/5 bg-white/[0.02]">
                    ${btnHtml}
                    ${adminBottomBtn}
                </div>
            </div>`;

        if (existingCard) {
            existingCard.outerHTML = html;
        } else {
            grid.insertAdjacentHTML('beforeend', html);
        }
    });
    
    // Remove deleted computers
    Array.from(grid.children).forEach(child => {
        if (child.id.startsWith('station-card-')) {
            const idPart = parseInt(child.id.replace('station-card-', ''));
            if (!activeIds.has(idPart)) {
                child.remove();
            }
        }
    });
    
    // Instant update after render
    updateTimers();
}

function updateTimers() {
    const timers = document.querySelectorAll('.timer');
    const prices = document.querySelectorAll('.live-price');
    const now = Date.now() + serverTimeOffset;
    
    // Update Timers
    timers.forEach(el => {
        try {
            const startStr = el.dataset.start;
            const start = new Date(startStr).getTime();
            let diff = now - start;
            
            if (isNaN(diff)) { el.textContent = "00:00:00"; return; }
            if (diff < 0) diff = 0;
            
            const hrs = Math.floor(diff / 3600000);
            const mins = Math.floor((diff % 3600000) / 60000);
            const secs = Math.floor((diff % 60000) / 1000);
            
            el.textContent = 
                (hrs < 10 ? '0' : '') + hrs + ':' + 
                (mins < 10 ? '0' : '') + mins + ':' + 
                (secs < 10 ? '0' : '') + secs;
        } catch(e) { console.error(e); }
    });

    // Update Prices
    prices.forEach(el => {
        try {
            const startStr = el.dataset.start;
            const start = new Date(startStr).getTime();
            let diff = now - start; 

            if (isNaN(diff) || diff < 0) diff = 0;
            
            const rate = parseFloat(el.dataset.rate) || currentRate;
            const hours = diff / 3600000;
            const rawPrice = hours * rate;
            const price = Math.round(rawPrice * 4) / 4; // Round to .25
            
            el.textContent = `Bill: ₱${price.toFixed(2)}`;
        } catch(e) {}
    });
}

function updateStats() {
    // Rely solely on server-side stats to prevent conflicts/fake data
    fetchDashboardStats();
}

// Modals
function openStartModal(id, name) {
    document.getElementById('start-station-id').value = id;
    document.getElementById('modal-station-name').textContent = `PC Name: ${name}`;
    document.getElementById('startModal').classList.remove('hidden');
    document.getElementById('start-customer').focus();
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    if(id === 'startModal') document.getElementById('startForm').reset();
}
window.closeModal = closeModal;

async function handleStartSubmit(e) {
    e.preventDefault();
    const stationId = document.getElementById('start-station-id').value;
    const customerName = document.getElementById('start-customer').value;
    
    try {
        const res = await fetch('../api/computers.php?action=start', {
            method: 'POST',
            body: JSON.stringify({ station_id: stationId, customer_name: customerName })
        });
        const json = await res.json();
        
        if(json.success) {
            closeModal('startModal');
            fetchComputers();
        } else {
            alert(json.message);
        }
    } catch(err) { alert("Error starting session"); }
}

function endSession(sessionId, customerName) {
    document.getElementById('stop-session-id').value = sessionId;
    document.getElementById('stop-customer-name').textContent = customerName || 'Customer';
    document.getElementById('stopConfirmModal').classList.remove('hidden');
}
window.endSession = endSession;

// --- ADMIN FUNCTIONS ---
function openAddComputerModal() { // Renamed from Station
    document.getElementById('addStationModal').classList.remove('hidden');
    document.getElementById('add-station-name').focus();
}

function openEditComputerModal(id, name) { // Renamed from Station
    document.getElementById('edit-station-id').value = id;
    document.getElementById('edit-station-name').value = name;
    
    const pc = computers.find(s => s.id == id);
    const rateInput = document.getElementById('edit-station-rate');
    const rateLabel = rateInput.previousElementSibling;
    const statusSelect = document.getElementById('edit-station-status');
    const statusContainer = document.getElementById('edit-status-container');
    
    // Always show rate input
    rateInput.style.display = 'block';
    if(rateLabel) rateLabel.style.display = 'block';

    const currentPcRate = pc.current_rate || pc.default_rate || 20.00;
    rateInput.value = currentPcRate;
    rateInput.placeholder = "Rate: " + currentPcRate;
    rateInput.disabled = false;

    if (pc && pc.status === 'Occupied') {
        if(rateLabel) rateLabel.innerText = "Hourly Rate (Active Session + Future)";
        statusContainer.classList.add('hidden');
    } else {
        if(rateLabel) rateLabel.innerText = "Hourly Rate";
        statusContainer.classList.remove('hidden');
        statusSelect.value = pc.status === 'Maintenance' ? 'Maintenance' : 'Available';
    }

    document.getElementById('editStationModal').classList.remove('hidden');
    document.getElementById('edit-station-name').focus();
}

async function handleAddComputer(e) {
    e.preventDefault();
    const name = document.getElementById('add-station-name').value;
    
    try {
        const res = await fetch('../api/computers.php?action=add_computer', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ name })
        });
        const json = await res.json();
        if (json.success) {
            closeModal('addStationModal');
            document.getElementById('addStationForm').reset();
            fetchComputers();
        } else { alert(json.message); }
    } catch (err) { alert('Error adding computer'); }
}

async function handleEditComputer(e) {
    e.preventDefault();
    const id = document.getElementById('edit-station-id').value;
    const name = document.getElementById('edit-station-name').value;
    const rate = document.getElementById('edit-station-rate').value;
    const status = document.getElementById('edit-station-status').value;
    
    const isOccupiedHidden = document.getElementById('edit-status-container').classList.contains('hidden');
    
    try {
        const body = { id, name };
        if (rate) body.rate = rate; 
        if (!isOccupiedHidden) body.status = status;
        
        const res = await fetch('../api/computers.php?action=edit_computer', { // Updated API
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(body)
        });
        const json = await res.json();
        if (json.success) {
            closeModal('editStationModal');
            fetchComputers();
        } else { alert(json.message); }
    } catch (err) { alert('Error updating computer'); }
}

async function processDeleteComputer() {
    const id = document.getElementById('delete-station-id').value;
    try {
        const res = await fetch('../api/computers.php?action=delete_computer', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id })
        });
        const json = await res.json();
        if (json.success) {
            closeModal('deleteStationModal');
            fetchComputers();
        } else { alert(json.message); }
    } catch (err) { alert('Error deleting computer'); }
}

function openDeleteComputerModal(id, name) {
    document.getElementById('delete-station-id').value = id;
    document.getElementById('delete-station-name').textContent = name;
    document.getElementById('deleteStationModal').classList.remove('hidden');
}

async function processStopSession() {
    const sessionId = document.getElementById('stop-session-id').value;
    closeModal('stopConfirmModal');
    try {
        const res = await fetch('../api/computers.php?action=end', {
            method: 'POST',
            body: JSON.stringify({ session_id: sessionId })
        });
        const json = await res.json();
        if(json.success) {
            document.getElementById('end-fee').textContent = '₱' + parseFloat(json.fee).toFixed(2);
            document.getElementById('endModal').classList.remove('hidden');
            fetchComputers();
        } else { alert(json.message); }
    } catch(err) { alert("Error ending session"); }
}

// Wire up Admin events
document.addEventListener('DOMContentLoaded', () => {
    // Note: 'rateForm' (global rates) is effectively deprecated or used only for bulk updates if we kept it.
    // The user asked to remove rate tables. So we might just hide the Rates button in HTML.
    
    const addForm = document.getElementById('addStationForm'); // Modal ID unchanged for now to save HTML edits
    if (addForm) addForm.addEventListener('submit', handleAddComputer);

    const editForm = document.getElementById('editStationForm');
    if (editForm) editForm.addEventListener('submit', handleEditComputer);
});

// Expose for HTML onclicks
window.openEditComputerModal = openEditComputerModal;
window.openDeleteComputerModal = openDeleteComputerModal;
window.processDeleteComputer = processDeleteComputer;
window.processStopSession = processStopSession;
