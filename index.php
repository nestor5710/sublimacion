<?php require 'auth_logic.php'; ?>
<?php if (isset($_SESSION['user_id'])) {
    header("Location: generator.php");
    exit;
} ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Mockup Generator - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body
    class="bg-gradient-to-br from-gray-900 to-gray-800 h-screen flex items-center justify-center text-white overflow-hidden">
    <div class="glass-panel p-8 rounded-2xl w-full max-w-md mx-4 animate-fade-in-up">
        <h1
            class="text-3xl font-bold text-center mb-2 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
            Mockup 3D
        </h1>
        <p class="text-center text-gray-400 mb-6 text-sm">Crea mockups realistas de tazas y botellas</p>
        <?php if (!empty($message)): ?>
            <div
                class="<?php echo $messageType === 'success' ? 'bg-green-500/20 border-green-500 text-green-300' : 'bg-red-500/20 border-red-500 text-red-300'; ?> border px-4 py-2 rounded mb-4 text-sm text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <!-- Tabs -->
        <div class="flex mb-6 border-b border-gray-700">
            <button id="tab-login"
                class="w-1/2 py-2 text-center text-blue-400 border-b-2 border-blue-400 font-medium transition-colors"
                onclick="switchTab('login')">Ingresar</button>
            <button id="tab-register"
                class="w-1/2 py-2 text-center text-gray-500 hover:text-gray-300 font-medium transition-colors"
                onclick="switchTab('register')">Registrarse</button>
        </div>
        <!-- Login Form -->
        <form id="form-login" method="POST" action="" class="space-y-4">
            <input type="hidden" name="action" value="login">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Usuario</label>
                <input type="text" name="username" required
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-600"
                    placeholder="Tu usuario">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 transition-colors placeholder-gray-600"
                    placeholder="••••••••">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 rounded-lg transition-all transform hover:scale-[1.02] shadow-lg shadow-blue-600/30">
                Iniciar Sesión
            </button>
        </form>
        <!-- Register Form -->
        <form id="form-register" method="POST" action="" class="space-y-4 hidden">
            <input type="hidden" name="action" value="register">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Nuevo Usuario</label>
                <input type="text" name="username" required
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500 transition-colors placeholder-gray-600"
                    placeholder="Elige un usuario">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-purple-500 transition-colors placeholder-gray-600"
                    placeholder="••••••••">
            </div>
            <button type="submit"
                class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-2 rounded-lg transition-all transform hover:scale-[1.02] shadow-lg shadow-purple-600/30">
                Registrarse
            </button>
        </form>
    </div>
    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('form-login');
            const registerForm = document.getElementById('form-register');
            const loginTab = document.getElementById('tab-login');
            const registerTab = document.getElementById('tab-register');
            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                loginTab.classList.add('text-blue-400', 'border-blue-400');
                loginTab.classList.remove('text-gray-500', 'border-transparent');
                registerTab.classList.add('text-gray-500', 'border-transparent');
                registerTab.classList.remove('text-purple-400', 'border-purple-400');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                loginTab.classList.remove('text-blue-400', 'border-blue-400');
                loginTab.classList.add('text-gray-500', 'border-transparent');
                registerTab.classList.remove('text-gray-500', 'border-transparent');
                registerTab.classList.add('text-purple-400', 'border-purple-400');
            }
        }
    </script>
</body>
</html>
