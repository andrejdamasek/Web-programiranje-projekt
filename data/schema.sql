CREATE DATABASE IF NOT EXISTS travnjak_centar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE travnjak_centar;

CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    first_name    VARCHAR(100) NOT NULL,
    last_name     VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_admin      TINYINT(1)   NOT NULL DEFAULT 0,
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    category_id       INT            NOT NULL,
    name              VARCHAR(180)   NOT NULL,
    brand             VARCHAR(120)   NOT NULL,
    short_description VARCHAR(255)   NOT NULL,
    description       TEXT           NOT NULL,
    price             DECIMAL(10,2)  NOT NULL,
    stock             INT            NOT NULL DEFAULT 0,
    power_type        VARCHAR(80)    DEFAULT NULL,
    blade_type        VARCHAR(80)    DEFAULT NULL,
    cutting_width_cm  DECIMAL(5,2)   DEFAULT NULL,
    basket_capacity_l DECIMAL(6,2)   DEFAULT NULL,
    weight_kg         DECIMAL(6,2)   DEFAULT NULL,
    image_url         VARCHAR(500)   NOT NULL,
    featured          TINYINT(1)     NOT NULL DEFAULT 0,
    created_at        TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id),
    UNIQUE KEY uq_product_name_brand (name, brand)
);

CREATE TABLE IF NOT EXISTS orders (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT           NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status      VARCHAR(50)   NOT NULL DEFAULT 'nova',
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    order_id          INT           NOT NULL,
    product_id        INT           NOT NULL,
    quantity          INT           NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_order   FOREIGN KEY (order_id)   REFERENCES orders(id),
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id)
);