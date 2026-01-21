<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Dashboard</title>
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
        <header class="py-4 px-8 flex justify-between items-center bg-slate-900/50 backdrop-blur-sm border-b border-cyan-500/10 z-10 shrink-0">
            <div class="flex items-center gap-4">
                <button id="mobile-menu-btn" class="lg:hidden text-cyan-500 hover:text-white transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-widest uppercase font-['Orbitron'] neon-text">
                        Dashboard
                    </h1>
                    <p class="text-xs text-cyan-500/70 font-mono mt-1">SYSTEM STATUS: ONLINE</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-xs font-mono px-3 py-1 bg-cyan-900/20 rounded border border-cyan-500/30 text-cyan-400 shadow-[0_0_10px_rgba(6,182,212,0.1)]">
                    <i class="far fa-clock mr-2"></i><span id="server-clock">--:--:--</span>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="flex-1 flex flex-col p-4 lg:p-6 min-h-0 overflow-y-auto lg:overflow-hidden" id="app">
            
            <!-- Stats Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6 shrink-0">
                <!-- Revenue -->
                <div class="glass-panel p-4 flex flex-col justify-between h-24 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-20 h-20 bg-cyan-500/10 rounded-full blur-xl group-hover:bg-cyan-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <p class="text-cyan-500 text-[10px] uppercase font-bold tracking-widest font-mono">Total Revenue</p>
                        <i class="fas fa-coins text-cyan-400 opacity-80 text-sm"></i>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold text-white tracking-widest font-mono" id="stat-revenue">--</h3>
                    </div>
                </div>

                <!-- Active -->
                <div class="glass-panel p-4 flex flex-col justify-between h-24 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <p class="text-emerald-500 text-[10px] uppercase font-bold tracking-widest font-mono">Occupied</p>
                        <i class="fas fa-network-wired text-emerald-400 opacity-80 text-sm"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-widest font-mono" id="stat-occupied">--</h3>
                </div>

                <!-- Available -->
                <div class="glass-panel p-4 flex flex-col justify-between h-24 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-20 h-20 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <p class="text-blue-500 text-[10px] uppercase font-bold tracking-widest font-mono">Available</p>
                        <i class="fas fa-desktop text-blue-400 opacity-80 text-sm"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-widest font-mono" id="stat-available">--</h3>
                </div>

                <!-- Maintenance -->
                 <div class="glass-panel p-4 flex flex-col justify-between h-24 relative overflow-hidden group">
                     <div class="absolute -right-6 -top-6 w-20 h-20 bg-red-500/10 rounded-full blur-xl group-hover:bg-red-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <p class="text-red-500 text-[10px] uppercase font-bold tracking-widest font-mono">Maintenance</p>
                        <i class="fas fa-tools text-red-400 opacity-80 text-sm"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-widest font-mono" id="stat-maintenance">--</h3>
                </div>

                 <!-- Waitlist -->
                 <div class="glass-panel p-4 flex flex-col justify-between h-24 relative overflow-hidden group">
                     <div class="absolute -right-6 -top-6 w-20 h-20 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition-all"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <p class="text-amber-500 text-[10px] uppercase font-bold tracking-widest font-mono">Waitlist</p>
                        <i class="fas fa-user-clock text-amber-400 opacity-80 text-sm"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white tracking-widest font-mono" id="stat-waiting">--</h3>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 flex-1 min-h-0">
                
                <!-- Live Activity Feed -->
                <div class="lg:col-span-2 glass-panel p-6 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent"></div>
                    <h3 class="text-lg font-bold text-white mb-6 uppercase font-mono tracking-widest flex items-center shrink-0">
                        <i class="fas fa-stream mr-3 text-cyan-400"></i> Recent Transactions
                    </h3>
                    <div id="feed-container" class="pr-2 flex-1 pb-4 min-h-0 flex flex-col justify-between overflow-hidden">
                        <div class="animate-pulse flex items-center space-x-4 opacity-50">
                            <div class="h-8 w-8 bg-cyan-900/30 rounded"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-2 bg-cyan-900/30 rounded w-1/4"></div>
                                <div class="h-2 bg-slate-800 rounded w-1/2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard -->
                <div class="glass-panel p-6 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-1 h-full bg-gradient-to-b from-transparent via-purple-500/20 to-transparent"></div>
                    <h3 class="text-lg font-bold text-white mb-6 uppercase font-mono tracking-widest flex items-center shrink-0">
                        <i class="fas fa-trophy mr-3 text-amber-400"></i> Elite Users
                    </h3>
                    <div id="leaderboard-container" class="flex flex-col justify-between h-full pr-2">
                         <div class="text-center text-gray-500 text-xs font-mono animate-pulse">Scanning database...</div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
