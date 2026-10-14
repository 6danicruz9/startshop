<?php
declare(strict_types=1);
$msg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['acc_action'] ?? '';
    if ($act === 'login') {
        $r = Auth::login($pdo, (string)$_POST['email'], (string)$_POST['password']);
        $msg = $r['ok'] ? 'Sesión iniciada' : $r['error'];
    } elseif ($act === 'register') {
        $r = Auth::register($pdo, (string)$_POST['email'], (string)$_POST['password'], (string)$_POST['full_name']);
        $msg = $r['ok'] ? 'Cuenta registrada' : $r['error'];
    } elseif ($act === 'logout') {
        session_destroy(); header("Location: ?route=account"); exit;
    }
}
$user = Auth::user($pdo);
?>
<main class="ss-Container">
  <?php if ($msg): ?><div style="padding:12px;background:var(--bg-subtle);margin-bottom:20px;"><?= e($msg) ?></div><?php endif; ?>
  <?php if (!$user): ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:2rem;">
      <form method="POST" style="background:var(--bg-elevated);padding:24px;border:1px solid var(--border-subtle);display:grid;gap:12px;">
        <h2 style="font-family:var(--font-display);margin:0;">Iniciar Sesión</h2>
        <input type="hidden" name="acc_action" value="login">
        <input type="email" name="email" required placeholder="Email" class="ss-SearchInput">
        <input type="password" name="password" required placeholder="Contraseña" class="ss-SearchInput">
        <button type="submit" class="ss-Btn">Entrar</button>
      </form>
      <form method="POST" style="background:var(--bg-elevated);padding:24px;border:1px solid var(--border-subtle);display:grid;gap:12px;">
        <h2 style="font-family:var(--font-display);margin:0;">Crear Cuenta</h2>
        <input type="hidden" name="acc_action" value="register">
        <input type="text" name="full_name" required placeholder="Nombre completo" class="ss-SearchInput">
        <input type="email" name="email" required placeholder="Email" class="ss-SearchInput">
        <input type="password" name="password" required placeholder="Contraseña" class="ss-SearchInput">
        <button type="submit" class="ss-Btn">Registrarme</button>
      </form>
    </div>
  <?php else: ?>
    <h1 style="font-family:var(--font-display);">Hola, <?= e($user['full_name']) ?></h1>
    <form method="POST"><input type="hidden" name="acc_action" value="logout"><button type="submit" class="ss-Btn ss-Btn--outline">Cerrar Sesión</button></form>
  <?php endif; ?>
</main>