<?php if (session()->has('success')) : ?>

    <script>
        Toastify({
            text: "<?= session('success'); ?>",
            duration: 10000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "#4fbe87",
        }).showToast();
    </script>

<?php endif ?>

<?php if (session()->has('info')) : ?>

    <script>
        Toastify({
            text: "<?= session('info'); ?>",
            duration: 10000,
            close: true,
            gravity: "top",
            position: "left",
        }).showToast();
    </script>

<?php endif ?>

<?php if (session()->has('danger')) : ?>

    <script>
        Toastify({
            text: "<?= session('danger'); ?>",
            duration: 10000, // um pouco maior a duração
            close: true,
            gravity: "bottom",
            position: "right",
            backgroundColor: "#dc3545",
        }).showToast();
    </script>

<?php endif ?>



<!-- Para os erros do csrf 'Not allowed' -->
<?php if (session()->has('error')) : ?>

    <script>
        Toastify({
            text: "<?= session('error'); ?>",
            duration: 10000, // um pouco maior a duração
            close: true,
            gravity: "bottom",
            position: "right",
            backgroundColor: "#dc3545",
        }).showToast();
    </script>

<?php endif ?>