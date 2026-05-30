<?php $__env->startSection('title', $cultivo->nombre . ' — Rentabilidad'); ?>
<?php $__env->startSection('page_title', $cultivo->nombre); ?>
<?php $__env->startSection('back_url', route('rentabilidad.index') . '?anio=' . $anio); ?>

<?php $__env->startPush('head'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="flex items-center gap-2 mb-3">
  <a href="?anio=<?php echo e($anio-1); ?>" class="btn btn-sm btn-secondary btn-icon">‹</a>
  <span class="font-bold" style="flex:1;text-align:center;"><?php echo e($cultivo->tipo); ?> · <?php echo e($anio); ?></span>
  <a href="?anio=<?php echo e($anio+1); ?>" class="btn btn-sm btn-secondary btn-icon">›</a>
</div>


<div class="stats-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:14px;">
  <div class="stat-card" style="background:var(--verde-bg);">
    <div class="stat-value text-green" style="font-size:1rem;">$<?php echo e(number_format($ingresoReal,0,',','.')); ?></div>
    <div class="stat-label">Ingresos totales</div>
  </div>
  <div class="stat-card" style="background:var(--marron-bg);">
    <div class="stat-value text-brown" style="font-size:1rem;">$<?php echo e(number_format($costoTotal,0,',','.')); ?></div>
    <div class="stat-label">Costo total</div>
    <?php if($totalManoObra > 0): ?>
    <div style="font-size:10px;color:#9CA3AF;margin-top:2px;">
      Insumos $<?php echo e(number_format($totalGastos,0,',','.')); ?> +
      M.O. $<?php echo e(number_format($totalManoObra,0,',','.')); ?>

    </div>
    <?php endif; ?>
  </div>
</div>


<div class="card mb-3" style="background:<?php echo e($rentabilidad >= 0 ? 'var(--verde-bg)' : '#fef2f2'); ?>;text-align:center;">
  <p class="text-xs font-bold text-gray" style="text-transform:uppercase;margin-bottom:4px;">Balance del cultivo</p>
  <p style="font-size:2rem;font-weight:800;color:<?php echo e($rentabilidad >= 0 ? 'var(--verde-dark)' : 'var(--rojo)'); ?>;">
    <?php echo e($rentabilidad >= 0 ? '+' : ''); ?>$<?php echo e(number_format($rentabilidad,0,',','.')); ?>

  </p>
  <div style="display:flex;justify-content:center;gap:20px;margin-top:8px;flex-wrap:wrap;">
    <div>
      <span class="text-xs text-gray">ROI </span>
      <span class="font-bold" style="color:<?php echo e($roi >= 0 ? 'var(--verde-dark)' : 'var(--rojo)'); ?>;"><?php echo e($roi); ?>%</span>
    </div>
    <?php if($cultivo->area): ?>
    <div>
      <span class="text-xs text-gray">Balance/ha </span>
      <span class="font-bold">$<?php echo e(number_format($cultivo->area > 0 ? $rentabilidad/$cultivo->area : 0,0,',','.')); ?></span>
    </div>
    <?php endif; ?>
    <div>
      <span class="text-xs text-gray">Cosechas </span>
      <span class="font-bold text-green">$<?php echo e(number_format($totalCosechas,0,',','.')); ?></span>
    </div>
    <?php if($totalManoObra > 0): ?>
    <div>
      <span class="text-xs text-gray">Mano de obra </span>
      <span class="font-bold text-brown">$<?php echo e(number_format($totalManoObra,0,',','.')); ?></span>
    </div>
    <?php endif; ?>
  </div>
</div>


<div class="card mb-3">
  <p class="font-bold mb-3">Evolución mensual <?php echo e($anio); ?></p>
  <canvas id="chartMensual" height="180"></canvas>
</div>


<div class="tabs">
  <button class="tab-btn active" onclick="showTab('gastos', this)">
    💰 Gastos (<?php echo e(count($gastos ?? [])); ?>)
  </button>
  <button class="tab-btn" onclick="showTab('ingresos', this)">
    📈 Ingresos (<?php echo e(count($ingresos ?? [])); ?>)
  </button>
  <button class="tab-btn" onclick="showTab('cosechas', this)">
    🌾 Cosechas (<?php echo e(count($cosechas ?? [])); ?>)
  </button>
  <?php if($manoDeObra->count() > 0): ?>
  <button class="tab-btn" onclick="showTab('mano-obra', this)">
    👥 Mano de obra (<?php echo e($manoDeObra->count()); ?>)
  </button>
  <?php endif; ?>
</div>


<div id="tab-gastos">
  <?php if($gastosCat->count()): ?>
  <p class="section-title">Por categoría</p>
  <?php $maxCat = $gastosCat->max('total') ?: 1; ?>
  <?php $__currentLoopData = $gastosCat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="mb-2">
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm font-bold"><?php echo e($cat->categoria); ?></span>
      <span class="text-sm font-bold text-brown">$<?php echo e(number_format($cat->total,0,',','.')); ?></span>
    </div>
    <div class="progress-bar">
      <div class="progress-fill" style="width:<?php echo e(round($cat->total/$maxCat*100)); ?>%;background:var(--marron-light);"></div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  
  <?php if($totalManoObra > 0): ?>
  <div class="mb-2">
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm font-bold">👥 Mano de obra</span>
      <span class="text-sm font-bold text-brown">$<?php echo e(number_format($totalManoObra,0,',','.')); ?></span>
    </div>
    <div class="progress-bar">
      <div class="progress-fill" style="width:<?php echo e(round($totalManoObra/$maxCat*100)); ?>%;background:#7C3AED55;"></div>
    </div>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <?php if(!empty($gastos) && count($gastos)): ?>
  <p class="section-title mt-3">Detalle de insumos</p>
  <?php $__currentLoopData = $gastos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="list-item">
    <div class="item-icon" style="background:var(--marron-bg);">💰</div>
    <div class="item-body">
      <div class="item-title"><?php echo e($g->descripcion); ?></div>
      <div class="item-sub"><?php echo e($g->categoria); ?> · <?php echo e(\Carbon\Carbon::parse($g->fecha)->format('d/m/Y')); ?></div>
    </div>
    <span class="font-bold text-brown text-sm">$<?php echo e(number_format($g->valor,0,',','.')); ?></span>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php else: ?>
  <div class="empty-state" style="padding:28px 16px;"><p>Sin gastos de insumos registrados.</p></div>
  <?php endif; ?>
</div>


<div id="tab-ingresos" style="display:none;">
  <?php if(!empty($ingresos) && count($ingresos)): ?>
  <?php $__currentLoopData = $ingresos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="list-item">
    <div class="item-icon" style="background:var(--verde-bg);">📈</div>
    <div class="item-body">
      <div class="item-title"><?php echo e($i->descripcion); ?></div>
      <div class="item-sub">
        <?php echo e(\Carbon\Carbon::parse($i->fecha)->format('d/m/Y')); ?>

        <?php if($i->comprador): ?> · <?php echo e($i->comprador); ?> <?php endif; ?>
      </div>
    </div>
    <span class="font-bold text-green text-sm">$<?php echo e(number_format($i->valor_total,0,',','.')); ?></span>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php else: ?>
  <div class="empty-state" style="padding:28px 16px;"><p>Sin ingresos registrados para este cultivo.</p></div>
  <?php endif; ?>
</div>


<div id="tab-cosechas" style="display:none;">
  <?php if(!empty($cosechas) && count($cosechas)): ?>
  <?php $__currentLoopData = $cosechas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="list-item">
    <div class="item-icon" style="background:var(--verde-bg);">🌾</div>
    <div class="item-body">
      <div class="item-title"><?php echo e($cs->producto ?? $cultivo->tipo); ?></div>
      <div class="item-sub">
        <?php echo e(number_format($cs->cantidad,1)); ?> <?php echo e($cs->unidad); ?>

        · <?php echo e(\Carbon\Carbon::parse($cs->fecha_cosecha)->format('d/m/Y')); ?>

        · Calidad: <?php echo e(ucfirst($cs->calidad ?? '-')); ?>

      </div>
    </div>
    <span class="font-bold text-green text-sm">$<?php echo e(number_format($cs->valor_estimado ?? 0,0,',','.')); ?></span>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php else: ?>
  <div class="empty-state" style="padding:28px 16px;"><p>Sin cosechas registradas para este cultivo.</p></div>
  <?php endif; ?>
</div>


<?php if($manoDeObra->count() > 0): ?>
<div id="tab-mano-obra" style="display:none;">

  
  <div class="card mb-3" style="background:var(--marron-bg);">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs text-gray font-bold" style="text-transform:uppercase;margin-bottom:4px;">Total mano de obra</p>
        <p class="font-bold" style="font-size:1.4rem;color:var(--marron-dark);">
          $<?php echo e(number_format($totalManoObra,0,',','.')); ?>

        </p>
      </div>
      <div style="text-align:right;">
        <p class="text-xs text-gray"><?php echo e($manoDeObra->count()); ?> pago(s)</p>
        <p class="text-xs text-gray">
          <?php echo e($manoDeObra->filter(fn($p) => $p->dias)->sum('dias')); ?> días trabajados
        </p>
      </div>
    </div>
  </div>

  
  <?php $__currentLoopData = $manoDeObra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="list-item">
    <div class="item-icon" style="background:#EDE9FE;">👤</div>
    <div class="item-body">
      <div class="item-title">
        <?php echo e($pago->trabajador); ?>

        <?php if($pago->cargo): ?>
          <span class="text-xs text-gray"> — <?php echo e($pago->cargo); ?></span>
        <?php endif; ?>
      </div>
      <div class="item-sub">
        <?php echo e($pago->concepto ?? ucfirst($pago->tipo_pago)); ?>

        · <?php echo e(\Carbon\Carbon::parse($pago->fecha)->format('d/m/Y')); ?>

        <?php if($pago->dias): ?> · <?php echo e($pago->dias); ?> días <?php endif; ?>
      </div>
    </div>
    <span class="font-bold text-sm" style="color:#7C3AED;">
      $<?php echo e(number_format($pago->valor,0,',','.')); ?>

    </span>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.color = '#64748b';

const meses    = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const gastos   = <?php echo json_encode($gastosArr, 15, 512) ?>;
const ingresos = <?php echo json_encode($ingresosArr, 15, 512) ?>;
const balance  = <?php echo json_encode($balanceArr, 15, 512) ?>;

new Chart(document.getElementById('chartMensual'), {
  type: 'bar',
  data: {
    labels: meses,
    datasets: [
      { label: 'Ingresos', data: ingresos, backgroundColor: 'rgba(61,139,61,.75)', borderRadius: 4, order: 2 },
      { label: 'Costos',   data: gastos,   backgroundColor: 'rgba(122,79,42,.65)', borderRadius: 4, order: 2 },
      {
        label: 'Balance',
        data: balance,
        type: 'line',
        borderColor: '#2563eb',
        backgroundColor: 'rgba(37,99,235,.1)',
        borderWidth: 2,
        pointRadius: 4,
        fill: false,
        tension: 0.4,
        order: 1,
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      tooltip: { callbacks: { label: ctx => ' $' + ctx.raw.toLocaleString('es-CO') } }
    },
    scales: {
      y: { ticks: { callback: v => '$' + (v/1000).toFixed(0) + 'k' } }
    }
  }
});

function showTab(tab, btn) {
  ['gastos','ingresos','cosechas','mano-obra'].forEach(t => {
    const el = document.getElementById('tab-'+t);
    if (el) el.style.display = 'none';
  });
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  const target = document.getElementById('tab-'+tab);
  if (target) target.style.display = 'block';
  btn.classList.add('active');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Juan Diego\Documents\Universidad Documentos clases\Desarrollo Web 2\AgroGranja Bovinos\AgrogranjaBovinos\resources\views/pages/rentabilidad-detalle.blade.php ENDPATH**/ ?>