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
        <a class="btn btn-info btn-sm float-start" href="<?php echo route_to('customers.show', $customer->_id) ?>">Voltar</a>
        <a class="btn btn-primary btn-sm float-end" href="<?php echo route_to('customers.cars.new', $customer->_id) ?>">Novo veículo</a>
      </div>
      <div class="card-body">
        <h4 class="card-title"><?php echo $title; ?></h4>

        <?php if (count($cars) === 0) : ?>

          <div class="alert alert-info text-center">Aqui serão exibidos os veículos do mensalista <?php echo $customer->name; ?></div>

        <?php else : ?>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Ações</th>
                  <th>Placa</th>
                  <th>Veículo</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cars as $car) : ?>
                  <tr>
                    <td>

                      <a class="btn btn-primary btn-sm me-2" href="<?php echo route_to('customers.cars.edit', (string) $car->_id); ?>">Editar</a>

                      <?php echo form_open(
                        route_to('customers.cars.delete', (string) $car->_id),
                        attributes: ['class' => 'd-inline', 'onsubmit' => 'return confirm("Tem certeza da remoção?");'],
                        hidden: ['_method' => 'DELETE']
                      );  ?>

                      <button type="submit" class="btn btn-danger btn-sm">Excluir</button>

                      <?php echo form_close();  ?>

                    </td>
                    <td><?php echo $car->plate; ?></td>
                    <td><?php echo $car->vehicle; ?></td>
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