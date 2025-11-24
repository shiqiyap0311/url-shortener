<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli("localhost", "root", "", "url_shortener");

// If redirected back with ?short=
$shortUrl = isset($_GET["short"]) ? $_GET["short"] : "";
$message = isset($_GET["msg"]) ? $_GET["msg"] : "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $long_url = trim($_POST["long_url"]);

    // Validate URL
    if (!filter_var($long_url, FILTER_VALIDATE_URL)) {
        // Redirect back with error
        header("Location: index.php?msg=" . urlencode("❌ Invalid URL format!"));
        exit;
    }

    // Generate short code
    $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

    // Insert into DB
    $stmt = $mysqli->prepare("INSERT INTO urls (code, long_url) VALUES (?, ?)");
    $stmt->bind_param("ss", $code, $long_url);
    $stmt->execute();

    $shortUrl = "http://localhost/url-shortener/redirect.php?c=" . $code;

    // Redirect to keep result after refresh
    header("Location: index.php?short=" . urlencode($shortUrl) . "&msg=" . urlencode("✅ Short URL generated successfully!"));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>URL Shortener</title>

    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 30px 40px;
            width: 420px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 15px;
            transition: 0.2s;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }

        button {
            width: 100%;
            background: #007bff;
            border: none;
            padding: 12px;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #0056b3;
        }

        .loading {
            display: none;
            margin-top: 10px;
            color: #007bff;
            font-size: 14px;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
            display: block;
        }

        .alert-success {
            background: #e8ffe8;
            border-left: 4px solid #2ecc71;
        }

        .alert-error {
            background: #ffe8e8;
            border-left: 4px solid #e74c3c;
        }

        .short-card {
            margin-top: 25px;
            padding: 15px;
            background: #eef5ff;
            border-left: 4px solid #007bff;
            border-radius: 8px;
            text-align: left;
        }

        .copy-btn {
            margin-top: 10px;
            background: #28a745;
            padding: 8px 12px;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            border: none;
            transition: 0.2s;
        }

        .copy-btn.copied {
            background: #1e7e34 !important;
        }
    </style>

    <script>
        function validateURL() {
            const url = document.querySelector("input[name='long_url']").value;
            const pattern = /^(http|https):\/\/[^ "]+$/;

            if (!pattern.test(url)) {
                alert("❌ Please enter a valid URL starting with http:// or https://");
                return false;
            }

            document.getElementById("loading").style.display = "block";
            return true;
        }

        function copyToClipboard() {
            const text = document.getElementById("shortLink").innerText;
            navigator.clipboard.writeText(text);

            const btn = document.getElementById("copyBtn");
            btn.innerText = "Copied!";
            btn.classList.add("copied");

            setTimeout(() => {
                btn.innerText = "Copy";
                btn.classList.remove("copied");
            }, 1500);
        }
    </script>

</head>

<body>

<div class="container">
    <h2>URL Shortener</h2>

    <?php if ($message): ?>
        <div class="alert <?= strpos($message, '❌') !== false ? 'alert-error' : 'alert-success' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" onsubmit="return validateURL();">
        <input type="text" name="long_url"
               value="https://t4jcxvyb4k3.sg.larksuite.com/docx/HBtCdxW0WoUhgxxQplgl0cSDg8d" required>

        <button type="submit">Shorten URL</button>

        <div id="loading" class="loading">⏳ Shortening...</div>
    </form>

    <?php if ($shortUrl): ?>
        <div class="short-card">
            <p><strong>Your Short URL:</strong></p>
            <a id="shortLink" href="<?= $shortUrl ?>" target="_blank"><?= $shortUrl ?></a>
            <br>
            <button id="copyBtn" class="copy-btn" onclick="copyToClipboard()">Copy</button>
        </div>
    <?php endif; ?>
</div>

</body>
</html>