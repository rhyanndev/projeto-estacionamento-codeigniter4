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

        <a class="btn btn-info btn-sm float-start" href="<?php echo route_to('customers.new') ?>">Listar mensalista</a>

      </div>
      <div class="card-body">
        <h4 class="card-title"><?php echo $title; ?></h4>

        <ul class="list-group list-group-flush">

          <li class="list-group-item"><strong>Nome:&nbsp;</strong><?php echo $customer->name; ?></li>
          <li class="list-group-item"><strong>CPF:&nbsp;</strong><?php echo $customer->cpf; ?></li>
          <li class="list-group-item"><strong>Telefone:&nbsp;</strong><?php echo $customer->phone; ?></li>
          <li class="list-group-item"><strong>E-mail:&nbsp;</strong><?php echo $customer->email; ?></li>
          <li class="list-group-item"><strong>Total de veículos:&nbsp;</strong><?php echo count($customer->cars ?? []); ?></li>

        </ul>

        <hr>

        <a class="btn btn-primary btn-sm me-2" href="<?php echo route_to('customers.edit', (string) $customer->_id); ?>">Editar</a>
        <a class="btn btn-dark btn-sm me-2" href="<?php echo route_to('customers.cars', (string) $customer->_id); ?>">Carros</a>

        <?php echo form_open(
          route_to('customers.delete', (string) $customer->_id),
          attributes: ['class' => 'd-inline', 'onsubmit' => 'return confirm("Tem certeza da remoção?");'],
          hidden: ['_method' => 'DELETE']
        );  ?>

        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>

        <?php echo form_close();  ?>

      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>

<?= $this->endSection() ?>