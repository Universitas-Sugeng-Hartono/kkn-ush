<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Illuminate\Support\Facades\Http;

class UpdateLokasiKosong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:update-lokasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbarui data lokasi yang kosong menggunakan latitude dan longitude via Nominatim API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memperbarui Lokasi Masuk...");
        $attendances = Absensi::whereNull('lokasi')->whereNotNull('latitude')->get();
        foreach($attendances as $absensi) {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$absensi->latitude}&lon={$absensi->longitude}";
            $response = Http::withHeaders(['User-Agent' => 'KKN-App/1.0'])->get($url);
            if ($response->successful() && isset($response->json()['display_name'])) {
                $absensi->lokasi = $response->json()['display_name'];
                $absensi->save();
                $this->line("Updated ID {$absensi->id} (Masuk)");
            }
            sleep(1);
        }

        $this->info("Memperbarui Lokasi Keluar...");
        $attendancesOut = Absensi::whereNull('lokasi_keluar')->whereNotNull('latitude_keluar')->get();
        foreach($attendancesOut as $absensi) {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$absensi->latitude_keluar}&lon={$absensi->longitude_keluar}";
            $response = Http::withHeaders(['User-Agent' => 'KKN-App/1.0'])->get($url);
            if ($response->successful() && isset($response->json()['display_name'])) {
                $absensi->lokasi_keluar = $response->json()['display_name'];
                $absensi->save();
                $this->line("Updated ID {$absensi->id} (Keluar)");
            }
            sleep(1);
        }

        $this->info("Proses selesai.");
    }
}
