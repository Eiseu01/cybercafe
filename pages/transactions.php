<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Cafe - Transactions</title>
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
                Transaction Logs
            </h1>
            <div class="glass-panel px-4 py-2 rounded-lg flex items-center space-x-3 border border-emerald-500/30">
                <span class="text-xs text-gray-400 uppercase tracking-widest">Daily Revenue</span>
                <span id="daily-revenue" class="text-xl font-bold text-emerald-400 font-mono">$0.00</span>
            </div>
        </header>

        <div class="flex-1 overflow-auto p-6 z-10">
            <div class="glass-panel rounded-xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-700 bg-slate-800/50 uppercase text-xs tracking-wider">
                            <th class="p-4 font-mono">#ID</th>
                            <th class="p-4">Session Info</th>
                            <th class="p-4">Amount</th>
                            <th class="p-4">Payment Time</th>
                            <th class="p-4">Method</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-rows" class="text-sm divide-y divide-gray-700">
                         <tr><td colspan="5" class="p-4 text-center text-gray-500">Retrieving Logs...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../assets/js/transactions.js"></script>
</body>
</html>
