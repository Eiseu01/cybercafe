<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Cafe - Waitlist</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-900 h-screen flex overflow-hidden font-sans text-gray-100">
    
    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Background Grid -->
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none" 
             style="background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(to right, #334155 1px, transparent 1px); background-size: 40px 40px;">
        </div>

        <header class="glass-panel z-10 py-4 px-6 flex justify-between items-center bg-slate-900/80">
            <h1 class="text-2xl font-bold uppercase tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                Waitlist Queue
            </h1>
            <button onclick="document.getElementById('addWaitlistModal').classList.remove('hidden')" class="btn-primary px-4 py-2 rounded uppercase text-xs tracking-wider">
                <i class="fas fa-plus mr-2"></i> Add Entry
            </button>
        </header>

        <div class="flex-1 overflow-auto p-6 z-10">
            <div class="glass-panel rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-700 bg-slate-800/50 uppercase text-xs tracking-wider">
                            <th class="p-4 font-mono">#ID</th>
                            <th class="p-4">Customer</th>
                            <th class="p-4">Queue Time</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="waitlist-rows" class="text-sm divide-y divide-gray-700">
                        <!-- Rows injected via JS -->
                         <tr><td colspan="5" class="p-4 text-center text-gray-500">Loading Queue...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Waitlist Modal -->
    <div id="addWaitlistModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel rounded-2xl w-96 p-8 relative neon-border border-cyan-500/30">
            <h3 class="text-xl font-bold mb-4 text-white uppercase tracking-wider font-mono">New Entry</h3>
            <form id="addWaitlistForm">
                <input type="text" id="waitlist-name" class="w-full bg-slate-900/50 border border-slate-600 rounded-lg px-4 py-3 mb-6 focus:ring-2 focus:ring-cyan-500 outline-none text-white placeholder-slate-600 font-mono" placeholder="CUSTOMER_NAME" required>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('addWaitlistModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white transition uppercase text-xs font-bold tracking-wider">Cancel</button>
                    <button type="submit" class="btn-primary px-6 py-2 rounded uppercase text-xs font-bold tracking-wider">Add to Queue</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel rounded-2xl w-96 p-8 relative neon-border border-purple-500/30">
            <h3 class="text-xl font-bold mb-1 text-white uppercase tracking-wider font-mono">Assign Station</h3>
            <p id="assign-name" class="text-xs text-purple-400 mb-6 font-mono">CUSTOMER: --</p>
            
            <form id="assignForm">
                <input type="hidden" id="assign-id">
                <label class="block mb-2 text-xs text-gray-400 uppercase tracking-widest">Select Terminal</label>
                <select id="assign-station-select" class="w-full bg-slate-900/50 border border-slate-600 rounded-lg px-4 py-3 mb-6 focus:ring-2 focus:ring-purple-500 outline-none text-white font-mono">
                    <!-- Options via JS -->
                </select>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('assignModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white transition uppercase text-xs font-bold tracking-wider">Cancel</button>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-2 rounded shadow-[0_0_15px_rgba(147,51,234,0.4)] transition uppercase text-xs font-bold tracking-wider">Assign</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/waitlist.js"></script>
</body>
</html>
