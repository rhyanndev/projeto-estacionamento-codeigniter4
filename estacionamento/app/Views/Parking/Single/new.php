<?= $this->extend('Layouts/main')  ?>


<?= $this->section('title') ?>

<?php echo $title ?? ''; ?>


<?= $this->endSection() ?>


<?= $this->section('css') ?>

<style>
    .btn-style-park {
        width: 70px !important;
        height: 40px !important;
        font-size: 0.9rem !important;
    }

    .small-font-plate {
        font-size: 0.7rem !important;
    }

    .btn-spot-free {
        background-color: #32de84 !important;
    }

    .list-inline.item:not(:last-child) {
        margin-right: 0 !important;
    }

    ul li:first-child {
        margin-left: 0 !important;
    }

    ul li:last-child {
        margin-right: 0 !important;
    }
</style>

<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-body">
                <h4 class="card-title"><?php echo $title; ?></h4>

                <p>
                    <strong>Categoria:&nbsp;</strong><?php echo $category->name; ?>
                </p>

                <p>
                    <strong>Vaga:&nbsp;</strong><?php echo $hidden['spot']; ?>
                </p>

                <hr>

                <!-- Abrindo um formulário  -->
                <?php echo form_open(
                    action: route_to('parking.create.single.ticket'),
                    attributes: ['onsubmit' => 'return confirm("Os dados estão corretos?  \n\n Não será possível editar as informações");'],
                    hidden: $hidden); ?>


                <div class="form-group mb-4">
                    <label for="choice">Escolha um tipo</label>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="choice" id="choice_hour" value="hour" checked>
                            Valor por hora <?php echo format_money_brl($category->price_hour); ?>
                        </label>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="choice" id="choice_day" value="day">
                            Valor por dia <?php echo format_money_brl($category->price_day); ?>
                        </label>
                    </div>
                </div>

                <hr>

                <div class="form-group mb-4">
                    <label for="vehicle">Veículo</label>
                    <input type="text" name="vehicle" class="form-control" placeholder="Ex.: Kia Sorento Branca" required id="vehicle" value="<?php echo old('vehicle'); ?>">
                    <?php echo validation_show_error('vehicle'); ?>
                </div>

                <div class="form-group mb-4">
                    <label for="plate">Placa do Veículo</label>
                    <input type="text" name="plate" class="form-control" placeholder="Informe a placa" required id="plate" value="<?php echo old('plate'); ?>">
                    <?php echo validation_show_error('plate'); ?>
                </div>

                <div class="form-group mb-4">
                    <label for="observations">Observações</label>
                    <textarea name="observations" placeholder="Pergunte ao cliente ou verifique se o veículo possuí alguma observação." class="form-control" id="observations" style="min-height: 100px;"></textarea>
                    <?php echo validation_show_error('observations'); ?>
                </div>

                <hr>

                <button type="submit" class="btn btn-primary me-2">Criar ticket avulso</button>

                <a href="<?php echo route_to('parking') ?>" class="btn btn-info">Cancelar abertura</a>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>

<?= $this->endSection() ?>