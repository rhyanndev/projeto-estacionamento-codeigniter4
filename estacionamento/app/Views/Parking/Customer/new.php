<?= $this->extend('Layouts/main')  ?>


<?= $this->section('title') ?>

<?php echo $title ?? ''; ?>


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

                <!-- Usado no script -->
                 <div id="divBlock"></div>

                <p>
                    <strong>Categoria:&nbsp;</strong><?php echo $category->name; ?>
                </p>

                <p>
                    <strong>Vaga:&nbsp;</strong><?php echo $hidden['spot']; ?>
                </p>

                <hr>

                <!-- Abrindo um formulário  -->
                <?php echo form_open(
                    action: route_to('parking.create.customer.ticket'),
                    attributes: ['onsubmit' => 'return confirm("Os dados estão corretos?  \n\n Não será possível editar as informações");'],
                    hidden: $hidden
                ); ?>


                <div class="form-group mb-4">
                    <label for="customer_id">Escolha um cliente</label>
                    <?php echo $customersOptions; ?>
                    <?php echo validation_show_error('customer_id') ?>
                </div>

                <div class="form-group mb-4">
                    <label for="car_id">Escolha o carro</label>

                    <div id="boxCars">

                        <select class="form-control" disabled>
                            <option>Escolha o cliente primeiro</option>
                        </select>

                    </div>

                    <?php echo validation_show_error('car_id') ?>
                </div>

                <div class="form-group mb-4">
                    <label for="observations">Observações</label>
                    <textarea name="observations" placeholder="Pergunte ao cliente ou verifique se o veículo possuí alguma observação." class="form-control" id="observations" style="min-height: 100px;"></textarea>
                    <?php echo validation_show_error('observations'); ?>
                </div>

                <hr>

                <button id="btnCreate" type="submit" class="btn btn-primary me-2">Criar ticket mensalista</button>

                <a href="<?php echo route_to('parking') ?>" class="btn btn-info">Cancelar abertura</a>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>

<script>
    const URL_GET_CUSTOMER_CARS = '<?php echo route_to('parking.get.curstomers.cars'); ?>';

    // recuperamos os hiddens inputs
    const hiddenPlate = document.getElementsByName('hidden_plate')[0];
    const hiddenVehicle = document.getElementsByName('hidden_vehicle')[0];

    // box que receberá os carros
    const boxCars = document.getElementById('boxCars');

    // recebera o nome do cliente para um possível alerta se estiver devendo
    let customerName = null;



    // adicionamos um 'ouvinte' no dropown de clientes para buscarmos os carros deles a cada mudança
    const dropdownCustomer = document.getElementById("customer_id");

    dropdownCustomer.addEventListener('change', (event) => {

        // limpamos o nome do cliente
        customerName = null;

        // limpamos os hiddens
        hiddenPlate.value = null;
        hiddenVehicle.value = null;

        // recuperamos o código do cliente
        const customerCode = event.target.value;

        if (!customerCode) {

            boxCars.innerHTML = `<select class="form-select" disabled>
                                    <option>Escolha o cliente primeiro</option>
                                </select>`;
            return;
        }

        customerName = dropdownCustomer.options[dropdownCustomer.selectedIndex].text;


        // Buscamos os carros dele  
        getCars(customerCode);

    });



    // recupera os carros do cliente
    const getCars = async (customerCode) => {

        let urlGetCars = URL_GET_CUSTOMER_CARS + '?' + setParamters({
            customer_code: customerCode,
        });


        const response = await fetch(urlGetCars, {
            method: "get",
            headers: setHeadersRequest()
        });

        if (!response.ok) {

            alert('Não foi possível recuperar os veículos');

            throw new Error(`HTTP error! status: ${response.status}`);

            return;
        }

        // response convertido em JSON
        const data = await response.json();

        // colocamos os carros na div
        renderCarsOptions(data.cars, boxCars);

        // verificamos se o cliente tem mensalidades em atraso
        const block = data.block;

        const alertMessage = `<div class="alert alert-warning d-flex text-dark align-items-center" role="alert">
                                <div>
                                    O cliente <strong>[ ${customerName} ]</strong> tem mensalidades em atraso!
                                </div>
                            </div>`;

        const btnCreate = document.getElementById('btnCreate');
        const divBlock = document.getElementById('divBlock');
        btnCreate.disabled = block;
        btnCreate.innerText = block ? 'Ação bloqueda' : 'Criar ticket mensalista';
        block ? btnCreate.classList.add(...['btn-danger', 'text-white']) : btnCreate.classList.remove(...['btn-danger', 'text-white']);
        divBlock.innerHTML = block ? alertMessage : '';

    }

    function renderCarsOptions(cars, targetDiv) {

        // limpamos os hiddens
        hiddenPlate.value = null;
        hiddenVehicle.value = null;

        const carDropdown = document.createElement('select');

        // Adiciona a classe 'form-select' ao dropdown
        carDropdown.classList.add('form-select');

        // Adiciona uma opção vazia como padrão
        const defaultOption = document.createElement('option');


        if (cars.length === 0) {

            // Se o array de carros estiver vazio, desabilita o dropdown e adiciona uma opção indicando que não há carros
            const option = document.createElement('option');
            option.text = 'Esse cliente não tem carros';
            carDropdown.add(option);
            carDropdown.disabled = true;
        } else {
            // Adiciona as opções do dropdown com base nos dados fornecidos

            defaultOption.text = '--- Escolha um carro ---';
            carDropdown.add(defaultOption);

            cars.forEach(car => {
                const option = document.createElement('option');
                option.value = car._id.$oid; // Defina o valor conforme necessário
                option.text = `${car.plate} - ${car.vehicle}`;
                carDropdown.id = 'car_id';
                carDropdown.name = 'car_id';

                // Adiciona atributos data-plate e data-vehicle
                option.setAttribute('data-plate', car.plate);
                option.setAttribute('data-vehicle', car.vehicle);

                carDropdown.add(option);
            });
        }

        // Limpando a div antes de adicionar o dropdown
        targetDiv.innerHTML = '';

        // Adiciona o dropdown à div específica
        targetDiv.appendChild(carDropdown);

        // Adiciona o evento de escuta para o evento 'change'
        carDropdown.addEventListener('change', function() {
            // Obtém o elemento selecionado
            const selectedOption = this.options[this.selectedIndex];

            // Atualiza os valores dos inputs hidden usando os atributos 'name'
            hiddenPlate.value = selectedOption.getAttribute('data-plate');
            hiddenVehicle.value = selectedOption.getAttribute('data-vehicle');
        });
    }



    /** 
     * Define o header da rquisição para Fetch API
     */
    const setHeadersRequest = () => {

        return {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        };
    }

    // define os parâmetros que serão enviados na requisição
    const setParamters = (object) => {

        return (new URLSearchParams(object)).toString();
    }
</script>

<?= $this->endSection() ?>