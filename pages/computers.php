<?php
require_once '../includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Terminals</title>
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
        <header class="py-4 px-8 flex justify-between items-center bg-slate-900/50 backdrop-blur-sm border-b border-cyan-500/10 z-10 sticky top-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-300 tracking-widest uppercase font-['Orbitron'] neon-text">
                    Computer List
                </h1>
                <p class="text-xs text-cyan-500/70 font-mono mt-1">COMPUTER MANAGEMENT</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                <button onclick="openAddComputerModal()" class="btn btn-primary shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                    <i class="fas fa-plus"></i> <span>Add Computer</span>
                </button>
                <?php endif; ?>
                
                <div class="text-xs font-mono px-3 py-1 bg-cyan-900/20 rounded border border-cyan-500/30 text-cyan-400">
                    <span id="server-clock">--:--</span>
                </div>
            </div>
        </header>

        <script>
            const USER_ROLE = '<?php echo $_SESSION['role'] ?? 'Staff'; ?>';
        </script>

        <!-- Content Body -->
        <div class="flex-1 overflow-auto p-8" id="app">
            
            <!-- Stations Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6" id="stations-grid">
                <!-- Stations will be injected here via JS -->
                <div id="initial-loader" class="col-span-full flex flex-col items-center justify-center py-20 text-cyan-500/30">
                    <i class="fas fa-circle-notch fa-spin text-4xl mb-4"></i>
                    <p class="font-mono tracking-widest uppercase">Loading Computers...</p>
                </div>
            </div>

        </div>
    </main>

    <!-- Modals (Cyber Style) -->

    <!-- Start Session Modal -->
    <div id="startModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel w-96 p-8 relative border-cyan-500/30 shadow-[0_0_30px_rgba(6,182,212,0.15)]">
            <h3 class="text-xl font-bold mb-2 text-white font-['Orbitron'] tracking-wider">Start Session</h3>
            <p class="text-xs text-cyan-400 font-mono mb-8" id="modal-station-name">PC Name: --</p>
            
            <form id="startForm">
                <input type="hidden" id="start-station-id">
                <label class="block mb-2 text-[10px] text-cyan-500 font-bold uppercase tracking-widest font-mono">Customer Name</label>
                <input type="text" id="start-customer" class="input-field mb-8" required placeholder="Enter Name">
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('startModal')" class="btn btn-secondary text-xs">Cancel</button>
                    <button type="submit" class="btn btn-primary text-xs">
                        Start
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stop Session Confirmation Modal -->
    <div id="stopConfirmModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel w-96 p-8 relative border-red-500/30 shadow-[0_0_30px_rgba(239,68,68,0.15)]">
             <div class="w-12 h-12 rounded-full bg-red-900/20 flex items-center justify-center text-red-500 mb-4 text-xl border border-red-500/50 shadow-[0_0_10px_rgba(239,68,68,0.3)] mx-auto">
                 <i class="fas fa-power-off"></i>
             </div>
             <h3 class="text-xl font-bold mb-2 text-white text-center font-['Orbitron'] tracking-wider">End Session?</h3>
            <p class="text-xs text-center text-gray-400 font-mono mb-8">
                Are you sure you want to end the session for <span id="stop-customer-name" class="font-bold text-white"></span>? Use "End Session" to stop the timer and calculate the bill.
            </p>
            <input type="hidden" id="stop-session-id">
            
            <div class="flex justify-center space-x-3">
                <button onclick="closeModal('stopConfirmModal')" class="btn btn-secondary text-xs">Cancel</button>
                <button onclick="processStopSession()" class="btn btn-danger text-xs">
                    End Session
                </button>
            </div>
        </div>
    </div>

    <!-- Payment/End Session Modal -->
    <div id="endModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel w-96 p-8 text-center relative border-emerald-500/30 shadow-[0_0_30px_rgba(16,185,129,0.15)]">
            <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-900/20 text-emerald-400 border border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                <i class="fas fa-check text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold mb-1 text-white font-['Orbitron'] tracking-wider">Session Ended</h3>
            <p class="text-xs text-emerald-400/70 font-mono mb-8">Payment Logged</p>
            
            <div class="py-6 bg-slate-800/50 rounded border border-white/5 mb-8">
                <div class="text-[10px] text-gray-500 uppercase tracking-widest mb-2 font-mono">Total Due</div>
                <div class="text-4xl font-bold text-white tracking-widest font-mono" id="end-fee">$0.00</div>
            </div>
            
            <button onclick="closeModal('endModal'); window.location.reload();" class="btn btn-primary w-full justify-center">
                Close
            </button>
        </div>
    </div>
    
    <!-- Add Station Modal (Admin Only) -->
    <div id="addStationModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel w-96 p-8 relative">
            <h3 class="text-xl font-bold mb-6 text-white font-['Orbitron'] tracking-wider">Add Computer</h3>
            <form id="addStationForm">
                <label class="block mb-2 text-[10px] text-cyan-500 uppercase font-bold tracking-widest font-mono">Computer Name</label>
                <input type="text" id="add-station-name" class="input-field mb-8" required placeholder="e.g. PC-11">
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('addStationModal')" class="btn btn-secondary text-xs">Cancel</button>
                    <button type="submit" class="btn btn-primary text-xs">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Station Modal (Admin Only) -->
    <div id="editStationModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel w-96 p-8 relative">
            <h3 class="text-xl font-bold mb-6 text-white font-['Orbitron'] tracking-wider">Edit Computer</h3>
            <form id="editStationForm">
                <input type="hidden" id="edit-station-id">
                
                <label class="block mb-2 text-[10px] text-cyan-500 uppercase font-bold tracking-widest font-mono">Computer Name</label>
                <input type="text" id="edit-station-name" class="input-field mb-6" required>
                
                <label class="block mb-2 text-[10px] text-cyan-500 uppercase font-bold tracking-widest font-mono">Rate (PHP/hr)</label>
                <input type="number" step="0.25" id="edit-station-rate" class="input-field mb-6" placeholder="Default Rate">

                <div id="edit-status-container">
                    <label class="block mb-2 text-[10px] text-cyan-500 uppercase font-bold tracking-widest font-mono">Current Status</label>
                    <div class="relative">
                         <select id="edit-station-status" class="input-field appearance-none cursor-pointer uppercase">
                            <option value="Available">Available</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                        <i class="fas fa-caret-down absolute right-4 top-4 text-cyan-500 pointer-events-none text-xs"></i>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-8">
                    <button type="button" onclick="closeModal('editStationModal')" class="btn btn-secondary text-xs">Cancel</button>
                    <button type="submit" class="btn btn-primary text-xs">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Station Modal (Admin Only) -->
    <div id="deleteStationModal" class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div class="glass-panel w-96 p-8 relative border-red-500/30">
            <h3 class="text-xl font-bold mb-4 text-white font-['Orbitron'] tracking-wider text-red-400">Delete Computer?</h3>
            <p class="text-xs text-gray-400 font-mono mb-8">
                Are you sure you want to delete <span id="delete-station-name" class="font-bold text-white"></span>? This cannot be undone.
            </p>
            <input type="hidden" id="delete-station-id">
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeModal('deleteStationModal')" class="btn btn-secondary text-xs">Cancel</button>
                <button onclick="processDeleteComputer()" class="btn btn-danger text-xs">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
