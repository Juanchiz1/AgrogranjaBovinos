<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: Georgia, 'Times New Roman', serif; font-size:11px; color:#1a2332; background:#fff; }

  /* ── CABECERA ───────────────────────────────────── */
  .header {
    background: #3d1f0a;
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
  .kpi.marron { background: #fdf3ea; border-color: #d97706; }
  .kpi.gris   { background: #f8fafc; border-color: #94a3b8; }
  .kpi .k-val {
    font-size: 17px;
    font-weight: 700;
    letter-spacing: -.5px;
    font-family: Georgia, serif;
  }
  .kpi.marron .k-val { color: #92400e; }
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

  /* ── LAYOUT DOS COLUMNAS ────────────────────────── */
  .two-col {
    display: flex;
    gap: 14px;
    margin: 0 28px 18px;
    align-items: flex-start;
  }
  .col-cat { flex: 0 0 38%; }
  .col-gap { flex: 0 0 62%; }

  /* ── SECCIÓN ────────────────────────────────────── */
  .section-title {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #94a3b8;
    font-family: sans-serif;
    padding-bottom: 6px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 8px;
  }

  /* ── TABLA CATEGORÍAS ───────────────────────────── */
  .cat-table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
  .cat-table thead th {
    background: #3d1f0a;
    color: #fff;
    padding: 6px 8px;
    text-align: left;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .3px;
    text-transform: uppercase;
  }
  .cat-table tbody td {
    padding: 5px 8px;
    font-size: 10px;
    border-bottom: 1px solid #f1f5f9;
  }
  .cat-table tbody tr:nth-child(even) td { background: #fdf8f4; }
  .cat-table .pct-bar-wrap {
    background: #f1f5f9;
    border-radius: 3px;
    height: 4px;
    margin-top: 3px;
    overflow: hidden;
  }
  .cat-table .pct-bar { background: #d97706; height: 100%; border-radius: 3px; }

  /* ── TABLA DETALLE ──────────────────────────────── */
  .section-full { margin: 0 28px 6px; }
  .detail-table { width: 100%; border-collapse: collapse; font-family: sans-serif; }
  .detail-table thead th {
    background: #3d1f0a;
    color: #fff;
    padding: 7px 8px;
    text-align: left;
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .3px;
    text-transform: uppercase;
  }
  .detail-table thead th.r { text-align: right; }
  .detail-table tbody tr { border-bottom: 1px solid #f1f5f9; }
  .detail-table tbody tr:nth-child(even) td { background: #fdf8f4; }
  .detail-table tbody td {
    padding: 6px 8px;
    vertical-align: top;
    font-size: 10px;
  }
  .detail-table tbody td.r {
    text-align: right;
    font-weight: 700;
    font-family: Georgia, serif;
    color: #92400e;
  }
  .detail-table tfoot td {
    padding: 8px;
    background: #3d1f0a;
    color: #fff;
    font-weight: 700;
    font-family: sans-serif;
    font-size: 10px;
  }
  .detail-table tfoot td.r {
    text-align: right;
    font-family: Georgia, serif;
    font-size: 13px;
    letter-spacing: -.3px;
  }
  .cat-chip {
    display: inline-block;
    background: #fef3c7;
    color: #92400e;
    padding: 1px 6px;
    border-radius: 3px;
    font-size: 8.5px;
    font-weight: 600;
  }
  .desc-text { font-weight: 600; color: #1a2332; }
  .meta-text { color: #94a3b8; font-size: 9px; margin-top: 1px; }

  /* ── FOOTER ─────────────────────────────────────── */
  .footer {
    margin: 16px 28px 0;
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
    <div class="header-brand">Agrogranja · Gastos</div>
    <div class="header-date">
      Generado el <?php echo e(now()->format('d/m/Y')); ?><br>
      a las <?php echo e(now()->format('H:i')); ?>

    </div>
  </div>
  <div class="header-title">Reporte de Gastos <?php echo e($anio); ?></div>
  <div class="header-sub">Egresos y costos operativos de la finca</div>
  <div class="header-meta">
    <div><span>Finca</span><strong><?php echo e($usuario->nombre_finca ?? 'Sin nombre'); ?></strong></div>
    <div><span>Propietario</span><strong><?php echo e($usuario->nombre); ?></strong></div>
    <?php if($usuario->municipio ?? false): ?>
    <div><span>Ubicación</span><strong><?php echo e($usuario->municipio); ?><?php echo e($usuario->departamento ? ', '.$usuario->departamento : ''); ?></strong></div>
    <?php endif; ?>
    <div><span>Periodo</span><strong>Año <?php echo e($anio); ?></strong></div>
  </div>
</div>


<div class="kpis">
  <div class="kpi marron">
    <div class="k-val">$<?php echo e(number_format($totalAnio,0,',','.')); ?></div>
    <div class="k-lbl">Total gastos <?php echo e($anio); ?></div>
  </div>
  <div class="kpi gris">
    <div class="k-val"><?php echo e($gastos->count()); ?></div>
    <div class="k-lbl">Registros</div>
  </div>
  <div class="kpi gris">
    <div class="k-val"><?php echo e($porCategoria->count()); ?></div>
    <div class="k-lbl">Categorías</div>
  </div>
  <div class="kpi gris">
    <div class="k-val">$<?php echo e(number_format($gastos->count() > 0 ? $totalAnio / $gastos->count() : 0, 0, ',', '.')); ?></div>
    <div class="k-lbl">Promedio por gasto</div>
  </div>
</div>


<div class="two-col">
  <div class="col-cat">
    <div class="section-title">Por categoría</div>
    <table class="cat-table">
      <thead>
        <tr>
          <th>Categoría</th>
          <th style="text-align:right">Total</th>
          <th style="text-align:right">%</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $porCategoria->sortByDesc(fn($v) => $v)->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $pct = $totalAnio > 0 ? round($total/$totalAnio*100,1) : 0; ?>
        <tr>
          <td>
            <?php echo e($cat); ?>

            <div class="pct-bar-wrap">
              <div class="pct-bar" style="width:<?php echo e($pct); ?>%"></div>
            </div>
          </td>
          <td style="text-align:right;font-weight:700;color:#92400e;font-family:Georgia,serif;">
            $<?php echo e(number_format($total,0,',','.')); ?>

          </td>
          <td style="text-align:right;color:#94a3b8;"><?php echo e($pct); ?>%</td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  </div>
</div>


<div class="section-full">
  <div class="section-title">Detalle de gastos</div>
  <table class="detail-table">
    <thead>
      <tr>
        <th style="width:20px">#</th>
        <th style="width:64px">Fecha</th>
        <th>Descripción</th>
        <th>Categoría</th>
        <th>Proveedor / Cultivo</th>
        <th class="r">Valor</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $gastos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td style="color:#cbd5e1;font-family:sans-serif;"><?php echo e($i+1); ?></td>
        <td style="font-family:sans-serif;white-space:nowrap;color:#64748b;">
          <?php echo e($g->fecha ? \Carbon\Carbon::parse($g->fecha)->format('d/m/Y') : '—'); ?>

        </td>
        <td>
          <div class="desc-text"><?php echo e($g->descripcion); ?></div>
          <?php if($g->cantidad): ?>
          <div class="meta-text"><?php echo e($g->cantidad); ?> <?php echo e($g->unidad_cantidad); ?></div>
          <?php endif; ?>
        </td>
        <td><span class="cat-chip"><?php echo e($g->categoria); ?></span></td>
        <td style="color:#64748b;font-size:10px;">
          <?php echo e($g->proveedor ?? ''); ?>

          <?php if($g->cultivo_nombre): ?>
            <?php if($g->proveedor): ?> · <?php endif; ?>
            <span style="color:#15803d;"><?php echo e($g->cultivo_nombre); ?></span>
          <?php endif; ?>
          <?php if(!$g->proveedor && !$g->cultivo_nombre): ?> — <?php endif; ?>
        </td>
        <td class="r">$<?php echo e(number_format($g->valor,0,',','.')); ?></td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5">Total gastos <?php echo e($anio); ?></td>
        <td class="r">$<?php echo e(number_format($totalAnio,0,',','.')); ?></td>
      </tr>
    </tfoot>
  </table>
</div>

<div class="footer">
  <div><strong>Agrogranja</strong> — Sistema de Gestión para Fincas &nbsp;|&nbsp; <?php echo e($gastos->count()); ?> registros · <?php echo e($porCategoria->count()); ?> categorías</div>
  <div>agrogranja.app</div>
</div>

</body>
</html><?php /**PATH C:\Users\Juan Diego\Documents\Universidad Documentos clases\Desarrollo Web 2\AgroGranja Bovinos\AgrogranjaBovinos\resources\views/exports/gastos-pdf.blade.php ENDPATH**/ ?>