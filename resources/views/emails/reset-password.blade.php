<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f6f9fc;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: #333;
        }

        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: linear-gradient(90deg, #1b5e20, #4caf50);
            color: #fff;
            text-align: center;
            padding: 25px 15px;
        }

        .logo {
            width: 180px;
            margin-bottom: 10px;
            display: inline-block;
        }

        .content {
            padding: 30px 40px;
            text-align: center;
        }

        .content h2 {
            margin-top: 0;
            color: #1e293b;
            font-size: 22px;
        }

        .content p {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(90deg, #2e7d32, #4caf50);
            color: #fff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(46, 125, 50, 0.3);
            transition: all 0.25s ease;
        }

        .btn:hover {
            background: linear-gradient(90deg, #1b5e20, #388e3c);
            box-shadow: 0 4px 10px rgba(46, 125, 50, 0.4);
        }

        .footer {
            background: #f1f5f9;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #64748b;
        }

        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">
                {{-- Inline SVG langsung --}}
                {!! file_get_contents(public_path('logo.svg')) !!}
            </div>
            <h1 style="margin: 10px 0;">TernakSyams</h1>
        </div>

        <div class="content">
            <h2>Reset Password Anda</h2>
            <p>Halo! Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.<br>
                Klik tombol di bawah ini untuk membuat kata sandi baru.</p>

            <a href="{{ $url }}" class="btn">Reset Password</a>

            <p style="margin-top:30px;">
                Jika Anda tidak meminta reset password, abaikan email ini.<br>
                Link ini akan kedaluwarsa dalam beberapa waktu.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} TernakSyams. All rights reserved.
        </div>
    </div>
</body>

</html>
