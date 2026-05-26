<?php
include("../includes/header.php");
include("../includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vetëm useri i kyçur mund të aksesojë
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: /PerfumeStore_20/login.php");
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../vendor/autoload.php";

echo '<link rel="stylesheet" href="../assets/css/contact.css">';
echo '<link rel="stylesheet" href="/PerfumeStore_20/assets/css/style.css">';

include("../includes/navbar.php");

$success = "";
$error   = "";
$errors  = [];

$name    = "";
$email   = "";
$subject = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name    = trim($_POST["name"]    ?? "");
    $email   = trim($_POST["email"]   ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // Validim
    if ($name === "") {
        $errors[] = "Name is required.";
    }
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if ($subject === "") {
        $errors[] = "Subject is required.";
    }
    if (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters.";
    }

    if (empty($errors)) {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host       = "smtp.gmail.com";
            $mail->SMTPAuth   = true;
            $mail->Username   = "arjanitalestrani15@gmail.com";
            $mail->Password   = "ntce uxqy cbgq cebf";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($email, htmlspecialchars($name));
            $mail->addAddress("arjanitalestrani15@gmail.com", "Maison De Parfum");
            $mail->addReplyTo($email, htmlspecialchars($name));

            $mail->isHTML(true);
            $mail->Subject = "Contact Form: " . htmlspecialchars($subject);

            $safeName    = htmlspecialchars($name,    ENT_QUOTES, "UTF-8");
            $safeEmail   = htmlspecialchars($email,   ENT_QUOTES, "UTF-8");
            $safeSubject = htmlspecialchars($subject, ENT_QUOTES, "UTF-8");
            $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, "UTF-8"));

            $mail->Body = "
                <div style='max-width:600px;margin:auto;padding:30px;font-family:Arial,sans-serif;border:1px solid #d4af37;border-radius:12px;'>
                    <h2 style='color:#d4af37;'>New Contact Message</h2>
                    <p><strong>Name:</strong> $safeName</p>
                    <p><strong>Email:</strong> $safeEmail</p>
                    <p><strong>Subject:</strong> $safeSubject</p>
                    <hr style='border:0.5px solid #d4af37;margin:20px 0;'>
                    <p><strong>Message:</strong></p>
                    <p>$safeMessage</p>
                </div>
            ";

            $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

            $mail->send();
            $success = "Your message has been sent successfully. We will get back to you soon!";

            // Pastro fushat pas dërgimit
            $name = $email = $subject = $message = "";

        } catch (Exception $e) {
            $error = "Message could not be sent. Please try again later.";
        }
    }
}
?>

<main class="contact-page">
    <div class="contact-container">

        <div class="contact-info">
            <h1>Contact Us</h1>
            <p>Have a question or want to know more about our fragrances? Send us a message and we'll get back to you as soon as possible.</p>

            <div class="contact-detail">
                <span class="contact-icon">📍</span>
                <span>Tirana East Gate (TEG), Tirana-Elbasan Highway</span>
            </div>
            <div class="contact-detail">
                <span class="contact-icon">📞</span>
                <span>+355 69 253 6666</span>
            </div>
            <div class="contact-detail">
                <span class="contact-icon">✉️</span>
                <span>parfum@gmail.com</span>
            </div>
            <div class="contact-detail">
                <span class="contact-icon">🕐</span>
                <span>Everyday 10:00 – 20:00</span>
            </div>
        </div>

        <div class="contact-form-box">
            <h2>Send a Message</h2>

            <?php if ($success !== ""): ?>
                <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php foreach ($errors as $e): ?>
                <div class="alert-error"><?php echo htmlspecialchars($e); ?></div>
            <?php endforeach; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text"
                           id="name"
                           name="name"
                           placeholder="Your full name"
                           value="<?php echo htmlspecialchars($name); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           placeholder="your@email.com"
                           value="<?php echo htmlspecialchars($email); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text"
                           id="subject"
                           name="subject"
                           placeholder="What is this about?"
                           value="<?php echo htmlspecialchars($subject); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message"
                              name="message"
                              placeholder="Write your message here..."
                              rows="6"
                              required><?php echo htmlspecialchars($message); ?></textarea>
                </div>

                <button type="submit" class="contact-btn">Send Message</button>
            </form>
        </div>

    </div>
</main>

<?php include("../includes/footer.php"); ?>