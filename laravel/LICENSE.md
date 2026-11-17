#### 2. Tạo file `LICENSE` (MIT License)
Tạo một file mới hoàn toàn có tên là **`LICENSE`** (không có phần mở rộng) ở thư mục gốc của dự án và dán nội dung sau vào[cite: 1]:
```text
MIT License

Copyright (c) 2026 HUIT Schedule Hub

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```[cite: 1]

---

### BƯỚC 2: Thiết lập Tự động hóa CI/CD (GitHub Actions)

Để hệ thống tự động kiểm tra lỗi cú pháp PHP mỗi khi bạn `git push` lên GitHub[cite: 1]:

1. Tạo thư mục cấu trúc bên trong dự án: `.github/workflows/`
2. Tạo file bên trong thư mục đó đặt tên là **`ci.yml`** (`.github/workflows/ci.yml`) với nội dung:
```yaml
name: PHP Code Check (CI)

on:
  push:
    branches: [ "main", "master" ]
  pull_request:
    branches: [ "main", "master" ]

jobs:
  php-syntax-check:
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v4

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.3'

    - name: Run PHP Syntax Check
      run: |
        find . -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n 1 php -l
```[cite: 1]

---

### Bước cuối cùng: Đẩy code hoàn thiện lên GitHub
Sau khi đã hoàn tất các file trên, bạn chỉ cần mở terminal và chạy các lệnh Git quen thuộc:
```bash
git add .
git commit -m "Complete Part 3: Open Source documentation and GitHub Actions CI/CD setup"
git push origin main