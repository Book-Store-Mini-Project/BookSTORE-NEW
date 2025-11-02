<?php
// Include the database connection
include 'config/db_connection.php';

// Start session (for potential future use, e.g., admin login check)
session_start();

// ===================================================
// === BOOK MANAGEMENT SECTION ===
// ===================================================

// 1️⃣ Fetch all books
$books = [];
$bookQuery = "SELECT * FROM books ORDER BY id DESC";
$result = mysqli_query($conn, $bookQuery);

while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}

// Default book form values
$book = ['bookName' => '', 'author' => '', 'category' => '', 'price' => '', 'description' => '', 'imageUrl' => ''];
$editBookMode = false;

// 2️⃣ Edit book (fetch book data)
if (isset($_GET['edit_book'])) {
    $editBookMode = true;
    $bookId = (int)$_GET['edit_book']; // Secure cast

    $editQuery = "SELECT * FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $editQuery);
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result) ?: $book;
}

// 3️⃣ Add or Update book
if (isset($_POST['book_submit'])) {
    $bookName = mysqli_real_escape_string($conn, $_POST['bookName']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float)$_POST['price'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $imageUrl = mysqli_real_escape_string($conn, $_POST['imageUrl']);

    if ($editBookMode) {
        $updateQuery = "UPDATE books SET bookName=?, author=?, category=?, price=?, description=?, imageUrl=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "sssdssi", $bookName, $author, $category, $price, $description, $imageUrl, $bookId);
        mysqli_stmt_execute($stmt);
    } else {
        $insertQuery = "INSERT INTO books (bookName, author, category, price, description, imageUrl) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssdss", $bookName, $author, $category, $price, $description, $imageUrl);
        mysqli_stmt_execute($stmt);
    }

    header("Location: admin.php#books");
    exit;
}

// 4️⃣ Delete book
if (isset($_GET['delete_book'])) {
    $deleteId = (int)$_GET['delete_book'];
    $deleteQuery = "DELETE FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $deleteId);
    mysqli_stmt_execute($stmt);

    header("Location: admin.php#books");
    exit;
}

// ===================================================
// === FAQ MANAGEMENT SECTION ===
// ===================================================

// 1️⃣ Fetch all FAQs
$faqs = [];
$faqQuery = "SELECT * FROM faq ORDER BY id DESC";
$result = mysqli_query($conn, $faqQuery);

while ($row = mysqli_fetch_assoc($result)) {
    $faqs[] = $row;
}

// Default FAQ form values
$faq = ['question' => '', 'answer' => ''];
$editFaqMode = false;

// 2️⃣ Edit FAQ
if (isset($_GET['edit_faq'])) {
    $editFaqMode = true;
    $faqId = (int)$_GET['edit_faq'];

    $editQuery = "SELECT * FROM faq WHERE id = ?";
    $stmt = mysqli_prepare($conn, $editQuery);
    mysqli_stmt_bind_param($stmt, "i", $faqId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $faq = mysqli_fetch_assoc($result) ?: $faq;
}

// 3️⃣ Add or Update FAQ
if (isset($_POST['faq_submit'])) {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $answer = mysqli_real_escape_string($conn, $_POST['answer']);

    if ($editFaqMode) {
        $updateQuery = "UPDATE faq SET question=?, answer=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssi", $question, $answer, $faqId);
        mysqli_stmt_execute($stmt);
    } else {
        $insertQuery = "INSERT INTO faq (question, answer) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ss", $question, $answer);
        mysqli_stmt_execute($stmt);
    }

    header("Location: admin.php#faqs");
    exit;
}

// 4️⃣ Delete FAQ
if (isset($_GET['delete_faq'])) {
    $deleteId = (int)$_GET['delete_faq'];
    $deleteQuery = "DELETE FROM faq WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $deleteId);
    mysqli_stmt_execute($stmt);

    header("Location: admin.php#faqs");
    exit;
}

// ===================================================
// === USER MANAGEMENT SECTION ===
// ===================================================

