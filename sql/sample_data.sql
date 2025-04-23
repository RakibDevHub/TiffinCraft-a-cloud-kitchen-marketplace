-- Inserting users (admin, seller, and buyer)
INSERT INTO users (name, email, password, role)
VALUES ('Admin User', 'admin@example.com', 'adminpassword', 'admin');

INSERT INTO users (name, email, password, role)
VALUES ('Seller User', 'seller@example.com', 'sellerpassword', 'seller');

INSERT INTO users (name, email, password, role)
VALUES ('Buyer User', 'buyer@example.com', 'buyerpassword', 'buyer');

-- Inserting areas (example areas where sellers operate)
INSERT INTO areas (name) VALUES ('Badda');
INSERT INTO areas (name) VALUES ('Gulshan');
INSERT INTO areas (name) VALUES ('Banani');

-- Inserting sample sellers and associating them with areas
INSERT INTO sellers (user_id, kitchen_name, area_id, status)
VALUES (2, 'Gourmet Kitchen', 1, 'approved'); -- Linking Seller User to Downtown area

INSERT INTO sellers (user_id, kitchen_name, area_id, status)
VALUES (3, 'Urban Bites', 2, 'pending'); -- Linking Seller User to Uptown area


-- Inserting sample menu items for sellers
INSERT INTO menu_items (seller_id, name, description, price, is_available)
VALUES (2, 'Gourmet Burger', 'Juicy beef patty with cheese', 9.99, 'Y');

INSERT INTO menu_items (seller_id, name, description, price, is_available)
VALUES (2, 'Spicy Fries', 'Crispy fries with a spicy kick', 4.99, 'Y');

INSERT INTO menu_items (seller_id, name, description, price, is_available)
VALUES (3, 'Vegan Wrap', 'Healthy and fresh vegan wrap', 7.99, 'Y');

-- Inserting sample buyers and associating them with areas
INSERT INTO buyers (user_id, address, area_id)
VALUES (3, '123 Main St, Badda', 1); -- Linking Buyer to Downtown area

-- Inserting sample orders (buyer orders a seller's item)
INSERT INTO orders (buyer_id, seller_id, item_id, quantity, total_price, order_status)
VALUES (3, 2, 1, 2, 19.98, 'placed');  -- Buyer orders two Gourmet Burgers from Gourmet Kitchen

INSERT INTO orders (buyer_id, seller_id, item_id, quantity, total_price, order_status)
VALUES (3, 2, 2, 1, 4.99, 'placed');  -- Buyer orders one Spicy Fries from Gourmet Kitchen


-- Inserting sample subscriptions (buyers subscribing to sellers)
INSERT INTO subscriptions (buyer_id, seller_id)
VALUES (3, 2);  -- Buyer subscribes to Gourmet Kitchen

-- Inserting sample flash deals for menu items
INSERT INTO flash_deals (item_id, discount_percent, start_time, end_time)
VALUES (1, 20, SYSDATE, SYSDATE + 1); -- 20% discount on Gourmet Burger for 1 day


-- Inserting sample reviews for items
INSERT INTO reviews (buyer_id, item_id, rating, comments)
VALUES (3, 1, 5, 'Amazing burger, will definitely order again!');

INSERT INTO reviews (buyer_id, item_id, rating, comments)
VALUES (3, 2, 4, 'Fries were crispy but could use more spice.');


-- Inserting kitchen verifications for sellers
INSERT INTO kitchen_verifications (seller_id, verified_by_admin_id, verification_status)
VALUES (2, 1, 'approved');  -- Admin approves Gourmet Kitchen's verification
