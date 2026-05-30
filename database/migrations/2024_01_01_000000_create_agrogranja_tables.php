<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('email', 150);
            $table->string('password', 255);
            $table->string('nombre_finca', 150)->nullable();
            $table->string('departamento', 100)->nullable();
            $table->string('municipio', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('foto_perfil', 255)->nullable();
            $table->string('foto_finca', 255)->nullable();
            $table->decimal('hectareas_total', 10, 2)->nullable();
            $table->string('tipo_produccion', 255)->nullable();
            $table->text('descripcion_finca')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->string('rut', 30)->nullable();
            $table->string('entidad_bancaria', 100)->nullable();
            $table->string('num_cuenta', 50)->nullable();
            $table->string('tipo_cuenta', 30)->nullable();
            $table->boolean('notif_tareas')->default(1);
            $table->boolean('notif_stock')->default(1);
            $table->string('moneda', 10)->default('COP');
            $table->string('tema', 20)->default('auto');
            $table->boolean('diagnostico_completado')->default(0);
            $table->boolean('onboarding_completado')->default(0);
            $table->boolean('activo')->default(1);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('lineas_productivas', function (Blueprint $table) {
            $table->string('codigo', 30);
            $table->string('nombre', 100);
            $table->string('emoji', 10)->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(1);
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('sesiones', function (Blueprint $table) {
            $table->string('id', 128);
            $table->integer('usuario_id');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->date('ultimo_acceso');
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->string('tipo', 100);
            $table->string('nombre', 150);
            $table->date('fecha_siembra');
            $table->date('fecha_cosecha_estimada')->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->enum('unidad', ['hectareas','metros2','fanegadas','lotes'])->default('hectareas');
            $table->enum('estado', ['activo','cosechado','vendido'])->default('activo');
            $table->integer('fase_actual_id')->nullable();
            $table->date('fecha_cambio_fase')->nullable();
            $table->decimal('rendimiento_esperado_ha', 10, 2)->nullable();
            $table->decimal('rendimiento_real_ha', 10, 2)->nullable();
            $table->text('notas')->nullable();
            $table->string('imagen', 255)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('cultivo_fases', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_cultivo', 100);
            $table->string('nombre', 100);
            $table->integer('orden');
            $table->integer('duracion_dias_min')->nullable();
            $table->integer('duracion_dias_max')->nullable();
            $table->string('color_hex', 7)->default('#4CAF50');
            $table->string('icono', 10)->default('?');
            $table->text('descripcion')->nullable();
            $table->string('creado_en');
        });

        Schema::create('plan_manejo_cultivo', function (Blueprint $table) {
            $table->id();
            $table->integer('cultivo_fase_id');
            $table->string('actividad', 200);
            $table->enum('tipo_actividad', ['fertilizacion','riego','control_fitosanitario','aplicacion_agroquimico','deshierbe','poda','monitoreo','otro'])->default('otro');
            $table->text('descripcion')->nullable();
            $table->string('producto_sugerido', 150)->nullable();
            $table->string('dosis_sugerida', 100)->nullable();
            $table->boolean('obligatoria')->default(0);
            $table->string('creado_en');
        });

        Schema::create('rendimiento_regional', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_cultivo', 100);
            $table->string('departamento', 100);
            $table->integer('anio');
            $table->decimal('rendimiento_promedio_ha', 10, 2);
            $table->decimal('rendimiento_min_ha', 10, 2)->nullable();
            $table->decimal('rendimiento_max_ha', 10, 2)->nullable();
            $table->string('unidad', 50)->default('ton/ha');
            $table->string('fuente', 200)->nullable();
            $table->string('creado_en');
        });

        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->enum('tipo', ['trabajador','proveedor','comprador','vecino','familiar','contacto','otro'])->default('contacto');
            $table->string('nombre', 150);
            $table->string('telefono', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('documento', 30)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('cargo', 100)->nullable();
            $table->enum('tipo_contrato', ['jornal','mensual','destajo','temporal','otro'])->nullable();
            $table->decimal('valor_jornal', 12, 2)->nullable();
            $table->decimal('valor_mensual', 12, 2)->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->string('labores', 255)->nullable();
            $table->boolean('activo')->default(1);
            $table->text('notas')->nullable();
            $table->boolean('favorito')->default(0);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('animales', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->string('especie', 100);
            $table->string('nombre_lote', 150)->nullable();
            $table->integer('cantidad')->default(1);
            $table->date('fecha_ingreso')->nullable();
            $table->enum('estado', ['activo','vendido','muerte'])->default('activo');
            $table->decimal('peso_promedio', 8, 2)->nullable();
            $table->enum('unidad_peso', ['kg','lb'])->default('kg');
            $table->text('notas')->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('ubicacion', 150)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_sacrificio')->nullable();
            $table->date('fecha_venta')->nullable();
            $table->decimal('valor_venta', 12, 2)->nullable();
            $table->decimal('precio_kilo', 10, 2)->nullable();
            $table->decimal('precio_unidad', 10, 2)->nullable();
            $table->boolean('vende_por_kilo')->default(1);
            $table->string('propietario', 150)->nullable();
            $table->boolean('favorito')->default(0);
            $table->boolean('atencion_especial')->default(0);
            $table->string('atencion_motivo', 255)->nullable();
            $table->string('etapa_vida', 20)->default('adulto');
            $table->string('produccion', 255)->nullable();
            $table->string('raza', 80)->nullable();
            $table->enum('categoria_bovina', ['vaca_lechera','vaca_carne','novilla','ternero','toro','buey'])->nullable();
            $table->decimal('peso_meta_kg', 8, 2)->nullable();
            $table->integer('madre_id')->nullable();
            $table->string('padre_descripcion', 150)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->string('nombre', 150);
            $table->string('categoria', 100);
            $table->decimal('cantidad_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(0);
            $table->string('unidad', 50);
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->string('proveedor', 150)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('notas')->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('ubicacion', 150)->nullable();
            $table->string('uso_principal', 50)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('cosechas', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->integer('cultivo_id')->nullable();
            $table->string('producto', 150);
            $table->decimal('cantidad', 10, 2);
            $table->string('unidad', 50);
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->decimal('valor_estimado', 12, 2)->nullable();
            $table->date('fecha_cosecha');
            $table->enum('calidad', ['excelente','buena','regular','baja'])->default('buena');
            $table->decimal('merma_porcentaje', 5, 2)->nullable();
            $table->string('destino', 100)->nullable();
            $table->string('comprador', 150)->nullable();
            $table->integer('cliente_id')->nullable();
            $table->string('almacen_ubicacion', 150)->nullable();
            $table->date('almacen_hasta')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('foto', 255)->nullable();
            $table->boolean('ingreso_creado')->default(0);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('gastos_recurrentes', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->string('categoria', 100);
            $table->string('descripcion', 255);
            $table->decimal('valor', 12, 2);
            $table->string('proveedor', 150)->nullable();
            $table->integer('proveedor_id')->nullable();
            $table->integer('cultivo_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->integer('persona_nomina_id')->nullable();
            $table->enum('frecuencia', ['semanal','quincenal','mensual','bimestral','trimestral','anual'])->default('mensual');
            $table->integer('dia_del_mes')->default(1);
            $table->boolean('activo')->default(1);
            $table->date('ultimo_generado')->nullable();
            $table->date('proximo_vencimiento')->nullable();
            $table->text('notas')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->integer('cultivo_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->string('titulo', 200);
            $table->string('tipo', 60)->default('otro');
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->boolean('completada')->default(0);
            $table->dateTime('fecha_completada')->nullable();
            $table->text('notas')->nullable();
            $table->text('notas_completada')->nullable();
            $table->string('responsable', 100)->nullable();
            $table->integer('persona_completada_id')->nullable();
            $table->enum('prioridad', ['baja','media','alta'])->default('media');
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->integer('cultivo_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->integer('cosecha_id')->nullable();
            $table->integer('tarea_id')->nullable();
            $table->integer('persona_id')->nullable();
            $table->string('categoria', 100);
            $table->string('descripcion', 255);
            $table->decimal('cantidad', 10, 2)->nullable();
            $table->string('unidad_cantidad', 50)->nullable();
            $table->decimal('valor', 12, 2);
            $table->date('fecha');
            $table->string('proveedor', 150)->nullable();
            $table->integer('proveedor_id')->nullable();
            $table->string('factura_numero', 100)->nullable();
            $table->string('foto_factura', 255)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('es_recurrente')->default(0);
            $table->integer('recurrente_id')->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->integer('cultivo_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->integer('cosecha_id')->nullable();
            $table->string('tipo', 50)->default('venta');
            $table->string('descripcion', 255);
            $table->decimal('cantidad', 10, 2)->nullable();
            $table->string('unidad', 50)->nullable();
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->decimal('valor_total', 12, 2);
            $table->date('fecha');
            $table->string('comprador', 150)->nullable();
            $table->integer('cliente_id')->nullable();
            $table->integer('persona_id')->nullable();
            $table->text('notas')->nullable();
            $table->string('foto_soporte', 255)->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('usuario_lineas', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->string('linea_codigo', 30);
            $table->integer('cantidad_aprox')->nullable();
            $table->enum('escala', ['pequena','mediana','grande'])->default('pequena');
            $table->text('metadata')->nullable();
            $table->string('notas', 255)->nullable();
            $table->boolean('activa')->default(1);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('animal_eventos', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->integer('usuario_id');
            $table->string('tipo', 30)->default('nota');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->string('foto_ruta', 255)->nullable();
            $table->date('fecha');
            $table->string('dosis', 100)->nullable();
            $table->date('proxima_dosis')->nullable();
            $table->integer('persona_id')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('animal_lactancia', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->integer('usuario_id');
            $table->integer('parto_id')->nullable();
            $table->integer('numero_lactancia')->default(1);
            $table->date('fecha_inicio');
            $table->date('fecha_secado')->nullable();
            $table->decimal('produccion_pico_litros', 8, 2)->nullable();
            $table->date('fecha_pico')->nullable();
            $table->decimal('produccion_acumulada_litros', 12, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('animal_ordenos', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->integer('usuario_id');
            $table->integer('lactancia_id')->nullable();
            $table->date('fecha');
            $table->enum('sesion', ['am','pm','unica'])->default('am');
            $table->decimal('litros', 8, 2);
            $table->decimal('temperatura_leche', 5, 2)->nullable();
            $table->string('observaciones', 255)->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('animal_pesos', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->integer('usuario_id');
            $table->decimal('peso', 8, 2);
            $table->enum('unidad', ['kg','lb'])->default('kg');
            $table->date('fecha');
            $table->string('notas', 255)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('animal_produccion', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->integer('usuario_id');
            $table->date('fecha');
            $table->string('tipo_produccion', 50);
            $table->decimal('cantidad', 10, 2);
            $table->string('unidad', 20);
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->decimal('valor_total', 14, 2)->nullable();
            $table->boolean('vendido')->default(0);
            $table->string('comprador', 150)->nullable();
            $table->string('notas', 255)->nullable();
            $table->boolean('ingreso_creado')->default(0);
            $table->timestamp('creado_en')->useCurrent();
            $table->enum('sesion', ['am','pm','noche','manana','tarde','unica','general'])->default('unica');
            $table->enum('destino', ['consumo_familiar','venta_directa','transformacion','inventario','desperdicio'])->default('venta_directa');
            $table->string('transformacion_tipo', 80)->nullable();
            $table->decimal('costo_estimado', 12, 2)->nullable();
            $table->integer('inventario_id')->nullable();
            $table->string('periodo', 20)->nullable();
        });

        Schema::create('animal_reproduccion', function (Blueprint $table) {
            $table->id();
            $table->integer('animal_id');
            $table->integer('usuario_id');
            $table->enum('tipo_servicio', ['monta_natural','inseminacion_artificial','monta_controlada'])->default('monta_natural');
            $table->date('fecha_servicio');
            $table->string('macho_descripcion', 150)->nullable();
            $table->date('fecha_diagnostico_prenez')->nullable();
            $table->enum('resultado_diagnostico', ['positivo','negativo','pendiente'])->default('pendiente');
            $table->date('fecha_probable_parto')->nullable();
            $table->date('fecha_parto_real')->nullable();
            $table->integer('num_crias_nacidas')->nullable();
            $table->integer('num_crias_vivas')->nullable();
            $table->enum('sexo_cria', ['macho','hembra','mixto'])->nullable();
            $table->decimal('peso_cria_kg', 6, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('animal_sanidad_programada', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->string('protocolo', 60);
            $table->string('nombre_protocolo', 120);
            $table->string('especie_aplicacion', 100)->default('Ganado bovino');
            $table->integer('frecuencia_dias');
            $table->date('ultima_aplicacion')->nullable();
            $table->date('proxima_aplicacion')->nullable();
            $table->string('producto_usado', 150)->nullable();
            $table->string('dosis', 80)->nullable();
            $table->enum('via_administracion', ['subcutanea','intramuscular','oral','intranasal','topica','otra'])->default('intramuscular');
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(1);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('cultivo_eventos', function (Blueprint $table) {
            $table->id();
            $table->integer('cultivo_id');
            $table->integer('usuario_id');
            $table->enum('tipo', ['nota','aplicacion','riego','poda','cambio_estado','foto','gasto','cosecha','tarea_completada','otro'])->default('nota');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->string('foto_ruta', 255)->nullable();
            $table->integer('referencia_id')->nullable();
            $table->integer('persona_id')->nullable();
            $table->date('fecha');
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('cultivo_eventos_avanzados', function (Blueprint $table) {
            $table->id();
            $table->integer('cultivo_id');
            $table->integer('usuario_id');
            $table->integer('fase_id')->nullable();
            $table->enum('tipo', ['aplicacion_agroquimico','riego','fertilizacion','control_fitosanitario','deshierbe','otro']);
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->string('foto_ruta', 255)->nullable();
            $table->integer('persona_id')->nullable();
            $table->string('producto_nombre', 150)->nullable();
            $table->string('producto_registro_ica', 50)->nullable();
            $table->decimal('dosis', 10, 4)->nullable();
            $table->string('dosis_unidad', 50)->nullable();
            $table->integer('periodo_carencia_dias')->nullable();
            $table->date('fecha_minima_cosecha')->nullable();
            $table->boolean('alerta_cosecha_activa')->default(0);
            $table->decimal('volumen_agua_litros', 10, 2)->nullable();
            $table->enum('metodo_riego', ['goteo','aspersion','surco','inundacion','manual','otro'])->nullable();
            $table->integer('duracion_minutos')->nullable();
            $table->decimal('nitrogeno_n', 8, 2)->nullable();
            $table->decimal('fosforo_p', 8, 2)->nullable();
            $table->decimal('potasio_k', 8, 2)->nullable();
            $table->string('fuente_fertilizante', 150)->nullable();
            $table->enum('metodo_aplicacion_fertilizante', ['foliar','edafico','fertiriego','otro'])->nullable();
            $table->string('plaga_enfermedad', 150)->nullable();
            $table->enum('tipo_control', ['quimico','biologico','cultural','mecanico','otro'])->nullable();
            $table->enum('nivel_severidad', ['bajo','medio','alto','critico'])->nullable();
            $table->enum('metodo_deshierbe', ['manual','mecanico','quimico','otro'])->nullable();
            $table->decimal('area_deshierbada_ha', 8, 4)->nullable();
            $table->string('creado_en');
            $table->date('actualizado_en');
        });

        Schema::create('cultivo_historial_fases', function (Blueprint $table) {
            $table->id();
            $table->integer('cultivo_id');
            $table->integer('usuario_id');
            $table->integer('fase_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('creado_en');
        });

        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->id();
            $table->integer('inventario_id');
            $table->integer('usuario_id');
            $table->integer('cultivo_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->integer('persona_id')->nullable();
            $table->enum('tipo', ['entrada','salida','ajuste']);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->string('motivo', 200)->nullable();
            $table->string('foto_soporte', 255)->nullable();
            $table->string('persona', 150)->nullable();
            $table->date('fecha');
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('persona_labores', function (Blueprint $table) {
            $table->id();
            $table->integer('persona_id');
            $table->integer('usuario_id');
            $table->date('fecha');
            $table->string('descripcion', 255);
            $table->integer('cultivo_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->decimal('horas', 5, 2)->nullable();
            $table->string('insumos_usados', 255)->nullable();
            $table->text('notas')->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('persona_pagos', function (Blueprint $table) {
            $table->id();
            $table->integer('persona_id');
            $table->integer('usuario_id');
            $table->enum('tipo_pago', ['jornal','quincenal','mensual','bono','anticipo','otro'])->default('jornal');
            $table->decimal('dias', 5, 2)->nullable();
            $table->decimal('valor', 12, 2);
            $table->date('fecha');
            $table->integer('cultivo_id')->nullable();
            $table->integer('animal_id')->nullable();
            $table->string('concepto', 255)->nullable();
            $table->string('notas', 255)->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('persona_pagos');
        Schema::dropIfExists('persona_labores');
        Schema::dropIfExists('inventario_movimientos');
        Schema::dropIfExists('cultivo_historial_fases');
        Schema::dropIfExists('cultivo_eventos_avanzados');
        Schema::dropIfExists('cultivo_eventos');
        Schema::dropIfExists('animal_sanidad_programada');
        Schema::dropIfExists('animal_reproduccion');
        Schema::dropIfExists('animal_produccion');
        Schema::dropIfExists('animal_pesos');
        Schema::dropIfExists('animal_ordenos');
        Schema::dropIfExists('animal_lactancia');
        Schema::dropIfExists('animal_eventos');
        Schema::dropIfExists('usuario_lineas');
        Schema::dropIfExists('ingresos');
        Schema::dropIfExists('gastos');
        Schema::dropIfExists('tareas');
        Schema::dropIfExists('gastos_recurrentes');
        Schema::dropIfExists('cosechas');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('animales');
        Schema::dropIfExists('personas');
        Schema::dropIfExists('rendimiento_regional');
        Schema::dropIfExists('plan_manejo_cultivo');
        Schema::dropIfExists('cultivo_fases');
        Schema::dropIfExists('cultivos');
        Schema::dropIfExists('sesiones');
        Schema::dropIfExists('lineas_productivas');
        Schema::dropIfExists('usuarios');
    }
};