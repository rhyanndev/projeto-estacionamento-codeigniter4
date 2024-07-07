<!-- Formulário para criar e editar -->

<div class="row">

    <div class="form-group col-md-12 mb-4">
        <label for="name">Nome</label>
        <input type="text" name="name" placeholder="Nome" class="form-control" value="<?php echo old('name', $customer->name ?? null); ?>">
        <?php echo validation_show_error('name'); ?>
    </div>

    <div class="form-group col-md-4 mb-4">
        <label for="cpf">CPF Válido</label>
        <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="CPF Válido" class="form-control" value="<?php echo old('cpf', $customer->cpf ?? null); ?>">
        <?php echo validation_show_error('cpf'); ?>
    </div>

    <div class="form-group col-md-4 mb-4">
        <label for="phone">Telefone</label>
        <input type="tel" name="phone" placeholder="Número válido" class="form-control" value="<?php echo old('phone', $customer->phone ?? null); ?>">
        <?php echo validation_show_error('phone'); ?>

    </div>

    <div class="form-group col-md-4 mb-4">
        <label for="email">E-mail</label>
        <input type="email" name="email" placeholder="E-mail" class="form-control" value="<?php echo old('email', $customer->email ?? null); ?>">
        <?php echo validation_show_error('email'); ?>

    </div>

</div>

<button type="submit" id="btnSubmit" class="btn btn-primary btn-sm me-2">Salvar</button>

<a href="<?php echo route_to('customers') ?>" class="btn btn-info btn-sm">Voltar</a>