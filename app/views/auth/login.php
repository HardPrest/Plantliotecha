<?php
$title = "Login | Plantliotecha";
?>

<section class="login-page">

    <div class="login-card">

        <div class="login-header">

            <div class="logo">

                🌿

            </div>

            <h1>Welcome Back</h1>

            <p>
                Continue growing your collection.
            </p>

        </div>

        <?php if (!empty($error)): ?>

            <div class="alert danger">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>

        <form action="/Plantliotecha/public/login" method="POST">

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
                    required>

            </div>

            <div class="form-group">

                <div class="label-row">

                    <label for="password">
                        Password
                    </label>

                    <a href="#" class="forgot-link">

                        Forgot Password?

                    </a>

                </div>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required>

            </div>

            <div class="remember-row">

                <label class="checkbox">

                    <input
                        type="checkbox"
                        name="remember">

                    <span>Remember Me</span>

                </label>

            </div>

            <button
                type="submit"
                class="button button-primary">

                Sign In

            </button>

        </form>

        <div class="divider">

            <span>or</span>

        </div>

        <div class="register-link">

            Don't have an account?

            <a href="/Plantliotecha/public/register">

                Create One

            </a>

        </div>

    </div>

</section>