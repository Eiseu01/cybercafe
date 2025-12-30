<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - High Performance Gaming</title>
    <link href="assets/css/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        body { margin: 0; overflow-x: hidden; background-color: #0f172a; font-family: 'Roboto Mono', monospace; }
        
        #hero-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 0;
            background: #0f172a;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            pointer-events: none; /* Allow click through to canvas if needed, but here we have buttons */
        }
        .hero-content > * { pointer-events: auto; }

        .glass-nav {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .title-glow {
            text-shadow: 0 0 30px rgba(6, 182, 212, 0.5);
            animation: pulse-glow 4s infinite ease-in-out;
        }
        @keyframes pulse-glow {
            0%, 100% { text-shadow: 0 0 20px rgba(6, 182, 212, 0.3); }
            50% { text-shadow: 0 0 40px rgba(6, 182, 212, 0.8); }
        }

        .feature-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }
        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 10px 40px -10px rgba(6, 182, 212, 0.3);
            background: rgba(30, 41, 59, 0.7);
        }
    </style>
</head>
<body class="bg-slate-900 text-gray-200 selection:bg-cyan-500/30 selection:text-cyan-200">

    <!-- 3D Canvas Background -->
    <div id="hero-canvas"></div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
            <a href="index.php" class="flex items-center space-x-3 group cursor-pointer">
                <div class="w-12 h-12 bg-cyan-500/10 rounded border border-cyan-500/30 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-cube text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white font-['Orbitron'] tracking-[0.2em] group-hover:text-cyan-400 transition-colors">NEXUS</h1>
            </a>

            <div class="hidden md:flex items-center space-x-12 text-sm font-bold uppercase tracking-widest text-gray-400">
                <a href="index.php" class="hover:text-white transition-colors hover:text-cyan-400">Home</a>
                <a href="pages/about.php" class="hover:text-white transition-colors hover:text-cyan-400">About</a>
                <a href="pages/contact.php" class="hover:text-white transition-colors hover:text-cyan-400">Contact</a>
            </div>

            <a href="pages/login.php" class="px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold uppercase tracking-widest rounded hover:shadow-[0_0_20px_rgba(6,182,212,0.6)] transition-all transform hover:-translate-y-0.5">
                Login
            </a>
        </div>
    </nav>

    <!-- Hero Content -->
    <div class="hero-content text-center px-4">
        
        <div class="mb-12"></div> <!-- Spacer to keep visuals balanced without the badge -->

        <h1 class="text-5xl md:text-8xl font-bold text-white font-['Orbitron'] leading-tight mb-8 title-glow">
            NEXUS<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600">INTERNET CAFE</span>
        </h1>
        
        <p class="text-lg md:text-xl text-gray-400 mb-12 max-w-2xl mx-auto font-light leading-relaxed">
            The best Cybercafe Management System for your business.<br>
            Manage computers, track time, and handle payments easily.
        </p>
        
        <p class="text-cyan-400 font-bold tracking-widest uppercase mb-10 text-sm">
            Simple. Fast. User Friendly.
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="pages/dashboard.php" class="relative group px-12 py-5 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold font-['Orbitron'] uppercase tracking-[0.2em] rounded-lg overflow-hidden shadow-[0_0_30px_rgba(6,182,212,0.4)] hover:shadow-[0_0_50px_rgba(6,182,212,0.6)] transition-all transform hover:-translate-y-1">
                <span class="relative z-10 group-hover:tracking-[0.3em] transition-all duration-300"><i class="fas fa-power-off mr-2"></i> Start System</span>
                <div class="absolute inset-0 bg-white/20 transform skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            </a>
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-32 bg-slate-900 relative z-10">
        <!-- Decoration -->
        <div class="absolute top-0 w-full h-32 bg-gradient-to-b from-[#0f172a] to-slate-900"></div>

        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24">
                <h2 class="text-4xl font-bold text-white font-['Orbitron'] tracking-[0.2em] mb-4 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">FEATURES</h2>
                <div class="w-32 h-1.5 bg-gradient-to-r from-transparent via-cyan-500 to-transparent mx-auto rounded-full shadow-[0_0_15px_rgba(6,182,212,0.5)]"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Module 1 -->
                <div class="feature-card p-10 rounded-2xl group border border-white/5 hover:border-cyan-500/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="w-20 h-20 bg-cyan-900/20 rounded-2xl flex items-center justify-center text-cyan-400 mb-8 group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300 border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.1)] relative z-10">
                        <i class="fas fa-clock text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white font-['Orbitron'] mb-4 tracking-wide relative z-10">Time Tracking</h3>
                    <p class="text-gray-400 text-sm leading-relaxed font-light relative z-10">
                        Automatically calculates the bill based on how long the customer uses the computer. fast and accurate.
                    </p>
                </div>

                <!-- Module 2 -->
                <div class="feature-card p-10 rounded-2xl group border border-white/5 hover:border-purple-500/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="w-20 h-20 bg-purple-900/20 rounded-2xl flex items-center justify-center text-purple-400 mb-8 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 border border-purple-500/20 shadow-[0_0_15px_rgba(168,85,247,0.1)] relative z-10">
                         <i class="fas fa-desktop text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white font-['Orbitron'] mb-4 tracking-wide relative z-10">Computer Control</h3>
                    <p class="text-gray-400 text-sm leading-relaxed font-light relative z-10">
                        Easily manage all computers from one place. You can start, stop, or check the status of any PC.
                    </p>
                </div>

                <!-- Module 3 -->
                <div class="feature-card p-10 rounded-2xl group border border-white/5 hover:border-emerald-500/30 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="w-20 h-20 bg-emerald-900/20 rounded-2xl flex items-center justify-center text-emerald-400 mb-8 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)] relative z-10">
                         <i class="fas fa-chart-line text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white font-['Orbitron'] mb-4 tracking-wide relative z-10">Sales Reports</h3>
                    <p class="text-gray-400 text-sm leading-relaxed font-light relative z-10">
                        View your daily earnings and see which customers are playing the most. Simple and easy to understand.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer" class="border-t border-white/5 bg-slate-900 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-white font-['Orbitron'] tracking-[0.3em] mb-4 opacity-50 hover:opacity-100 transition-opacity cursor-default">NEXUS</h2>
            <p class="text-gray-600 text-xs font-mono uppercase tracking-widest mb-10">Cybercafe Solutions • 2025 • Ace John Nieva</p>
            
            <div class="flex justify-center space-x-8 text-gray-500">
                <a href="#" class="hover:text-cyan-400 transition-colors transform hover:scale-125"><i class="fab fa-discord text-xl"></i></a>
                <a href="#" class="hover:text-cyan-400 transition-colors transform hover:scale-125"><i class="fab fa-twitter text-xl"></i></a>
                <a href="#" class="hover:text-cyan-400 transition-colors transform hover:scale-125"><i class="fab fa-github text-xl"></i></a>
            </div>
        </div>
    </footer>

    <!-- Three.js Scene -->
    <script>
        const scene = new THREE.Scene();
        // Fog to blend particles into the background color
        scene.fog = new THREE.FogExp2(0x0f172a, 0.001);

        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('hero-canvas').appendChild(renderer.domElement);

        // Starfield
        const starsGeometry = new THREE.BufferGeometry();
        const starsCount = 1500;
        const posArray = new Float32Array(starsCount * 3);

        for(let i = 0; i < starsCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 100; // Spread stars
        }

        starsGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const starsMaterial = new THREE.PointsMaterial({
            size: 0.15,
            color: 0x22d3ee, // Cyan stars
            transparent: true,
            opacity: 0.8
        });
        const starMesh = new THREE.Points(starsGeometry, starsMaterial);
        scene.add(starMesh);

        // Floating Geometric Shapes (Debris)
        const geometry = new THREE.IcosahedronGeometry(1, 0);
        const material = new THREE.MeshBasicMaterial({ color: 0x6366f1, wireframe: true, transparent: true, opacity: 0.3 });
        
        const shapes = [];
        for(let i=0; i<15; i++) {
            const mesh = new THREE.Mesh(geometry, material);
            mesh.position.x = (Math.random() - 0.5) * 40;
            mesh.position.y = (Math.random() - 0.5) * 40;
            mesh.position.z = (Math.random() - 0.5) * 40;
            mesh.rotation.x = Math.random() * Math.PI;
            scene.add(mesh);
            shapes.push({ mesh, speed: (Math.random() * 0.02) + 0.005 });
        }

        camera.position.z = 30;

        // Interaction
        let mouseX = 0;
        let mouseY = 0;
        let targetX = 0;
        let targetY = 0;

        const windowHalfX = window.innerWidth / 2;
        const windowHalfY = window.innerHeight / 2;

        document.addEventListener('mousemove', (event) => {
            mouseX = (event.clientX - windowHalfX);
            mouseY = (event.clientY - windowHalfY);
        });

        // Loop
        const clock = new THREE.Clock();

        function animate() {
            targetX = mouseX * 0.001;
            targetY = mouseY * 0.001;

            const elapsedTime = clock.getElapsedTime();

            // Smooth rotation for starfield
            starMesh.rotation.y += 0.0005;
            starMesh.rotation.x += 0.0002;

            // Interactive rotation
            starMesh.rotation.y += 0.05 * (targetX - starMesh.rotation.y);
            starMesh.rotation.x += 0.05 * (targetY - starMesh.rotation.x);

            // Animate Shapes
            shapes.forEach(item => {
                item.mesh.rotation.x += item.speed;
                item.mesh.rotation.y += item.speed;
                // Gentle bobbing
                item.mesh.position.y += Math.sin(elapsedTime + item.mesh.position.x) * 0.01;
            });

            renderer.render(scene, camera);
            requestAnimationFrame(animate);
        }

        animate();

        // Resize HW
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // Scroll Effect (Fade out canvas content on scroll)
        window.addEventListener('scroll', () => {
             const scrollY = window.scrollY;
             const opacity = 1 - (scrollY / 700);
             if(opacity >= 0) {
                 document.getElementById('hero-canvas').style.opacity = opacity;
                 document.querySelector('.hero-content').style.opacity = opacity;
             }
        });
    </script>
</body>
</html>