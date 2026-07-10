<!-- Welcome to Game Dev Section -->
<section class="py-24 px-6 relative bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800/60 overflow-hidden transition-colors duration-300">
    <!-- Game Dev Animated Background Canvas -->
    <canvas id="gamedev-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 transition-opacity duration-500"></canvas>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16 relative z-10">
        <!-- Left Side: Image -->
        <div class="w-full md:w-1/2 flex justify-center">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-400 to-purple-400 blur-[80px] opacity-20 dark:opacity-30 rounded-full"></div>
                <img src="{{ asset('games.png') }}" alt="Game Development Montage" class="relative w-full max-w-lg h-auto hover:scale-105 transition-transform duration-500 drop-shadow-xl" onerror="this.src='https://via.placeholder.com/600x500/1e293b/94a3b8?text=Games+Montage'">
            </div>
        </div>
        
        <!-- Right Side: Content -->
        <div class="w-full md:w-1/2 flex flex-col items-center md:items-start text-center md:text-left">
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-widest text-slate-800 dark:text-white mb-8 uppercase leading-tight drop-shadow-sm">
                Welcome to the World <br/>
                <span class="text-blue-600 dark:text-brand-light drop-shadow-sm">of Game Development</span>
            </h2>
            <p class="text-[1.1rem] md:text-lg text-slate-600 dark:text-slate-300 leading-[1.8] tracking-wide text-justify md:text-left font-medium">
                The Game development has evolved from 1970s text-based games to thrilling 2D and 3D adventures. The 1980s popularized arcade gaming, while the 1990s introduced groundbreaking 3D graphics. Today, both indie and major studios innovate with retro styles and cutting-edge technology. This dynamic industry offers endless creative opportunities, especially for out-of-school youth eager to explore and create. Whether making games or enjoying them, there's a place for everyone.
            </p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('gamedev-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    let width = canvas.width = canvas.offsetWidth;
    let height = canvas.height = canvas.offsetHeight;
    
    // Handle resize
    function resize() {
        const rect = canvas.getBoundingClientRect();
        width = canvas.width = rect.width;
        height = canvas.height = rect.height;
    }
    window.addEventListener('resize', resize);
    // Initial size setting
    setTimeout(resize, 100);

    // Track Theme
    let isDark = document.documentElement.classList.contains('dark');
    const observer = new MutationObserver(() => {
        const currentDark = document.documentElement.classList.contains('dark');
        if (currentDark !== isDark) {
            isDark = currentDark;
            initParticles();
        }
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    // Interactive mouse positioning
    let mouse = { x: null, y: null, targetX: null, targetY: null };
    const section = canvas.parentElement;
    section.addEventListener('mousemove', (e) => {
        const rect = section.getBoundingClientRect();
        mouse.targetX = e.clientX - rect.left;
        mouse.targetY = e.clientY - rect.top;
    });
    section.addEventListener('mouseleave', () => {
        mouse.targetX = null;
        mouse.targetY = null;
    });

    // Particle classes
    class Star {
        constructor() {
            this.reset();
            this.y = Math.random() * height;
        }
        reset() {
            this.x = Math.random() * width;
            this.y = 0;
            this.size = Math.random() * 1.5 + 0.5;
            this.speed = Math.random() * 1.5 + 0.5;
            this.alpha = Math.random() * 0.5 + 0.3;
        }
        update() {
            this.y += this.speed;
            if (this.y > height) {
                this.reset();
            }
        }
        draw() {
            ctx.fillStyle = `rgba(255, 255, 255, ${this.alpha})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    class GameText {
        constructor() {
            this.reset();
            this.y = Math.random() * height;
        }
        reset() {
            const messages = ['+100 PTS', 'LVL UP', 'INSERT COIN', 'GAME ON', 'SELECT', 'READY', 'STAGE 1', 'COMBO X5'];
            this.text = messages[Math.floor(Math.random() * messages.length)];
            this.x = Math.random() * (width - 150) + 50;
            this.y = height + Math.random() * 100;
            this.speed = Math.random() * 0.4 + 0.3;
            this.alpha = 0;
            this.maxAlpha = Math.random() * 0.4 + 0.2;
            this.scale = Math.random() * 0.3 + 0.85;
            this.life = 0;
            this.maxLife = Math.random() * 200 + 150;
        }
        update() {
            this.y -= this.speed;
            this.life++;
            
            // Fade in and out
            if (this.life < 40) {
                this.alpha = (this.life / 40) * this.maxAlpha;
            } else if (this.life > this.maxLife - 40) {
                this.alpha = ((this.maxLife - this.life) / 40) * this.maxAlpha;
            } else {
                this.alpha = this.maxAlpha;
            }

            if (this.life >= this.maxLife || this.y < -50) {
                this.reset();
            }
        }
        draw() {
            ctx.save();
            ctx.font = `bold ${Math.floor(13 * this.scale)}px 'Courier New', Courier, monospace`;
            ctx.fillStyle = isDark ? `rgba(129, 140, 248, ${this.alpha})` : `rgba(79, 70, 229, ${this.alpha})`;
            ctx.shadowColor = isDark ? 'rgba(99, 102, 241, 0.4)' : 'rgba(79, 70, 229, 0.2)';
            ctx.shadowBlur = isDark ? 6 : 2;
            ctx.fillText(this.text, this.x, this.y);
            ctx.restore();
        }
    }

    class WireframeShape {
        constructor() {
            this.reset();
            this.y = Math.random() * height;
        }
        reset() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.size = Math.random() * 30 + 20;
            this.speedX = (Math.random() - 0.5) * 0.4;
            this.speedY = (Math.random() - 0.5) * 0.4;
            this.rotation = Math.random() * Math.PI * 2;
            this.rotSpeed = (Math.random() - 0.5) * 0.01;
            // Types: 0: Space Invader outline, 1: Pacman, 2: Cube outline, 3: Triangle (Triforce)
            this.type = Math.floor(Math.random() * 4);
            this.alpha = Math.random() * 0.2 + 0.05;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            this.rotation += this.rotSpeed;

            if (this.x < -this.size || this.x > width + this.size || this.y < -this.size || this.y > height + this.size) {
                this.reset();
                // Respawn near edges
                if (Math.random() > 0.5) {
                    this.x = Math.random() > 0.5 ? -this.size : width + this.size;
                } else {
                    this.y = Math.random() > 0.5 ? -this.size : height + this.size;
                }
            }
        }
        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rotation);
            ctx.lineWidth = 1;
            ctx.strokeStyle = isDark 
                ? `rgba(99, 102, 241, ${this.alpha})`  // Indigo in dark
                : `rgba(30, 41, 59, ${this.alpha * 0.4})`; // Slate in light
            
            if (isDark) {
                ctx.shadowColor = 'rgba(99, 102, 241, 0.3)';
                ctx.shadowBlur = 4;
            }

            ctx.beginPath();
            if (this.type === 0) {
                // Space Invader wireframe shape
                const s = this.size / 6;
                ctx.rect(-2*s, -2*s, s, s);
                ctx.rect(1*s, -2*s, s, s);
                ctx.rect(-1*s, -1*s, s, s);
                ctx.rect(0*s, -1*s, s, s);
                ctx.rect(-3*s, 0, 7*s, s);
                ctx.rect(-3*s, 1*s, s, s);
                ctx.rect(-1*s, 1*s, 3*s, s);
                ctx.rect(3*s, 1*s, s, s);
                ctx.rect(-3*s, 2*s, s, s);
                ctx.rect(3*s, 2*s, s, s);
                ctx.rect(-2*s, 2*s, s, s);
                ctx.rect(1*s, 2*s, s, s);
            } else if (this.type === 1) {
                // Pacman
                ctx.arc(0, 0, this.size / 2, 0.2 * Math.PI, 1.8 * Math.PI);
                ctx.lineTo(0, 0);
                ctx.closePath();
            } else if (this.type === 2) {
                // 3D-ish Isometric Cube Wireframe
                const s = this.size / 2;
                // Front face
                ctx.rect(-s/2, -s/2, s, s);
                // Back face offset
                ctx.rect(-s/4, -s/4, s, s);
                // Connect corners
                ctx.moveTo(-s/2, -s/2); ctx.lineTo(-s/4, -s/4);
                ctx.moveTo(s/2, -s/2); ctx.lineTo(3*s/4, -s/4);
                ctx.moveTo(-s/2, s/2); ctx.lineTo(-s/4, 3*s/4);
                ctx.moveTo(s/2, s/2); ctx.lineTo(3*s/4, 3*s/4);
            } else {
                // Triforce / Triangle
                const s = this.size / 2;
                ctx.moveTo(0, -s);
                ctx.lineTo(s, s);
                ctx.lineTo(-s, s);
                ctx.closePath();
                // Inner lines
                ctx.moveTo(0, s);
                ctx.lineTo(s/2, 0);
                ctx.lineTo(-s/2, 0);
                ctx.closePath();
            }
            ctx.stroke();
            ctx.restore();
        }
    }

    let stars = [];
    let texts = [];
    let shapes = [];

    function initParticles() {
        stars = [];
        texts = [];
        shapes = [];

        // Stars are for space shooter vibe in dark mode
        if (isDark) {
            for (let i = 0; i < 40; i++) {
                stars.push(new Star());
            }
        }

        // Texts fade in/out for arcade vibes in both modes
        for (let i = 0; i < 4; i++) {
            texts.push(new GameText());
        }

        // Shapes float around
        for (let i = 0; i < 8; i++) {
            shapes.push(new WireframeShape());
        }
    }

    initParticles();

    // Mouse ease tracking
    if (mouse.x === null) {
        mouse.x = width / 2;
        mouse.y = height / 2;
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);

        // Smoothly interpolate mouse
        if (mouse.targetX !== null) {
            mouse.x += (mouse.targetX - mouse.x) * 0.1;
            mouse.y += (mouse.targetY - mouse.y) * 0.1;
        }

        // Draw background theme-specific grid/matrix structure
        if (isDark) {
            // Dark Mode: Cyber Grid & Space Starfield
            // Stars
            stars.forEach(star => {
                star.update();
                star.draw();
            });

            // Cyber Horizon Grid lines (horizontal lines with perspective)
            ctx.strokeStyle = 'rgba(99, 102, 241, 0.05)';
            ctx.lineWidth = 1;
            const vpX = width / 2;
            const vpY = height * 0.2; // Vanishing point
            for (let i = -10; i <= 10; i++) {
                ctx.beginPath();
                ctx.moveTo(vpX, vpY);
                ctx.lineTo(vpX + i * (width / 8), height);
                ctx.stroke();
            }

            // Horizontal perspective lines
            for (let y = vpY; y < height; y += (height - y) * 0.2 + 5) {
                ctx.beginPath();
                ctx.moveTo(0, y);
                ctx.lineTo(width, y);
                ctx.stroke();
            }

            // Draw glowing scanline overlay effect
            ctx.fillStyle = 'rgba(15, 23, 42, 0.1)';
            for (let y = 0; y < height; y += 4) {
                ctx.fillRect(0, y, width, 1);
            }

        } else {
            // Light Mode: Clean Editor Blueprint grid
            ctx.strokeStyle = 'rgba(79, 70, 229, 0.04)';
            ctx.lineWidth = 1;
            const gridSize = 50;

            // Draw Blueprint Grid
            for (let x = 0; x < width; x += gridSize) {
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, height);
                ctx.stroke();
            }
            for (let y = 0; y < height; y += gridSize) {
                ctx.beginPath();
                ctx.moveTo(0, y);
                ctx.lineTo(width, y);
                ctx.stroke();
            }

            // Level Blueprint details
            ctx.strokeStyle = 'rgba(79, 70, 229, 0.08)';
            ctx.beginPath();
            ctx.moveTo(80, 0); ctx.lineTo(80, height);
            ctx.moveTo(0, 120); ctx.lineTo(width, 120);
            ctx.stroke();

            ctx.fillStyle = 'rgba(79, 70, 229, 0.2)';
            ctx.font = '9px monospace';
            ctx.fillText('X-AXIS', width - 50, 115);
            ctx.fillText('Y-AXIS', 85, height - 20);
        }

        // Draw interactive crosshair / level designer editor highlight around mouse
        if (mouse.targetX !== null) {
            ctx.save();
            ctx.lineWidth = 0.5;
            if (isDark) {
                ctx.strokeStyle = 'rgba(129, 140, 248, 0.25)';
                ctx.fillStyle = 'rgba(129, 140, 248, 0.15)';
                ctx.shadowColor = 'rgba(129, 140, 248, 0.5)';
                ctx.shadowBlur = 8;
            } else {
                ctx.strokeStyle = 'rgba(79, 70, 229, 0.15)';
                ctx.fillStyle = 'rgba(79, 70, 229, 0.08)';
            }

            ctx.beginPath();
            ctx.moveTo(mouse.x - 20, mouse.y); ctx.lineTo(mouse.x + 20, mouse.y);
            ctx.moveTo(mouse.x, mouse.y - 20); ctx.lineTo(mouse.x, mouse.y + 20);
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(mouse.x, mouse.y, 10, 0, Math.PI * 2);
            ctx.stroke();

            ctx.fillStyle = isDark ? 'rgba(129, 140, 248, 0.4)' : 'rgba(79, 70, 229, 0.4)';
            ctx.font = '9px monospace';
            const coordText = `X:${Math.round(mouse.x)} Y:${Math.round(mouse.y)}`;
            ctx.fillText(coordText, mouse.x + 15, mouse.y - 10);
            ctx.restore();
        }

        // Update & Draw shapes
        shapes.forEach(shape => {
            shape.update();
            shape.draw();
        });

        // Update & Draw texts
        texts.forEach(text => {
            text.update();
            text.draw();
        });

        requestAnimationFrame(animate);
    }

    animate();
});
</script>
