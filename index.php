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
            <p class="text-gray-600 text-xs font-mono uppercase tracking-widest mb-10">© 2025, Nieva Ace John</p>
        </div>
    </footer>

    <!-- Shader Scripts -->
    <script id="vertexShader" type="x-shader/x-vertex">
        varying vec2 vUv;
        void main() {
            vUv = uv;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
    </script>

    <script id="fragmentShader" type="x-shader/x-fragment">
        uniform float u_time;
        uniform vec2 u_resolution;
        uniform vec2 u_mouse;
        
        varying vec2 vUv;

        // Psuedo-random function from The Book of Shaders
        float random (in vec2 st) {
            return fract(sin(dot(st.xy, vec2(12.9898,78.233))) * 43758.5453123);
        }

        // Noise function
        float noise (in vec2 st) {
            vec2 i = floor(st);
            vec2 f = fract(st);

            // Four corners in 2D of a tile
            float a = random(i);
            float b = random(i + vec2(1.0, 0.0));
            float c = random(i + vec2(0.0, 1.0));
            float d = random(i + vec2(1.0, 1.0));

            vec2 u = f * f * (3.0 - 2.0 * f);

            return mix(a, b, u.x) +
                    (c - a)* u.y * (1.0 - u.x) +
                    (d - b) * u.x * u.y;
        }

        void main() {
            vec2 st = gl_FragCoord.xy / u_resolution.xy;
            st.x *= u_resolution.x / u_resolution.y; // Aspect correction

            vec3 color = vec3(0.0);

            // 1. Background Gradient (Dark Blue/Slate)
            vec3 color1 = vec3(0.06, 0.09, 0.16); // Slate 900ish
            vec3 color2 = vec3(0.02, 0.04, 0.1);  // Darker
            
            // Vignette
            float dist = distance(st, vec2(0.5 * (u_resolution.x/u_resolution.y), 0.5));
            color = mix(color1, color2, dist * 1.5);

            // 2. Moving Grid System
            vec2 gridUV = st * 6.0; // Scale 
            gridUV.y -= u_time * 0.2; // Move down
            
            // Add some waviness to the grid
            gridUV.x += sin(gridUV.y * 3.0 + u_time * 0.5) * 0.1;

            vec2 gridPos = fract(gridUV);
            
            // Thin lines
            float lineRes = 0.02;
            float gridVal = smoothstep(lineRes, 0.0, gridPos.x) + smoothstep(1.0-lineRes, 1.0, gridPos.x) +
                            smoothstep(lineRes, 0.0, gridPos.y) + smoothstep(1.0-lineRes, 1.0, gridPos.y);
            
            // 3. Digital Pulse / "Data Stream"
            float pulse = noise(vec2(st.x * 2.0, st.y * 2.0 - u_time * 0.5));
            float beam = smoothstep(0.6, 0.7, pulse) * smoothstep(0.8, 0.7, pulse); // Isolate specific noise band
            
            // Combine with Cyber Cyan Color
            vec3 gridColor = vec3(0.0, 0.8, 1.0); // Cyan
            
            // Final mixing
            color += gridVal * gridColor * 0.15 * (1.0 - dist); // Fade grid at edges
            color += beam * vec3(0.0, 0.5, 1.0) * 0.3; // Add "Code" beams

            gl_FragColor = vec4(color, 1.0);
        }
    </script>

    <!-- Three.js Scene -->
    <script>
        const scene = new THREE.Scene();
        // Remove fog as we are handling background in shader
        // scene.fog = new THREE.FogExp2(0x0f172a, 0.001);

        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('hero-canvas').appendChild(renderer.domElement);

        // --- SHADER BACKGROUND ---
        const shaderGeometry = new THREE.PlaneGeometry(2, 2); // Full screen quad setup typically, but for Scene bg we usually use a big plane or actual background
        // For a true background effect in ThreeJS without a helper library, putting a large plane behind everything is easiest.
        // Or strictly mapping to screen coords. Here we will use a large plane fixed to camera or just filling view.
        // Better approach for "Background": Use a mesh that fills the screen at a fixed depth.
        
        // Actually, create a PlaneGeometry that covers the view at z = -10
        // Calculate needed size based on FOV and distance
        const dist = 50;
        const vFOV = THREE.Math.degToRad(camera.fov); 
        const height = 2 * Math.tan(vFOV / 2) * dist;
        const width = height * camera.aspect;

        const bgGeometry = new THREE.PlaneGeometry(width * 1.5, height * 1.5); // *1.5 for safety margin on rotation/movement
        
        const uniforms = {
            u_time: { value: 0.0 },
            u_resolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) },
            u_mouse: { value: new THREE.Vector2(0, 0) }
        };

        const bgMaterial = new THREE.ShaderMaterial({
            uniforms: uniforms,
            vertexShader: document.getElementById('vertexShader').textContent,
            fragmentShader: document.getElementById('fragmentShader').textContent,
            transparent: false
        });

        const bgMesh = new THREE.Mesh(bgGeometry, bgMaterial);
        bgMesh.position.z = -20; // Put it well behind
        scene.add(bgMesh);


        // --- EXISTING ELEMENTS (Enhanced) ---

        // Starfield
        const starsGeometry = new THREE.BufferGeometry();
        const starsCount = 2000;
        const posArray = new Float32Array(starsCount * 3);

        for(let i = 0; i < starsCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 120; // Wider spread
        }

        starsGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const starsMaterial = new THREE.PointsMaterial({
            size: 0.2, // Slightly larger
            color: 0x22d3ee, // Cyan stars
            transparent: true,
            opacity: 0.9,
            blending: THREE.AdditiveBlending
        });
        const starMesh = new THREE.Points(starsGeometry, starsMaterial);
        scene.add(starMesh);

        // Floating Geometric Shapes (Debris)
        const geometry = new THREE.IcosahedronGeometry(1, 0);
        const material = new THREE.MeshBasicMaterial({ color: 0x6366f1, wireframe: true, transparent: true, opacity: 0.4 });
        
        const shapes = [];
        for(let i=0; i<20; i++) {
            const mesh = new THREE.Mesh(geometry, material);
            mesh.position.x = (Math.random() - 0.5) * 60;
            mesh.position.y = (Math.random() - 0.5) * 60;
            mesh.position.z = (Math.random() - 0.5) * 40; // Some closer, some further
            mesh.rotation.x = Math.random() * Math.PI;
            scene.add(mesh);
            shapes.push({ mesh, speed: (Math.random() * 0.02) + 0.005, floatOffset: Math.random() * 100 });
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
            
            // Normalize for shader
            uniforms.u_mouse.value.x = event.clientX / window.innerWidth;
            uniforms.u_mouse.value.y = 1.0 - (event.clientY / window.innerHeight); // Flip Y
        });

        // Loop
        const clock = new THREE.Clock();

        function animate() {
            targetX = mouseX * 0.001;
            targetY = mouseY * 0.001;

            const elapsedTime = clock.getElapsedTime();
            
            // Update Shader Uniforms
            uniforms.u_time.value = elapsedTime;

            // Smooth rotation for starfield
            starMesh.rotation.y += 0.0005;
            starMesh.rotation.x += 0.0002;

            // Interactive rotation
            starMesh.rotation.y += 0.05 * (targetX - starMesh.rotation.y);
            starMesh.rotation.x += 0.05 * (targetY - starMesh.rotation.x);
            
            // Also rotate the background slightly for parallax feel
            bgMesh.rotation.x = targetY * 0.1;
            bgMesh.rotation.y = targetX * 0.1;

            // Animate Shapes
            shapes.forEach(item => {
                item.mesh.rotation.x += item.speed;
                item.mesh.rotation.y += item.speed;
                // Complex Float
                item.mesh.position.y += Math.sin(elapsedTime * 0.5 + item.floatOffset) * 0.02;
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
            
            // Update Shader Resolution
            uniforms.u_resolution.value.x = window.innerWidth;
            uniforms.u_resolution.value.y = window.innerHeight;
            
            // Update Grid Plane Size (Keep it covering screen)
            const vFOV = THREE.Math.degToRad(camera.fov); 
            const height = 2 * Math.tan(vFOV / 2) * dist;
            const width = height * camera.aspect;
            bgMesh.geometry.dispose(); // Cleanup old
            bgMesh.geometry = new THREE.PlaneGeometry(width * 1.5, height * 1.5);
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