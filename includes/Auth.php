<?php
declare(strict_types=1);
class Auth {
    public static function user(PDO $pdo): ?array {
        if (empty($_SESSION['user_id'])) return null;
        $stmt = $pdo->prepare("SELECT id, email, full_name, phone, role FROM ss_users WHERE id = ?");
        $stmt->execute([(int)$_SESSION['user_id']]);
        return $stmt->fetch() ?: null;
    }
    public static function login(PDO $pdo, string $email, string $password): array {
        $stmt = $pdo->prepare("SELECT * FROM ss_users WHERE email = ?");
        $stmt->execute([strtolower(trim($email))]);
        $user = $stmt->fetch();
        if (!$user || empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            return ['ok' => false, 'error' => 'Credenciales incorrectas.'];
        }
        $_SESSION['user_id'] = (int)$user['id'];
        return ['ok' => true, 'user' => $user];
    }
    public static function register(PDO $pdo, string $email, string $password, string $fullName, ?string $phone = null): array {
        $email = strtolower(trim($email));
        $check = $pdo->prepare("SELECT id FROM ss_users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) return ['ok' => false, 'error' => 'El email ya está registrado.'];
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO ss_users (email, password_hash, full_name, phone, role, email_verified_at) VALUES (?, ?, ?, ?, 'customer', NOW())")
            ->execute([$email, $hash, $fullName, $phone]);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        return ['ok' => true];
    }
    public static function oauthSimulateOrCallback(PDO $pdo, string $provider, string $email, string $name, string $uid): void {
        $stmt = $pdo->prepare("SELECT id FROM ss_users WHERE email = ?");
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u) {
            $_SESSION['user_id'] = (int)$u['id'];
        } else {
            $pdo->prepare("INSERT INTO ss_users (email, full_name, role, oauth_provider, oauth_uid) VALUES (?, ?, 'customer', ?, ?)")
                ->execute([$email, $name, $provider, $uid]);
            $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        }
    }
}