<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Contact Us</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        body { margin: 0; overflow-x: hidden; background-color: #0f172a; font-family: 'Roboto Mono', monospace; }
        #canvas-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
        .glass-panel { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-nav { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .input-cyber { background: rgba(0,0,0,0.3); border: 1px solid rgba(6,182,212,0.3); color: white; transition: all 0.3s; }
        .input-cyber:focus { border-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.2); outline: none; }
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
                <a href="about.php" class="hover:text-cyan-400 transition-colors">About</a>
                <a href="contact.php" class="text-cyan-400">Contact</a>
            </div>
            <a href="login.php" class="px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold uppercase tracking-widest rounded hover:shadow-[0_0_20px_rgba(6,182,212,0.6)]">Login</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-40 pb-20 px-6 min-h-screen flex items-center justify-center">
        <!-- Contact Info Only (Centered) -->
        <div class="max-w-2xl w-full bg-slate-900/50 p-10 rounded-3xl border border-cyan-500/20 backdrop-blur-md shadow-[0_0_50px_rgba(6,182,212,0.1)]">
            
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-white font-['Orbitron'] mb-6 tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">CONTACT US</h1>
                <p class="text-gray-400 text-lg">Have a question or want to reserve a computer? Reach out to us directly.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Location -->
                <div class="flex flex-col items-center text-center p-4 hover:bg-white/5 rounded-xl transition-colors group">
                    <div class="w-14 h-14 rounded-full bg-cyan-900/30 flex items-center justify-center text-cyan-400 border border-cyan-500/30 mb-4 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                        <i class="fas fa-map-marker-alt text-xl"></i>
                    </div>
                    <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest mb-1">Location</div>
                    <div class="text-white font-medium">Baliwasan, Zamboanga City</div>
                </div>

                <!-- Email -->
                <div class="flex flex-col items-center text-center p-4 hover:bg-white/5 rounded-xl transition-colors group">
                    <div class="w-14 h-14 rounded-full bg-purple-900/30 flex items-center justify-center text-purple-400 border border-purple-500/30 mb-4 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest mb-1">Email Support</div>
                    <div class="text-white font-medium">ace@support.com</div>
                </div>

                <!-- Phone -->
                <div class="flex flex-col items-center text-center p-4 hover:bg-white/5 rounded-xl transition-colors group">
                    <div class="w-14 h-14 rounded-full bg-emerald-900/30 flex items-center justify-center text-emerald-400 border border-emerald-500/30 mb-4 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                        <i class="fas fa-phone text-xl"></i>
                    </div>
                    <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest mb-1">Phone</div>
                    <div class="text-white font-medium">+63 912 345 6789</div>
                </div>
            </div>

        </div>
    </div>

    <!-- Background Logic -->
    <script>
        const scene = new THREE.Scene();
        scene.fog = new THREE.FogExp2(0x0f172a, 0.002);
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-bg').appendChild(renderer.domElement);

        // Particles
        const partGeo = new THREE.BufferGeometry();
        const partCount = 400;
        const pos = new Float32Array(partCount * 3);
        for(let i=0; i<partCount*3; i++) pos[i] = (Math.random()-0.5)*80;
        partGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
        const partMat = new THREE.PointsMaterial({color: 0x22d3ee, size: 0.15, transparent: true, opacity: 0.6});
        scene.add(new THREE.Points(partGeo, partMat));

        camera.position.z = 30;

        let mouseX = 0, mouseY = 0;
        document.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX - window.innerWidth/2) * 0.001;
            mouseY = (e.clientY - window.innerHeight/2) * 0.001;
        });

        function animate() {
            requestAnimationFrame(animate);
            scene.rotation.y += 0.001;
            scene.rotation.x += (mouseY - scene.rotation.x) * 0.05;
            scene.rotation.y += (mouseX - scene.rotation.y) * 0.05;
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
