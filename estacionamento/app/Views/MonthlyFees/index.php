<?= $this->extend('Layouts/main')  ?>


<?= $this->section('title') ?>

<?php

use App\Enum\MonthlyFeeStatus;

 echo $title ?? ''; ?>

<?= $this->endSection() ?>


<?= $this->section('css') ?>

<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="row">

  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-header">

        <a class="btn btn-dark btn-sm float-end" href="<?php echo route_to('monthly.fees.new') ?>">Nova</a>

      </div>
      <div class="card-body">
        <h4 class="card-title"><?php echo $title; ?></h4>

        <!--Contando os registros-->
        <?php if(count($fees) === 0): ?>

        <div class="alert alert-info text-center">Aqui serão exibidas as mensalidades</div>

        <?php else: ?>

          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Cliente</th>
                <th>Categoria</th>
                <th>Preço por mês</th>
                <th>Data de vencimento</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($fees as $fee) : ?>
              <tr>
                <td><a class="btn btn-primary btn-sm me-2" href="<?php echo route_to('monthly.fees.edit', (string)$fee->_id); ?>">Editar</a>
              
                <?php echo form_open(
                  route_to('monthly.fees.delete', (string)$fee->_id), 
                  attributes:['class' => 'd-inline', 'onsubmit' => 'return confirm("Tem certeza da remoção?");'],
                  hidden: ['_method' => 'DELETE']);  ?>

               <button type="submit" class="btn btn-danger btn-sm">Excluir</button> 
                <?php echo form_close();  ?>

                </td>
                <td><?php echo $fee?->customer?->name ?? 'Não localizado'; ?></td>
                <td><?php echo $fee?->category?->name ?? 'Não localizada'; ?></td>
                <td><?php echo format_money_brl($fee?->category?->price_month); ?></td>
                <td><?php echo $fee->due_date; ?></td>
                <td><?php echo MonthlyFeeStatus::tryFrom($fee->status)->toString(); ?></td>
              </tr>
              <?php endforeach; ?>

            </tbody>
          </table>
        </div>

        <?php endif; ?>


      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>



<?= $this->endSection() ?>