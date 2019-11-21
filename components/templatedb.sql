CREATE DATABASE templatedb;
USE templatedb;

-- --------------------------------------------------------

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `image` longblob NOT NULL,
  FOREIGN KEY (`category_id`) REFERENCES categories(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `images` (
  `product_id` varchar(50) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` longblob NOT NULL,
  FOREIGN KEY (`product_id`) REFERENCES products(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------------------------------------

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text(500) COLLATE utf8mb4_unicode_ci,
  `role_id` int(10) UNSIGNED NOT NULL,
  FOREIGN KEY (`role_id`) REFERENCES roles(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cart` (
  `user_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quanity` int(10) NOT NULL,
  UNIQUE(`user_email`, `product_id`),
  FOREIGN KEY (`user_email`) REFERENCES users(`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES products(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
  `user_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  FOREIGN KEY (`user_email`) REFERENCES users(`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_products` (
  `order_id` int(10) UNSIGNED COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quanity` int(10) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES orders(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES products(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `carousel` (
  `id` int(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

INSERT INTO `roles` (`id`, `role`) VALUES (1, 'admin'), (2, 'user');

INSERT INTO `users` (`email`, `first_name`, `last_name`, `phone`, `password`, `role_id`) VALUES ('admin@admin.com', 'Admin', 'Admin', '0000000000', 'password', 1);

-- --------------------------------------------------------

CREATE VIEW products_view AS
SELECT products.id, products.title, products.price, products.description, products.category_id, categories.category, products.image
FROM products
INNER JOIN categories
ON products.category_id = categories.id;

CREATE VIEW users_view AS
SELECT users.email, users.first_name, users.last_name, users.phone, users.password, users.address, roles.role
FROM users
INNER JOIN roles
ON users.role_id = roles.id;

CREATE VIEW `cart_view` AS
SELECT cart.user_email, GROUP_CONCAT(cart.product_id) AS 'product_id_list', GROUP_CONCAT(products.title) AS 'product_title_list', GROUP_CONCAT(products.price) AS 'product_price_list', GROUP_CONCAT(cart.quanity) AS 'product_quanity_list', GROUP_CONCAT(products.price*cart.quanity) AS 'product_total_price_list', SUM(products.price * cart.quanity) AS 'grand_total_price'
FROM cart
INNER JOIN products ON cart.product_id = products.id
GROUP BY cart.user_email;

CREATE VIEW orders_view AS
SELECT orders.id AS 'order_id', CONCAT(users.first_name, ' ', users.last_name) AS 'user_name', users.phone AS 'user_phone', orders.user_email, GROUP_CONCAT(order_products.product_id) AS 'product_id_list', GROUP_CONCAT(products.title) AS 'product_title_list', GROUP_CONCAT(order_products.quanity) AS 'quanity_list', GROUP_CONCAT(order_products.price) AS 'price_list', GROUP_CONCAT(order_products.price*order_products.quanity) AS 'total_price_list',  SUM(order_products.quanity * order_products.price) AS 'grand_total_price', orders.status
FROM orders
INNER JOIN order_products ON orders.id = order_products.order_id
INNER JOIN products ON order_products.product_id = products.id
INNER JOIN users ON orders.user_email = users.email
GROUP BY order_products.order_id;