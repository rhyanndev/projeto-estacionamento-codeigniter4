<?= $this->extend('Layouts/main')  ?>


<?= $this->section('title') ?>

<?php echo $title ?? ''; ?>

<?= $this->endSection() ?>


<?= $this->section('css') ?>

<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="row">

  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">

        <a class="btn btn-info btn-sm float-start" href="<?php echo route_to('customers') ?>">Listar mensalistas</a>

      </div>
      <div class="card-body">
        <h4 class="card-title"><?php echo $title; ?></h4>

        <?php echo form_open(route_to('customers.create'), attributes: ['class' => 'form-sample']); ?>

        <?php echo $this->include('Customers/_form'); ?>

        <?php echo form_close(); ?>

      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>

<script>
  <?php echo $this->include('Customers/_scripts.js'); ?>
</script>

<?= $this->endSection() ?>