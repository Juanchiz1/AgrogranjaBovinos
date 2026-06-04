<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UsuarioLinea extends Model
{
    protected $table = 'usuario_lineas';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id', 'linea_codigo', 'cantidad_aprox',
        'escala', 'metadata', 'notas', 'activa',
    ];

    protected $casts = [
        'activa'         => 'boolean',
        'cantidad_aprox' => 'integer',
        'metadata'       => 'array',
    ];

    // ── Relaciones ────────────────────────────────────────────

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function linea(): BelongsTo
    {
        return $this->belongsTo(LineaProductiva::class, 'linea_codigo', 'codigo');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeDelUsuario($query, int $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }

    public function scopeActivas($query)
    {
        return $query->where('activa', 1);
    }
}