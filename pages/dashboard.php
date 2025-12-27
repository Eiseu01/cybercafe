<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Cafe - Dashboard</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-900 h-screen flex overflow-hidden font-sans text-gray-100">

    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Background Grid -->
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none" 
             style="background-image: linear-gradient(#334155 1px, transparent 1px), linear-gradient(to right, #334155 1px, transparent 1px); background-size: 40px 40px;">
        </div>
        
        <!-- Header -->
        <header class="glass-panel z-10 py-4 px-6 flex justify-between items-center bg-slate-900/80">
            <h1 class="text-2xl font-bold uppercase tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-500">
                Station Command
            </h1>
            <div class="flex items-center space-x-4">
                <div class="text-xs text-cyan-500 font-mono border border-cyan-500/30 px-3 py-1 rounded bg-cyan-900/20">
                    SERVER_TIME: <span id="server-clock" class="text-white font-bold">Loading...</span>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="flex-1 overflow-auto p-6 relative z-10" id="app">
            
            <!-- Quick Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Available -->
                <div class="glass-panel p-5 rounded-xl border-l-4 border-emerald-500 relative group overflow-hidden">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-125 transition-transform">
                        <i class="fas fa-check-circle text-6xl text-emerald-500"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase tracking-wider mb-1">Available Rigs</div>
                    <div class="text-3xl font-bold text-white font-mono" id="stat-available">--</div>
                    <div class="w-full bg-slate-700 h-1 mt-4 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full w-3/4 shadow-[0_0_10px_#10b981]"></div>
                    </div>
                </div>

                <!-- Occupied -->
                <div class="glass-panel p-5 rounded-xl border-l-4 border-red-500 relative group overflow-hidden">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-125 transition-transform">
                        <i class="fas fa-headset text-6xl text-red-500"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase tracking-wider mb-1">Active Sessions</div>
                    <div class="text-3xl font-bold text-white font-mono" id="stat-occupied">--</div>
                    <div class="w-full bg-slate-700 h-1 mt-4 rounded-full overflow-hidden">
                        <div class="bg-red-500 h-full w-1/2 shadow-[0_0_10px_#ef4444]"></div>
                    </div>
                </div>
                
                <!-- Waiting -->
                <div class="glass-panel p-5 rounded-xl border-l-4 border-amber-500 relative group overflow-hidden">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-125 transition-transform">
                        <i class="fas fa-clock text-6xl text-amber-500"></i>
                    </div>
                    <div class="text-gray-400 text-xs uppercase tracking-wider mb-1">In Queue</div>
                    <div class="text-3xl font-bold text-white font-mono" id="stat-waiting">--</div>
                    <div class="w-full bg-slate-700 h-1 mt-4 rounded-full overflow-hidden">
                        <div class="bg-amber-500 h-full w-1/4 shadow-[0_0_10px_#f59e0b]"></div>
                    </div>
                </div>
            </div>

            <!-- Stations Grid -->
            <h2 class="text-lg text-gray-400 mb-4 font-mono uppercase tracking-widest border-b border-gray-700 pb-2">
                <i class="fas fa-network-wired mr-2"></i>Terminal Grid
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6" id="stations-grid">
                <!-- Stations will be injected here via JS -->
                <div class="col-span-full text-center text-cyan-500 py-20 font-mono animate-pulse">
                    INITIALIZING TERMINALS...
                </div>
            </div>

        </div>
    </main>

    <!-- Start Session Modal -->
    <div id="startModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel rounded-2xl w-96 p-8 relative neon-border border-cyan-500/30">
            <h3 class="text-2xl font-bold mb-1 text-white uppercase tracking-wider font-mono">
                <span class="text-emerald-400">>></span> Initialize
            </h3>
            <p class="text-xs text-cyan-400 mb-6 font-mono" id="modal-station-name">TARGET: STATION_--</p>
            
            <form id="startForm">
                <input type="hidden" id="start-station-id">
                <label class="block mb-2 text-xs text-gray-400 uppercase tracking-widest">Customer Identity</label>
                <input type="text" id="start-customer" class="w-full bg-slate-900/50 border border-slate-600 rounded-lg px-4 py-3 mb-6 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-white placeholder-slate-600 outline-none font-mono" required placeholder="ENTER_NAME">
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('startModal')" class="px-4 py-2 text-gray-400 hover:text-white hover:bg-white/5 rounded transition uppercase text-xs font-bold tracking-wider">Abort</button>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded shadow-[0_0_15px_rgba(16,185,129,0.4)] transition uppercase text-xs font-bold tracking-wider">
                        Power On
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stop Session Confirmation Modal -->
    <div id="stopConfirmModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel rounded-2xl w-96 p-8 relative neon-border border-red-500/30">
             <h3 class="text-2xl font-bold mb-4 text-white uppercase tracking-wider font-mono">
                <span class="text-red-500">>></span> Terminate?
            </h3>
            <p class="text-sm text-gray-300 mb-8 border-l-2 border-red-500 pl-4">
                Confirm session end for <span id="stop-customer-name" class="font-bold text-white font-mono"></span>. This action is irreversible.
            </p>
            <input type="hidden" id="stop-session-id">
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeModal('stopConfirmModal')" class="px-4 py-2 text-gray-400 hover:text-white hover:bg-white/5 rounded transition uppercase text-xs font-bold tracking-wider">Cancel</button>
                <button onclick="processStopSession()" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded shadow-[0_0_15px_rgba(220,38,38,0.4)] transition uppercase text-xs font-bold tracking-wider">
                    Confirm Stop
                </button>
            </div>
        </div>
    </div>

    <!-- Payment/End Session Modal -->
    <div id="endModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel rounded-2xl w-96 p-8 text-center relative neon-border border-cyan-500/30">
            <div class="mb-4 inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-500/10 border border-emerald-500/50">
                <i class="fas fa-check text-4xl text-emerald-400 drop-shadow-[0_0_10px_rgba(52,211,153,0.8)]"></i>
            </div>
            
            <h3 class="text-xl font-bold mb-1 text-white uppercase tracking-widest font-mono">Session Complete</h3>
            <p class="text-xs text-cyan-500 mb-6 font-mono">TRANSACTION_LOGGED</p>
            
            <div class="py-4 bg-slate-900/50 rounded-lg border border-slate-700 mb-6">
                <div class="text-xs text-gray-400 uppercase tracking-widest mb-1">Total Fee</div>
                <div class="text-4xl font-bold text-white font-mono neon-text" id="end-fee">$0.00</div>
            </div>
            
            <button onclick="closeModal('endModal'); fetchStations();" class="w-full py-3 bg-cyan-600 hover:bg-cyan-500 text-white rounded shadow-[0_0_20px_rgba(8,145,178,0.4)] transition uppercase font-bold tracking-wider">
                Close Ticket
            </button>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
