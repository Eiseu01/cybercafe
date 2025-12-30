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
        <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Info Side -->
            <div>
                <h1 class="text-5xl font-bold text-white font-['Orbitron'] mb-6 tracking-wide">CONTACT US</h1>
                <p class="text-gray-400 mb-10 text-lg">Have a question? Suggestion? Or just want to reserve a computer? Message us!</p>
                
                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded bg-cyan-900/40 flex items-center justify-center text-cyan-400 border border-cyan-500/20">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">Location</div>
                            <div class="text-white">Cyber City, Tech Avenue 101</div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded bg-purple-900/40 flex items-center justify-center text-purple-400 border border-purple-500/20">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">Email Support</div>
                            <div class="text-white">support@nexuscafe.com</div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded bg-emerald-900/40 flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">Phone</div>
                            <div class="text-white">+63 912 345 6789</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Side -->
            <div class="glass-panel p-8 rounded-2xl shadow-xl border-t border-cyan-500/20">
                <form onsubmit="event.preventDefault(); alert('Message sent! We will reply soon.');">
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-cyan-500 uppercase tracking-widest mb-2">Your Name</label>
                        <input type="text" class="input-cyber w-full p-3 rounded" placeholder="John Doe" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-cyan-500 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" class="input-cyber w-full p-3 rounded" placeholder="john@example.com" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-cyan-500 uppercase tracking-widest mb-2">Message</label>
                        <textarea class="input-cyber w-full p-3 rounded h-32" placeholder="Start typing..." required></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold rounded hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] transition-all font-['Orbitron'] tracking-widest">
                        SEND MESSAGE
                    </button>
                </form>
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
