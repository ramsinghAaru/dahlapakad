@extends('layouts.app')

@section('title', 'Play Dehla Pakad Online | Dehla Pakad Card Game')

@push('meta')
    <meta name="description" content="Play Dehla Pakad, the exciting Indian card game, online with players from around the world. Join a game now!">
    <meta name="keywords" content="Dehla Pakad, card game, Indian card game, online game, play cards">
    <meta property="og:title" content="Play Dehla Pakad Online | Dehla Pakad Card Game">
    <meta property="og:description" content="Play Dehla Pakad, the exciting Indian card game, online with players from around the world. Join a game now!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
@endpush

@push('styles')
<style>
    /* Game container */
    #game-container {
        position: relative;
        width: 100%;
        height: calc(100vh - 200px);
        min-height: 500px;
        background-color: var(--deep-black);
        border-radius: 12px;
        overflow: hidden;
        margin: 2rem 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    /* Game stage */
    #stage {
        width: 100%;
        height: 100%;
        display: block;
    }
    
    /* HUD (Heads-Up Display) */
    .hud {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 10;
    }
    
    .hud .pane {
        pointer-events: auto;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(20, 20, 20, 0.9);
        border: 1px solid var(--brand-red);
        border-radius: 12px;
        padding: 12px 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(5px);
    }
    
    /* Top HUD */
    .hud .top {
        top: 20px;
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
        width: 95%;
        max-width: 1200px;
    }
    
    /* Bottom HUD */
    .hud .bottom {
        bottom: 20px;
        width: 95%;
        max-width: 1000px;
        background: rgba(20, 20, 20, 0.8);
    }
    
    /* Game info */
    .game-info {
        display: flex;
        gap: 15px;
        color: var(--light-gray);
        font-size: 0.9rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .game-info > div {
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(0, 0, 0, 0.3);
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .game-info b {
        color: var(--brand-red);
        font-weight: 600;
    }
    
    /* Cards */
    .cards {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        padding: 15px 0;
    }
    
    .card2d {
        width: 70px;
        height: 100px;
        border-radius: 8px;
        background: #ffffff;
        color: #111;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #eee;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 1.1rem;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }
    
    .card2d:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        z-index: 5;
    }
    
    .card2d[data-suit="♥"],
    .card2d[data-suit="♦"] {
        color: #e74c3c;
    }
    
    /* Game controls */
    .game-controls {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-game {
        background: var(--brand-red);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    
    .btn-game:hover {
        background: var(--dark-gradient-red);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 11, 67, 0.3);
    }
    
    .btn-game.secondary {
        background: transparent;
        border: 1px solid var(--light-gray);
        color: var(--light-gray);
    }
    
    .btn-game.secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--brand-red);
        color: white;
    }
    
    /* Player hand section */
    .hand-section {
        margin-top: 10px;
    }
    
    .hand-section h4 {
        color: var(--light-gray);
        font-size: 0.9rem;
        margin-bottom: 8px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        #game-container {
            height: 65vh;
            min-height: 450px;
        }
        
        .hud .top {
            gap: 12px;
            padding: 10px;
        }
        
        .game-info {
            font-size: 0.8rem;
            gap: 8px;
        }
        
        .card2d {
            width: 55px;
            height: 85px;
            font-size: 1rem;
        }
    }
    
    @media (max-width: 768px) {
        #game-container {
            height: 60vh;
            min-height: 400px;
            margin: 1rem 0;
            border-radius: 8px;
        }
        
        .hud .pane {
            padding: 10px 15px;
        }
        
        .hud .top {
            top: 10px;
            gap: 8px;
        }
        
        .hud .bottom {
            bottom: 10px;
        }
        
        .card2d {
            width: 45px;
            height: 70px;
            font-size: 0.9rem;
        }
        
        .btn-game {
            padding: 6px 12px;
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .card2d {
            width: 40px;
            height: 60px;
            font-size: 0.8rem;
        }
        
        .game-info {
            font-size: 0.7rem;
        }
        
        .hud .top {
            gap: 6px;
        }
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/three@0.161.0/build/three.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/three@0.161.0/examples/js/controls/OrbitControls.js'></script>
<script src='https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js'></script>
@endpush

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4 text-brand">Play Dehla Pakad Online</h1>
    
    <!-- Game Container -->
    <div id="game-container">
        <canvas id="stage"></canvas>
        
        <div class="hud">
            <!-- Top HUD -->
            <div class="pane top">
                <div class="game-info">
                    <div><b>Room:</b> <span id="roomCode">Lobby</span></div>
                    <div><b>Seat:</b> <span id="seat">—</span></div>
                    <div><b>Turn:</b> <span id="turn">—</span></div>
                    <div><b>Trump:</b> <span id="trump">—</span></div>
                </div>
                <div class="game-controls">
                    <button id="btnDeal" class="btn-game">
                        <i class="bi bi-shuffle me-1"></i> Deal Cards
                    </button>
                    <button id="btnMethod1" class="btn-game secondary" title="Set Trump Method 1">
                        <i class="bi bi-1-circle me-1"></i> M1
                    </button>
                    <button id="btnMethod2" class="btn-game secondary" title="Set Trump Method 2">
                        <i class="bi bi-2-circle me-1"></i> M2
                    </button>
                    <button id="btnJoin" class="btn-game">
                        <i class="bi bi-joystick me-1"></i> Join Game
                    </button>
                    <button id="btnAuth" class="btn-game secondary">
                        <i class="bi bi-person-circle me-1"></i> Login
                    </button>
                </div>
            </div>
            
            <!-- Bottom HUD -->
            <div class="pane bottom">
                <div class="hand-section">
                    <h4>Your Hand</h4>
                    <div class="cards" id="hand">
                        <!-- Cards will be added here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Game Info Section -->
    <div class="row mt-5 g-4">
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-brand">
                        <i class="bi bi-question-circle me-2"></i> How to Play
                    </h5>
                    <div class="card-text">
                        <p class="mb-3">Dehla Pakad is a strategic trick-taking card game where the main objective is to collect tens and win tricks.</p>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item bg-transparent border-start-0 border-end-0">
                                <strong>Start the Game:</strong> Join a room or create a new one
                            </li>
                            <li class="list-group-item bg-transparent border-start-0 border-end-0">
                                <strong>Play Cards:</strong> Click on a card from your hand to play it
                            </li>
                            <li class="list-group-item bg-transparent border-start-0 border-end-0">
                                <strong>Follow Suit:</strong> If possible, you must play a card of the same suit as the first card played
                            </li>
                            <li class="list-group-item bg-transparent border-start-0 border-end-0">
                                <strong>Win Tricks:</strong> The highest card of the leading suit wins the trick
                            </li>
                            <li class="list-group-item bg-transparent border-start-0 border-end-0">
                                <strong>Score Points:</strong> Collect tens to score points and win the game
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-brand">
                        <i class="bi bi-journal-text me-2"></i> Game Rules
                    </h5>
                    <div class="card-text">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <h6 class="text-uppercase text-muted small mb-3">Setup</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><i class="bi bi-people-fill text-brand me-2"></i> 2-4 players</li>
                                        <li class="mb-2"><i class="bi bi-card-checklist text-brand me-2"></i> Standard 52-card deck</li>
                                        <li class="mb-2"><i class="bi bi-clock-history text-brand me-2"></i> 15-30 minutes per game</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100">
                                    <h6 class="text-uppercase text-muted small mb-3">Scoring</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><i class="bi bi-10-square-fill text-brand me-2"></i> 10 of any suit: 10 points</li>
                                        <li class="mb-2"><i class="bi bi-suit-diamond-fill text-danger me-2"></i> 10♦: 20 points</li>
                                        <li class="mb-2"><i class="bi bi-trophy-fill text-warning me-2"></i> First to 100 wins</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="bi bi-lightbulb-fill me-2"></i>
                            <strong>Pro Tip:</strong> Pay attention to the cards played to track which tens are still in play.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title text-brand" id="authModalLabel">
                    <i class="bi bi-person-circle me-2"></i> Login to Play
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="loginForm" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control" id="username" placeholder="Enter username" required>
                            <div class="invalid-feedback">
                                Please enter a username
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                            <div class="invalid-feedback">
                                Please enter your password
                            </div>
                        </div>
                        <div class="form-text text-end">
                            <a href="#" class="text-decoration-none">Forgot password?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-brand w-100 py-2 mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
                    </button>
                    
                    <div class="text-center text-muted mb-3">
                        <span class="bg-white px-2">OR</span>
                    </div>
                    
                    <button type="button" class="btn btn-outline-secondary w-100 mb-3">
                        <i class="bi bi-google me-2"></i> Continue with Google
                    </button>
                    <button type="button" class="btn btn-outline-primary w-100 mb-3">
                        <i class="bi bi-facebook me-2"></i> Continue with Facebook
                    </button>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light justify-content-center">
                <p class="mb-0">
                    Don't have an account? 
                    <a href="#" id="showRegister" class="text-brand fw-bold text-decoration-none">
                        Sign up now
                    </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize Three.js scene
const canvas = document.getElementById('stage');
const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
const scene = new THREE.Scene();
scene.background = new THREE.Color(0x0f1012);

// Camera setup
const camera = new THREE.PerspectiveCamera(50, 2, 0.1, 100);
camera.position.set(0, 6, 10);

// Controls
const controls = new THREE.OrbitControls(camera, renderer.domElement);
controls.enablePan = false;
controls.enableDamping = true;
controls.target.set(0, 1, 0);

// Lighting
const hemi = new THREE.HemisphereLight(0xffffff, 0x111111, 0.9);
scene.add(hemi);

const dir = new THREE.DirectionalLight(0xffffff, 0.9);
dir.position.set(4, 8, 6);
scene.add(dir);

// Table
const table = new THREE.Mesh(
    new THREE.CylinderGeometry(6.5, 6.5, 0.6, 64),
    new THREE.MeshStandardMaterial({ 
        color: 0x1c1c1e, 
        metalness: 0.1, 
        roughness: 0.8 
    })
);
table.position.y = 0.3;
scene.add(table);

// Felt surface
const felt = new THREE.Mesh(
    new THREE.CircleGeometry(6.35, 64),
    new THREE.MeshStandardMaterial({ 
        color: 0x0f3b2e, 
        metalness: 0.05, 
        roughness: 0.95 
    })
);
felt.rotation.x = -Math.PI / 2;
felt.position.y = 0.61;
scene.add(felt);

// Center point for cards
const centre = new THREE.Object3D();
centre.position.set(0, 0.65, 0);
scene.add(centre);

// Handle window resize
function resize() {
    const w = canvas.clientWidth;
    const h = canvas.clientHeight;
    
    if (canvas.width !== w || canvas.height !== h) {
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
    }
}

// Animation loop
function animate() {
    resize();
    controls.update();
    renderer.render(scene, camera);
    requestAnimationFrame(animate);
}

// Start animation loop
animate();

// Card creation helper
function createCardMesh() {
    return new THREE.Mesh(
        new THREE.BoxGeometry(0.63, 0.02, 0.88),
        new THREE.MeshStandardMaterial({ 
            color: 0xffffff, 
            metalness: 0.2, 
            roughness: 0.7 
        })
    );
}

// Game state
const state = {
    room: 'Lobby',
    seat: '—',
    turn: '—',
    trump: null,
    hand: ['AS', 'KH', 'QD', 'JC', 'TS', '9S', '8H', '7D', '6C', '5C', '4H', '3D', '2S']
};

// Card pool for 3D cards
const cardPool = [];

// Play a card animation
function playCard() {
    const card = createCardMesh();
    card.position.set(0, 0.66, 3.9);
    card.rotation.x = -0.02;
    card.rotation.y = 0.18;
    scene.add(card);
    
    const target = centre.position.clone();
    const mid = card.position.clone()
        .lerp(target, 0.6)
        .add(new THREE.Vector3(0, 0.15, 0));
    
    gsap.timeline()
        .to(card.position, {
            x: mid.x,
            y: mid.y,
            z: mid.z,
            duration: 0.35,
            ease: 'power2.out'
        })
        .to(card.position, {
            x: target.x + (Math.random() * 0.2 - 0.1),
            y: target.y + (cardPool.length * 0.002),
            z: target.z + (Math.random() * 0.2 - 0.1),
            duration: 0.25,
            ease: 'power2.inOut'
        }, '<')
        .to(card.rotation, {
            y: card.rotation.y + (Math.random() * 0.6 - 0.3),
            x: -0.01,
            duration: 0.25
        }, '<');
    
    cardPool.push(card);
}

// Update UI elements
document.getElementById('roomCode').textContent = state.room;
document.getElementById('seat').textContent = state.seat;
document.getElementById('turn').textContent = state.turn;

// Render player's hand
const handEl = document.getElementById('hand');

function renderHand() {
    handEl.innerHTML = '';
    
    state.hand.forEach((card, index) => {
        const el = document.createElement('div');
        el.className = 'card2d';
        el.textContent = card;
        el.style.opacity = 0;
        el.style.transform = 'translateY(20px) scale(0.95)';
        
        el.onclick = () => {
            // Remove card from hand
            state.hand.splice(index, 1);
            
            // Animate card removal
            gsap.to(el, {
                opacity: 0,
                y: -20,
                scale: 0.9,
                duration: 0.18,
                ease: 'power2.in',
                onComplete: () => {
                    el.remove();
                    renderHand();
                }
            });
            
            // Play 3D card animation
            playCard();
            
            // Update turn (temporary, will be handled by server)
            const nextTurn = { 'N': 'W', 'W': 'S', 'S': 'E', 'E': 'N' };
            state.turn = nextTurn[state.turn] || 'N';
            document.getElementById('turn').textContent = state.turn;
        };
        
        handEl.appendChild(el);
        
        // Animate card appearance
        gsap.to(el, {
            opacity: 1,
            y: 0,
            scale: 1,
            delay: index * 0.02,
            duration: 0.25,
            ease: 'power2.out'
        });
    });
}

// Initial render
renderHand();

// Event listeners
document.getElementById('btnDeal').onclick = () => {
    // Clear existing 3D cards
    while (cardPool.length) {
        const card = cardPool.pop();
        scene.remove(card);
        card.geometry.dispose();
    }
    
    // Shuffle and redeal
    state.hand.sort(() => Math.random() - 0.5);
    renderHand();
};

document.getElementById('btnMethod1').onclick = () => {
    state.trump = 'M1';
    document.getElementById('trump').textContent = 'M1';
};

document.getElementById('btnMethod2').onclick = () => {
    state.trump = 'M2';
    document.getElementById('trump').textContent = 'M2';
};

document.getElementById('btnAuth').onclick = () => {
    const authModal = new bootstrap.Modal(document.getElementById('authModal'));
    authModal.show();
};

document.getElementById('btnJoin').onclick = () => {
    // In a real app, this would connect to your backend
    alert('Joining game room...');
    state.room = 'Game #' + Math.floor(1000 + Math.random() * 9000);
    state.seat = ['N', 'E', 'S', 'W'][Math.floor(Math.random() * 4)];
    state.turn = 'N';
    
    document.getElementById('roomCode').textContent = state.room;
    document.getElementById('seat').textContent = state.seat;
    document.getElementById('turn').textContent = state.turn;
};

// Handle login form submission
document.getElementById('loginForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    // In a real app, this would validate with your backend
    console.log('Login attempt:', { username, password });
    
    // Close modal
    const authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
    authModal.hide();
    
    // Show success message
    alert('Login successful! Welcome back, ' + username);
});

// Toggle between login and register forms
document.getElementById('showRegister')?.addEventListener('click', (e) => {
    e.preventDefault();
    alert('Registration form would appear here');
});

// Handle window resize
window.addEventListener('resize', () => {
    resize();
});
</script>
@endpush
