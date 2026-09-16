<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lamaran Pekerjaan Baru</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f5f7; padding: 20px; color: #333;">
    <div style="max-width: 600px; background: #ffffff; padding: 30px; border-radius: 8px; margin: 0 auto;">
        <h2 style="color: #4f46e5; margin-top: 0;">Ada Pelamar Baru!</h2>
        <p>Seseorang baru saja mengirimkan lamaran pekerjaan melalui website:</p>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; width: 140px;">Posisi:</td>
                <td style="padding: 8px 0;">{{ $application->career->title }} ({{ $application->career->department }})
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Nama Pelamar:</td>
                <td style="padding: 8px 0;">{{ $application->full_name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                <td style="padding: 8px 0;">{{ $application->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">No. HP / WhatsApp:</td>
                <td style="padding: 8px 0;">{{ $application->phone }}</td>
            </tr>
        </table>

        @if($application->cover_letter)
            <div
                style="margin-top: 20px; padding: 15px; background: #f9fafb; border-left: 4px solid #4f46e5; border-radius: 4px;">
                <h4 style="margin: 0 0 8px 0; color: #374151;">Pesan / Cover Letter:</h4>
                <p style="margin: 0; font-size: 14px; white-space: pre-line;">{{ $application->cover_letter }}</p>
            </div>
        @endif

        <p style="margin-top: 25px; font-size: 13px; color: #6b7280;">
            * Berkas CV/Resume terlampir pada email ini.
        </p>
    </div>
</body>

</html>