<?php

include 'partials/header.php';
include 'partials/navbar.php';
include 'partials/hero.php';

if(isPostRequest()) {
    $email = getPostData('email');
    $password = getPostData('password');
    $user = new User();
    
    if($user->login($email, $password)) {
        redirect('admin.php');
    } else {
        echo 'Login Failed';
    }
}

?>

<main class="container my-5">
    <h2 class="text-center mb-4">Login</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address *</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password *</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required
                    >
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center">
                Don't have an account? <a href="register.html">Register here</a>.
            </p>
        </div>
    </div>
</main>

<?php include 'partials/footer.php';?>