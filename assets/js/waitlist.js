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

                <td class="p-4 font-bold text-white">${item.customer_name}</td>
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
    await fetch('../api/waitlist.php?action=add', {
        method: 'POST',
        body: JSON.stringify({ customer_name: name })
    });
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

async function openAssignModal(id, name) {
    document.getElementById('assign-id').value = id;
    document.getElementById('assign-name').textContent = `CUSTOMER: ${name}`;
    
    // Fetch available stations
    const res = await fetch('../api/stations.php');
    const json = await res.json();
    const select = document.getElementById('assign-station-select');
    select.innerHTML = '';
    
    const available = json.data.filter(s => s.status === 'Available');
    
    if(available.length === 0) {
        select.innerHTML = '<option disabled selected>NO TERMINALS AVAILABLE</option>';
    } else {
        available.forEach(s => {
            select.innerHTML += `<option value="${s.id}">${s.station_name}</option>`;
        });
    }
    
    document.getElementById('assignModal').classList.remove('hidden');
}
window.openAssignModal = openAssignModal;

async function submitAssign() {
    const waitlistId = document.getElementById('assign-id').value;
    const stationId = document.getElementById('assign-station-select').value;
    
    if(!stationId) return;

    // Get name text and strip prefix
    const nameText = document.getElementById('assign-name').textContent;
    const name = nameText.replace('CUSTOMER: ', '');
    
    const res = await fetch('../api/stations.php?action=start', {
        method: 'POST',
        body: JSON.stringify({ station_id: stationId, customer_name: name })
    });
    const json = await res.json();
    
    if(json.success) {
        // Mark fulfilled
        await fetch('../api/waitlist.php?action=fulfill', {
            method: 'POST',
            body: JSON.stringify({ id: waitlistId })
        });
        
        document.getElementById('assignModal').classList.add('hidden');
        window.location.href = 'dashboard.php'; 
    } else {
        alert(json.message);
    }
}
window.submitAssign = submitAssign;
