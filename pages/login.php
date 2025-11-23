<?php
include_once '../includes/config.php';
include_once '../includes/db.php';
include_once '../includes/functions.php';

session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "pages/home.php");
    exit();
}

$error_message = '';
$user_error = '';
$pass_error = '';
$login_success = false;

// Login Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectDB();

    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    if (empty($username)) {
        $user_error = "Username is required.";
    }
    
    if (empty($password)) {
        $pass_error = "Password is required.";
    }

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $login_success = true;
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      background: var(--container-bg);
      padding: 20px 50px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      width: 320px;
      position: relative;
    }
    h1 {
      text-align: center;
      margin-bottom: 10px;
      margin-top: 0px;
    }
    #logoHolder {
      text-align: center;
      margin-bottom: 55px;
    }
    #logo {
      margin-bottom: 0px;
      padding-bottom: 0px;
    }
    #catchphrase {
      font-size: 12px;
      color: #555;
      margin-top: 0px;
      padding-top: 0px;
      margin-bottom: 25px;
      font-style: italic;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input {
      width: 93%;
      padding: 10px;
      margin: 8px 0px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border: none;
      background: #4CAF50;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
    }
    button:hover {
      background: #45a049;
    }
    .switch {
      text-align: center;
      margin-top: 10px;
      margin-bottom: 20px;
      font-size: 14px;
    }
    .switch a {
      color: #007BFF;
      cursor: pointer;
      text-decoration: none;
    }
    .switch a:hover {
      text-decoration: underline;
    }
    .error {
      color: red;
      margin: -5px 0 8px;
    }
    #togglePassword {
      font-size: 12px;
      position: absolute; 
      right: 10px; 
      top: 50%; 
      transform: translateY(-50%); 
      cursor: pointer; 
      user-select: none; 
      color: #333;
    }
    .back-button {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 1.5rem;
        color: var(--dark-color);
        text-decoration: none;
    }
  </style>
</head>
<body>

<div id="success-container" class="floating-success">
    Login Successful! Redirecting...
</div>

<div class="container">
  <a href="<?php echo BASE_URL; ?>index.php" class="back-button"><i class="fas fa-home"></i></a>
  <form id="loginForm" method="POST" action="login.php">
      <div id="logoHolder">
        <img id="logo" src="<?php echo BASE_URL; ?>assets/logoo.png" alt="NoteCore App Logo" width="160px" height="160px">
        <h1>NoteCore</h1>
        <p id="catchphrase">You only have to think once, NoteCore remembers for you</p>
      </div>
    
    <h2>Login</h2>

    <?php if (!empty($error_message)): ?>
        <div id="error-message" class="error" style="text-align: center;"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <label>Username</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required
      pattern="^[a-zA-Z0-9_]{3,16}$"
      title="3-16 characters. Letters, numbers, and underscores only.">
    <div id="userError" class="error"><?php echo $user_error; ?></div>

    <label>Password</label>
    <div style="position: relative; width: 100%;">
      <input type="password" id="password" name="password" placeholder="Enter password" required>
      <i id="togglePassword" class="fa-solid fa-eye"></i>
    </div>
    <div id="passError" class="error"><?php echo $pass_error; ?></div>

    <br>
    <button type="submit">Login</button>
    <div class="switch">Don't have an account yet? <a href="<?php echo BASE_URL; ?>pages/register.php">Register here</a></div>
  </form>
</div>

<script>
  const password = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');

  // Toggle password visibility
  togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.classList.toggle('fa-eye');
    togglePassword.classList.toggle('fa-eye-slash');
  });

  <?php if ($login_success): ?>
    const successContainer = document.getElementById('success-container');
    successContainer.style.display = 'block';
    setTimeout(function() {
        window.location.href = "<?php echo BASE_URL; ?>pages/home.php";
    }, 2000);
  <?php endif; ?>

  <?php if (!empty($error_message)): ?>
    const errorMessage = document.getElementById('error-message');
    setTimeout(function() {
        errorMessage.classList.add('fade-out');
    }, 3000);
  <?php endif; ?>
</script>

</body>
</html>