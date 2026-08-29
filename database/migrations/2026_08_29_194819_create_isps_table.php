<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateIspsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isps', function (Blueprint $table) {
            $table->id();
            $table->string('isp_code')->unique()->index();
            $table->string('isp_name');
            $table->string('isp_tagline')->nullable();
            $table->string('isp_logo')->nullable();
            $table->string('isp_favicon')->nullable();
            $table->string('phone')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('currency_symbol', 10)->default('৳');
            $table->string('invoice_title', 100)->default('MONEY RECEIPT');
            $table->text('payment_instruction')->nullable();
            $table->string('payment_methods')->nullable();
            $table->string('payment_number')->nullable();
            $table->text('invoice_footer')->nullable();
            $table->string('signatory_title')->default('Authorized Signatory');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Seed default ISPs based on existing distinct isp_code in clients
        try {
            $existingCodes = DB::table('clients')->select('isp_code')->distinct()->whereNotNull('isp_code')->pluck('isp_code')->toArray();
        } catch (\Exception $e) {
            $existingCodes = [];
        }

        if (empty($existingCodes)) {
            $existingCodes = ['carnival', 'bijoy', 'icc'];
        }

        foreach ($existingCodes as $index => $code) {
            $code = strtolower(trim($code));
            if (!$code) continue;

            $name = ucfirst($code);
            if ($code === 'carnival') $name = 'Carnival Internet';
            elseif ($code === 'bijoy') $name = 'Bijoy Online';
            elseif ($code === 'icc') $name = 'ICC Communication';
            else $name = ucfirst($code) . ' Network';

            DB::table('isps')->insert([
                'isp_code'            => $code,
                'isp_name'            => $name,
                'isp_tagline'         => 'High-Speed Broadband Internet & Network Solutions',
                'isp_logo'            => null,
                'phone'               => '01XXXXXXXXX',
                'billing_phone'       => '01XXXXXXXXX',
                'email'               => 'support@' . $code . '.com.bd',
                'website'             => 'www.' . $code . '.com.bd',
                'address'             => 'Dhaka, Bangladesh',
                'currency_symbol'     => '৳',
                'invoice_title'       => 'MONEY RECEIPT',
                'payment_instruction' => 'Please pay through bKash/Nagad Merchant or Cash within the due date.',
                'payment_methods'     => 'bKash / Nagad / Cash',
                'payment_number'      => '01XXXXXXXXX',
                'invoice_footer'      => 'Keep this invoice receipt for future reference. Thank you for being with us!',
                'signatory_title'     => 'Authorized Signatory',
                'is_default'          => ($code === 'carnival' || ($index === 0 && !in_array('carnival', $existingCodes))),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isps');
    }
}
