<?= $this->extend('layouts/blank-base') ?>

<?= $this->section('content') ?>




<form action="<?= route_to('test.reminder') ?>" method="post">
    <?= csrf_field() ?>
    <input type="text" name="name" placeholder="Your Name" required />
    <input type="email" name="email" placeholder="Your Email" required />
    <input type="text" name="subject" placeholder="Subject" required />
    <textarea name="message" placeholder="Message" required></textarea>
    <button type="submit">Send Email</button>
</form>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>

<script>
    $(function() {
        $('.toastsDefaultSuccess').click(function() {
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Toast Title',
                subtitle: 'Subtitle',
                body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
        });
        $('.toastsDefaultBottomRight').click(function() {
            $(document).Toasts('create', {
                title: 'Toast Title',
                position: 'bottomRight',
                body: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
        });
    });
</script>

<?= $this->endSection() ?>