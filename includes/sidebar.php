<!-- includes/sidebar.php -->
<aside class="w-64 bg-slate-900/90 backdrop-blur-xl border-r border-white/10 text-white flex flex-col relative overflow-hidden">
    <!-- Glow Effect -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-500 to-purple-500"></div>
    <div class="absolute -top-20 -left-20 w-40 h-40 bg-purple-600/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-60 h-60 bg-cyan-600/10 rounded-full blur-3xl"></div>

    <div class="p-6 text-center z-10">
        <h1 class="font-bold text-3xl tracking-wider neon-text font-mono">
            <i class="fas fa-gamepad mr-2"></i>NEXUS
        </h1>
        <span class="text-xs text-gray-400 block font-normal tracking-widest mt-1 uppercase">Control Center</span>
    </div>

    <nav class="flex-1 px-4 space-y-3 z-10 mt-4">
        <?php 
        $current = basename($_SERVER['PHP_SELF']); 
        // Active: Glassy background + Neon Border + Glow text
        $activeClass = "bg-white/10 border-l-4 border-cyan-400 text-cyan-300 shadow-[0_0_15px_rgba(34,211,238,0.1)]";
        // Inactive: Transparent + Hover effect
        $inactiveClass = "text-gray-400 hover:text-white hover:bg-white/5 border-l-4 border-transparent transition-all duration-300";
        ?>
        
        <a href="dashboard.php" class="flex items-center py-3 px-4 rounded-r-lg group <?php echo $current=='dashboard.php'?$activeClass:$inactiveClass; ?>">
            <i class="fas fa-desktop w-6 text-center transition-transform group-hover:scale-110"></i> 
            <span class="ml-3 font-medium">Dashboard</span>
        </a>
        
        <a href="waitlist.php" class="flex items-center py-3 px-4 rounded-r-lg group <?php echo $current=='waitlist.php'?$activeClass:$inactiveClass; ?>">
            <i class="fas fa-list-ol w-6 text-center transition-transform group-hover:scale-110"></i> 
            <span class="ml-3 font-medium">Waitlist</span>
        </a>
        
        <a href="transactions.php" class="flex items-center py-3 px-4 rounded-r-lg group <?php echo $current=='transactions.php'?$activeClass:$inactiveClass; ?>">
            <i class="fas fa-history w-6 text-center transition-transform group-hover:scale-110"></i> 
            <span class="ml-3 font-medium">Transactions</span>
        </a>
    </nav>

    <div class="p-4 border-t border-white/10 z-10 bg-slate-900/50">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-500 to-purple-600 p-[2px]">
                <div class="w-full h-full rounded-full bg-slate-900 flex items-center justify-center">
                    <span class="font-bold text-cyan-400"><?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'U'; ?></span>
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-white"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Staff'; ?></p>
                <p class="text-xs text-green-400 flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-1 animate-pulse"></span> Online</p>
            </div>
        </div>
        <a href="../api/auth.php?action=logout" class="block text-center py-2 border border-red-500/50 text-red-400 rounded hover:bg-red-500 hover:text-white transition-all duration-300 text-sm uppercase tracking-wider">
            <i class="fas fa-power-off mr-2"></i> Logout
        </a>
    </div>
</aside>
