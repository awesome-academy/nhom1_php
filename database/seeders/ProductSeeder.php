<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo thư mục public storage lưu ảnh
        Storage::disk('public')->makeDirectory('products');

        // 2. Danh sách 10 món demo chi tiết
        $items = [
            // ================= ĐỒ UỐNG (DRINKS) =================
            [
                'category_slug' => Str::slug('Espresso & Cà phê Ý'),
                'name' => 'Cà phê Latte Hạnh Nhân',
                'type' => 'drink',
                'price' => 55000,
                'stock' => 100,
                'summary' => 'Hương vị béo ngậy từ sữa hạnh nhân kết hợp cốt Espresso Arabica đậm đà.',
                'description' => "【Product Summary】\nHương vị béo ngậy từ sữa hạnh nhân kết hợp cốt Espresso Arabica đậm đà.\n\n【Mô tả chi tiết】\nCà phê Latte Hạnh Nhân tại Brew & Bite được pha chế từ 100% hạt Arabica Cầu Đất rang vừa, hòa quyện cùng sữa hạnh nhân không đường đánh bọt mịn chuẩn nghệ thuật Latte Art. Thức uống mang lại vị đắng dịu, hậu vị ngọt ngào và tốt cho sức khỏe.",
                'images' => [
                    'latte-art.jpg' => true,
                    'latte-detail.jpg' => false,
                ],
                'variants' => [
                    ['variant_group' => 'size', 'name' => 'Size M (350ml)', 'extra_price' => 0],
                    ['variant_group' => 'size', 'name' => 'Size L (500ml)', 'extra_price' => 10000],
                    ['variant_group' => 'sugar', 'name' => '100% Đường', 'extra_price' => 0],
                    ['variant_group' => 'sugar', 'name' => '50% Đường', 'extra_price' => 0],
                    ['variant_group' => 'sugar', 'name' => 'Không đường', 'extra_price' => 0],
                    ['variant_group' => 'ice', 'name' => 'Đá đầy ly', 'extra_price' => 0],
                    ['variant_group' => 'ice', 'name' => 'Đá riêng', 'extra_price' => 0],
                    ['variant_group' => 'topping', 'name' => 'Thêm Shot Espresso', 'extra_price' => 15000],
                ]
            ],
            [
                'category_slug' => Str::slug('Espresso & Cà phê Ý'),
                'name' => 'Espresso Double Shot',
                'type' => 'drink',
                'price' => 38000,
                'stock' => 150,
                'summary' => 'Chiết xuất nguyên chất đậm đặc với lớp crema sánh mịn chuẩn Ý.',
                'description' => "【Product Summary】\nChiết xuất nguyên chất đậm đặc với lớp crema sánh mịn chuẩn Ý.\n\n【Mô tả chi tiết】\nMột ly Espresso Double Shot đánh thức sự tập trung. Được chiết xuất bằng áp suất 9 bar tiêu chuẩn từ dòng hạt Specialty Arabica & Robusta mộc, giữ trọn vẹn hương vị caramel, socola đen và hương hoa tự nhiên.",
                'images' => [
                    'espresso-double.jpg' => true,
                ],
                'variants' => []
            ],
            [
                'category_slug' => Str::slug('Cold Brew & Cà phê Ủ Lạnh'),
                'name' => 'Cold Brew Cam Vàng Sả Tươi',
                'type' => 'drink',
                'price' => 52000,
                'stock' => 80,
                'summary' => 'Cà phê ủ lạnh 18 tiếng lên men tự nhiên hòa quyện tép cam vàng ngọt thanh mát.',
                'description' => "【Product Summary】\nCà phê ủ lạnh 18 tiếng lên men tự nhiên hòa quyện tép cam vàng ngọt thanh mát.\n\n【Mô tả chi tiết】\nSự kết hợp giữa vị chua thanh tự nhiên của cam vàng, hương thơm thảo mộc dịu nhẹ từ sả tươi cùng vị đắng mượt mà, ít axit của cà phê ủ lạnh suốt 18 giờ. Lựa chọn sảng khoái và giải nhiệt tuyệt vời.",
                'images' => [
                    'cold-brew-orange.jpg' => true,
                    'cold-brew-glass.jpg' => false,
                ],
                'variants' => [
                    ['variant_group' => 'size', 'name' => 'Size M', 'extra_price' => 0],
                    ['variant_group' => 'size', 'name' => 'Size L', 'extra_price' => 10000],
                    ['variant_group' => 'sugar', 'name' => 'Ngọt thanh (Chuẩn quán)', 'extra_price' => 0],
                    ['variant_group' => 'sugar', 'name' => 'Ít ngọt', 'extra_price' => 0],
                    ['variant_group' => 'ice', 'name' => 'Đá đầy ly', 'extra_price' => 0],
                ]
            ],
            [
                'category_slug' => Str::slug('Trà Sữa & Matcha'),
                'name' => 'Matcha Latte Uji Cao Cấp',
                'type' => 'drink',
                'price' => 58000,
                'stock' => 90,
                'summary' => 'Bột trà xanh Uji Kyoto nhập khẩu kết hợp sữa tươi thanh trùng béo ngậy.',
                'description' => "【Product Summary】\nBột trà xanh Uji Kyoto nhập khẩu kết hợp sữa tươi thanh trùng béo ngậy.\n\n【Mô tả chi tiết】\nSử dụng 100% bột Matcha hữu cơ từ vùng Uji (Kyoto) đánh bọt thủ công bằng chasen tre truyền thống, hòa cùng sữa tươi thanh trùng tạo nên màu xanh tươi sáng, vị chát dịu và hậu vị ngọt thanh sâu lắng.",
                'images' => [
                    'matcha-latte.jpg' => true,
                    'matcha-detail.jpg' => false,
                ],
                'variants' => [
                    ['variant_group' => 'size', 'name' => 'Size M', 'extra_price' => 0],
                    ['variant_group' => 'size', 'name' => 'Size L', 'extra_price' => 12000],
                    ['variant_group' => 'sugar', 'name' => '70% Đường', 'extra_price' => 0],
                    ['variant_group' => 'sugar', 'name' => '30% Đường', 'extra_price' => 0],
                    ['variant_group' => 'topping', 'name' => 'Trân châu trắng konjac', 'extra_price' => 10000],
                ]
            ],
            [
                'category_slug' => Str::slug('Trà Trái Cây & Thảo Mộc'),
                'name' => 'Trà Đào Cam Sả Thượng Hạng',
                'type' => 'drink',
                'price' => 49000,
                'stock' => 120,
                'summary' => 'Trà lài ủ lạnh thơm mát, ăn kèm 3 miếng đào giòn ngâm mật ong ngọt ngào.',
                'description' => "【Product Summary】\nTrà lài ủ lạnh thơm mát, ăn kèm 3 miếng đào giòn ngâm mật ong ngọt ngào.\n\n【Mô tả chi tiết】\nCốt trà lài hảo hạng hãm cùng nhánh sả tươi đập dập, lát cam mọng nước và từng miếng đào ngâm giòn ngọt sần sật. Mang lại cảm giác thanh nhiệt và hương hoa quả tươi tràn đầy năng lượng.",
                'images' => [
                    'tra-dao-cam-sa.jpg' => true,
                ],
                'variants' => [
                    ['variant_group' => 'size', 'name' => 'Size M', 'extra_price' => 0],
                    ['variant_group' => 'size', 'name' => 'Size L', 'extra_price' => 10000],
                    ['variant_group' => 'topping', 'name' => 'Thêm 2 miếng đào giòn', 'extra_price' => 12000],
                ]
            ],
            [
                'category_slug' => Str::slug('Trà Trái Cây & Thảo Mộc'),
                'name' => 'Trà Hoa Cúc Mật Ong Dưỡng Nhan',
                'type' => 'drink',
                'price' => 45000,
                'stock' => 70,
                'summary' => 'Bông cúc sấy lạnh tự nhiên hãm cùng mật ong rừng, giúp thư giãn tinh thần.',
                'description' => "【Product Summary】\nBông cúc sấy lạnh tự nhiên hãm cùng mật ong rừng, giúp thư giãn tinh thần.\n\n【Mô tả chi tiết】\nTrà Hoa Cúc được chọn lọc từ những bông cúc trắng sấy lạnh, hòa quyện vị ngọt tự nhiên của mật ong hoa nhãn và lát chanh tươi. Thức uống thảo mộc giúp an thần, ngủ ngon và hỗ trợ tiêu hóa.",
                'images' => [
                    'tra-hoa-cuc.jpg' => true,
                ],
                'variants' => [
                    ['variant_group' => 'ice', 'name' => 'Dùng nóng', 'extra_price' => 0],
                    ['variant_group' => 'ice', 'name' => 'Dùng lạnh (kèm đá)', 'extra_price' => 0],
                ]
            ],

            // ================= ĐỒ ĂN (FOOD & BAKERY) =================
            [
                'category_slug' => Str::slug('Bánh Ngọt & Pastry'),
                'name' => 'Bánh Croissant Bơ Pháp Truyền Thống',
                'type' => 'food',
                'price' => 38000,
                'stock' => 50,
                'summary' => 'Bánh sừng bò ngàn lớp vỏ giòn rụm, ruột xốp mềm thơm lừng bơ Pháp.',
                'description' => "【Product Summary】\nBánh sừng bò ngàn lớp vỏ giòn rụm, ruột xốp mềm thơm lừng bơ Pháp.\n\n【Mô tả chi tiết】\nBánh Croissant nướng mới mỗi sáng với kỹ thuật cán bột 27 lớp bơ sữa chuẩn Pháp. Vỏ bánh vàng ươm giòn rụm, ruột ẩm mềm dai nhẹ và thơm phức mùi bơ hảo hạng khi cắn từng miếng.",
                'images' => [
                    'croissant-butter.jpg' => true,
                    'croissant-cut.jpg' => false,
                ],
                'variants' => []
            ],
            [
                'category_slug' => Str::slug('Bánh Ngọt & Pastry'),
                'name' => 'Bánh Tiramisu Ý Mascarpone',
                'type' => 'food',
                'price' => 48000,
                'stock' => 40,
                'summary' => 'Phô mai Mascarpone mềm mịn phủ cacao đắng hòa quyện cà phê Kahlua.',
                'description' => "【Product Summary】\nPhô mai Mascarpone mềm mịn phủ cacao đắng hòa quyện cà phê Kahlua.\n\n【Mô tả chi tiết】\nCốt bánh bông lan Savoiardi ngâm đẫm cà phê Espresso và rượu nhẹ Kahlua, xen kẽ giữa các tầng kem phô mai Mascarpone béo ngậy mượt mà, bề mặt phủ đều bột cacao nguyên chất đắng nhẹ cân bằng vị giác.",
                'images' => [
                    'tiramisu-classic.jpg' => true,
                    'tiramisu-box.jpg' => false,
                ],
                'variants' => []
            ],
            [
                'category_slug' => Str::slug('Bánh Mì & Cookies'),
                'name' => 'Cookies Socola Chip Hạt Điều',
                'type' => 'food',
                'price' => 28000,
                'stock' => 80,
                'summary' => 'Bánh quy nướng bơ tươi giòn xốp rải đầy socola nguyên chất và hạt điều bùi bùi.',
                'description' => "【Product Summary】\nBánh quy nướng bơ tươi giòn xốp rải đầy socola nguyên chất và hạt điều bùi bùi.\n\n【Mô tả chi tiết】\nBánh quy thủ công nướng tươi giòn rụm từ bột mì nguyên cám, bơ Pháp, hạt điều rang muối đập dập thơm bùi và những giọt socola chip đen tan chảy đậm đà. Rất thích hợp dùng kèm một tách Cold Brew.",
                'images' => [
                    'chocolate-cookies.jpg' => true,
                ],
                'variants' => []
            ],
            [
                'category_slug' => Str::slug('Bánh Mì & Cookies'),
                'name' => 'Bánh Mì Bagel Kẹp Cream Cheese',
                'type' => 'food',
                'price' => 42000,
                'stock' => 35,
                'summary' => 'Vỏ bánh tròn dai giòn đặc trưng phết đẫm lớp phô mai kem chua ngọt mịn màng.',
                'description' => "【Product Summary】\nVỏ bánh tròn dai giòn đặc trưng phết đẫm lớp phô mai kem chua ngọt mịn màng.\n\n【Mô tả chi tiết】\nBánh Bagel chuẩn công thức New York với vỏ ngoài giòn dai và ruột đặc mềm. Khi nướng nóng được phết ngập sốt Cream Cheese béo ngậy, chua thanh nhẹ nhàng, là món ăn nhẹ giàu năng lượng cho buổi sáng.",
                'images' => [
                    'bagel-cream-cheese.jpg' => true,
                ],
                'variants' => []
            ],
        ];

        // 3. Thực hiện lưu vào Database
        foreach ($items as $item) {
            $category = Category::where('slug', $item['category_slug'])->first();

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'category_id' => $category?->id,
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'price' => $item['price'],
                    'stock_quantity' => $item['stock'],
                    'description' => $item['description'],
                    'is_active' => true,
                ]
            );

            // 3.1. Lưu ảnh (ProductImage) và copy file vào Storage
            foreach ($item['images'] as $fileName => $isPrimary) {
                $source = database_path('seeders/images/' . $fileName);
                $target = 'products/' . $fileName;

                if (File::exists($source)) {
                    File::copy($source, storage_path('app/public/' . $target));
                }

                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'image_path' => $target,
                    ],
                    [
                        'is_primary' => $isPrimary,
                    ]
                );
            }

            // 3.2. Lưu biến thể (ProductVariant)
            foreach ($item['variants'] as $variant) {
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                    ],
                    [
                        'variant_group' => $variant['variant_group'],
                        'extra_price' => $variant['extra_price'],
                    ]
                );
            }
        }
    }
}