<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: Georgia, 'Times New Roman', serif; font-size:11px; color:#1a2332; background:#fff; }

  /* ── PORTADA / CABECERA ─────────────────────────── */
  .header {
    background: #1a3c1a;
    color: #fff;
    padding: 28px 28px 22px;
    position: relative;
    overflow: hidden;
  }
  .header::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
  }
  .header::after {
    content: '';
    position: absolute;
    bottom: -20px; left: 40%;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.03);
  }
  .header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
  }
  .header-brand {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    opacity: .7;
    font-family: sans-serif;
  }
  .header-date {
    font-size: 9px;
    opacity: .65;
    font-family: sans-serif;
    text-align: right;
  }
  .header-title {
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -1px;
    line-height: 1.1;
    margin-bottom: 6px;
  }
  .header-sub {
    font-size: 12px;
    opacity: .8;
    font-family: sans-serif;
  }
  .header-meta {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,.2);
    display: flex;
    gap: 24px;
    font-family: sans-serif;
    font-size: 10px;
  }
  .header-meta span { opacity: .7; margin-right: 4px; }
  .header-meta strong { opacity: 1; }

  /* ── BALANCE PRINCIPAL ──────────────────────────── */
  .balance-box {
    margin: 20px 28px;
    padding: 20px 24px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .balance-box.positivo { background: #f0fdf4; border: 2px solid #22c55e; }
  .balance-box.negativo { background: #fef2f2; border: 2px solid #ef4444; }
  .balance-main .valor {
    font-size: 34px;
    font-weight: 700;
    letter-spacing: -1.5px;
    font-family: Georgia, serif;
  }
  .balance-main .valor.pos { color: #15803d; }
  .balance-main .valor.neg { color: #dc2626; }
  .balance-main .etiqueta {
    font-size: 10px;
    color: #64748b;
    font-family: sans-serif;
    margin-top: 3px;
    letter-spacing: .5px;
    text-transform: uppercase;
  }
  .balance-pill {
    background: #15803d;
    color: #fff;
    font-size: 9px;
    font-family: sans-serif;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 99px;
  }
  .balance-pill.neg { background: #dc2626; }

  /* ── TARJETAS KPI ───────────────────────────────── */
  .kpis {
    display: flex;
    gap: 10px;
    margin: 0 28px 20px;
  }
  .kpi {
    flex: 1;
    padding: 14px 12px;
    border-radius: 10px;
    border-left: 3px solid transparent;
  }
  .kpi.verde  { background: #f0fdf4; border-color: #22c55e; }
  .kpi.marron { background: #fdf3ea; border-color: #d97706; }
  .kpi.azul   { background: #eff6ff; border-color: #3b82f6; }
  .kpi.gris   { background: #f8fafc; border-color: #94a3b8; }
  .kpi .k-val {
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.5px;
    font-family: Georgia, serif;
  }
  .kpi.verde  .k-val { color: #15803d; }
  .kpi.marron .k-val { color: #b45309; }
  .kpi.azul   .k-val { color: #1d4ed8; }
  .kpi.gris   .k-val { color: #475569; }
  .kpi .k-lbl {
    font-size: 9px;
    color: #94a3b8;
    font-family: sans-serif;
    font-weight: 600;
    letter-spacing: .8px;
    text-transform: uppercase;
    margin-top: 3px;
  }

  /* ── SECCIONES ──────────────────────────────────── */
  .section {
    margin: 0 28px 18px;
  }
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

  /* ── TABLA MENSUAL ──────────────────────────────── */
  .month-table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
  .month-table th {
    background: #1a3c1a;
    color: #fff;
    padding: 6px 5px;
    text-align: center;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .5px;
  }
  .month-table th:first-child { text-align: left; padding-left: 10px; }
  .month-table td {
    padding: 5px 5px;
    text-align: center;
    font-size: 9px;
    border-bottom: 1px solid #f1f5f9;
  }
  .month-table td:first-child { text-align: left; padding-left: 10px; font-weight: 600; }
  .month-table tr:nth-child(even) td { background: #f8fafc; }
  .pos { color: #15803d; font-weight: 700; }
  .neg { color: #dc2626; font-weight: 700; }
  .bal-row td { background: #f0fdf4 !important; }

  /* ── FIRMA / FOOTER ─────────────────────────────── */
  .footer {
    margin: 24px 28px 0;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: sans-serif;
  }
  .footer-brand { font-size: 9px; color: #94a3b8; }
  .footer-brand strong { color: #475569; }
  .footer-sig { font-size: 8px; color: #cbd5e1; letter-spacing: 1px; text-transform: uppercase; }
</style>
</head>
<body>

<div class="header">
  <div class="header-top">
    <div class="header-brand">Agrogranja · Sistema de Gestión</div>
    <div class="header-date">
      Generado el {{ now()->format('d/m/Y') }}<br>
      a las {{ now()->format('H:i') }}
    </div>
  </div>
  <div class="header-title">Reporte General {{ $anio }}</div>
  <div class="header-sub">Resumen financiero y productivo de la finca</div>
  <div class="header-meta">
    <div><span>Finca</span><strong>{{ $usuario->nombre_finca ?? 'Sin nombre' }}</strong></div>
    <div><span>Propietario</span><strong>{{ $usuario->nombre }}</strong></div>
    @if($usuario->municipio ?? false)
    <div><span>Ubicación</span><strong>{{ $usuario->municipio }}{{ $usuario->departamento ? ', '.$usuario->departamento : '' }}</strong></div>
    @endif
    <div><span>Periodo</span><strong>Año {{ $anio }}</strong></div>
  </div>
</div>

{{-- Balance principal --}}
<div class="balance-box {{ $balance >= 0 ? 'positivo' : 'negativo' }}" style="margin-top:20px;">
  <div class="balance-main">
    <div class="valor {{ $balance >= 0 ? 'pos' : 'neg' }}">${{ number_format(abs($balance),0,',','.') }}</div>
    <div class="etiqueta">Balance {{ $anio }} — {{ $balance >= 0 ? 'Ganancia neta' : 'Pérdida neta' }}</div>
  </div>
  <div class="balance-pill {{ $balance >= 0 ? '' : 'neg' }}">
    {{ $balance >= 0 ? 'Rentable' : 'En pérdida' }}
  </div>
</div>

{{-- KPIs --}}
<div class="kpis">
  <div class="kpi verde">
    <div class="k-val">${{ number_format($totalIngresos/1000,1) }}k</div>
    <div class="k-lbl">Ingresos totales</div>
  </div>
  <div class="kpi marron">
    <div class="k-val">${{ number_format($totalGastos/1000,1) }}k</div>
    <div class="k-lbl">Gastos totales</div>
  </div>
  <div class="kpi azul">
    <div class="k-val">${{ number_format($totalCosechas/1000,1) }}k</div>
    <div class="k-lbl">Val. cosechas</div>
  </div>
  <div class="kpi gris">
    <div class="k-val">{{ $cultivos->count() }}</div>
    <div class="k-lbl">Cultivos</div>
  </div>
</div>

{{-- Tabla mensual --}}
<div class="section">
  <div class="section-title">Balance mensual</div>
  <table class="month-table">
    <thead>
      <tr>
        <th>Concepto</th>
        @foreach(['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'] as $m)
        <th>{{ $m }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Ingresos</td>
        @for($m=1;$m<=12;$m++)
        <td class="pos">${{ number_format(($ingresosMes[$m]??0)/1000,0) }}k</td>
        @endfor
      </tr>
      <tr>
        <td>Gastos</td>
        @for($m=1;$m<=12;$m++)
        <td class="neg">${{ number_format(($gastosMes[$m]??0)/1000,0) }}k</td>
        @endfor
      </tr>
      <tr class="bal-row">
        <td>Balance</td>
        @for($m=1;$m<=12;$m++)
        @php $b2 = ($ingresosMes[$m]??0) - ($gastosMes[$m]??0); @endphp
        <td class="{{ $b2 >= 0 ? 'pos' : 'neg' }}">${{ number_format($b2/1000,0) }}k</td>
        @endfor
      </tr>
    </tbody>
  </table>
</div>

<div class="footer">
  <div class="footer-brand"><strong>Agrogranja</strong> — Sistema de Gestión para Fincas</div>
  <div class="footer-sig">agrogranja.app &nbsp;·&nbsp; Reporte generado automáticamente</div>
</div>

</body>
</html>