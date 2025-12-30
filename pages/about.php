<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - About Us</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        body { margin: 0; overflow-x: hidden; background-color: #0f172a; font-family: 'Roboto Mono', monospace; }
        #canvas-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
        .glass-panel { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-nav { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="text-gray-200">

    <div id="canvas-bg"></div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav" id="navbar">
        <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
            <a href="../index.php" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-cyan-500/10 rounded border border-cyan-500/30 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all">
                    <i class="fas fa-cube text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white font-['Orbitron'] tracking-[0.2em]">NEXUS</h1>
            </a>
            <div class="hidden md:flex items-center space-x-12 text-sm font-bold uppercase tracking-widest text-gray-400">
                <a href="../index.php" class="hover:text-cyan-400 transition-colors">Home</a>
                <a href="about.php" class="text-cyan-400">About</a>
                <a href="contact.php" class="hover:text-cyan-400 transition-colors">Contact</a>
            </div>
            <a href="login.php" class="px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold uppercase tracking-widest rounded hover:shadow-[0_0_20px_rgba(6,182,212,0.6)]">Login</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-40 pb-20 px-6 min-h-screen">
        <div class="max-w-4xl mx-auto">
            
            <div class="text-center mb-16">
                <h1 class="text-5xl font-bold text-white font-['Orbitron'] mb-6 tracking-wide">ABOUT US</h1>
                <p class="text-cyan-400 font-mono tracking-widest uppercase text-sm">We provide the best gaming experience.</p>
            </div>

            <div class="glass-panel p-10 rounded-2xl mb-10 shadow-lg">
                <h2 class="text-2xl font-bold text-white font-['Orbitron'] mb-4 flex items-center">
                    <i class="fas fa-bullseye text-cyan-500 mr-4"></i> Our Mission
                </h2>
                <p class="text-gray-400 leading-loose">
                    At Nexus Internet Cafe, our goal is simple: <strong>To be the best place for gamers.</strong><br>
                    We use high-end computers, fast internet, and comfortable chairs so you can play your favorite games without lag. Whether you are a pro player or just having fun with friends, we are here for you.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="glass-panel p-8 rounded-2xl">
                    <h3 class="text-xl font-bold text-white font-['Orbitron'] mb-4 text-purple-400">High Technology</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        We use the latest RTX Graphics Cards and 240Hz Monitors. Every click is instant. No delays. Just pure speed.
                    </p>
                </div>
                <div class="glass-panel p-8 rounded-2xl">
                    <h3 class="text-xl font-bold text-white font-['Orbitron'] mb-4 text-emerald-400">Community</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Nexus is more than a shop. It is a home for gamers. Join our tournaments and meet new friends who love gaming too.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-white/5 bg-slate-900/50 pt-10 pb-10 text-center">
        <p class="text-gray-600 text-xs font-mono uppercase tracking-widest">Nexus Internet Cafe • 2025</p>
    </footer>

    <!-- Background Logic -->
    <script>
        const scene = new THREE.Scene();
        scene.fog = new THREE.FogExp2(0x0f172a, 0.002);
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-bg').appendChild(renderer.domElement);

        // Grid Floor
        const gridHelper = new THREE.GridHelper(100, 50, 0x06b6d4, 0x1e293b);
        scene.add(gridHelper);

        // Stars
        const starGeo = new THREE.BufferGeometry();
        const starCount = 500;
        const pos = new Float32Array(starCount * 3);
        for(let i=0; i<starCount*3; i++) pos[i] = (Math.random()-0.5)*100;
        starGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
        const starMat = new THREE.PointsMaterial({color: 0xffffff, size: 0.1, transparent: true, opacity: 0.5});
        scene.add(new THREE.Points(starGeo, starMat));

        camera.position.set(0, 10, 20);
        camera.lookAt(0, 0, 0);

        function animate() {
            requestAnimationFrame(animate);
            gridHelper.rotation.y += 0.001;
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
    </script>
</body>
</html>
