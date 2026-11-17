<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUIT Schedule Hub - Hệ Thống Quản Lý Đa Cơ Sở</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <!-- Header chuẩn nhận diện HUIT -->
    <header class="bg-red-700 text-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center">
            <div>
                <span class="text-xs uppercase tracking-wider font-semibold text-red-200">BỘ CÔNG THƯƠNG - TRƯỜNG ĐẠI HỌC CÔNG THƯƠNG TP. HỒ CHÍ MINH</span>
                <h1 class="text-2xl font-bold tracking-tight">HUIT SCHEDULE HUB</h1>
            </div>
            <div class="mt-2 md:mt-0 text-sm bg-red-800 px-3 py-1.5 rounded-full border border-red-600">
                Hệ thống Quản lý Lịch & Đào tạo Đa Cơ Sở
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8 max-w-7xl">
        
        <!-- HỆ THỐNG TABS ĐIỀU HƯỚNG QUẢN LÝ -->
        <div class="flex border-b border-slate-200 mb-6 bg-white rounded-t-lg shadow-xs px-4 pt-2">
            <a href="{{ route('schedules.index', ['tab' => 'timetable', 'branch_id' => $branchId, 'teacher_id' => $teacherId]) }}" 
               class="py-3 px-6 font-semibold text-sm border-b-2 transition {{ ($tab ?? 'timetable') === 'timetable' ? 'border-red-600 text-red-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                🗓️ Lưới Thời Khóa Biểu (15 Tiết)
            </a>
            <a href="{{ route('schedules.index', ['tab' => 'teachers']) }}" 
               class="py-3 px-6 font-semibold text-sm border-b-2 transition {{ ($tab ?? '') === 'teachers' ? 'border-red-600 text-red-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                👨‍🏫 Danh Sách Giảng Viên & Khối Lượng
            </a>
            <a href="{{ route('schedules.index', ['tab' => 'subjects']) }}" 
               class="py-3 px-6 font-semibold text-sm border-b-2 transition {{ ($tab ?? '') === 'subjects' ? 'border-red-600 text-red-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                📚 Quản Lý Học Phần & Phụ Trách
            </a>
        </div>

        <!-- ================= TAB 1: LƯỚI THỜI KHÓA BIỂU (15 TIẾT) ================= -->
        @if(($tab ?? 'timetable') === 'timetable')
            <!-- Khối Bộ lọc tra cứu -->
            <div class="bg-white shadow-sm border border-slate-200 rounded-lg p-6 mb-6">
                <h2 class="text-base font-semibold text-slate-800 mb-4 pb-2 border-b border-slate-100">
                    🔍 Bộ Lọc Lưới Lịch Theo Cơ Sở & Giảng Viên
                </h2>
                <form method="GET" action="{{ route('schedules.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <input type="hidden" name="tab" value="timetable">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Cơ sở trường học</label>
                        <select name="branch_id" class="w-full border-slate-300 rounded-md shadow-sm border p-2.5 text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">-- Tất cả cơ sở --</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-600 mb-1">Giảng viên</label>
                        <select name="teacher_id" class="w-full border-slate-300 rounded-md shadow-sm border p-2.5 text-sm focus:ring-red-500 focus:border-red-500">
                            <option value="">-- Tất cả giảng viên --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }} ({{ $teacher->expertise }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-md shadow transition duration-150 text-sm">
                            Áp Dụng Lọc Lịch
                        </button>
                    </div>
                </form>
            </div>

            <!-- LƯỚI 15 TIẾT CHUẨN ĐÀO TẠO (VỚI ROWSPAN & PARSING LOGIC) -->
            <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-slate-100 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wide">🗓️ Ma Trận Thời Khóa Biểu (Phân bổ 15 tiết/ngày)</h3>
                    <span class="text-xs text-slate-500 font-medium">Block chuẩn: Giãn dòng tự động theo số tiết</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-slate-200 text-xs">
                        <thead>
                            <tr class="bg-slate-200 text-slate-700 text-center">
                                <th class="border border-slate-300 px-2 py-3 w-28 font-bold">Tiết / Thời gian</th>
                                <th class="border border-slate-300 px-4 py-3 font-bold">Thứ 2</th>
                                <th class="border border-slate-300 px-4 py-3 font-bold">Thứ 3</th>
                                <th class="border border-slate-300 px-4 py-3 font-bold">Thứ 4</th>
                                <th class="border border-slate-300 px-4 py-3 font-bold">Thứ 5</th>
                                <th class="border border-slate-300 px-4 py-3 font-bold">Thứ 6</th>
                                <th class="border border-slate-300 px-4 py-3 font-bold">Thứ 7</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                                $timeSlots = [
                                    1  => '07:00 - 07:50', 2  => '07:50 - 08:40', 3  => '08:50 - 09:40', 
                                    4  => '09:40 - 10:30', 5  => '10:30 - 11:20', 6  => '11:20 - 12:10',
                                    7  => '13:00 - 13:50', 8  => '13:50 - 14:40', 9  => '14:50 - 15:40', 
                                    10 => '15:40 - 16:30', 11 => '16:30 - 17:20', 12 => '17:40 - 18:30',
                                    13 => '18:30 - 19:20', 14 => '19:20 - 20:10', 15 => '20:10 - 21:00',
                                ];

                                // Khởi tạo ma trận lưới và mảng skip để xử lý rowspan
                                $grid = [];
                                $skip = [];
                                for ($p = 1; $p <= 15; $p++) {
                                    foreach ($days as $dIndex => $day) {
                                        $grid[$p][$dIndex] = [];
                                        $skip[$p][$dIndex] = false;
                                    }
                                }

                                foreach ($schedules as $sch) {
                                    $dayName = trim($sch->day_of_week);
                                    $dIndex = array_search($dayName, $days);
                                    if ($dIndex === false) continue;

                                    // Parse chính xác tiết bắt đầu và tiết kết thúc bằng Regex
                                    preg_match('/(\d+)(?:\s*-\s*(\d+))?/', $sch->shift_or_periods, $matches);
                                    $start = isset($matches[1]) ? (int)$matches[1] : 1;
                                    $end = isset($matches[2]) ? (int)$matches[2] : $start;
                                    
                                    $start = max(1, min(15, $start));
                                    $end = max($start, min(15, $end));
                                    $span = $end - $start + 1;

                                    $grid[$start][$dIndex][] = [
                                        'schedule' => $sch,
                                        'span' => $span
                                    ];

                                    // Đánh dấu bỏ qua các dòng bị chiếm chỗ bởi rowspan
                                    for ($p = $start + 1; $p <= $end; $p++) {
                                        $skip[$p][$dIndex] = true;
                                    }
                                }
                            @endphp

                            @for ($period = 1; $period <= 15; $period++)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="border border-slate-300 px-2 py-2 text-center font-semibold bg-slate-50 text-slate-700">
                                        <div>Tiết {{ $period }}</div>
                                        <div class="text-[10px] text-slate-500 font-normal">{{ $timeSlots[$period] }}</div>
                                    </td>

                                    @foreach ($days as $dIndex => $day)
                                        @if ($skip[$period][$dIndex])
                                            @continue
                                        @endif

                                        @php
                                            $cellItems = $grid[$period][$dIndex];
                                            $maxSpan = 1;
                                            foreach ($cellItems as $item) {
                                                if ($item['span'] > $maxSpan) {
                                                    $maxSpan = $item['span'];
                                                }
                                            }
                                        @endphp

                                        <!-- Thêm class 'relative' để định vị khung kéo giãn -->
                                        <td class="border border-slate-300 p-2 align-top w-1/6 relative h-auto" @if($maxSpan > 1) rowspan="{{ $maxSpan }}" @endif>
                                            <div class="absolute inset-1.5 flex flex-col gap-1">
                                                @forelse($cellItems as $item)
                                                    @php $sch = $item['schedule']; @endphp
                                                    <!-- Thêm flex-1 để thẻ tự động giãn đều chiều cao theo số rowspan -->
                                                    <div class="bg-red-50 border-l-4 border-red-600 p-2 rounded shadow-xs flex-1 flex flex-col justify-between overflow-hidden">
                                                        <div>
                                                            <p class="font-bold text-red-900 text-[11px]">{{ $sch->subject_name }}</p>
                                                            <p class="text-slate-700 font-medium text-[10px]">👨‍🏫 {{ $sch->teacher->full_name ?? 'N/A' }}</p>
                                                            <p class="text-slate-600 text-[10px]">🏫 {{ $sch->branch->branch_name ?? '' }} - <b>{{ $sch->room }}</b></p>
                                                        </div>
                                                        <span class="inline-block mt-1 bg-red-200 text-red-800 text-[9px] px-1.5 py-0.5 rounded font-semibold w-fit">{{ $sch->shift_or_periods }}</span>
                                                    </div>
                                                @empty
                                                    <!-- Ô trống -->
                                                @endforelse
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

        <!-- ================= TAB 2: QUẢN LÝ GIẢNG VIÊN ================= -->
        @elseif(($tab ?? '') === 'teachers')
            <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden p-6">
                <h3 class="font-bold text-slate-800 text-base mb-4">👨‍🏫 Danh Sách Giảng Viên & Thống Kê Khối Lượng Giảng Dạy</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-100 text-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">STT</th>
                                <th class="px-6 py-3 text-left font-semibold">Họ và tên</th>
                                <th class="px-6 py-3 text-left font-semibold">Email liên hệ</th>
                                <th class="px-6 py-3 text-left font-semibold">Số điện thoại</th>
                                <th class="px-6 py-3 text-left font-semibold">Chuyên môn</th>
                                <th class="px-6 py-3 text-center font-semibold">Tổng lớp dạy trong tuần</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($teachers as $index => $t)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-900">{{ $t->full_name }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $t->email }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $t->phone }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-medium">{{ $t->expertise }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-emerald-100 text-emerald-800 font-bold px-3 py-1 rounded-full text-xs">
                                            {{ $t->schedules_count }} lớp
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        <!-- ================= TAB 3: QUẢN LÝ HỌC PHẦN ================= -->
        @elseif(($tab ?? '') === 'subjects')
            <div class="bg-white shadow-sm border border-slate-200 rounded-lg overflow-hidden p-6">
                <h3 class="font-bold text-slate-800 text-base mb-4">📚 Danh Sách Học Phần & Giảng Viên Phụ Trách Hằng Tuần</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($subjects as $subjectName => $classSchedules)
                        <div class="border border-slate-200 rounded-lg p-5 bg-slate-50 shadow-xs">
                            <h4 class="font-bold text-red-700 text-base mb-2 border-b pb-2 flex justify-between items-center">
                                <span>📖 {{ $subjectName }}</span>
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full font-semibold">{{ count($classSchedules) }} lớp học</span>
                            </h4>
                            <div class="space-y-2 mt-3">
                                @foreach($classSchedules as $sch)
                                    <div class="bg-white p-3 rounded border border-slate-200 text-xs flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-slate-900">👨‍🏫 {{ $sch->teacher->full_name ?? 'N/A' }}</p>
                                            <p class="text-slate-500">🏫 {{ $sch->branch->branch_name ?? '' }} (Phòng: {{ $sch->room }})</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="bg-green-100 text-green-800 font-semibold px-2 py-0.5 rounded">{{ $sch->day_of_week }}</span>
                                            <p class="text-slate-500 text-[10px] mt-1">{{ $sch->shift_or_periods }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-8 text-slate-500">Chưa có dữ liệu học phần nào trong hệ thống.</div>
                    @endforelse
                </div>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="text-center py-6 text-xs text-slate-500 border-t border-slate-200 mt-12 bg-white">
        HUIT Schedule Hub &copy; 2026 - Hệ thống quản lý và phân lịch giảng dạy đa cơ sở.
    </footer>
</body>
</html>