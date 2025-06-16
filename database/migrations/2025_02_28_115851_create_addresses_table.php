<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cep', 9);
            $table->string('rua');
            $table->string('numero');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('delivery_type', ['retirada', 'delivery'])->default('retirada');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->decimal('shipping_cost', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn(['delivery_type', 'address_id', 'shipping_cost']);
        });

        Schema::dropIfExists('addresses');
    }
};
