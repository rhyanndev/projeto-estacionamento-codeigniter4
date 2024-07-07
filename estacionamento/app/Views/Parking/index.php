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

        <div class="row">

          <?php echo $spots; ?>

        </div>

      </div>
    </div>
  </div>

</div>


<!-- Modal escolha do tipo de ticket -->
<div class="modal fade" id="modalChoice" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="modalChoiceToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Criar Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <button class="btn btn-lg btn-success" id="btnCreateNewDetachedTicked" data-bs-dismiss="modal">Abrir Ticket Avulso</button>
        <button class="btn btn-lg btn-primary" id="btnCreateNewCustomerTicked" data-bs-dismiss="modal">Abrir Ticket Mensalista</button>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('js') ?>

<script>
  // algumas constantes

  // para a view de criação de avulso
  const URL_NEW_DETACHED_TICKET = '<?php echo route_to('parking.new.single.ticket'); ?>';

  // para a view de criação de ticket mensalista
  const URL_NEW_CUSTOMER_TICKET = '<?php echo route_to('parking.new.customer.ticket'); ?>';

  // para a view de detalhes de ticket
  const URL_SHOW_TICKET = '<?php echo route_to('parking.show.ticket'); ?>';


  // modal de escolha do tipo
  const modalChoice = document.getElementById('modalChoice');


  // ao ser clicado iremos para a view de criação de avulso
  const btnCreateNewCustomerTicked = document.getElementById('btnCreateNewCustomerTicked');

  // ao ser clicado iremos para a view de criação de ticket mensalista
  const btnCreateNewDetachedTicked = document.getElementById('btnCreateNewDetachedTicked');

  // botões de edição de ticket
  const btnEditTicked = document.getElementById('btnEditTicked');

  // agora recupero os elementos que tenham a classe '.btn-new-ticket', 
  const buttonsNewTicket = document.querySelectorAll('.btn-new-ticket');

  // agora recupero os elementos que tenham a classe '.btn-view-ticket', 
  const buttonsActionTicket = document.querySelectorAll('.btn-view-ticket');


  // usaremos para enviar no request
  let intendedCode = null;
  let intendedSpot = null;



  // percorro todos os botões para criação de ticket
  buttonsNewTicket.forEach(element => {

    // e fico 'escutando' o click no elemento
    // e para cada click recupero o valores de 'data-code' e 'data-spot'
    element.addEventListener("click", function(e) {

      const code = event.target.dataset.code;
      const spot = event.target.dataset.spot;

      if (!code || code === 'undefined') {

        alert('Não foi possível determinar a categoria.');
        return;
      }

      // atribuímos às variáveis de escopo global
      intendedCode = code;
      intendedSpot = spot;

      // abrimos o modal para escolher o tipo de ticket
      new bootstrap.Modal(modalChoice).show();

    });
  });



  // adicionamos o 'ouvinte' no botão de criação de ticket avulso
  btnCreateNewDetachedTicked.addEventListener('click', () => {

    let urlCreation = URL_NEW_DETACHED_TICKET + '?' + setParamters({
      code: intendedCode,
      spot: intendedSpot,
    });

    window.location.href = urlCreation;
  });


  // adicionamos o 'ouvinte' no botão de criação de ticket para mensalista
  btnCreateNewCustomerTicked.addEventListener('click', () => {

    let urlCreation = URL_NEW_CUSTOMER_TICKET + '?' + setParamters({
      code: intendedCode,
      spot: intendedSpot,
    });

    window.location.href = urlCreation;
  });



  // percorro todos os botões para edição ou encerramento de ticket
  buttonsActionTicket.forEach(element => {

    // e fico 'escutando' o click no elemento
    // e para cada click recupero o valores de 'data-code' e 'data-spot'
    element.addEventListener("click", function(e) {

      // recuperamos o código do ticket
      const ticketCode = event.target.dataset.code;


      if (!ticketCode || ticketCode === 'undefined') {

        alert('Não foi possível determinar qual é o Ticket.');
        return;
      }

      // adicionamos o parâmetros da URL
      let urlAction = URL_SHOW_TICKET + '?' + setParamters({
        ticket_id: ticketCode,
      });


      // requisitamos a URL
      window.location.href = urlAction;

    });
  });



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