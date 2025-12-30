<!-- includes/sidebar.php -->
<aside class="w-64 bg-[#0B1120] border-r border-white/5 flex flex-col h-full relative z-20 shadow-2xl">
    
    <div class="px-6 py-8 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow-lg shadow-indigo-500/30">
            <i class="fas fa-cube"></i>
        </div>
        <div>
            <h1 class="font-bold text-xl tracking-wide text-white font-['Orbitron']">
                NEXUS
            </h1>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-1">
        <?php 
        $current = basename($_SERVER['PHP_SELF']); 
        // Active: Subtle Indigo tint + left accent
        $activeClass = "bg-indigo-500/10 text-indigo-400 border-l-2 border-indigo-500";
        // Inactive: Transparent + Hover
        $inactiveClass = "text-slate-400 hover:text-slate-200 hover:bg-white/5 border-l-2 border-transparent transition-all duration-200";
        ?>
        
        <?php 
        $role = $_SESSION['role'] ?? 'Staff';
        $dashLink = 'dashboard.php';
        ?>
        
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-500">Menu</div>

        <a href="<?php echo $dashLink; ?>" class="flex items-center py-2.5 px-3 rounded-r-lg group text-sm font-medium tracking-wide <?php echo $current=='dashboard.php'?$activeClass:$inactiveClass; ?>">
            <i class="fas fa-chart-pie w-6 <?php echo $current=='dashboard.php'?'text-indigo-400':'text-slate-500 group-hover:text-slate-300'; ?>"></i> 
            <span class="ml-2">Dashboard</span>
        </a>

        <a href="computers.php" class="flex items-center py-2.5 px-3 rounded-r-lg group text-sm font-medium tracking-wide <?php echo $current=='computers.php'?$activeClass:$inactiveClass; ?>">
            <i class="fas fa-desktop w-6 <?php echo $current=='computers.php'?'text-indigo-400':'text-slate-500 group-hover:text-slate-300'; ?>"></i> 
            <span class="ml-2">Computers</span>
        </a>

        <a href="waitlist.php" class="flex items-center py-2.5 px-3 rounded-r-lg group text-sm font-medium tracking-wide <?php echo $current=='waitlist.php'?$activeClass:$inactiveClass; ?>">
            <i class="fas fa-list-ol w-6 <?php echo $current=='waitlist.php'?'text-indigo-400':'text-slate-500 group-hover:text-slate-300'; ?>"></i> 
            <span class="ml-2">Waitlist</span>
        </a>
        
        <a href="transactions.php" class="flex items-center py-2.5 px-3 rounded-r-lg group text-sm font-medium tracking-wide <?php echo $current=='transactions.php'?$activeClass:$inactiveClass; ?>">
            <i class="fas fa-history w-6 <?php echo $current=='transactions.php'?'text-indigo-400':'text-slate-500 group-hover:text-slate-300'; ?>"></i> 
            <span class="ml-2">Transactions</span>
        </a>
    </nav>

    <div class="p-4 border-t border-white/5 m-4">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-bold border border-white/10 text-xs">
                <?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'U'; ?>
            </div>
            <div class="overflow-hidden">
                <p class="text-xs font-bold text-slate-200 truncate"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></p>
                <p class="text-[10px] text-slate-500 uppercase tracking-wide">
                    <?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'Staff'; ?>
                </p>
            </div>
        </div>
        <a href="../api/auth.php?action=logout" class="flex items-center justify-center gap-2 w-full py-2 bg-slate-800 hover:bg-red-500/10 hover:text-red-400 text-slate-400 border border-white/5 hover:border-red-500/20 rounded-lg transition-all duration-200 text-xs font-bold tracking-wide">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>
