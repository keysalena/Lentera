<!DOCTYPE html>
<html>

<head>
    <title>Jadwal Konseling</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden;">
        <div style="background: #D97706; padding: 20px; text-align: center; color: white;">
            <h2 style="margin: 0;">Konfirmasi Jadwal Konseling</h2>
        </div>
        <div style="padding: 20px;">
            <p>Halo, <strong>{{ $siswa->nama }}</strong>,</p>
            <p>Guru BK Anda telah menetapkan jadwal konsultasi tatap muka untuk membahas hasil eksplorasi karier Anda. Berikut detailnya:</p>

            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 5px 0;">📅 <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($jadwal->jadwal_konsultasi)->format('d F Y') }}</p>
                <p style="margin: 5px 0;">⏰ <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($jadwal->jadwal_konsultasi)->format('H:i') }} WIB</p>
                <p style="margin: 5px 0;">📍 <strong>Ruang:</strong> Ruang BK Sekolah</p>
            </div>

            <p><strong>Penting:</strong> Silakan buka file <code>.ics</code> yang terlampir di email ini untuk menambahkan jadwal secara otomatis ke kalender ponsel Anda agar tidak terlewat.</p>
        </div>
    </div>
</body>

</html>