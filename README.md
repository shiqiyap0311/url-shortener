# URL Shortener (PHP + MySQL)

A simple and beautiful URL Shortener web app built using:

- PHP (Backend)
- MySQL (Database)
- HTML + CSS + JavaScript (Frontend)
- No frameworks required

This project creates short URLs like:
Input: https://t4jcxvyb4k3.sg.larksuite.com/docx/HBtCdxW0WoUhgxxQplgl0cSDg8d
Output: http://localhost/url-shortener/redirect.php?c=Ab12Xy

---

## 🚀 Features

### ✔ Shorten any long URL  
Generates a 6-character short code and stores it in MySQL.

### ✔ Beautiful UI  
Clean, modern interface with card-style output.

### ✔ Copy-to-Clipboard  
One-click “Copy” → button changes to **Copied!**

### ✔ Auto URL Validation  
Prevents invalid input using JavaScript & PHP validation.

### ✔ Persistent URL after Refresh  
Short link remains visible after page reload using URL parameters.

### ✔ Loading Animation  
“Shortening…” message while processing.

### ✔ Uses Redirect Page  
Visiting short link automatically redirects to original URL.

---

## 📁 Project Structure
url-shortener/
│── index.php         # Main UI + logic
│── redirect.php      # Handles redirection
│── README.md

---

## 🗄 Database Setup

Create database:
url_shortener

Create table:

```sql
CREATE TABLE urls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE,
    long_url TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

