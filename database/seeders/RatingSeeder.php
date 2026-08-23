<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Tạo user mẫu để có nhiều người đánh giá ──────────────────
        $reviewers = [
            ['name' => 'Nguyễn Thị Mai',    'email' => 'mai.nguyen@example.com'],
            ['name' => 'Trần Văn Hùng',      'email' => 'hung.tran@example.com'],
            ['name' => 'Lê Thị Lan Anh',     'email' => 'lananh.le@example.com'],
            ['name' => 'Phạm Minh Tuấn',     'email' => 'tuan.pham@example.com'],
            ['name' => 'Đặng Thị Thu Hà',    'email' => 'thuha.dang@example.com'],
            ['name' => 'Bùi Quang Khải',     'email' => 'khai.bui@example.com'],
            ['name' => 'Hoàng Thị Bảo Châu', 'email' => 'baochau.hoang@example.com'],
            ['name' => 'Vũ Tiến Dũng',       'email' => 'dung.vu@example.com'],
        ];

        $users = [];
        foreach ($reviewers as $r) {
            $users[] = User::firstOrCreate(
                ['email' => $r['email']],
                [
                    'name'     => $r['name'],
                    'password' => Hash::make('password'),
                    'role'     => 'user',
                ]
            );
        }

        // ── 2. Dữ liệu review theo từng sản phẩm (slug => reviews) ───────
        // Format mỗi phần tử: [chỉ_số_user, số_sao, bình_luận]
        $data = [

            // ── CÀ PHÊ LATTE HẠNH NHÂN ──────────────────────────────────
            'ca-phe-latte-hanh-nhan' => [
                [0, 5, 'Mình uống lần đầu mà ghiền luôn! Vị béo nhẹ từ sữa hạnh nhân, không ngấy tí nào. Lớp bọt sữa mịn như nhung, nhìn đã mắt lắm ☕'],
                [1, 4, 'Hương thơm rất dễ chịu, uống xong vẫn cảm nhận được vị hậu ngọt nhẹ. Nếu thêm shot espresso nữa thì hoàn hảo.'],
                [2, 5, 'Quán pha chuẩn vị hơn mấy chỗ khác mình từng thử. Sữa hạnh nhân thật sự chứ không phải pha bột. Sẽ order đều!'],
                [3, 4, 'Ngon và healthy, bữa nào chill làm việc mình đều gọi ly này. Giá hợp lý nên không phàn nàn gì.'],
                [4, 5, 'Màu sắc đẹp xuất sắc, chụp ảnh đăng story ai cũng hỏi mua ở đâu 😍 Vị thì thôi khỏi chê rồi!'],
            ],

            // ── ESPRESSO DOUBLE SHOT ─────────────────────────────────────
            'espresso-double-shot' => [
                [1, 5, 'Đắng chuẩn, crema sánh vàng như ý. Dân cà phê chính hiệu sẽ hiểu cảm giác này. Không pha đường, không thêm gì, uống nguyên vẹn.'],
                [5, 4, 'Hạt rang vừa, không bị cháy. Hương caramel và socola ngầm rất rõ sau khi uống. Recommend cho ai thích cà phê mộc.'],
                [6, 3, 'Với người mới uống espresso thì có thể hơi mạnh, nhưng nếu quen rồi thì không thể phủ nhận chất lượng. Mình sẽ quay lại.'],
                [7, 5, 'Tuyệt vời! Đây là espresso ngon nhất mình uống ở Hà Nội. Crema dày, vị đắng vừa, hậu vị kéo dài.'],
            ],

            // ── COLD BREW CAM VÀNG SẢ TƯƠI ──────────────────────────────
            'cold-brew-cam-vang-sa-tuoi' => [
                [0, 5, 'Ôi trời ơi ngon không tưởng! Cà phê mà lại tươi mát như sinh tố ấy. Mùa hè này phải gọi liên tục thôi 🍊'],
                [2, 5, 'Cold brew ủ 18h rõ ràng khác hẳn loại pha nhanh. Vị ít axit, uống nhiều không xót bụng. Cam và sả kết hợp rất khéo.'],
                [4, 4, 'Lần đầu thử vì tò mò, không ngờ thành món ruột. Nhờ có sả nên uống vào người thấy dễ chịu, thanh mát hẳn.'],
                [6, 4, 'Đóng gói đẹp, đi kèm ống hút giấy. Vị thức uống 10/10. Chỉ tiếc hôm nay pha hơi ít đá hơn bình thường.'],
                [7, 5, 'Combination này ai nghĩ ra là thiên tài luôn. Sả + cam + cold brew = combo mùa hè không đâu sánh bằng!'],
            ],

            // ── MATCHA LATTE UJI CAO CẤP ────────────────────────────────
            'matcha-latte-uji-cao-cap' => [
                [1, 5, 'Matcha Uji thật sự chất lượng khác hẳn matcha thường. Màu xanh đẹp, vị chát thanh, không bị ngọt gắt. Mình là fan matcha và rất hài lòng!'],
                [3, 4, 'Uống vừa thơm vừa ngon. Ít ngọt hơn các chỗ khác rất nhiều, cảm giác uống vào tỉnh táo mà không lo béo. Sẽ quay lại!'],
                [5, 5, 'Đây là cup matcha latte đẹp nhất mình từng chụp 🍵 Lớp bọt sữa latte art công phu, vị thì không cần bàn nữa rồi.'],
                [0, 4, 'Thêm trân châu konjac vào ngon lắm, giòn giòn không bị ngọt. Tổng thể ly này rất balance, không bị quá ngọt hay quá chát.'],
            ],

            // ── TRÀ ĐÀO CAM SẢ THƯỢNG HẠNG ─────────────────────────────
            'tra-dao-cam-sa-thuong-hang' => [
                [2, 5, 'Chưa uống loại trà đào nào ngon thế này! Đào giòn ngâm mật ong thiệt sự ngọt tự nhiên, không hóa chất. Hương sả và cam hòa quyện rất tinh tế.'],
                [4, 5, 'Quá đỉnh 🌸 Mỗi ngụm là một lần ngạc nhiên. Màu đẹp như ảnh, vị thật như mô tả. Đây giờ là order quen của mình rồi.'],
                [6, 4, 'Rất thanh, rất nhẹ, phù hợp uống buổi chiều. Miếng đào giòn là điểm nhấn đặc biệt. Chỉ muốn thêm 1 miếng nữa thôi 😄'],
                [7, 4, 'Hương thơm tự nhiên, không hắc mùi hóa chất như một số chỗ khác. Đá đầy ly giữ lạnh rất tốt. Hài lòng lắm.'],
            ],

            // ── TRÀ HOA CÚC MẬT ONG DƯỠNG NHAN ─────────────────────────
            'tra-hoa-cuc-mat-ong-duong-nhan' => [
                [0, 5, 'Uống vào thấy nhẹ người ngay. Hương hoa cúc thật sự rất dịu, mật ong rừng ngọt thanh không ngấy. Tối trước ngủ hay uống ly này.'],
                [3, 4, 'Trà thảo mộc mà ngon như thế này thì hiếm lắm. Phù hợp cho người kiêng đường hoặc muốn uống gì đó lành mạnh.'],
                [5, 5, 'Dùng nóng trong buổi sáng thì tuyệt vời luôn. Nhẹ nhàng, thơm, giúp mình tỉnh táo mà không bị hồi hộp như cà phê. ❤️'],
            ],

            // ── BÁNH CROISSANT BƠ PHÁP ──────────────────────────────────
            'banh-croissant-bo-phap-truyen-thong' => [
                [1, 5, 'Giòn rụm từ lớp ngoài đến tận trong. Mùi bơ thơm phức, ngửi là thèm liền. Nướng mới sáng nên còn nóng hổi khi nhận.'],
                [2, 4, 'Croissant ngon hơn mấy chỗ bakery nổi tiếng mình từng ăn. Bột cán kỹ, lớp đều, không bị bết. Sẽ order đều vào buổi sáng!'],
                [4, 5, 'Ăn không cần chấm gì thêm vẫn ngon. Vỏ vàng ươm giòn nhẹ, ruột ẩm mềm dai nhẹ. Đây là croissant chuẩn Pháp nhất mình từng ăn ở VN.'],
                [6, 4, 'Cặp với ly latte thì không gì bằng. Bánh nhỏ vừa ăn, không quá no, phù hợp ăn nhẹ buổi sáng. Giá cũng ổn.'],
            ],

            // ── BÁNH TIRAMISU Ý MASCARPONE ───────────────────────────────
            'banh-tiramisu-y-mascarpone' => [
                [0, 5, 'Tiramisu ngon nhất từ trước đến nay mình được ăn! Kem Mascarpone mịn không tưởng, cốt bánh thấm cà phê vừa đủ, cacao phủ đều. 10/10!'],
                [3, 5, 'Không quá ngọt, không quá đắng — cân bằng hoàn hảo. Cảm giác như đang ngồi ở một tiệm cafe nhỏ Italy ấy.'],
                [5, 4, 'Đóng hộp đẹp, tiện mang đi tặng. Vị ngon mà không bị ngấy dù ăn hết cả miếng to. Sẽ order thêm cho dịp sinh nhật bạn.'],
                [7, 5, 'Đây là tiramisu thủ công thật sự chứ không phải bán công nghiệp. Cảm nhận được tâm huyết của người làm bánh. Xuất sắc!'],
            ],

            // ── COOKIES SOCOLA CHIP HẠT ĐIỀU ─────────────────────────────
            'cookies-socola-chip-hat-dieu' => [
                [1, 4, 'Giòn đều, không bị cứng hay quá mềm. Hạt điều nhiều hơn mình nghĩ, ăn vào thấy rất bùi. Mua cả hộp để ăn dần cả tuần.'],
                [2, 5, 'Socola chip chất lượng, tan trong miệng. Kết hợp cùng cold brew là combo perfect cho chiều mưa 🍪☕'],
                [6, 4, 'Ngon hơn bánh ngoài siêu thị nhiều. Thủ công thật sự, mỗi cái mỗi hình dạng khác nhau trông handmade đáng yêu lắm.'],
            ],

            // ── BÁNH MÌ BAGEL KẸP CREAM CHEESE ─────────────────────────
            'banh-mi-bagel-kep-cream-cheese' => [
                [0, 4, 'Vỏ giòn dai đúng kiểu bagel, không bị bột hay bết. Cream cheese bơm nhiều hào phóng, ăn rất thỏa mãn.'],
                [4, 5, 'New York style chuẩn! Mình hay ăn bagel lúc ở nước ngoài nên khi về tìm mãi mới thấy chỗ làm đúng kiểu như này. Rất hài lòng.'],
                [7, 4, 'Ăn no mà không ngán, thích hợp bữa sáng bận rộn. Vừa tiện vừa ngon. Lần sau sẽ thử thêm topping khác xem sao.'],
            ],
        ];

        // ── 3. Insert dữ liệu vào bảng ratings ───────────────────────────
        foreach ($data as $slug => $reviews) {
            $product = Product::where('slug', $slug)->first();

            if (! $product) {
                $this->command->warn("Không tìm thấy sản phẩm: {$slug}");
                continue;
            }

            foreach ($reviews as [$userIndex, $star, $comment]) {
                $user = $users[$userIndex] ?? null;
                if (! $user) {
                    continue;
                }

                Rating::updateOrCreate(
                    [
                        'user_id'    => $user->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'rating'  => $star,
                        'comment' => $comment,
                    ]
                );
            }

            $this->command->info("✓ Đã seed ratings cho: {$product->name}");
        }
    }
}
