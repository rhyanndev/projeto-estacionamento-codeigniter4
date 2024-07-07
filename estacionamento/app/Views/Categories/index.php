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

        <a class="btn btn-dark btn-sm float-end" href="<?php echo route_to('categories.new') ?>">Nova</a>

      </div>
      <div class="card-body">
        <h4 class="card-title"><?php echo $title; ?></h4>

        <!--Contando os registros-->
        <?php if(count($categories) === 0): ?>

        <div class="alert alert-info text-center">Não há dados para exibir</div>

        <?php else: ?>

          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Ações</th>
                <th>Nome</th>
                <th>Preço por hora</th>
                <th>Preço por dia</th>
                <th>Preço por mês</th>
                <th>Vagas</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($categories as $category) : ?>
              <tr>
                <td><a class="btn btn-primary btn-sm me-2" href="<?php echo route_to('categories.edit', (string) $category->_id); ?>">Editar</a>
              
                <?php echo form_open(
                  route_to('categories.delete', (string) $category->_id), 
                  attributes:['class' => 'd-inline', 'onsubmit' => 'return confirm("Tem certeza da remoção?");'],
                  hidden: ['_method' => 'DELETE']);  ?>

               <button type="submit" class="btn btn-danger btn-sm">Excluir</button> 

                <?php echo form_close();  ?>

                </td>
                <td><?php echo $category['name']; ?></td>
                <td><?php echo format_money_brl($category['price_hour']); ?></td>
                <td><?php echo format_money_brl($category['price_day']); ?></td>
                <td><?php echo format_money_brl($category['price_month']); ?></td>
                <td><?php echo $category['spots']; ?></td>
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