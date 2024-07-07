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

                <?php echo form_open(route_to('company.process'), attributes: ['class' => 'form-sample']); ?>


                <!-- Formulário para criar e editar -->

                <div class="row">

                    <div class="form-group col-md-12 mb-4">
                        <label for="name">Nome</label>
                        <!--Vai tentar acessar a propriedade name do objeto, caso não existir vai receber um valor null-->
                        <input type="text" name="name" placeholder="Nome da empresa" class="form-control" value="<?php echo old('name', $company->name ?? null); ?>">
                        <?php echo validation_show_error('name'); ?>
                    </div>

                    <div class="form-group col-md-6 mb-4">
                        <label for="phone">Telefone</label>
                        <input type="tel" name="phone" placeholder="Telefone da empresa" class="form-control" value="<?php echo old('phone', $company->phone ?? null); ?>">
                        <?php echo validation_show_error('phone'); ?>
                    </div>

                    <div class="form-group col-md-6 mb-4">
                        <label for="address">Endereço</label>
                        <input type="text" name="address" placeholder="Endereço da empresa" class="form-control" value="<?php echo old('address', $company->address ?? null); ?>">
                        <?php echo validation_show_error('address'); ?>
                    </div>

                    <div class="form-group col-md-12 mb-4">
                        <label for="message">Mensagem</label>
                        <textarea class="form-control" style="min-height: 200px;" name="message" id="message" cols="30" rows="10"><?php echo old('message', $company->message ?? null); ?></textarea>
                        <?php echo validation_show_error('message'); ?>
                    </div>                  

                </div>

                <!-- Atualizando o registro -->
                <!-- Se for diferente de null, esse registro já existe na base de dados-->
                <?php if($company->id !== null): ?>

                <!--Utilizando o método PUT para atualizar-->
                    <?php echo form_hidden('_method', 'PUT'); ?>

                <?php endif; ?>



                <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm me-2">Salvar</button>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>

<?= $this->endSection() ?>