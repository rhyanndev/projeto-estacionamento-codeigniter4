<?= $this->extend('Layouts/main')  ?>


<?= $this->section('title') ?>

<?php

use App\Cells\ShowTicketCell;

echo $title ?? ''; ?>


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


                <?php echo view_cell(library: ShowTicketCell::class, params: ['ticket' => $ticket]); ?>

                <div class="mt-5">

                    <?php echo form_open(
                        route_to('parking.process.close.ticket', $ticket->id()), 
                        attributes: ['class' => 'd-inline'],
                        hidden: ['_method' => 'PUT']
                    ); ?>

                    <div class="form-group mb-5">
                        <label for="payment_method">Método de pagamento</label>
                        <?php echo $paymentMethodsOptions; ?>
                        <?php echo validation_show_error('payment_method'); ?>

                    </div>

                    <button type="submit" class="btn btn-danger btn-sm me-2 mb-2">Finalizar ticket</button>

                    <?php echo form_close(); ?>


                    <a class="btn btn-dark btn-sm me-2 mb-2" href="<?php echo route_to(
                                                                        'parking.pdf.ticket',
                                                                        $ticket->id()
                                                                    ); ?>">Gerar PDF</a>

                    <a class="btn btn-info btn-sm me-2 mb-2" href="<?php echo route_to(
                                                                        'parking'); ?>">Criar novo ticket</a>

                </div>


            </div>
        </div>
    </div>

</div>


<?= $this->endSection() ?>


<?= $this->section('js') ?>


<?= $this->endSection() ?>