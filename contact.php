<?php
/**
 * Contact form handler
 * --------------------
 * IMPORTANT:
 * - This only works if your hosting supports PHP mail().
 * - Many hosts (like GoDaddy, Hostinger, Namecheap, Bluehost) DO support it.
 * - Local servers (XAMPP, WAMP, localhost) DO NOT.
 */

$to_email = "dbrown612345@gmail.com"; // Your inbox

header('Content-Type: text/html; charset=UTF-8');

function render_page($title, $message) {
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>{$title}</title>";
    echo "<link href='https://fonts.googleapis.com/css2?family=Syne:wght@700&family=IBM+Plex+Sans:wght@400;500&display=swap' rel='stylesheet'>";
    echo "<style>
        body{background:#0a0710;color:#f1ecff;font-family:'IBM Plex Sans',sans-serif;
             display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;text-align:center;padding:24px;}
        h1{font-family:'Syne',sans-serif;color:#c77dff;font-size:28px;}
        a{color:#c77dff;}
    </style></head><body><div>";
    echo "<h1>{$title}</h1><p>{$message}</p>";
    echo "<p><a href='index.html'>&larr; Back to site</a></p>";
    echo "</div></body></html>";
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html#contact');
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    render_page("Something's missing", "Please fill in all fields with a valid email.");
    exit;
}

// Prevent header injection
$name_clean = str_replace(["\r", "\n"], '', $name);

$subject = "New message from {$name_clean} via your website";
$body    = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";

$headers = "From: noreply@yourdomain.com\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($to_email, $subject, $body, $headers);

if ($sent) {
    render_page("Message sent", "Thanks, {$name}! We'll get back to you soon.");
} else {
    render_page("Couldn't send message", "Something went wrong. Please email us directly instead.");
}
?>
