// assets/js/waitlist.js
document.addEventListener('DOMContentLoaded', () => {
    fetchWaitlist();
    
    const form = document.getElementById('addWaitlistForm'); // Ensure ID matches
    if(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('waitlist-name').value;
            await addToWaitlist(name);
            document.getElementById('waitlist-name').value = '';
            document.getElementById('addWaitlistModal').classList.add('hidden');
        });
    }

    // Assign form
    const assignForm = document.getElementById('assignForm');
    if(assignForm) {
        assignForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await submitAssign();
        });
    }
});

async function fetchWaitlist() {
    const res = await fetch('../api/waitlist.php');
    const json = await res.json();
    const rows = document.getElementById('waitlist-rows');
    rows.innerHTML = '';
    
    if(!json.data || json.data.length === 0) {
        rows.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-gray-500 italic">Queue is empty.</td></tr>`;
        return;
    }

    json.data.forEach((item, index) => {
        const date = new Date(item.request_time);
        const timeStr = date.toLocaleTimeString();
        
        rows.innerHTML += `
            <tr class="hover:bg-white/5 transition border-b border-gray-700/50">

                <td class="p-4 font-bold text-white flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name=${item.customer_name}&background=random&color=fff&size=32" class="rounded w-8 h-8 opacity-80 border border-white/10">
                    <span class="font-mono tracking-wide uppercase">${item.customer_name}</span>
                </td>
                <td class="p-4 text-gray-400 font-mono text-xs">${timeStr}</td>
                <td class="p-4"><span class="bg-amber-500/10 text-amber-500 border border-amber-500/50 py-1 px-3 rounded-full text-xs uppercase tracking-wider">${item.status}</span></td>
                <td class="p-4 text-right">
                    <button onclick="openAssignModal(${item.id}, '${item.customer_name}')" class="text-xs uppercase font-bold tracking-wider text-cyan-400 hover:text-white mr-4 transition cursor-pointer"><i class="fas fa-check mr-1"></i> Assign</button>
                    <button onclick="cancelWaitlist(${item.id})" class="text-xs uppercase font-bold tracking-wider text-red-400 hover:text-white transition cursor-pointer"><i class="fas fa-times mr-1"></i> Cancel</button>
                </td>
            </tr>
        `;
    });
}

async function addToWaitlist(name) {
    const res = await fetch('../api/waitlist.php?action=add', {
        method: 'POST',
        body: JSON.stringify({ customer_name: name })
    });
    const json = await res.json();
    if(!json.success) {
        alert(json.message);
    }
    fetchWaitlist();
}

async function cancelWaitlist(id) {
    if(!confirm('Remove this entry from the queue?')) return;
    await fetch('../api/waitlist.php?action=cancel', {
        method: 'POST',
        body: JSON.stringify({ id })
    });
    fetchWaitlist();
}
window.cancelWaitlist = cancelWaitlist;

// Custom Dropdown Logic
function setupDropdown() {
    const trigger = document.getElementById('dropdown-trigger');
    const list = document.getElementById('dropdown-list');
    const container = document.getElementById('custom-dropdown-container');

    if(trigger && list) {
        // Toggle
        trigger.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent immediate close
            list.classList.toggle('hidden');
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                list.classList.add('hidden');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', setupDropdown);

async function openAssignModal(id, name) {
    document.getElementById('assign-id').value = id;
    document.getElementById('assign-name').textContent = `CUSTOMER: ${name}`;
    
    // Reset Dropdown
    document.getElementById('assign-station-select').value = '';
    document.getElementById('dropdown-selected-text').textContent = 'SELECT TERMINAL';
    document.getElementById('dropdown-selected-text').className = 'text-gray-400';
    
    // Fetch available stations
    const res = await fetch('../api/computers.php');
    const json = await res.json();
    const list = document.getElementById('dropdown-list');
    list.innerHTML = '';
    
    // Status is 'Available' in computers table
    const seen = new Set();
    const available = json.data.filter(s => {
        if(s.status === 'Available' && !seen.has(s.id)) {
            seen.add(s.id);
            return true;
        }
        return false;
    });
    
    if(available.length === 0) {
        list.innerHTML = `<div class="p-3 text-sm text-gray-500 font-mono italic">NO TERMINALS AVAILABLE</div>`;
    } else {
        available.forEach(s => {
            const item = document.createElement('div');
            item.className = 'p-3 hover:bg-purple-900/30 cursor-pointer text-sm text-gray-300 hover:text-white transition font-mono border-b border-gray-800 last:border-0';
            item.textContent = s.computer_name;
            item.onclick = () => {
                // Select Item
                document.getElementById('assign-station-select').value = s.id;
                const disp = document.getElementById('dropdown-selected-text');
                disp.textContent = s.computer_name;
                disp.className = 'text-white font-bold tracking-widest';
                document.getElementById('dropdown-list').classList.add('hidden');
            };
            list.appendChild(item);
        });
    }
    
    document.getElementById('assignModal').classList.remove('hidden');
}
window.openAssignModal = openAssignModal;

async function submitAssign() {
    console.log("Submit Assign Triggered");
    const waitlistId = document.getElementById('assign-id').value;
    const stationId = document.getElementById('assign-station-select').value;
    
    if(!stationId) {
        alert("Please select a valid Available Terminal.");
        return;
    }

    // Get name text and strip prefix
    const nameText = document.getElementById('assign-name').textContent;
    const name = nameText.replace('CUSTOMER: ', '').trim(); // Added trim
    
    // UI Feedback
    const btn = document.querySelector('#assignForm button[type="submit"]');
    const originalText = btn.textContent;
    btn.textContent = "Assigning...";
    btn.disabled = true;

    try {
        console.log(`Assigning ${name} to Station ${stationId}...`);
        
        const res = await fetch('../api/computers.php?action=start', {
            method: 'POST',
            body: JSON.stringify({ station_id: stationId, customer_name: name })
        });
        
        const json = await res.json();
        console.log("Assign Response:", json);
        
        if(json.success) {
            // Mark fulfilled
            await fetch('../api/waitlist.php?action=fulfill', {
                method: 'POST',
                body: JSON.stringify({ id: waitlistId })
            });
            
            document.getElementById('assignModal').classList.add('hidden');
            window.location.href = 'dashboard.php'; 
        } else {
            alert("Failed to assign: " + json.message);
            btn.textContent = originalText;
            btn.disabled = false;
        }
    } catch(e) {
        console.error(e);
        alert("Network Error: " + e.message);
        btn.textContent = originalText;
        btn.disabled = false;
    }
}
window.submitAssign = submitAssign;
