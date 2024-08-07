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

            <div class="card-body">
                <h4 class="card-title"><?php echo $title; ?></h4>

                <!-- Definindo rotas, atributos html e o hidden de campos ocultos-->
                <?php echo form_open(
                    route_to('customers.cars.update', (string) $car->_id),
                    attributes: ['class' => 'form-sample'],
                    hidden: ['_method' => 'PUT']
                ); ?>

                <?php echo $this->include('Cars/_form'); ?>

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