import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
// Init
const container = document.getElementById('canvas-container');
const scene = new THREE.Scene();
scene.background = new THREE.Color(0x222222); // Dark studio background
// Camera
const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 100);
camera.position.set(0, 1.5, 4);
// Renderer
const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setPixelRatio(window.devicePixelRatio);
renderer.shadowMap.enabled = true;
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.0;
container.appendChild(renderer.domElement);
// Controls
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;
controls.dampingFactor = 0.05;
controls.minDistance = 2;
controls.maxDistance = 10;
// Lighting (High Definition Studio Setup)
const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
scene.add(ambientLight);
const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
dirLight.position.set(5, 5, 5);
dirLight.castShadow = true;
dirLight.shadow.mapSize.width = 1024;
dirLight.shadow.mapSize.height = 1024;
scene.add(dirLight);
const backLight = new THREE.DirectionalLight(0xaaccff, 0.5);
backLight.position.set(-5, 0, -5);
scene.add(backLight);
// Group to hold current model
const modelGroup = new THREE.Group();
scene.add(modelGroup);
let currentMesh = null;
let currentMaterial = null;
// Texture Loader
const textureLoader = new THREE.TextureLoader();
const placeholderTexture = textureLoader.load('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='); // 1x1 Grey pixel
// --- Geometry Generators ---
// 1. MUG Generator
function createMug() {
    const group = new THREE.Group();
    // Body (Cylinder)
    const bodyGeo = new THREE.CylinderGeometry(0.4, 0.4, 1.0, 64);
    // Adjust UVs for better wrapping if needed, but default cylinder UVs usually work for simple wrap.
    // However, for a mug, the texture usually only goes on the outside.
    // We will use 2 materials: [0] = Side (Texture), [1] = Top (White), [2] = Bottom (White)
    // CylinderGeometry materials index: 0: radial side, 1: top, 2: bottom
    const matSide = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        roughness: 0.2,
        metalness: 0.1, // Ceramic
        map: placeholderTexture
    });
    const matCap = new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.2 });
    // For cylinder, we need an array of materials to map correctly
    const bodyMesh = new THREE.Mesh(bodyGeo, [matSide, matCap, matCap]);
    bodyMesh.castShadow = true;
    bodyMesh.receiveShadow = true;
    group.add(bodyMesh);
    // Handle (Torus)
    const handleGeo = new THREE.TorusGeometry(0.25, 0.05, 16, 32, Math.PI);
    const handleMat = new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: 0.2 });
    const handle = new THREE.Mesh(handleGeo, handleMat);
    handle.position.set(0.4, 0.1, 0); // Side of cylinder
    handle.rotation.z = -Math.PI / 2;
    handle.castShadow = true;
    group.add(handle);
    // Make 'matSide' globally accessible to update texture
    currentMaterial = matSide;
    return group;
}
// 2. BOTTLE Generator
function createBottle() {
    const group = new THREE.Group();
    // Body
    const bodyHeight = 1.2;
    const bodyRadius = 0.35;
    const bodyGeo = new THREE.CylinderGeometry(bodyRadius, bodyRadius, bodyHeight, 64);
    const matSide = new THREE.MeshStandardMaterial({
        color: 0xdddddd, // Aluminum base color
        roughness: 0.3,
        metalness: 0.8, // Aluminum
        map: placeholderTexture
    });
    const matCap = new THREE.MeshStandardMaterial({ color: 0xcccccc, roughness: 0.3, metalness: 0.8 });
    const bodyMesh = new THREE.Mesh(bodyGeo, [matSide, matCap, matCap]);
    bodyMesh.castShadow = true;
    bodyMesh.receiveShadow = true;
    bodyMesh.position.y = -0.2; // Shift down a bit
    group.add(bodyMesh);
    // Neck
    const neckGeo = new THREE.CylinderGeometry(0.1, bodyRadius, 0.3, 32);
    const neckMesh = new THREE.Mesh(neckGeo, matCap);
    neckMesh.position.y = (bodyHeight / 2) - 0.2 + 0.15; // Stack on top
    neckMesh.castShadow = true;
    group.add(neckMesh);
    // Cap
    const capGeo = new THREE.CylinderGeometry(0.12, 0.12, 0.15, 32);
    const capMat = new THREE.MeshStandardMaterial({ color: 0x333333, roughness: 0.8 }); // Black plastic cap
    const capMesh = new THREE.Mesh(capGeo, capMat);
    capMesh.position.y = (bodyHeight / 2) - 0.2 + 0.3 + 0.075;
    capMesh.castShadow = true;
    group.add(capMesh);
    currentMaterial = matSide;
    return group;
}
// --- Logic ---
function showModel(type) {
    // Clear old model
    while (modelGroup.children.length > 0) {
        modelGroup.remove(modelGroup.children[0]);
    }
    if (type === 'mug') {
        const mug = createMug();
        modelGroup.add(mug);
        // Reset rotation
        modelGroup.rotation.set(0, 0, 0);
    } else if (type === 'bottle') {
        const bottle = createBottle();
        modelGroup.add(bottle);
        modelGroup.rotation.set(0, 0, 0);
    }
    // Re-apply current user texture if exists
    if (userTexture) {
        updateTexture(userTexture);
    }
}
// Global variable to store uploaded texture
let userTexture = null;
function updateTexture(texture) {
    if (currentMaterial) {
        currentMaterial.map = texture;
        currentMaterial.needsUpdate = true;
    }
    userTexture = texture;
}
// File Input Event
const fileInput = document.getElementById('image-upload');
fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                const texture = new THREE.Texture(img);
                texture.colorSpace = THREE.SRGBColorSpace;
                // Fix wrapping logic so image covers "front" properly
                texture.center.set(0.5, 0.5); // Center rotation/scaling
                texture.needsUpdate = true;
                updateTexture(texture);
            };
        };
        reader.readAsDataURL(file);
    }
});
// Expose switch function to window for HTML buttons
window.switchModel = showModel;
// Init Default
showModel('mug');
// Animation Loop
function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
}
animate();
// Resize Handler
window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
});
