<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Mockups 3D</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="importmap">
      {
        "imports": {
          "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
          "three/addons/": "https://unpkg.com/three@0.160.0/examples/jsm/"
        }
      }
    </script>
    <style>
        body {
            margin: 0;
            overflow: hidden;
            background-color: #1a1a1a;
        }
        #canvas-container {
            width: 100vw;
            height: 100vh;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
        }
        .ui-panel {
            z-index: 10;
            background: rgba(30, 30, 30, 0.8);
            backdrop-filter: blur(8px);
        }
    </style>
</head>
<body class="text-white">
    <!-- Navbar -->
    <nav class="absolute top-0 w-full p-4 flex justify-between items-center ui-panel border-b border-gray-700">
        <div class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
            Mockup 3D Generator
        </div>
        <div class="flex items-center gap-4">
            <span class="text-gray-300 text-sm">Hola,
                <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
            <a href="index.php"
                class="bg-red-500/80 hover:bg-red-500 text-white px-3 py-1 rounded text-sm transition-colors">Salir</a>
        </div>
    </nav>
    <!-- Controls Sidebar -->
    <div class="absolute top-20 left-4 w-64 p-4 rounded-xl ui-panel border border-gray-700 shadow-xl space-y-6">
        <!-- Model Selector -->
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Seleccionar
                Modelo</label>
            <div class="flex bg-gray-800 p-1 rounded-lg">
                <button id="btn-mug"
                    class="flex-1 py-2 text-sm rounded-md bg-blue-600 text-white shadow-lg transition-all">Taza</button>
                <button id="btn-bottle"
                    class="flex-1 py-2 text-sm rounded-md text-gray-400 hover:text-white transition-all">Botella</button>
            </div>
        </div>
        <!-- File Upload -->
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Subir Diseño</label>
            <div class="relative">
                <input type="file" id="image-upload" accept="image/png, image/jpeg" class="hidden">
                <label for="image-upload"
                    class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-gray-800 transition-all">
                    <svg class="w-8 h-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span class="text-xs text-gray-400">Click para subir (PNG/JPG)</span>
                </label>
            </div>
        </div>
        <!-- Instructions -->
        <div class="text-xs text-gray-500 border-t border-gray-700 pt-4">
            <p>• Usa un diseño rectangular para mejor ajuste.</p>
            <p>• Arrastra para rotar con <b>Click Izquierdo</b>.</p>
            <p>• Zoom con <b>Rueda del Ratón</b>.</p>
        </div>
    </div>
    <!-- 3D Canvas -->
    <div id="canvas-container"></div>
    <!-- Application Script -->
    <script type="module" src="app3d.js"></script>
    <script>
        // Simple tabs logic for the buttons
        const btnMug = document.getElementById('btn-mug');
        const btnBottle = document.getElementById('btn-bottle');
        function setActiveButton(active) {
            if (active === 'mug') {
                btnMug.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
                btnMug.classList.remove('text-gray-400');
                btnBottle.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
                btnBottle.classList.add('text-gray-400');
            } else {
                btnBottle.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
                btnBottle.classList.remove('text-gray-400');
                btnMug.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
                btnMug.classList.add('text-gray-400');
            }
        }
        btnMug.addEventListener('click', () => { setActiveButton('mug'); window.switchModel('mug'); });
        btnBottle.addEventListener('click', () => { setActiveButton('bottle'); window.switchModel('bottle'); });
    </script>
</body>
</html>
