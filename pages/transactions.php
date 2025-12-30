<?php
// pages/transactions.php
require_once '../includes/auth_check.php';
require_once '../includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Nexus Control</title>
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="../assets/css/output.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts (Standardized & Preconnected) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/input.css">
</head>
<body class="bg-[#0f172a] text-slate-300 font-sans antialiased overflow-hidden selection:bg-indigo-500/30">

    <div class="flex h-screen w-full relative">
        
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

            <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full relative overflow-hidden bg-slate-950">
             
             <!-- Background Grid (Optional, subtle) -->
             <div class="absolute inset-0 z-0 opacity-10 pointer-events-none" 
                 style="background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(to right, #334155 1px, transparent 1px); background-size: 40px 40px;">
            </div>

            <!-- Header -->
            <header class="py-4 px-8 flex justify-between items-center bg-slate-900/50 backdrop-blur-sm border-b border-cyan-500/10 z-10 sticky top-0">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-widest uppercase font-['Orbitron'] neon-text">
                        Transaction History
                    </h1>
                    <p class="text-xs text-cyan-500/70 font-mono mt-1 uppercase tracking-wider">Financial Logs & Audit Trail</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Daily Revenue Badge -->
                    <div class="flex items-center gap-3 px-4 py-2 rounded border border-emerald-500/20 bg-emerald-900/10 shadow-[0_0_10px_rgba(16,185,129,0.1)]">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-emerald-500/80 tracking-widest font-mono">Daily Revenue</p>
                            <p class="text-lg font-bold text-emerald-400 leading-none font-mono" id="daily-revenue">...</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Toolbar & Controls -->
            <div class="px-8 py-6 z-10 flex flex-wrap gap-4 justify-between items-center">
                <!-- Search -->
                <div class="relative w-full max-w-md">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    <input type="text" id="search-input" placeholder="Search by customer or terminal..." 
                        class="w-full bg-slate-900 border border-slate-800 focus:border-indigo-500/50 rounded-lg pl-10 pr-4 py-2.5 text-sm text-slate-200 placeholder-slate-500 outline-none transition-all shadow-sm">
                </div>

                <!-- Date Filter -->
                 <div class="flex items-center gap-2 bg-slate-900 border border-slate-800 rounded-lg p-1 pr-3">
                    <input type="date" id="date-filter" class="bg-transparent text-slate-300 text-sm border-none outline-none pl-3 cursor-pointer">
                    <button id="btn-show-all" class="text-xs font-bold text-indigo-400 hover:text-white px-3 py-1.5 rounded hover:bg-white/5 transition uppercase tracking-wide">
                        Show All
                    </button>
                 </div>
            </div>

            <!-- Content Body -->
            <div class="flex-1 overflow-auto px-8 pb-8 relative z-10" id="app">
                <div class="bg-slate-900/50 border border-white/5 rounded-xl overflow-hidden shadow-xl backdrop-blur-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-900/80 border-b border-white/5 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                                    <th class="px-6 py-4 font-medium">Customer Information</th>
                                    <th class="px-6 py-4 font-medium">Earnings</th>
                                    <th class="px-6 py-4 font-medium">Timestamp</th>
                                    <th class="px-6 py-4 font-medium">Duration</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-list" class="divide-y divide-white/5 text-sm">
                                <tr>
                                    <td colspan="4" class="p-12 text-center text-slate-500 animate-pulse">
                                        <i class="fas fa-spinner fa-spin mb-2 text-indigo-500 text-lg"></i><br>
                                        Loading Data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Footer -->
                    <div class="border-t border-white/5 bg-slate-900/30 px-6 py-4 flex justify-between items-center">
                        <div class="text-xs text-slate-500 font-medium">
                            Showing Page <span id="page-indicator" class="text-slate-300 font-bold">1</span> of <span id="total-pages">1</span>
                        </div>
                        <div class="flex gap-2">
                            <button id="btn-prev" class="px-3 py-1.5 rounded-lg border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed transition text-xs font-medium">
                                <i class="fas fa-chevron-left mr-1"></i> Previous
                            </button>
                            <button id="btn-next" class="px-3 py-1.5 rounded-lg border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed transition text-xs font-medium">
                                Next <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="../assets/js/dashboard.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/transactions.js?v=<?php echo time(); ?>"></script>
</body>
</html>
