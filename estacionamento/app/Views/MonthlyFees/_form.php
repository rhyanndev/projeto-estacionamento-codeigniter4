<?php

use App\Enum\MonthlyFeeStatus; ?>

<div class="row">

    <div class="form-group col-md-4">

        <label for="customer_id">Cliente mensalista</label>
        <?php echo $customersOptions; ?>
        <?php echo validation_show_error('customer_id') ?>

    </div>

    <div class="form-group col-md-4">

        <label for="category_id">Categoria</label>
        <?php echo $categoriesOptions; ?>
        <?php echo validation_show_error('category_id') ?>

    </div>

    <div class="form-group col-md-4">

        <label for="due_date">Data de vencimento</label>
        <input type="date" class="form-control" required name="due_date" id="due_date" value="<?php echo old('due_date', $fee->due_date ?? null) ?>">
        <?php echo validation_show_error('due_date') ?>

    </div>
    
</div>

<div class="form-check mb-4">

    <label class="form-check-label">

        <?php echo form_hidden('status', MonthlyFeeStatus::Waiting->value); ?>
        <input type="checkbox" class="form-check-input" name="status" <?php if (fee_is_paid($fee->status ?? null)) : ?> checked <?php endif; ?> value="<?php echo MonthlyFeeStatus::Paid->value;  ?>">
        Mensalidade paga
    </label>

</div>

<button type="submit" id="btnSubmit" class="btn btn-primary btn-sm me-2">Salvar</button>

<a href="<?php echo route_to('monthly.fees') ?>" class="btn btn-info btn-sm">Voltar</a>