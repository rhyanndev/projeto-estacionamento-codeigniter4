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

        <a class="btn btn-dark btn-sm float-end" href="<?php echo route_to('customers.new') ?>">Novo mensalista</a>

      </div>
      <div class="card-body">
        <h4 class="card-title"><?php echo $title; ?></h4>

        <?php if(count($customers) === 0): ?>

        <div class="alert alert-info text-center">Aqui serão exibidos os mensalistas</div>

        <?php else: ?>

          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Total de veículos</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($customers as $customer) : ?>
              <tr>
                <td>
                  
                <a class="btn btn-primary btn-sm me-2" href="<?php echo route_to('customers.show', (string) $customer->_id); ?>">Detalhes</a>

                </td>
                <td><?php echo $customer->name; ?></td>
                <td><?php echo $customer->cpf; ?></td>
                <td><?php echo $customer->phone; ?></td>
                <td><?php echo $customer->email; ?></td>
                <td><?php echo count($customer->cars ?? []); ?></td>
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