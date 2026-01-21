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
        
        #canvas-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .glass-nav {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(6, 182, 212, 0.1);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
        }

        .input-cyber {
            background: rgba(8, 14, 28, 0.6);
            border: 1px solid rgba(6, 182, 212, 0.2);
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        .input-cyber:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
            background: rgba(8, 14, 28, 0.9);
            outline: none;
        }

        .btn-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="text-gray-200 selection:bg-cyan-500/30">

    <!-- Shader Background -->
    <div id="canvas-container"></div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
            <a href="../index.php" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-cyan-500/10 rounded border border-cyan-500/30 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-cube text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white font-['Orbitron'] tracking-[0.2em] group-hover:text-cyan-400 transition-colors">NEXUS</h1>
            </a>
            <div class="hidden md:flex items-center space-x-12 text-sm font-bold uppercase tracking-widest text-gray-400">
                <a href="../index.php" class="hover:text-white transition-colors hover:text-cyan-400">Home</a>
                <a href="about.php" class="hover:text-white transition-colors hover:text-cyan-400">About</a>
                <a href="contact.php" class="text-cyan-400">Contact</a>
            </div>
            <a href="login.php" class="px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-xs font-bold uppercase tracking-widest rounded hover:shadow-[0_0_20px_rgba(6,182,212,0.6)] transition-all transform hover:-translate-y-0.5">Login</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="relative z-10 pt-36 pb-20 px-6 min-h-screen flex items-center justify-center">
        <div class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Left Column: Info -->
            <div class="flex flex-col justify-center space-y-8 animate-fade-in-left">
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold text-white font-['Orbitron'] mb-6 leading-tight">
                        GET IN <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-600">TOUCH</span>
                    </h1>
                    <p class="text-gray-400 text-lg max-w-md font-light">
                        Have questions about our rates, specs, or availability? Our support team is online and ready to assist.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Location -->
                    <div class="flex items-center p-4 rounded-2xl bg-slate-900/40 border border-white/5 hover:border-cyan-500/30 transition-colors group gap-8">
                        <div class="w-16 h-16 shrink-0 rounded-xl bg-cyan-900/20 flex items-center justify-center text-cyan-400 border border-cyan-500/20 group-hover:bg-cyan-500 group-hover:text-white transition-all">
                            <i class="fas fa-map-marker-alt text-2xl"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-cyan-500 tracking-widest mb-1">HQ Location</div>
                            <div class="text-white font-medium text-lg">Baliwasan, Zamboanga City</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center p-4 rounded-2xl bg-slate-900/40 border border-white/5 hover:border-purple-500/30 transition-colors group gap-8">
                        <div class="w-16 h-16 shrink-0 rounded-xl bg-purple-900/20 flex items-center justify-center text-purple-400 border border-purple-500/20 group-hover:bg-purple-500 group-hover:text-white transition-all">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-purple-500 tracking-widest mb-1">Email Support</div>
                            <div class="text-white font-medium text-lg">ace@support.com</div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-center p-4 rounded-2xl bg-slate-900/40 border border-white/5 hover:border-emerald-500/30 transition-colors group gap-8">
                        <div class="w-16 h-16 shrink-0 rounded-xl bg-emerald-900/20 flex items-center justify-center text-emerald-400 border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-phone text-2xl"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-emerald-500 tracking-widest mb-1">Direct Line</div>
                            <div class="text-white font-medium text-lg">+63 912 345 6789</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form -->
            <div class="glass-panel p-8 md:p-10 rounded-3xl relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transparent pointer-events-none"></div>
                
                <h3 class="text-2xl font-bold text-white font-['Orbitron'] mb-8 flex items-center">
                    <span class="w-2 h-8 bg-cyan-500 mr-4 rounded-full shadow-[0_0_10px_rgba(6,182,212,0.5)]"></span>
                    SEND MESSAGE
                </h3>

                <form class="space-y-6 relative z-10">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-cyan-500 uppercase tracking-widest ml-1">Name</label>
                            <input type="text" class="input-cyber w-full px-4 py-3 rounded-lg text-sm" placeholder="John Doe">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-cyan-500 uppercase tracking-widest ml-1">Email</label>
                            <input type="email" class="input-cyber w-full px-4 py-3 rounded-lg text-sm" placeholder="john@example.com">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-cyan-500 uppercase tracking-widest ml-1">Subject</label>
                        <select class="input-cyber w-full px-4 py-3 rounded-lg text-sm appearance-none cursor-pointer">
                            <option>General Inquiry</option>
                            <option>Technical Support</option>
                            <option>Feedback</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-cyan-500 uppercase tracking-widest ml-1">Message</label>
                        <textarea class="input-cyber w-full px-4 py-3 rounded-lg text-sm h-32 resize-none" placeholder="Type your message here..."></textarea>
                    </div>

                    <button type="button" class="btn-glow w-full py-4 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold rounded-lg uppercase tracking-[0.2em] font-['Orbitron'] text-sm mt-4 hover:from-cyan-500 hover:to-blue-500">
                        Transmit Data
                    </button>
                </form>
            </div>

        </div>
    </div>

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
        
        varying vec2 vUv;

        float random (in vec2 st) {
            return fract(sin(dot(st.xy, vec2(12.9898,78.233))) * 43758.5453123);
        }

        void main() {
            vec2 st = gl_FragCoord.xy / u_resolution.xy;
            st.x *= u_resolution.x / u_resolution.y;

            vec3 color = vec3(0.0);

            // Deep Space Background
            vec3 bgCol = vec3(0.05, 0.07, 0.12);
            color = bgCol;

            // Hex/Grid Pattern
            vec2 grid = st * 10.0;
            grid.x += sin(grid.y * 0.5 + u_time * 0.2) * 0.5; // Wave effect
            
            vec2 ipos = floor(grid);
            vec2 fpos = fract(grid);

            // Random blinking nodes
            float blink = random(ipos);
            float fade = sin(u_time * 3.0 + blink * 10.0);
            
            if (blink > 0.92) {
                color += vec3(0.0, 0.8, 1.0) * fade * 0.5; // Cyan dots
            }

            // Connecting lines effect
            float lineStroke = smoothstep(0.02, 0.0, abs(fpos.y - 0.5));
            color += vec3(0.0, 0.3, 0.6) * lineStroke * 0.1;

            // Vignette
            float dist = distance(st, vec2(0.5 * (u_resolution.x/u_resolution.y), 0.5));
            color *= (1.0 - dist * 0.5);

            gl_FragColor = vec4(color, 1.0);
        }
    </script>

    <script>
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-container').appendChild(renderer.domElement);

        const uniforms = {
            u_time: { value: 0.0 },
            u_resolution: { value: new THREE.Vector2(window.innerWidth, window.innerHeight) }
        };

        const material = new THREE.ShaderMaterial({
            uniforms: uniforms,
            vertexShader: document.getElementById('vertexShader').textContent,
            fragmentShader: document.getElementById('fragmentShader').textContent
        });

        // Background Plane - Calculated to screen size at depth -10
        const dist = 30; // Distance of background plane
        const vFOV = THREE.Math.degToRad(camera.fov); 
        const height = 2 * Math.tan(vFOV / 2) * dist;
        const width = height * camera.aspect;
        
        const plane = new THREE.Mesh(new THREE.PlaneGeometry(width * 1.5, height * 1.5), material);
        plane.position.z = -20; // Correct depth for background
        scene.add(plane);


        // --- 3D FLOATING ELEMENTS (RESTORED) ---
        const geometry = new THREE.IcosahedronGeometry(1.5, 0); 
        const geomMaterial = new THREE.MeshBasicMaterial({ 
            color: 0x22d3ee, // Cyan
            wireframe: true, 
            transparent: true, 
            opacity: 0.3 
        });
        
        const shapes = [];
        // Add a primary large shape
        const mainShape = new THREE.Mesh(new THREE.IcosahedronGeometry(4, 1), new THREE.MeshBasicMaterial({
            color: 0x6366f1, // Purple/Indigo
            wireframe: true,
            transparent: true,
            opacity: 0.1
        }));
        mainShape.position.set(15, 0, -10); // Position to the right, deep in Z
        scene.add(mainShape);

        // Scattered smaller shapes
        for(let i=0; i<15; i++) {
            const mesh = new THREE.Mesh(geometry, geomMaterial);
            mesh.position.x = (Math.random() - 0.5) * 40;
            mesh.position.y = (Math.random() - 0.5) * 20;
            mesh.position.z = (Math.random() - 0.5) * 10;
            mesh.rotation.x = Math.random() * Math.PI;
            scene.add(mesh);
            shapes.push({ 
                mesh, 
                rotSpeed: (Math.random() * 0.02) + 0.005,
                floatSpeed: (Math.random() * 0.005) + 0.002,
                yOffset: Math.random() * 100 
            });
        }
        
        // Move camera back to see 3D elements
        camera.position.z = 10;

        const clock = new THREE.Clock();

        // Mouse interaction
        let mouseX = 0;
        let mouseY = 0;
        document.addEventListener('mousemove', (event) => {
            mouseX = (event.clientX - window.innerWidth / 2) * 0.001;
            mouseY = (event.clientY - window.innerHeight / 2) * 0.001;
        });

        function animate() {
            requestAnimationFrame(animate);
            const elapsedTime = clock.getElapsedTime();
            uniforms.u_time.value = elapsedTime;

            // Animate Main Shape
            mainShape.rotation.x += 0.002;
            mainShape.rotation.y += 0.003;

            // Animate Scattered Shapes
            shapes.forEach(item => {
                item.mesh.rotation.x += item.rotSpeed;
                item.mesh.rotation.y += item.rotSpeed;
                item.mesh.position.y += Math.sin(elapsedTime * 2.0 + item.yOffset) * item.floatSpeed;
            });
            
            // Subtle Camera Movement
            camera.position.x += (mouseX * 5 - camera.position.x) * 0.05;
            camera.position.y += (-mouseY * 5 - camera.position.y) * 0.05;
            camera.lookAt(0,0,0);

            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            const width = window.innerWidth;
            const height = window.innerHeight;
            renderer.setSize(width, height);
            uniforms.u_resolution.value.set(width, height);
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
        });
    </script>
</body>
</html>
