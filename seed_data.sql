SET NAMES utf8mb4;
INSERT IGNORE INTO `ss_categories` (`id`, `slug`, `name_es`, `name_en`, `image_url`, `featured`) VALUES
(1, 'outerwear-tecnico', 'Outerwear Técnico', 'Technical Outerwear', 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=900&q=80', 1),
(2, 'calzado-arquitectonico', 'Calzado Arquitectónico', 'Architectural Footwear', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', 1),
(3, 'sastreria-modular', 'Sastrería Modular', 'Modular Tailoring', 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=80', 1),
(4, 'equipaje-precision', 'Equipaje de Precisión', 'Precision Carry', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80', 1);

INSERT IGNORE INTO `ss_products`
(`id`, `category_id`, `sku`, `slug`, `brand`, `name_es`, `name_en`, `short_desc_es`, `short_desc_en`, `description_es`, `description_en`, `base_price`, `sale_price`, `stock`, `rating_avg`, `rating_count`, `is_featured`, `is_new`, `main_image`, `gallery_json`, `specs_json`)
VALUES
(1, 1, 'SS-AW26-001', 'parka-gtx-monolith-negro', 'ATELIER LAB', 'Parka GTX Monolith 3L', 'GTX Monolith 3L Parka', 'Membrana impermeable 28.000mm con costuras termoselladas.', '28,000mm waterproof membrane with taped seams.', 'Diseñada para climas extremos. Confeccionada en tejido japonés de 3 capas transpirable con cremalleras estancas YKK AquaGuard y capucha articulada.', 'Engineered for extreme climates with 3-layer waterproof breathable membrane.', 420.00, 369.00, 18, 4.95, 24, 1, 1, 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=1000&q=85', '["https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=1000&q=85","https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=1000&q=85"]', '{"Membrana":"Gore-Tex Pro 3L","Impermeabilidad":"28.000 mm","Peso":"540g"}'),
(2, 2, 'SS-AW26-002', 'runner-vibram-kinetik-gris', 'KINETIK ARCH', 'Sneaker Vibram Kinetik V4', 'Vibram Kinetik V4 Sneaker', 'Suela Vibram Megagrip y upper en malla Dyneema.', 'Vibram Megagrip outsole and Dyneema mesh upper.', 'Híbrido entre trail técnico y silueta brutalista urbana. Amortiguación de doble densidad y refuerzos termosoldados sin costuras.', 'Hybrid between technical trail and urban brutalist silhouette.', 265.00, NULL, 32, 4.85, 19, 1, 1, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1000&q=85', '["https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1000&q=85"]', '{"Suela":"Vibram Megagrip","Upper":"Ripstop Dyneema","Peso":"310g"}'),
(3, 3, 'SS-AW26-003', 'pantalon-cargo-articulated-oliva', 'ATELIER LAB', 'Pantalón Cargo Articulado Schoeller', 'Articulated Schoeller Cargo Pant', 'Tejido elástico en 4 direcciones repelente al agua.', '4-way stretch water-repellent fabric.', 'Rodillas preformadas ergonómicas, 6 bolsillos magnéticos Fidlock y ajuste de tobillo oculto.', 'Ergonomic articulated knees and magnetic Fidlock pockets.', 230.00, 195.00, 25, 4.90, 14, 1, 0, 'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=1000&q=85', '["https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=1000&q=85"]', '{"Tejido":"Schoeller Dryskin","Cierres":"Fidlock V-Buckle"}'),
(4, 4, 'SS-AW26-004', 'mochila-nomad-cordura-28l', 'NOMAD SYSTEM', 'Mochila Modular Cordura 1680D 28L', 'Modular Cordura 1680D Backpack 28L', 'Compartimento suspendido para portátil de 16" y chasis impermeable.', 'Suspended 16" laptop compartment and waterproof chassis.', 'Construcción en Cordura balística 1680D con paneles MOLLE cortados a láser y arnés ergonómico transpirable.', '1680D ballistic Cordura construction with laser-cut panels.', 290.00, NULL, 12, 5.00, 31, 1, 1, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=1000&q=80', '["https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=1000&q=80"]', '{"Capacidad":"28 Litros","Material":"Cordura Balística 1680D"}');

INSERT IGNORE INTO `ss_product_variants` (`id`, `product_id`, `sku_variant`, `size`, `color_name`, `color_hex`, `price_delta`, `stock`) VALUES
(1, 1, 'SS-AW26-001-S-BLK', 'S', 'Negro Obsidiana', '#0d0d0d', 0.00, 5),
(2, 1, 'SS-AW26-001-M-BLK', 'M', 'Negro Obsidiana', '#0d0d0d', 0.00, 8),
(3, 1, 'SS-AW26-001-L-OLV', 'L', 'Verde Táctico', '#3b4d3b', 0.00, 5),
(4, 2, 'SS-AW26-002-41-GRY', '41', 'Gris Titanio', '#71717a', 0.00, 10),
(5, 2, 'SS-AW26-002-42-GRY', '42', 'Gris Titanio', '#71717a', 0.00, 12),
(6, 3, 'SS-AW26-003-M-OLV', 'M', 'Verde Táctico', '#3b4d3b', 0.00, 15),
(7, 4, 'SS-AW26-004-OS-BLK', 'ÚNICA', 'Negro Obsidiana', '#0d0d0d', 0.00, 12);

INSERT IGNORE INTO `ss_coupons` (`id`, `code`, `discount_type`, `discount_value`, `min_order_amount`, `is_active`) VALUES
(1, 'ATELIER10', 'percent', 10.00, 50.00, 1),
(2, 'MINUS30', 'fixed', 30.00, 200.00, 1);

INSERT IGNORE INTO `ss_reviews` (`id`, `product_id`, `author_name`, `rating`, `title`, `comment`, `photos_json`, `verified_purchase`) VALUES
(1, 1, 'Marco Santini', 5, 'Impermeabilidad absoluta', 'Probada bajo lluvia intensa. El corte y el sellado térmico son de primer nivel.', '[]', 1),
(2, 2, 'Lucas Velasco', 5, 'Agarre y diseño brutal', 'La suela Vibram agarra en asfalto mojado como ninguna otra zapatilla.', '[]', 1);