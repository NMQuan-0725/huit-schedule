<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Teacher;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo các cơ sở thực tế của trường
        $branch1 = Branch::create(['branch_name' => 'Cơ sở chính Tân Phú', 'address' => '140 Lê Trọng Tấn, Tây Thạnh, Tân Phú, TP.HCM']);
        $branch2 = Branch::create(['branch_name' => 'Cơ sở trung tâm liên kết', 'address' => 'Quận 1, TP.HCM']);

        $faker = \Faker\Factory::create('vi_VN');

        // Tạo danh sách giảng viên
        $teachers = [];
        for ($i = 0; $i < 5; $i++) {
            $teachers[] = Teacher::create([
                'full_name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'expertise' => $faker->randomElement(['Lập trình Web', 'Cơ sở dữ liệu', 'Trí tuệ nhân tạo', 'Mạng máy tính'])
            ]);
        }

        // Tạo lịch dạy mẫu
        $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
        $shifts = ['Tiết 1-3 (Buổi sáng)', 'Tiết 4-6 (Buổi sáng)', 'Tiết 7-9 (Buổi chiều)', 'Tiết 10-12 (Buổi chiều)'];

        foreach ($teachers as $teacher) {
            Schedule::create([
                'teacher_id' => $teacher->id,
                'branch_id' => rand(1, 2) == 1 ? $branch1->id : $branch2->id,
                'subject_name' => $faker->randomElement(['Lập trình Laravel', 'Hệ quản trị CSDL', 'Đồ án tốt nghiệp', 'Nhập môn CNPM']),
                'room' => 'Phòng máy ' . rand(101, 305),
                'day_of_week' => $days[array_rand($days)],
                'shift_or_periods' => $shifts[array_rand($shifts)]
            ]);
        }
    }
}