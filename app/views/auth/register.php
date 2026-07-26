<?php if (!empty($errors)): ?>

<div class="alert danger">

    <ul>

        <?php foreach($errors as $error): ?>

            <li><?= htmlspecialchars($error) ?></li>

        <?php endforeach; ?>

    </ul>

</div>

<?php endif; ?>


<?php
$title = "Create Account | Plantliotecha";
?>


<!DOCTYPE html>
<html lang="en">
    
    <link rel="stylesheet" href="assets/css/app.css">

<section class="login-page">

    <div class="login-card">

        <div class="login-header">

            <div class="logo">
                🌿
            </div>

            <h1>Create Your Account</h1>

            <p>
                Start building your personal plant library.
            </p>

        </div>

        <?php if (!empty($errors)): ?>

            <div class="alert danger">

                <ul>

                    <?php foreach ($errors as $error): ?>

                        <li><?= htmlspecialchars($error) ?></li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>

        <form action="" method="POST">

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    maxlength="30"
                    placeholder="Choose a username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    required>

            </div>

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    autocomplete="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required>

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Minimum 8 characters"
                    required>

            </div>

            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    autocomplete="new-password"
                    placeholder="Re-enter your password"
                    required>

            </div>

            <div class="terms-row">

                <label class="checkbox">

                    <input
                        type="checkbox"
                        id="agree"
                        name="agree"
                        required>

                    <span>
                        I agree to the
                        <a href="#">Terms of Service</a>
                        and
                        <a href="#">Privacy Policy</a>.
                    </span>

                </label>

            </div>

            <button
                type="submit"
                class="button button-primary">

                Create Account

            </button>

        </form>

        <div class="divider">

            <span>or</span>

        </div>

        <div class="register-link">

            Already have an account?

            <a href="/Plantliotecha/public/login.php">

                Sign In

            </a>

        </div>

    </div>

</section>

</html>