<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
require_once '../config/database.php';
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$errors = [];
$success = $_SESSION['profile_success'] ?? '';
unset($_SESSION['profile_success']);
$fetch = $pdo->prepare('SELECT id, username, email, display_name, bio, profile_picture, is_public, theme, created_at FROM users WHERE id = ? LIMIT 1');
$fetch->execute([$_SESSION['user_id']]);
$profile = $fetch->fetch(PDO::FETCH_ASSOC);
if (!$profile) { session_destroy(); header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) $errors[] = 'Your session expired. Please try again.';
    $profile['display_name'] = trim($_POST['display_name'] ?? '');
    $profile['email'] = trim($_POST['email'] ?? '');
    $profile['bio'] = trim($_POST['bio'] ?? '');
    $profile['is_public'] = isset($_POST['is_public']) ? 1 : 0;
    $profile['theme'] = $_POST['theme'] ?? 'light';
    $uploadedFilename = null;
    if (!filter_var($profile['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($profile['display_name']) > 50) $errors[] = 'Display name must be 50 characters or fewer.';
    if (!in_array($profile['theme'], ['light', 'dark'], true)) $errors[] = 'Choose a valid theme.';

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload = $_FILES['profile_picture'];
        if ($upload['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Your profile picture could not be uploaded.';
        } elseif ($upload['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Profile pictures must be 2 MB or smaller.';
        } elseif (!is_uploaded_file($upload['tmp_name'])) {
            $errors[] = 'The selected profile picture is invalid.';
        } else {
            $image = @getimagesize($upload['tmp_name']);
            $extensions = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
            if ($image === false || !isset($extensions[$image[2]])) {
                $errors[] = 'Use a JPG, PNG, or WebP image for your profile picture.';
            } else {
                $uploadedFilename = bin2hex(random_bytes(16)) . '.' . $extensions[$image[2]];
            }
        }
    }
    $duplicate = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1');
    $duplicate->execute([$profile['email'], $_SESSION['user_id']]);
    if ($duplicate->fetch()) $errors[] = 'That email is already in use.';
    $current = $_POST['current_password'] ?? ''; $new = $_POST['new_password'] ?? ''; $confirm = $_POST['confirm_password'] ?? ''; $passwordHash = null;
    if ($current !== '' || $new !== '' || $confirm !== '') {
        $passwordQuery = $pdo->prepare('SELECT password FROM users WHERE id = ?'); $passwordQuery->execute([$_SESSION['user_id']]);
        if (!password_verify($current, $passwordQuery->fetchColumn())) $errors[] = 'Your current password is incorrect.';
        if (strlen($new) < 8) $errors[] = 'New passwords must be at least 8 characters.';
        if ($new !== $confirm) $errors[] = 'New passwords do not match.';
        if (!$errors) $passwordHash = password_hash($new, PASSWORD_DEFAULT);
    }
    if (!$errors && $uploadedFilename !== null) {
        $uploadDirectory = __DIR__ . '/uploads/profile-pictures';
        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
            $errors[] = 'The profile-picture folder could not be created.';
        } elseif (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadDirectory . '/' . $uploadedFilename)) {
            $errors[] = 'Your profile picture could not be saved.';
        } else {
            $profile['profile_picture'] = $uploadedFilename;
        }
    }
    if (!$errors) {
        $sql = 'UPDATE users SET display_name=?, email=?, bio=?, is_public=?, theme=?';
        $values = [$profile['display_name'] ?: null, $profile['email'], $profile['bio'] ?: null, $profile['is_public'], $profile['theme']];
        if ($uploadedFilename !== null) { $sql .= ', profile_picture=?'; $values[] = $profile['profile_picture']; }
        if ($passwordHash !== null) { $sql .= ', password=?'; $values[] = $passwordHash; }
        $sql .= ' WHERE id=?'; $values[] = $_SESSION['user_id'];
        $pdo->prepare($sql)->execute($values);
        $_SESSION['email'] = $profile['email']; $_SESSION['theme'] = $profile['theme'];
        $_SESSION['profile_success'] = 'Profile preferences saved.';
        header('Location: profile.php'); exit;
    }
}
$theme = $profile['theme'];
$initial = strtoupper(substr($profile['display_name'] ?: $profile['username'], 0, 1));
$avatarPath = !empty($profile['profile_picture']) && $profile['profile_picture'] !== 'default-avatar.png'
    ? 'uploads/profile-pictures/' . rawurlencode($profile['profile_picture'])
    : null;
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Profile & settings | Plantliotecha</title><link rel="stylesheet" href="assets/css/app.css"><link rel="stylesheet" href="assets/css/theme.css"><link rel="stylesheet" href="assets/css/profile-upload.css"></head><body data-theme="<?= htmlspecialchars($theme) ?>"><main class="container profile-page">
<p><a href="dashboard.php">&larr; Dashboard</a> &middot; <a href="plants.php">My plants</a> &middot; <a href="logout.php">Log out</a></p>
<header class="profile-header"><div class="profile-avatar" aria-hidden="true"><?php if ($avatarPath): ?><img src="<?= htmlspecialchars($avatarPath) ?>" alt=""><?php else: ?><?= htmlspecialchars($initial) ?><?php endif; ?></div><div><h1>Profile & settings</h1><p>Personalize your Plantliotecha account and privacy preferences.</p></div></header>
<?php if ($success): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert danger"><ul><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" class="profile-form" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
<section><h2>Public identity</h2><p class="section-copy">Choose what represents you in Plantliotecha.</p><div class="form-grid"><div><span class="field-label">Username</span><p class="static-value">@<?= htmlspecialchars($profile['username']) ?></p></div><div><label for="display_name">Display name</label><input id="display_name" name="display_name" maxlength="50" placeholder="Optional" value="<?= htmlspecialchars($profile['display_name'] ?? '') ?>"></div></div><label for="profile_picture">Profile picture</label><input id="profile_picture" type="file" name="profile_picture" accept="image/jpeg,image/png,image/webp"><p class="upload-help">JPG, PNG, or WebP. Maximum file size: 2 MB.</p><label for="bio">Bio</label><textarea id="bio" name="bio" maxlength="1000" rows="5" placeholder="Tell fellow plant lovers a little about yourself."><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea><label class="checkbox setting-check"><input type="checkbox" name="is_public" <?= $profile['is_public'] ? 'checked' : '' ?>><span><strong>Public profile</strong><br>Let future community features show your profile and shared collection.</span></label></section>
<section><h2>Appearance</h2><p class="section-copy">More themes can join this collection later. Your choice is saved to your account.</p><div class="theme-options"><label class="theme-choice"><input type="radio" name="theme" value="light" <?= $theme === 'light' ? 'checked' : '' ?>><span class="theme-preview light-preview"><b>Light</b><small>Fresh and calm</small></span></label><label class="theme-choice"><input type="radio" name="theme" value="dark" <?= $theme === 'dark' ? 'checked' : '' ?>><span class="theme-preview dark-preview"><b>Dark</b><small>Easy on the eyes</small></span></label></div></section>
<section><h2>Account & security</h2><p class="section-copy">Member since <?= htmlspecialchars(date('F Y', strtotime($profile['created_at']))) ?>.</p><label for="email">Email address</label><input id="email" type="email" name="email" required value="<?= htmlspecialchars($profile['email']) ?>"><div class="form-grid password-grid"><div><label for="current_password">Current password</label><input id="current_password" type="password" name="current_password" autocomplete="current-password"></div><div><label for="new_password">New password</label><input id="new_password" type="password" name="new_password" autocomplete="new-password" placeholder="At least 8 characters"></div><div><label for="confirm_password">Confirm new password</label><input id="confirm_password" type="password" name="confirm_password" autocomplete="new-password"></div></div></section><p class="profile-actions"><button class="button" type="submit">Save profile</button><a class="button secondary" href="dashboard.php">Cancel</a></p></form></main></body></html>
