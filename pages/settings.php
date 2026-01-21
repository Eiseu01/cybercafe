<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Settings</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body class="h-screen flex overflow-hidden font-sans text-gray-200">

    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
        
        <!-- Header -->
        <header class="py-4 px-8 flex justify-between items-center bg-slate-900/50 backdrop-blur-sm border-b border-cyan-500/10 z-10">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-widest uppercase font-['Orbitron'] neon-text">
                    System Configuration
                </h1>
                <p class="text-xs text-cyan-500/70 font-mono mt-1">ADMIN CONTROL PANEL</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-xs font-mono px-3 py-1 bg-cyan-900/20 rounded border border-cyan-500/30 text-cyan-400 shadow-[0_0_10px_rgba(6,182,212,0.1)]">
                    <i class="far fa-clock mr-2"></i><span id="server-clock">--:--:--</span>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="flex-1 p-6 overflow-y-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                
                <!-- Billing Rates -->
                <div class="glass-panel p-8 border border-white/10 rounded-xl relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-cyan-500/5 rounded-full blur-2xl pointer-events-none"></div>
                    <h2 class="text-xl text-cyan-400 mb-6 font-mono font-bold flex items-center">
                        <i class="fas fa-coins mr-3"></i>BILLING RATES
                    </h2>
                    
                    <form id="updateRateForm" class="relative z-10">
                        <label class="block text-gray-400 text-xs font-bold tracking-wider mb-2 uppercase">Global Hourly Rate (PHP)</label>
                        <div class="flex gap-4">
                            <input type="number" step="0.01" name="rate" id="global-rate-input"
                                class="bg-slate-900/80 border border-slate-600 text-white p-3 rounded flex-1 focus:border-cyan-500 focus:outline-none focus:shadow-[0_0_10px_rgba(6,182,212,0.3)] transition font-mono text-lg" 
                                placeholder="20.00" required>
                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white px-6 py-2 rounded text-xs font-bold tracking-widest transition shadow-[0_0_15px_rgba(8,145,178,0.4)]">
                                UPDATE
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2 font-mono">* Updates the standard rate reference.</p>
                    </form>
                </div>

                <!-- Computer Management -->
                <div class="glass-panel p-8 border border-white/10 rounded-xl relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-purple-500/5 rounded-full blur-2xl pointer-events-none"></div>
                    <h2 class="text-xl text-purple-400 mb-6 font-mono font-bold flex items-center">
                        <i class="fas fa-network-wired mr-3"></i>COMPUTER MANAGEMENT
                    </h2>
                    
                    <!-- Add Station -->
                    <form id="addStationForm" class="flex gap-2 mb-6 relative z-10">
                        <input type="text" id="new-station-name" placeholder="Enter Computer Name (e.g. PC-11)" 
                            class="bg-slate-900/80 border border-slate-600 text-white p-3 rounded flex-1 focus:border-purple-500 focus:outline-none font-mono text-sm" required>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2 rounded text-xs font-bold tracking-widest transition shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                            ADD
                        </button>
                    </form>

                    <!-- Station List -->
                    <div class="relative z-10">
                        <h3 class="text-xs text-gray-400 font-bold mb-3 uppercase tracking-wider">Active Computers</h3>
                        <div id="settings-station-list" class="space-y-2 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                            <div class="text-center text-gray-500 text-xs py-4">Loading stations...</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>
    
    <!-- Modals (Reused for consistent behavior if needed, or simple alerts) -->
    <!-- We will use simple alerts for settings to keep it lightweight as requested -->

    <script src="../assets/js/settings.js"></script>
</body>
</html>
