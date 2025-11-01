-- Create books table
CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    imageUrl VARCHAR(255) NOT NULL,
    bookName VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    category ENUM('Sinhala', 'English', 'Tamil') NOT NULL
);

-- Sinhala Books
INSERT INTO books (id, imageUrl, bookName, author, price, description, category) VALUES 
(1, 'assets/product page images/S1.jpg', 'Corona Dosthara', 'John Rivers', 1800, 'A thrilling mystery novel with unexpected twists.', 'Sinhala'),
(2, 'assets/product page images/S2.jpg', 'Sinahal Anduwa', 'Ella Brooks', 1200, 'A poetic journey through love and loss.', 'Sinhala'),
(3, 'assets/product page images/S3.jpg', 'Ganga Addara', 'Dan Croft', 2300, 'A cyber-thriller that explores the dangers of encryption.', 'Sinhala'),
(4, 'assets/product page images/S4.jpg', 'Gangaru Puda Kavi', 'Liam Carter', 950, 'A gripping fantasy novel set in a parallel world.', 'Sinhala'),
(5, 'assets/product page images/S5.jpg', 'Sinhala Sahithya', 'Sophia Wells', 2700, 'An epic tale of betrayal and redemption.', 'Sinhala'),
(6, 'assets/product page images/S6.jpg', 'Viyarana Yathura', 'Ethan Blake', 1700, 'A time-travel adventure with unexpected consequences.', 'Sinhala');

-- English Books
INSERT INTO books (id, imageUrl, bookName, author, price, description, category) VALUES 
(7, 'assets/product page images/1.jpg', 'How Innovation Books', 'Matt Ridley', 1800, 'A thrilling mystery novel with unexpected twists.', 'English'),
(8, 'assets/product page images/2.png', 'Revision WorkBook', 'Ella Brooks', 1200, 'A poetic journey through love and loss.', 'English'),
(9, 'assets/product page images/3.png', 'Student Diary', 'Dan Croft', 2300, 'A cyber-thriller that explores the dangers of encryption.', 'English'),
(10, 'assets/product page images/4.png', 'Sell English', 'Liam Carter', 950, 'A gripping fantasy novel set in a parallel world.', 'English'),
(11, 'assets/product page images/5.png', 'Crafting Idea', 'Sophia Wells', 2700, 'An epic tale of betrayal and redemption.', 'English'),
(12, 'assets/product page images/6.png', 'Bussiness Report', 'Ethan Blake', 1700, 'A time-travel adventure with unexpected consequences.', 'English');

-- Tamil Books
INSERT INTO books (id, imageUrl, bookName, author, price, description, category) VALUES 
(13, 'assets/product page images/T1.jpg', 'Thirukkaral', 'W.H.Drew', 1800, 'A thrilling mystery novel with unexpected twists.', 'Tamil'),
(14, 'assets/product page images/T2.jpg', 'Indulaima', 'D.H.Kavilma', 1200, 'A poetic journey through love and loss.', 'Tamil'),
(15, 'assets/product page images/T3.jpg', 'Pongatuk', 'Ramanisanthiran', 2300, 'A cyber-thriller that explores the dangers of encryption.', 'Tamil'),
(16, 'assets/product page images/T4.jpg', 'Odu Malar', 'Liam Carter', 950, 'A gripping fantasy novel set in a parallel world.', 'Tamil'),
(17, 'assets/product page images/T5.jpg', 'Katikiyan', 'Sophia Wells', 2700, 'An epic tale of betrayal and redemption.', 'Tamil'),
(18, 'assets/product page images/T6.jpg', 'Penmani', 'Ethan Blake', 1700, 'A time-travel adventure with unexpected consequences.', 'Tamil');


CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

 CREATE TABLE cart (
  cart_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  book_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  image_path VARCHAR(255),
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 );

CREATE TABLE payments (
  payment_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  exp_date VARCHAR(10),
  total DECIMAL(10,2),
  status ENUM('pending', 'completed') DEFAULT 'completed',
  payment_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
  order_id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  quantity INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (order_id, user_id, book_id)
);



INSERT INTO faq (question, answer) VALUES
('What types of books do you sell?', 'We offer a wide range of books including fiction, non-fiction, academic, children\'s books, and more.'),
('How can I place an order?', 'You can place an order by browsing our collection, adding items to your cart, and proceeding to checkout.'),
('How long does delivery take?', 'Standard delivery takes 3-7 business days depending on your location. Express delivery options are also available.'),
('Can I return or exchange a book?', 'Yes, returns and exchanges are accepted within 7 days of delivery, provided the book is in original condition.'),
('Do you offer international shipping?', 'Currently, we only ship within the country. We are working on adding international delivery soon.'),
('How can I track my order?', 'Once your order is shipped, a tracking number will be shared via email or SMS for real-time updates.'),
('Do you have a loyalty or membership program?', 'Yes! Our Book Lovers Club offers points on every purchase and exclusive member discounts.'),
('What payment methods are accepted?', 'We accept credit/debit cards, net banking, UPI, and popular digital wallets.'),
('Can I gift wrap an order?', 'Yes, gift wrapping is available at checkout for a small additional charge.'),
('How do I contact customer support?', 'You can reach us via our Contact Us page, or email readify@gmail.com.');


