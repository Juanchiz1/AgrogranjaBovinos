<?php

namespace App\Http\Controllers;

use App\Traits\ManejadorImagenes;
use App\Http\Requests\GastoRequest;
use App\Models\Gasto;
use App\Models\Cultivo;
use App\Models\Animal;
use App\Models\Cosecha;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GastoController extends Controller
{
    use ManejadorImagenes;

    private function categorias(): array
    {
        return [
            '🌱 Siembra'         => ['Semillas','Trasplante','Preparación de suelo'],
            '🌿 Nutrición'       => ['Fertilizantes','Abonos orgánicos','Correctivos de suelo'],
            '🧴 Sanidad vegetal' => ['Plaguicidas','Herbicidas','Fungicidas','Insecticidas'],
            '💧 Agua y riego'    => ['Riego','Infraestructura hídrica'],
            '🐄 Ganadería'       => ['Alimento animal','Veterinario','Medicamentos animales','Vacunas'],
            '👷 Personal'        => ['Mano de obra','Jornales','Administración'],
            '🚛 Logística'       => ['Transporte','Combustible','Fletes'],
            '🔧 Infraestructura' => ['Herramientas','Maquinaria','Mantenimiento','Arriendo de maquinaria'],
            '🏡 Finca'           => ['Arriendo de tierra','Servicios públicos','Seguros','Impuestos'],
            '📦 Otros'           => ['Otros'],
        ];
    }

    private function categoriasPlanas(): array
    {
        $lista = [];
        foreach ($this->categorias() as $items) {
            foreach ($items as $item) $lista[] = $item;
        }
        return $lista;
    }

    /**
     * Muestra el listado de gastos con filtros y estadísticas del período.
     */
    public function index(Request $request)
    {
        $uid = session('usuario_id');

        // Este query mantiene los JOINs en DB::table porque las vistas
        // necesitan columnas de 4 tablas distintas en una sola colección.
        $query = DB::table('gastos as g')
            ->leftJoin('cultivos as c',  'c.id',  '=', 'g.cultivo_id')
            ->leftJoin('animales as a',  'a.id',  '=', 'g.animal_id')
            ->leftJoin('cosechas as co', 'co.id', '=', 'g.cosecha_id')
            ->leftJoin('personas as p',  'p.id',  '=', 'g.persona_id')
            ->where('g.usuario_id', $uid)
            ->select(
                'g.*',
                'c.nombre as cultivo_nombre',
                'a.nombre_lote as animal_nombre', 'a.especie as animal_especie',
                'co.producto as cosecha_nombre',
                'p.nombre as proveedor_nombre_bd', 'p.tipo as proveedor_tipo'
            );

        if ($request->q) {
            $q = $request->q;
            $query->where(fn($w) => $w
                ->where('g.descripcion', 'like', "%$q%")
                ->orWhere('g.categoria',  'like', "%$q%")
                ->orWhere('g.proveedor',  'like', "%$q%"));
        }
        if ($request->mes) $query->whereRaw("DATE_FORMAT(g.fecha,'%Y-%m') = ?", [$request->mes]);
        if ($request->cat) $query->where('g.categoria', $request->cat);
        if ($request->asociado) {
            match($request->asociado) {
                'cultivo' => $query->whereNotNull('g.cultivo_id'),
                'animal'  => $query->whereNotNull('g.animal_id'),
                'cosecha' => $query->whereNotNull('g.cosecha_id'),
                'ninguno' => $query->whereNull('g.cultivo_id')->whereNull('g.animal_id')->whereNull('g.cosecha_id'),
                default   => null,
            };
        }

        $gastos    = $query->orderBy('g.fecha', 'desc')->get();
        $totalMes  = Gasto::delUsuario($uid)->delMes()->sum('valor');
        $totalAnio = Gasto::delUsuario($uid)->delAnio()->sum('valor');
        $statsCat  = Gasto::delUsuario($uid)->delMes()
            ->selectRaw('categoria, SUM(valor) as total')
            ->groupBy('categoria')->orderByDesc('total')->limit(5)->get();

        $cultivos         = Cultivo::delUsuario($uid)->activos()->orderBy('nombre')->get();
        $animales         = Animal::delUsuario($uid)->activos()->orderBy('nombre_lote')->get();
        $cosechas         = Cosecha::delUsuario($uid)->orderBy('fecha_cosecha', 'desc')->limit(20)->get();
        $tareas           = DB::table('tareas')->where('usuario_id',$uid)->where('completada',0)->orderBy('fecha')->get();
        $categorias       = $this->categorias();
        $categoriasPlanas = $this->categoriasPlanas();
        $trabajadores = Persona::delUsuario($uid)->activos()->whereIn('tipo', ['trabajador','familiar','otro'])->orderBy('nombre')->get();
        $inventarioItems = DB::table('inventario')->where('usuario_id', $uid)->orderBy('categoria')->orderBy('nombre')->get();

        $proveedores = Persona::delUsuario($uid)->activos()->proveedores()->orderBy('nombre')->get();


        try {
            $recurrentes = DB::table('gastos_recurrentes')->where('usuario_id',$uid)->where('activo',1)->orderBy('proximo_vencimiento')->get();
        } catch (\Exception $e) { $recurrentes = collect(); }

        return view('pages.gastos', compact(
            'gastos','totalMes','totalAnio','statsCat',
            'cultivos','animales','cosechas','tareas',
            'categorias','categoriasPlanas','proveedores','recurrentes','trabajadores', 'inventarioItems'
        ));
    }

    /**
     * Registra un nuevo gasto.
     */
    public function store(GastoRequest $request)
    {
        $uid  = session('usuario_id');
        $foto = null;
        if ($request->hasFile('foto_factura')) {
            $foto = $this->guardarImagen($request->file('foto_factura'), 'gastos/facturas');
        }

        $proveedorNombre = $request->proveedor;
        if ($request->proveedor_id) {
            $prov = Persona::find($request->proveedor_id);
            if ($prov) $proveedorNombre = $prov->nombre;
        }

        Gasto::create([
            'usuario_id'      => $uid,
            'categoria'       => $request->categoria,
            'descripcion'     => $request->descripcion,
            'cantidad'        => $request->cantidad ?: null,
            'unidad_cantidad' => $request->unidad_cantidad,
            'valor'           => $request->valor,
            'fecha'           => $request->fecha ?? now()->toDateString(),
            'proveedor'       => $proveedorNombre,
            'proveedor_id'    => $request->proveedor_id ?: null,
            'cultivo_id'      => $request->cultivo_id ?: null,
            'animal_id'       => $request->animal_id ?: null,
            'cosecha_id'      => $request->cosecha_id ?: null,
            'tarea_id'        => $request->tarea_id ?: null,
            'factura_numero'  => $request->factura_numero,
            'notas'           => $request->notas,
            'foto_factura'    => $foto,
            'es_recurrente'   => 0,
        ]);


        // Crear pago en persona si está asociado a trabajador
if ($request->persona_nomina_id ?? null) {
    try {
        DB::table('persona_pagos')->insert([
            'persona_id' => $request->persona_nomina_id,
            'usuario_id' => $uid,
            'tipo_pago'  => match($request->frecuencia) {
                'quincenal' => 'quincenal',
                'mensual'   => 'mensual',
                'semanal'   => 'jornal',
                default     => 'otro',
            },
            'valor'      => $request->valor,
            'fecha'      => now()->toDateString(),
            'cultivo_id' => $request->cultivo_id ?? null,
            'animal_id'  => $request->animal_id ?? null,
            'concepto'   => $request->descripcion . ' (recurrente)',
            'creado_en'  => now()->toDateTimeString(),
        ]);
    } catch (\Exception $e) {
        // Si falla, no interrumpe el flujo principal
    }
}
        // ── Agregar al inventario si se marcó ────────────────────────────
if ($request->agregar_inventario && $request->inv_nombre) {
    $existente = DB::table('inventario')
        ->where('usuario_id', $uid)
        ->where('nombre', $request->inv_nombre)
        ->first();

    if ($existente) {
        DB::table('inventario')->where('id', $existente->id)
            ->increment('cantidad_actual', $request->inv_cantidad ?: 0);
        DB::table('inventario_movimientos')->insert([
            'inventario_id'  => $existente->id,
            'usuario_id'     => $uid,
            'tipo'           => 'entrada',
            'cantidad'       => $request->inv_cantidad ?: 0,
            'precio_unitario'=> $request->valor && $request->inv_cantidad
                                ? round($request->valor / $request->inv_cantidad, 0) : null,
            'motivo'         => 'Compra: '.$request->descripcion,
            'fecha'          => $request->fecha ?? now()->toDateString(),
            'creado_en'      => now()->toDateTimeString(),
        ]);
    } else {
        $invId = DB::table('inventario')->insertGetId([
            'usuario_id'      => $uid,
            'nombre'          => $request->inv_nombre,
            'categoria'       => $request->inv_categoria ?: $request->categoria,
            'cantidad_actual' => $request->inv_cantidad ?: 0,
            'stock_minimo'    => $request->inv_stock_minimo ?: 0,
            'unidad'          => $request->inv_unidad ?: 'unidades',
            'precio_unitario' => $request->valor && $request->inv_cantidad
                                 ? round($request->valor / $request->inv_cantidad, 0) : null,
            'creado_en'       => now()->toDateTimeString(),
            'actualizado_en'  => now()->toDateTimeString(),
        ]);
        DB::table('inventario_movimientos')->insert([
            'inventario_id' => $invId,
            'usuario_id'    => $uid,
            'tipo'          => 'entrada',
            'cantidad'      => $request->inv_cantidad ?: 0,
            'motivo'        => 'Compra inicial: '.$request->descripcion,
            'fecha'         => $request->fecha ?? now()->toDateString(),
            'creado_en'     => now()->toDateTimeString(),
        ]);
    }
}

// ── Registrar labor si es mano de obra y tiene persona ────────────
if ($request->persona_nomina_id &&
    in_array($request->categoria, ['Mano de obra','Jornales','Administración'])) {
    DB::table('persona_pagos')->insert([
        'persona_id' => $request->persona_nomina_id,
        'usuario_id' => $uid,
        'tipo_pago'  => 'jornal',
        'valor'      => $request->valor,
        'fecha'      => $request->fecha ?? now()->toDateString(),
        'cultivo_id' => $request->cultivo_id ?: null,
        'animal_id'  => $request->animal_id ?: null,
        'concepto'   => $request->descripcion,
        'creado_en'  => now()->toDateTimeString(),
    ]);
}

        return redirect()->route('gastos.index')->with('msg','Gasto registrado.')->with('msgType','success');
    }

    /**
     * Actualiza un gasto existente.
     */
    public function update(GastoRequest $request, $id)
    {
        $uid   = session('usuario_id');
        $gasto = Gasto::where('id',$id)->where('usuario_id',$uid)->firstOrFail();

        $data = [
            'categoria'       => $request->categoria,
            'descripcion'     => $request->descripcion,
            'cantidad'        => $request->cantidad ?: null,
            'unidad_cantidad' => $request->unidad_cantidad,
            'valor'           => $request->valor,
            'fecha'           => $request->fecha,
            'proveedor'       => $request->proveedor,
            'proveedor_id'    => $request->proveedor_id ?: null,
            'cultivo_id'      => $request->cultivo_id ?: null,
            'animal_id'       => $request->animal_id ?: null,
            'cosecha_id'      => $request->cosecha_id ?: null,
            'tarea_id'        => $request->tarea_id ?: null,
            'factura_numero'  => $request->factura_numero,
            'notas'           => $request->notas,
        ];

        if ($request->hasFile('foto_factura')) {
            $this->eliminarImagen($gasto->foto_factura);
            $data['foto_factura'] = $this->guardarImagen($request->file('foto_factura'), 'gastos/facturas');
        }

        $gasto->update($data);

        return redirect()->route('gastos.index')->with('msg','Gasto actualizado.')->with('msgType','success');
    }

    /**
     * Elimina un gasto y su foto de factura si existe.
     */
    public function destroy($id)
    {
        $uid   = session('usuario_id');
        $gasto = Gasto::where('id',$id)->where('usuario_id',$uid)->firstOrFail();
        $this->eliminarImagen($gasto->foto_factura);
        $gasto->delete();

        return redirect()->route('gastos.index')->with('msg','Gasto eliminado.')->with('msgType','warning');
    }

    /**
     * Guarda un nuevo proveedor en la tabla personas.
     */
    public function storeProveedor(Request $request)
    {
        $request->validate(['nombre'=>'required|string|max:150']);
        try {
            DB::table('personas')->insert([
                'usuario_id' => session('usuario_id'),
                'tipo'       => 'proveedor',
                'nombre'     => $request->nombre,
                'telefono'   => $request->telefono,
                'email'      => $request->email,
                'direccion'  => $request->direccion,
                'notas'      => $request->notas,
                'activo'     => 1,
                'creado_en'  => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('gastos.index')
                ->with('msg','⚠️ Error al guardar el proveedor.')
                ->with('msgType','error');
        }
        return redirect()->route('gastos.index')->with('msg','Proveedor guardado.')->with('msgType','success');
    }

    /**
     * Desactiva un proveedor (soft delete).
     */
    public function destroyProveedor($id)
    {
        Persona::where('id',$id)->where('tipo','proveedor')->where('usuario_id',session('usuario_id'))->update(['activo'=>0]);
        return redirect()->route('gastos.index')->with('msg','Proveedor eliminado.')->with('msgType','warning');
    }

    /**
     * Registra un gasto recurrente programado.
     */
    public function storeRecurrente(Request $request)
    {
        $request->validate([
            'categoria'   => 'required',
            'descripcion' => 'required',
            'valor'       => 'required|numeric',
            'frecuencia'  => 'required',
        ]);

        $uid     = session('usuario_id');
        $dia     = $request->dia_del_mes ?? 1;
        $proximo = now()->day($dia);
        if ($proximo->isPast()) $proximo->addMonth();

        try {
            DB::table('gastos_recurrentes')->insert([
                'usuario_id'          => $uid,
                'categoria'           => $request->categoria,
                'descripcion'         => $request->descripcion,
                'valor'               => $request->valor,
                'proveedor'           => $request->proveedor,
                'proveedor_id'        => $request->proveedor_id ?: null,
                'cultivo_id'          => $request->cultivo_id ?: null,
                'animal_id'           => $request->animal_id ?: null,
                'frecuencia'          => $request->frecuencia,
                'dia_del_mes'         => $dia,
                'activo'              => 1,
                'proximo_vencimiento' => $proximo->toDateString(),
                'notas'               => $request->notas,
                'creado_en'           => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('gastos.index')
                ->with('msg','⚠️ Error al guardar el gasto recurrente.')
                ->with('msgType','error');
        }

        return redirect()->route('gastos.index')->with('msg','Gasto recurrente creado.')->with('msgType','success');
    }

    /**
     * Genera el gasto real a partir de un recurrente y actualiza el próximo vencimiento.
     */
    public function generarRecurrente($id)
    {
        $uid = session('usuario_id');
        $r   = DB::table('gastos_recurrentes')->where('id',$id)->where('usuario_id',$uid)->firstOrFail();

        Gasto::create([
            'usuario_id'    => $uid,
            'categoria'     => $r->categoria,
            'descripcion'   => $r->descripcion . ' (recurrente)',
            'valor'         => $r->valor,
            'fecha'         => now()->toDateString(),
            'proveedor'     => $r->proveedor,
            'proveedor_id'  => $r->proveedor_id,
            'cultivo_id'    => $r->cultivo_id,
            'animal_id'     => $r->animal_id,
            'es_recurrente' => 1,
            'recurrente_id' => $r->id,
        ]);

        $proximo = now();
        match($r->frecuencia) {
            'semanal'     => $proximo->addDays(7),
            'quincenal'   => $proximo->addDays(15),
            'mensual'     => $proximo->addMonth(),
            'bimestral'   => $proximo->addMonths(2),
            'trimestral'  => $proximo->addMonths(3),
            'anual'       => $proximo->addYear(),
            default       => null,
        };

        DB::table('gastos_recurrentes')->where('id',$id)->update([
            'ultimo_generado'     => now()->toDateString(),
            'proximo_vencimiento' => $proximo->toDateString(),
        ]);

        return redirect()->route('gastos.index')->with('msg','Gasto generado correctamente.')->with('msgType','success');
    }

    /**
     * Desactiva un gasto recurrente.
     */
    public function destroyRecurrente($id)
    {
        DB::table('gastos_recurrentes')->where('id',$id)->where('usuario_id',session('usuario_id'))->update(['activo'=>0]);
        return redirect()->route('gastos.index')->with('msg','Gasto recurrente desactivado.')->with('msgType','warning');
    }
}