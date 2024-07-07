<!-- Formulário para criar e editar -->

<div class="row">

    <div class="form-group col-md-12 mb-4">
        <label for="plate">Placa</label>
        <input type="text" name="plate" placeholder="Placa" class="form-control" value="<?php echo old('plate', $car->plate ?? null); ?>">
        <?php echo validation_show_error('plate'); ?>
    </div>

    <div class="form-group col-md-12 mb-4">
        <label for="vehicle">Descrição do veículo</label>
        <input type="text" name="vehicle" placeholder="Ex: Fiat Uno EVO VIVACE Preto 2011" class="form-control" value="<?php echo old('vehicle', $car->vehicle ?? null); ?>">
        <?php echo validation_show_error('vehicle'); ?>
    </div>

</div>


<?php echo form_hidden('customer_id', $customer_id); ?>


<button type="submit" id="btnSubmit" class="btn btn-primary btn-sm me-2">Salvar</button>

<a href="<?php echo route_to('customers.cars', (string) $customer_id) ?>" class="btn btn-info btn-sm">Voltar</a>