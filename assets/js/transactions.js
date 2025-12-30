let currentPage = 1;
let totalPages = 1;
const localDate = new Date();
const offset = localDate.getTimezoneOffset();
const localDateString = new Date(localDate.getTime() - (offset*60*1000)).toISOString().split('T')[0];
let currentDateFilter = localDateString; // Default to Local Today
let currentSearch = '';
let debounceTimer;

document.addEventListener('DOMContentLoaded', () => {
    // 1. Set Date Input to Today
    const dateInput = document.getElementById('date-filter');
    if(dateInput) {
        dateInput.value = currentDateFilter;
        dateInput.addEventListener('change', (e) => {
            currentDateFilter = e.target.value;
            currentPage = 1; // Reset to page 1
            loadTransactions();
        });
    }

    // 2. Show All Button
    document.getElementById('btn-show-all').addEventListener('click', () => {
        currentDateFilter = 'all';
        if(dateInput) dateInput.value = '';
        currentPage = 1;
        loadTransactions();
    });

    // 3. Search
    const searchInput = document.getElementById('search-input');
    if(searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                currentSearch = e.target.value;
                currentPage = 1;
                loadTransactions();
            }, 300);
        });
    }

    // 4. Pagination Buttons
    document.getElementById('btn-prev').addEventListener('click', () => {
        if(currentPage > 1) {
            currentPage--;
            loadTransactions();
        }
    });

    document.getElementById('btn-next').addEventListener('click', () => {
        if(currentPage < totalPages) {
            currentPage++;
            loadTransactions();
        }
    });

    // Initial Load
    loadTransactions();
});

async function loadTransactions() {
    const list = document.getElementById('transaction-list');
    list.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-gray-500 font-mono animate-pulse">Scanning Database...</td></tr>';
    
    try {
        let url = `../api/transactions.php?page=${currentPage}&limit=10`;
        
        if (currentDateFilter !== 'all') {
            url += `&date=${currentDateFilter}`;
        }
        
        if (currentSearch) {
            url += `&q=${encodeURIComponent(currentSearch)}`;
        }
        
        const res = await fetch(url);
        const json = await res.json();
        
        if (json.success) {
            renderList(json.data);
            updatePagination(json.pagination);
            calculateDailyRevenue(json.data); 
        } else {
            list.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400">Error: ${json.message}</td></tr>`;
        }
    } catch (e) {
        console.error(e);
        list.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-red-400">Connection Failed</td></tr>';
    }
}

function renderList(transactions) {
    const list = document.getElementById('transaction-list');
    list.innerHTML = '';
    
    if (transactions.length === 0) {
        list.innerHTML = '<tr><td colspan="4" class="p-8 text-center text-gray-500 font-mono">No records found for this date.</td></tr>';
        return;
    }

    transactions.forEach(t => {
        const date = new Date(t.payment_time);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        
        // Calculate minutes duration
        const start = new Date(t.start_time);
        const end = new Date(t.end_time);
        const diffMs = end - start;
        const durationMinutes = Math.round(diffMs / 60000);
        
        const hrs = Math.floor(durationMinutes / 60);
        const mins = durationMinutes % 60;
        const durationStr = (hrs > 0 ? `${hrs}h ` : '') + `${mins}m`;

        const html = `
            <tr class="hover:bg-slate-800/30 transition group">
                <td class="p-4">
                     <div class="font-bold text-white font-mono">${t.customer_name}</div>
                     <div class="text-xs text-gray-500 uppercase tracking-wider text-cyan-400/70 group-hover:text-cyan-400 transition">${t.computer_name || 'Terminal'}</div>
                </td>
                <td class="p-4">
                    <span class="text-emerald-400 font-bold font-mono">₱${parseFloat(t.amount).toFixed(2)}</span>
                </td>
                <td class="p-4 text-gray-400 font-mono text-xs">
                    ${formattedDate}
                </td>
                <td class="p-4 text-gray-300 font-mono text-xs">
                    ${durationStr}
                </td>
            </tr>
        `;
        list.insertAdjacentHTML('beforeend', html);
    });
}

function updatePagination(meta) {
    totalPages = meta.total_pages;
    document.getElementById('page-indicator').textContent = meta.current_page;
    document.getElementById('total-pages').textContent = meta.total_pages;
    
    document.getElementById('btn-prev').disabled = (meta.current_page <= 1);
    document.getElementById('btn-next').disabled = (meta.current_page >= meta.total_pages);
    
    // Quick Fade Control
    const nextBtn = document.getElementById('btn-next');
    if(meta.current_page >= meta.total_pages) {
        nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function calculateDailyRevenue(transactions) {
    const total = transactions.reduce((acc, curr) => acc + parseFloat(curr.amount), 0);
    const el = document.getElementById('daily-revenue');
    if(el) el.textContent = '₱' + total.toFixed(2) + (totalPages > 1 ? '+' : '');
}
