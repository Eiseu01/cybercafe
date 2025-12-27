// assets/js/transactions.js
document.addEventListener('DOMContentLoaded', () => {
    fetchTransactions();
});

async function fetchTransactions() {
    try {
        const res = await fetch('../api/transactions.php');
        const json = await res.json();
        
        if (json.success) {
            // Stats (Update correct ID based on PHP file: 'daily-revenue')
            if(document.getElementById('daily-revenue'))
                document.getElementById('daily-revenue').textContent = '$' + parseFloat(json.stats.today_revenue).toFixed(2);
            
            // Rows
            const rows = document.getElementById('transaction-rows'); // Matches id in transactions.php overwriten file
            if(!rows) return;
            
            rows.innerHTML = '';
            
            if(json.data.length === 0) {
                rows.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-gray-500 italic">No transactions found today.</td></tr>`;
                return;
            }

            json.data.forEach(t => {
                rows.innerHTML += `
                    <tr class="hover:bg-white/5 transition border-b border-gray-700/50">
                        <td class="p-4 font-mono text-cyan-500 text-xs">#LOG_${t.id}</td>
                        <td class="p-4 text-xs font-mono text-gray-400">${t.payment_time}</td>
                        <td class="p-4 font-bold text-white">${t.customer_name}</td>
                        <td class="p-4 text-gray-300 text-sm">${t.station_name}</td>
                        <td class="p-4 font-bold text-emerald-400 font-mono">$${parseFloat(t.amount).toFixed(2)}</td>
                    </tr>
                `;
            });
        }
    } catch (e) {
        console.error(e);
    }
}
