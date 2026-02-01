<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reseller Approved</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background:#198754; padding:24px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:22px;">
                                🎉 Akun Reseller Disetujui
                            </h1>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:16px; color:#333; margin:0 0 12px;">
                                Halo <strong>{{ $reseller->name }}</strong>,
                            </p>

                            <p style="font-size:15px; color:#555; line-height:1.6; margin:0 0 16px;">
                                Kabar baik! Pendaftaran Anda sebagai <strong>Reseller TernakSyams</strong>
                                telah <strong style="color:#198754;">disetujui</strong>.
                            </p>

                            <p style="font-size:15px; color:#555; line-height:1.6; margin:0 0 24px;">
                                Sekarang Anda sudah dapat mengakses fitur reseller dan mulai menjalankan
                                bisnis bersama kami.
                            </p>

                            {{-- BUTTON --}}
                            {{-- <div style="text-align:center; margin:30px 0;">
                                <a href="{{ url('/login') }}"
                                    style="background:#198754; color:#ffffff; text-decoration:none;
                                      padding:12px 28px; border-radius:6px; font-size:15px;
                                      display:inline-block;">
                                    Masuk ke Dashboard
                                </a>
                            </div> --}}

                            <p style="font-size:14px; color:#777; line-height:1.6;">
                                Jika Anda memiliki pertanyaan, silakan balas email ini atau hubungi tim support kami.
                            </p>

                            <p style="font-size:14px; color:#555; margin-top:30px;">
                                Salam sukses,<br>
                                <strong>Tim TernakSyams</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background:#f1f3f5; padding:16px; text-align:center;">
                            <p style="font-size:12px; color:#888; margin:0;">
                                © {{ date('Y') }} TernakSyams. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