// Fetch all users (adjust columns if your 'users' table differs)
$users = [];
$userQuery = "SELECT id, name, email, created_at FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $userQuery);
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Delete user
if (isset($_GET['delete_user'])) {
    $deleteId = (int)$_GET['delete_user'];
    // Optional: Delete related data first (e.g., orders) - add if needed
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $deleteId);
    mysqli_stmt_execute($stmt);

    header("Location: admin.php#users");
    exit;
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Books, FAQs & Users</title>
    <link rel="stylesheet" href="./css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Extra styles for User Management */
        .badge-admin { background: #dc3545; color: white; }
        .badge-user { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-book-reader"></i>
            <h2>BookStore Admin</h2>
        </div>
        <nav class="nav-menu">
            <a href="#books" class="nav-item active">
                <i class="fas fa-book"></i>
                <span>Books</span>
            </a>
            <a href="#faqs" class="nav-item">
                <i class="fas fa-question-circle"></i>
                <span>FAQs</span>
            </a>
            <a href="#users" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </nav>
    </div>

    <div class="main-content">
        <header class="top-header">
            <h1>Dashboard</h1>
            <div class="header-actions">
                <div class="stats">
                    <div class="stat-card">
                        <i class="fas fa-book"></i>
                        <div>
                            <span class="stat-number"><?= count($books) ?></span>
                            <span class="stat-label">Books</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-question-circle"></i>
                        <div>
                            <span class="stat-number"><?= count($faqs) ?></span>
                            <span class="stat-label">FAQs</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <div>
                            <span class="stat-number"><?= count($users) ?></span>
                            <span class="stat-label">Users</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ==================== BOOK MANAGEMENT ==================== -->
        <section id="books" class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-book"></i> Book Management</h2>
            </div>

            <div class="form-card">
                <h3><?= $editBookMode ? '<i class="fas fa-edit"></i> Edit Book' : '<i class="fas fa-plus-circle"></i> Add New Book' ?></h3>
                <form method="POST" class="modern-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label><i class="fas fa-book"></i> Book Name</label>
                            <input type="text" name="bookName" placeholder="Enter book name" value="<?= htmlspecialchars($book['bookName']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Author</label>
                            <input type="text" name="author" placeholder="Enter author name" value="<?= htmlspecialchars($book['author']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-tag"></i> Category</label>
                            <select name="category" required>
                                <option value="">Select Category</option>
                                <?php
                                $categories = ['Sinhala', 'English', 'Tamil'];
                                foreach ($categories as $c) {
                                    $selected = $book['category'] == $c ? 'selected' : '';
                                    echo "<option value='$c' $selected>$c</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-dollar-sign"></i> Price</label>
                            <input type="number" name="price" placeholder="Enter price" value="<?= htmlspecialchars($book['price']) ?>" step="0.01" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Description</label>
                        <textarea name="description" placeholder="Enter book description" rows="3" required><?= htmlspecialchars($book['description']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Image URL</label>
                        <input type="text" name="imageUrl" placeholder="Enter image URL" value="<?= htmlspecialchars($book['imageUrl']) ?>" required>
                    </div>
                    <button type="submit" name="book_submit" class="btn btn-primary">
                        <i class="fas <?= $editBookMode ? 'fa-save' : 'fa-plus' ?>"></i>
                        <?= $editBookMode ? 'Update Book' : 'Add Book' ?>
                    </button>
                    <?php if ($editBookMode): ?>
                        <a href="admin.php#books" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-card">
                <h3><i class="fas fa-list"></i> All Books</h3>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Book Name</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $b): ?>
                            <tr>
                                <td><?= $b['id'] ?></td>
                                <td><img src="<?= htmlspecialchars($b['imageUrl']) ?>" alt="Book" class="table-image"></td>
                                <td class="text-bold"><?= htmlspecialchars($b['bookName']) ?></td>
                                <td><?= htmlspecialchars($b['author']) ?></td>
                                <td><span class="badge badge-<?= strtolower($b['category']) ?>"><?= htmlspecialchars($b['category']) ?></span></td>
                                <td class="text-bold">Rs.<?= number_format((float)$b['price'], 2) ?></td>
                                <td class="text-truncate"><?= htmlspecialchars($b['description']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="admin.php?edit_book=<?= $b['id'] ?>#books" class="btn-icon btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="admin.php?delete_book=<?= $b['id'] ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Delete this book?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ==================== FAQ MANAGEMENT ==================== -->
        <section id="faqs" class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-question-circle"></i> FAQ Management</h2>
            </div>

            <div class="form-card">
                <h3><?= $editFaqMode ? '<i class="fas fa-edit"></i> Edit FAQ' : '<i class="fas fa-plus-circle"></i> Add New FAQ' ?></h3>
                <form method="POST" class="modern-form">
                    <div class="form-group">
                        <label><i class="fas fa-question"></i> Question</label>
                        <input type="text" name="question" placeholder="Enter question" value="<?= htmlspecialchars($faq['question']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-comment-dots"></i> Answer</label>
                        <textarea name="answer" placeholder="Enter answer" rows="4" required><?= htmlspecialchars($faq['answer']) ?></textarea>
                    </div>
                    <button type="submit" name="faq_submit" class="btn btn-primary">
                        <i class="fas <?= $editFaqMode ? 'fa-save' : 'fa-plus' ?>"></i>
                        <?= $editFaqMode ? 'Update FAQ' : 'Add FAQ' ?>
                    </button>
                    <?php if ($editFaqMode): ?>
                        <a href="admin.php#faqs" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-card">
                <h3><i class="fas fa-list"></i> All FAQs</h3>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faqs as $f): ?>
                            <tr>
                                <td><?= $f['id'] ?></td>
                                <td class="text-bold"><?= htmlspecialchars($f['question']) ?></td>
                                <td><?= htmlspecialchars($f['answer']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="admin.php?edit_faq=<?= $f['id'] ?>#faqs" class="btn-icon btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="admin.php?delete_faq=<?= $f['id'] ?>" class="btn-icon btn-delete" title="Delete" onclick="return confirm('Delete this FAQ?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ==================== USER MANAGEMENT ==================== -->
        <section id="users" class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-users"></i> User Management</h2>
            </div>

            <div class="table-card">
                <h3><i class="fas fa-list"></i> All Users</h3>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Registered On</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr><td colspan="6" style="text-align:center; color: #888;">No users found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= $u['id'] ?></td>
                                    <td class="text-bold"><?= htmlspecialchars($u['name']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                                    <td>
                                        <span class="badge <?= (isset($u['role']) && $u['role'] === 'admin') ? 'badge-admin' : 'badge-user' ?>">
                                            <?= ucfirst($u['role'] ?? 'user') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="admin.php?delete_user=<?= $u['id'] ?>" class="btn-icon btn-delete" title="Delete User" onclick="return confirm('Permanently delete this user? This cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        // Smooth scrolling + active nav highlight
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                const target = document.querySelector(this.getAttribute('href'));
                target.scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Auto-highlight on scroll
        window.addEventListener('scroll', () => {
            ['books', 'faqs', 'users'].forEach(sec => {
                const el = document.getElementById(sec);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= 120 && rect.bottom >= 120) {
                        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                        document.querySelector(`a[href="#${sec}"]`).classList.add('active');
                    }
                }
            });
        });
    </script>
</body>
</html>