<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($subject ?? 'Pending Travel Orders Notification') ?></title>
</head>

<body style="margin:0;padding:0;background-color:#f5f5f5;font-family:Arial,Helvetica,sans-serif;color:#333;">
    <div style="max-width:700px; margin:40px auto; background:#ffffff; border:1px solid #dcdfe3; border-radius:8px; overflow:hidden;">

        <!-- ================= HEADER (LOGO LEFT + CENTERED TEXT BLOCK) ================= -->
        <div style="background:#1b5e20; padding:20px 40px; color:#ffffff;">
            <!-- CENTER WRAPPER -->
            <div style="max-width:600px; margin:0 auto; text-align:center;">
                <div style="display:inline-block; vertical-align:middle;">
                    <div style="font-size:18px; font-weight:bold; line-height:1.3;">
                        Department of Environment and Natural Resources
                    </div>
                    <div style="font-size:13px; margin-top:4px;">
                        Provincial Environment and Natural Resources Office
                    </div>
                </div>
            </div>
        </div>
        <?= $this->renderSection('content') ?>
        <!-- ================= FOOTER ================= -->
        <div style="background:#f1f3f5; padding:16px 40px; text-align:center; font-size:11px; color:#6c757d;">
            Copyright &copy; <?= date('Y') ?> Department of Environment and Natural Resources – PENRO.<br>
            All rights reserved.
        </div>
    </div>

</body>

</html>