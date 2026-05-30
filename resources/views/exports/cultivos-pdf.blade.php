<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: Georgia, 'Times New Roman', serif; font-size:11px; color:#1a2332; background:#fff; }

  /* ── CABECERA ───────────────────────────────────── */
  .header {
    background: #1a3c1a;
    color: #fff;
    padding: 24px 28px 20px;
  }
  .header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
  }
  .header-brand {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    opacity: .65;
    font-family: sans-serif;
  }
  .header-date {
    font-size: 9px;
    opacity: .6;
    font-family: sans-serif;
    text-align: right;
    line-height: 1.5;
  }
  .header-title {
    font-size: 22px;
    font-weight: 700;
    letter-spacing: -.5px;
    margin-bottom: 4px;
  }
  .header-sub {
    font-size: 11px;
    opacity: .75;
    font-family: sans-serif;
  }
  .header-meta {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,.18);
    display: flex;
    gap: 20px;
    font-family: sans-serif;
    font-size: 10px;
  }
  .header-meta span { opacity: .65; margin-right: 3px; }
  .header-meta strong { opacity: 1; }

  /* ── KPIs ───────────────────────────────────────── */
  .kpis {
    display: flex;
    gap: 10px;
    margin: 18px 28px;
  }
  .kpi {
    flex: 1;
    padding: 13px 12px;
    border-radius: 10px;
    border-left: 3px solid transparent;
  }
  .kpi.verde  { background: #f0fdf4; border-color: #22c55e; }
  .kpi.naranja{ background: #fff7ed; border-color: #f59e0b; }
  .kpi.marron { background: #fdf3ea; border-color: #d97706; }
  .kpi.azul   { background: #eff6ff; border-color: #3b82f6; }
  .kpi .k-val {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -.5px;
    font-family: Georgia, serif;
  }
  .kpi.verde  .k-val { color: #15803d; }
  .kpi.naranja .k-val { color: #b45309; }
  .kpi.marron .k-val { color: #7a4f2a; }
  .kpi.azul   .k-val { color: #1d4ed8; }
  .kpi .k-lbl {
    font-size: 9px;
    color: #94a3b8;
    font-family: sans-serif;
    font-weight: 600;
    letter-spacing: .8px;
    text-transform: uppercase;
    margin-top: 3px;
  }

  /* ── SECCIÓN ────────────────────────────────────── */
  .section { margin: 0 28px 6px; }
  .section-title {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #94a3b8;
    font-family: sans-serif;
    padding-bottom: 6px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 10px;
  }

  /* ── TABLA ──────────────────────────────────────── */
  table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
  thead th {
    background: #1a3c1a;
    color: #fff;
    padding: 7px 10px;
    text-align: left;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
  }
  thead th:last-child { text-align: left; }
  tbody tr { border-bottom: 1px solid #f1f5f9; }
  tbody tr:nth-child(even) td { background: #f8fdf8; }
  tbody td { padding: 7px 10px; vertical-align: top; }
  tbody td.num { text-align: right; font-weight: 600; font-family: Georgia, serif; }

  .nombre { font-weight: 700; font-size: 11px; color: #1a3c1a; }
  .tipo-tag {
    display: inline-block;
    background: #f0fdf4;
    color: #15803d;
    padding: 1px 7px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: 600;
    margin-top: 2px;
  }

  .badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: 700;
    font-family: sans-serif;
  }
  .badge-activo    { background: #dcfce7; color: #15803d; }
  .badge-cosechado { background: #fef3c7; color: #92400e; }
  .badge-vendido   { background: #fde8d8; color: #9a3412; }

  .nota-text { color: #64748b; font-size: 9.5px; font-style: italic; }

  /* ── FOOTER ─────────────────────────────────────── */
  .footer {
    margin: 20px 28px 0;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    font-family: sans-serif;
    font-size: 9px;
    color: #94a3b8;
  }
  .footer strong { color: #475569; }
</style>
</head>
<body>

<div class="header">
  <div class="header-top">
    <div class="header-brand">Agrogranja · Cultivos</div>
    <div class="header-date">
      Generado el {{ now()->format('d/m/Y') }}<br>
      a las {{ now()->format('H:i') }}
    </div>
  </div>
  <div class="header-title">Reporte de Cultivos</div>
  <div class="header-sub">Registro completo de siembras y producción agrícola</div>
  <div class="header-meta">
    <div><span>Finca</span><strong>{{ $usuario->nombre_finca ?? 'Sin nombre' }}</strong></div>
    <div><span>Propietario</span><strong>{{ $usuario->nombre }}</strong></div>
    @if($usuario->municipio ?? false)
    <div><span>Ubicación</span><strong>{{ $usuario->municipio }}{{ $usuario->departamento ? ', '.$usuario->departamento : '' }}</strong></div>
    @endif
    <div><span>Total registros</span><strong>{{ $cultivos->count() }} cultivos</strong></div>
  </div>
</div>

<div class="kpis">
  <div class="kpi verde">
    <div class="k-val">{{ $stats['activos'] }}</div>
    <div class="k-lbl">Activos</div>
  </div>
  <div class="kpi naranja">
    <div class="k-val">{{ $stats['cosechados'] }}</div>
    <div class="k-lbl">Cosechados</div>
  </div>
  <div class="kpi marron">
    <div class="k-val">{{ $stats['vendidos'] }}</div>
    <div class="k-lbl">Vendidos</div>
  </div>
  <div class="kpi azul">
    <div class="k-val">{{ number_format($stats['total_area'],1) }}</div>
    <div class="k-lbl">Área total (ha)</div>
  </div>
</div>

<div class="section">
  <div class="section-title">Listado de cultivos</div>
  <table>
    <thead>
      <tr>
        <th style="width:24px">#</th>
        <th>Nombre / Lote</th>
        <th>Tipo</th>
        <th>Fecha siembra</th>
        <th>Área</th>
        <th>Estado</th>
        <th>Observaciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cultivos as $i => $c)
      <tr>
        <td style="color:#94a3b8;font-family:sans-serif;">{{ $i+1 }}</td>
        <td>
          <div class="nombre">{{ $c->nombre }}</div>
        </td>
        <td style="font-family:sans-serif;">{{ $c->tipo }}</td>
        <td style="font-family:sans-serif;white-space:nowrap;">
          {{ $c->fecha_siembra ? \Carbon\Carbon::parse($c->fecha_siembra)->format('d/m/Y') : '—' }}
        </td>
        <td style="font-family:sans-serif;white-space:nowrap;">
          {{ $c->area ? number_format($c->area,2).' '.($c->unidad ?? 'ha') : '—' }}
        </td>
        <td><span class="badge badge-{{ $c->estado }}">{{ ucfirst($c->estado) }}</span></td>
        <td class="nota-text">{{ $c->notas ? \Illuminate\Support\Str::limit($c->notas, 45) : '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="footer">
  <div><strong>Agrogranja</strong> — Sistema de Gestión para Fincas &nbsp;|&nbsp; Total: {{ $cultivos->count() }} cultivos registrados</div>
  <div>agrogranja.app</div>
</div>

</body>
</html>