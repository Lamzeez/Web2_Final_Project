<?php
include_once '../includes/config.php';
include_once '../includes/db.php';
include_once '../includes/functions.php';

session_start();

$message = '';
$message_type = '';
$user_error = '';
$pass_error = '';
$email_error = '';
$registration_success = false;

// Registration Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = connectDB();

    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username)) {
        $user_error = "Username is required.";
    }
    if (empty($email)) {
        $email_error = "Email is required.";
    }
    if (empty($password)) {
        $pass_error = "Password is required.";
    }
    if (empty($confirm_password)) {
        $pass_error = "Please confirm your password.";
    }


    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = "Invalid email format.";
        } elseif ($password !== $confirm_password) {
            $pass_error = "Passwords do not match.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows > 0) {
                $message = "Username or email already taken.";
                $message_type = "danger";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);
    
                if ($stmt->execute()) {
                    $registration_success = true;
                } else {
                    $message = "Error: " . $stmt->error;
                    $message_type = "danger";
                }
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
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
      background: #8edae5;
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
    .success {
        color: green;
        text-align: center;
    }
    #togglePassword, #toggleConfirmPassword {
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
    .success-prompt {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(142, 218, 229, 0.9);
        color: var(--dark-color);
        text-align: center;
        padding-top: 40%;
        border-radius: 12px;
    }
    .success-prompt h2 {
        font-size: 2rem;
    }
  </style>
</head>
<body>

<div class="container">
    <a href="<?php echo BASE_URL; ?>index.php" class="back-button"><i class="fas fa-home"></i></a>
  <form id="registerForm" method="POST" action="register.php">
      <div id="logoHolder">
        <img id="logo" src="<?php echo BASE_URL; ?>assets/logoo.png" alt="NoteCore App Logo" width="160px" height="160px">
        <h1>NoteCore</h1>
        <p id="catchphrase">You only have to think once, NoteCore remembers for you</p>
      </div>
    
    <h2>Register</h2>

    <?php if (!empty($message)): ?>
        <div class="error" style="text-align: center;"><?php echo $message; ?></div>
    <?php endif; ?>

    <label>Username</label>
    <input type="text" id="username" name="username" placeholder="Enter username" required
      pattern="^[a-zA-Z0-9_]{3,16}$"
      title="3-16 characters. Letters, numbers, and underscores only.">
    <div id="userError" class="error"><?php echo $user_error; ?></div>

    <label>Email</label>
    <input type="email" id="email" name="email" placeholder="Enter email" required
      pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
      title="Enter a valid email address.">
    <div id="emailError" class="error"><?php echo $email_error; ?></div>

    <label>Password</label>
    <div style="position: relative; width: 100%;">
      <input type="password" id="password" name="password" placeholder="Enter password" required>
      <i id="togglePassword" class="fa-solid fa-eye"></i>
    </div>
    
    <label>Confirm Password</label>
    <div style="position: relative; width: 100%;">
      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
      <i id="toggleConfirmPassword" class="fa-solid fa-eye"></i>
    </div>
    <div id="passError" class="error"><?php echo $pass_error; ?></div>

    <br>
    <button type="submit">Register</button>
    <div class="switch">Already have an account? <a href="<?php echo BASE_URL; ?>pages/login.php">Login</a></div>
  </form>
  <div class="success-prompt" id="successPrompt">
      <h2>Registration Successful!</h2>
      <p>You can now <a href="<?php echo BASE_URL; ?>pages/login.php">login</a>.</p>
  </div>
</div>

<script>
  const password = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const confirm_password = document.getElementById('confirm_password');
  const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

  //Password peek toggle
  togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.classList.toggle('fa-eye');
    togglePassword.classList.toggle('fa-eye-slash');
  });

  toggleConfirmPassword.addEventListener('click', () => {
    const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
    confirm_password.setAttribute('type', type);
    toggleConfirmPassword.classList.toggle('fa-eye');
    toggleConfirmPassword.classList.toggle('fa-eye-slash');
  });

  <?php if ($registration_success): ?>
    document.getElementById('successPrompt').style.display = 'block';
  <?php endif; ?>
</script>

</body>
</html>