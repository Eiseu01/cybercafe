<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - System Access</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        body { margin: 0; overflow: hidden; background-color: #0f172a; font-family: 'Roboto Mono', monospace; }
        #canvas-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        }
        
        .glass-panel {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(6, 182, 212, 0.1);
            box-shadow: 0 0 60px rgba(0, 0, 0, 0.6);
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .input-field {
            background: rgba(8, 14, 28, 0.6);
            border: 1px solid rgba(6, 182, 212, 0.2);
            transition: all 0.3s ease;
            color: #fff;
        }
        .input-field:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.3);
            background: rgba(8, 14, 28, 0.8);
        }

        .glitch-text {
            position: relative;
            animation: glitch 3s infinite;
        }
        
        @keyframes glitch {
            0% { text-shadow: 0 0 0 #06b6d4; }
            92% { text-shadow: 0 0 0 #06b6d4; transform: translate(0); }
            94% { text-shadow: 2px 2px 0 #ef4444; transform: translate(2px, 2px); }
            96% { text-shadow: -2px -2px 0 #06b6d4; transform: translate(-2px, -2px); }
            98% { text-shadow: 0 0 0 #06b6d4; transform: translate(0); }
            100% { text-shadow: 0 0 0 #06b6d4; }
        }

        .btn-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.5);
            letter-spacing: 0.15em;
        }
        .btn-glow::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center relative">

    <!-- 3D Canvas -->
    <div id="canvas-container"></div>

    <!-- UI Overlay -->
    <div class="glass-panel p-8 rounded-2xl w-full max-w-md relative z-10 m-4 border-t border-l border-white/10 shadow-2xl backdrop-blur-xl">
        
        <!-- Header -->
        <div class="text-center mb-8">
            
            <!-- Nexus Logo -->
            <a href="../index.php" class="flex flex-col items-center justify-center mb-4 group cursor-pointer transition-opacity hover:opacity-90">
                <div class="w-14 h-14 bg-cyan-500/10 rounded-xl border border-cyan-500/30 flex items-center justify-center text-cyan-400 mb-3 shadow-[0_0_30px_rgba(6,182,212,0.2)] group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-cube text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-white font-['Orbitron'] tracking-[0.3em] group-hover:text-cyan-400 transition-colors">NEXUS</h2>
            </a>

            <div class="h-px w-24 bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent mx-auto mb-8"></div>

            <h1 class="text-3xl font-bold text-white font-['Orbitron'] tracking-widest mb-2 glitch-text uppercase">Login Page</h1>
        </div>

        <form id="loginForm" class="space-y-6">
            <div>
                <label class="block text-[10px] font-bold text-cyan-500 uppercase tracking-widest mb-2 font-mono ml-1">Username</label>
                <div class="relative group">
                    <input type="text" id="username" class="input-field w-full px-4 py-3.5 rounded-lg text-sm focus:outline-none" placeholder="Enter Username" required autocomplete="off">
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-cyan-500 uppercase tracking-widest mb-2 font-mono ml-1">Password</label>
                <div class="relative group">
                    <input type="password" id="password" class="input-field w-full px-4 py-3.5 rounded-lg text-sm focus:outline-none" placeholder="Enter Password" required>
                </div>
            </div>
            
            <div id="errorMsg" class="p-3 bg-red-500/10 border-l-2 border-red-500 text-red-400 text-xs font-mono hidden flex items-center animate-pulse">
                <i class="fas fa-exclamation-triangle mr-3"></i><span>Authentication Failed</span>
            </div>

            <button type="submit" class="btn-glow w-full py-4 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold rounded-lg shadow-lg uppercase tracking-widest font-['Orbitron'] text-sm mt-4">
                Sign In
            </button>
        </form>
    </div>

    <!-- Footer Stats -->
    <div class="absolute bottom-6 left-8 flex items-center space-x-4 text-[10px] text-cyan-900/40 font-mono hidden md:flex pointer-events-none z-0">
        <div>CORE: <span class="text-cyan-500/60">ONLINE</span></div>
        <div>|</div>
        <div>LATENCY: <span class="text-cyan-500/60">1ms</span></div>
        <div>|</div>
        <div>ENCRYPTION: <span class="text-cyan-500/60">AES-256</span></div>
    </div>

    <!-- Three.js Logic -->
    <script>
        // Scenario Setup
        const scene = new THREE.Scene();
        // Fog for depth fading
        scene.fog = new THREE.FogExp2(0x0f172a, 0.002);

        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-container').appendChild(renderer.domElement);

        // Geometries
        const geometry = new THREE.IcosahedronGeometry(10, 2); // Core Shape
        const wireframeMaterial = new THREE.MeshBasicMaterial({ 
            color: 0x06b6d4, 
            wireframe: true, 
            transparent: true, 
            opacity: 0.15 
        });
        const core = new THREE.Mesh(geometry, wireframeMaterial);
        scene.add(core);

        // Inner Core (Glowing)
        const innerGeo = new THREE.IcosahedronGeometry(4, 1);
        const innerMat = new THREE.MeshBasicMaterial({
            color: 0x22d3ee,
            wireframe: true,
            transparent: true,
            opacity: 0.5
        });
        const innerCore = new THREE.Mesh(innerGeo, innerMat);
        scene.add(innerCore);

        // Particle Field
        const particlesGeometry = new THREE.BufferGeometry();
        const particlesCount = 700;
        const posArray = new Float32Array(particlesCount * 3);

        for(let i = 0; i < particlesCount * 3; i++) {
            // Spread particles wide
            posArray[i] = (Math.random() - 0.5) * 60;
        }

        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.05,
            color: 0x94a3b8,
            transparent: true,
            opacity: 0.4
        });
        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        camera.position.z = 20;

        // Interaction state
        let mouseX = 0;
        let mouseY = 0;

        document.addEventListener('mousemove', (event) => {
            mouseX = event.clientX - window.innerWidth / 2;
            mouseY = event.clientY - window.innerHeight / 2;
        });

        // Animation Loop
        function animate() {
            requestAnimationFrame(animate);

            // Rotate Core
            core.rotation.x += 0.001;
            core.rotation.y += 0.002;

            innerCore.rotation.x -= 0.002;
            innerCore.rotation.y -= 0.004;

            // Rotate Particles
            particlesMesh.rotation.y = -mouseX * 0.0001;
            particlesMesh.rotation.x = -mouseY * 0.0001;

            // Camera Parallax
            camera.position.x += (mouseX * 0.005 - camera.position.x) * 0.05;
            camera.position.y += (-mouseY * 0.005 - camera.position.y) * 0.05;
            camera.lookAt(scene.position);

            renderer.render(scene, camera);
        }

        animate();

        // Responsive Resizing
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // Form Logic (Preserved)
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const errorMsg = document.getElementById('errorMsg');
            const btn = e.target.querySelector('button');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> ESTABLISHING LINK...';
            errorMsg.classList.add('hidden');

            try {
                const response = await fetch('../api/auth.php?action=login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i> ACCESS GRANTED';
                    btn.classList.remove('from-cyan-600', 'to-blue-600');
                    btn.classList.add('bg-emerald-500', 'shadow-[0_0_30px_rgba(16,185,129,0.5)]');
                    setTimeout(() => window.location.href = 'dashboard.php', 800);
                } else {
                    errorMsg.querySelector('span').textContent = data.message || 'Access Denied';
                    errorMsg.classList.remove('hidden');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                errorMsg.querySelector('span').textContent = "Connection Terminated";
                errorMsg.classList.remove('hidden');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
