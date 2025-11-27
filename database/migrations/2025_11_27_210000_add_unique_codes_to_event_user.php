<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Agrega códigos QR y de barras ÚNICOS por inscripción (usuario + evento)
     */
    public function up(): void
    {
        // Paso 1: Agregar columnas que faltan
        Schema::table('event_user', function (Blueprint $table) {
            if (!Schema::hasColumn('event_user', 'qr_code')) {
                $table->string('qr_code', 36)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('event_user', 'barcode')) {
                $table->string('barcode', 15)->nullable()->after('qr_code');
            }
            if (!Schema::hasColumn('event_user', 'code_status')) {
                $table->enum('code_status', ['active', 'used', 'revoked'])->default('active')->after('barcode');
            }
            if (!Schema::hasColumn('event_user', 'code_used_at')) {
                $table->timestamp('code_used_at')->nullable()->after('code_status');
            }
            if (!Schema::hasColumn('event_user', 'scan_count')) {
                $table->integer('scan_count')->default(0)->after('code_used_at');
            }
            if (!Schema::hasColumn('event_user', 'created_at')) {
                $table->timestamps();
            }
        });

        // Paso 2: Generar códigos para inscripciones que no tengan
        $registrations = DB::table('event_user')->whereNull('qr_code')->orWhere('qr_code', '')->get();
        foreach ($registrations as $reg) {
            $qrCode = Str::uuid()->toString();
            $barcode = $this->generateBarcode($reg->event_id, $reg->user_id);
            
            // Asegurar unicidad del barcode
            while (DB::table('event_user')->where('barcode', $barcode)->where('id', '!=', $reg->id)->exists()) {
                $barcode = $this->generateBarcode($reg->event_id, $reg->user_id);
            }
            
            DB::table('event_user')
                ->where('id', $reg->id)
                ->update([
                    'qr_code' => $qrCode,
                    'barcode' => $barcode,
                    'created_at' => $reg->assigned_at ?? now(),
                    'updated_at' => now(),
                ]);
        }

        // Paso 3: Agregar restricciones unique si no existen
        // Primero verificamos si los índices ya existen
        $indexes = collect(DB::select("SHOW INDEX FROM event_user"))->pluck('Key_name')->unique();
        
        Schema::table('event_user', function (Blueprint $table) use ($indexes) {
            if (!$indexes->contains('event_user_qr_code_unique')) {
                $table->unique('qr_code');
            }
            if (!$indexes->contains('event_user_barcode_unique')) {
                $table->unique('barcode');
            }
        });
    }

    public function down(): void
    {
        $indexes = collect(DB::select("SHOW INDEX FROM event_user"))->pluck('Key_name')->unique();
        
        Schema::table('event_user', function (Blueprint $table) use ($indexes) {
            if ($indexes->contains('event_user_qr_code_unique')) {
                $table->dropUnique(['qr_code']);
            }
            if ($indexes->contains('event_user_barcode_unique')) {
                $table->dropUnique(['barcode']);
            }
        });
        
        Schema::table('event_user', function (Blueprint $table) {
            $columns = ['qr_code', 'barcode', 'code_status', 'code_used_at', 'scan_count', 'created_at', 'updated_at'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('event_user', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    private function generateBarcode(int $eventId, int $userId): string
    {
        $prefix = 'E' . str_pad($eventId, 4, '0', STR_PAD_LEFT);
        $userPart = 'U' . str_pad($userId, 4, '0', STR_PAD_LEFT);
        $random = strtoupper(Str::random(4));
        
        return $prefix . $userPart . $random;
    }
};
