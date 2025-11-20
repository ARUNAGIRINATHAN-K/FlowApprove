<?php
session_start();
require_once '../src/config.php';
require_once '../src/modules/auth/User.php';
include_once '../templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->login($email, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>

<h2>Login</h2>

<?php if (isset($error)) : ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form action="login.php" method="post">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php include_once '../templates/footer.php'; ?>
