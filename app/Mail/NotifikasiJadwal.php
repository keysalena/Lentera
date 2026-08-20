<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiJadwal extends Mailable
{
    use Queueable, SerializesModels;

    public $siswa;
    public $jadwal;

    public function __construct($siswa, $jadwal)
    {
        $this->siswa = $siswa;
        $this->jadwal = $jadwal; 
    }

    public function build()
    {
        $waktuMulai = \Carbon\Carbon::parse($this->jadwal->jadwal_konsultasi)->setTimezone('UTC')->format('Ymd\THis\Z');
        $waktuSelesai = \Carbon\Carbon::parse($this->jadwal->jadwal_konsultasi)->addHour()->setTimezone('UTC')->format('Ymd\THis\Z');

        $icalContent = "BEGIN:VCALENDAR\n" .
            "VERSION:2.0\n" .
            "PRODID:-//LENTERA App//ID\n" .
            "BEGIN:VEVENT\n" .
            "UID:" . uniqid() . "@lentera.app\n" .
            "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\n" .
            "DTSTART:" . $waktuMulai . "\n" .
            "DTEND:" . $waktuSelesai . "\n" .
            "SUMMARY:Konsultasi Karier BK LENTERA\n" .
            "DESCRIPTION:Jadwal konsultasi hasil eksplorasi karier AI Anda.\n" .
            "END:VEVENT\n" .
            "END:VCALENDAR";

        return $this->subject('📅 Konfirmasi Jadwal Konsultasi Karier LENTERA')
            ->view('emails.jadwal_konseling') 
            ->attachData($icalContent, 'jadwal_konseling.ics', [
                'mime' => 'text/calendar',
            ]);
    }
}
