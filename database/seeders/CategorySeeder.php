<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Specialty Coffee' => [
                'description' => 'Các dòng cà phê hạt chọn lọc pha thủ công và máy chuẩn phong cách Ý.',
                'children' => [
                    'Espresso & Cà phê Ý' => 'Espresso, Americano, Latte, Cappuccino chuẩn vị.',
                    'Cold Brew & Cà phê Ủ Lạnh' => 'Cà phê ủ lạnh 16-24 tiếng mang hương vị mượt mà, thanh mát.',
                ]
            ],
            'Craft Tea' => [
                'description' => 'Trà thủ công kết hợp trà xanh hữu cơ, trà đen và hoa quả tươi nhiệt đới.',
                'children' => [
                    'Trà Sữa & Matcha' => 'Trà sữa lá cao cấp và bột trà xanh Uji Kyoto nguyên chất.',
                    'Trà Trái Cây & Thảo Mộc' => 'Trà hoa quả nhiệt đới tươi mát và trà hoa thanh lọc cơ thể.',
                ]
            ],
            'Artisan Bakery' => [
                'description' => 'Bánh mì và bánh ngọt nướng tươi thủ công mỗi ngày bằng nguyên liệu cao cấp.',
                'children' => [
                    'Bánh Ngọt & Pastry' => 'Croissant ngàn lớp giòn tan và bánh mềm Tiramisu.',
                    'Bánh Mì & Cookies' => 'Cookies bơ socola chip và bánh mì nướng dinh dưỡng.',
                ]
            ]
        ];

        foreach ($categories as $parentName => $data) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($parentName)],
                [
                    'name' => $parentName,
                    'description' => $data['description'],
                    'parent_id' => null,
                ]
            );

            foreach ($data['children'] as $childName => $childDesc) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'name' => $childName,
                        'description' => $childDesc,
                        'parent_id' => $parent->id,
                    ]
                );
            }
        }
    }
}
