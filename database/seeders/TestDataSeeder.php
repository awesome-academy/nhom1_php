<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;

// Tạo categories
$coffee    = Category::create(['name' => 'Coffee',     'slug' => 'coffee',      'description' => 'Các loại cà phê']);
$coldBrew  = Category::create(['name' => 'Cold Brew',  'slug' => 'cold-brew',   'parent_id'   => $coffee->id]);
$hotCoffee = Category::create(['name' => 'Hot Coffee', 'slug' => 'hot-coffee',  'parent_id'   => $coffee->id]);

// Tạo products
$p1 = Product::create([
    'category_id'    => $coldBrew->id,
    'name'           => 'Cold Brew Original',
    'slug'           => 'cold-brew-original',
    'description'    => 'Cà phê ủ lạnh 24 giờ, vị đậm đà, ít chua.',
    'type'           => 'drink',
    'price'          => 45000,
    'stock_quantity' => 50,
    'is_active'      => true,
]);

$p2 = Product::create([
    'category_id'    => $hotCoffee->id,
    'name'           => 'Americano',
    'slug'           => 'americano',
    'description'    => 'Espresso pha loãng với nước nóng.',
    'type'           => 'drink',
    'price'          => 39000,
    'stock_quantity' => 30,
    'is_active'      => true,
]);

// Tạo ảnh
ProductImage::create(['product_id' => $p1->id, 'image_path' => 'images/cold-brew.jpg',  'is_primary' => true]);
ProductImage::create(['product_id' => $p1->id, 'image_path' => 'images/cold-brew-2.jpg','is_primary' => false]);
ProductImage::create(['product_id' => $p2->id, 'image_path' => 'images/americano.jpg',  'is_primary' => true]);

// Tạo variants
ProductVariant::create(['product_id' => $p1->id, 'name' => 'Size M', 'variant_group' => 'size',  'extra_price' => 0]);
ProductVariant::create(['product_id' => $p1->id, 'name' => 'Size L', 'variant_group' => 'size',  'extra_price' => 5000]);
ProductVariant::create(['product_id' => $p1->id, 'name' => 'Ít đá',  'variant_group' => 'ice',   'extra_price' => 0]);

echo "Done!\n";
echo "Categories: " . Category::count() . "\n";
echo "Products: " . Product::count() . "\n";
echo "Images: " . ProductImage::count() . "\n";
echo "Variants: " . ProductVariant::count() . "\n";
