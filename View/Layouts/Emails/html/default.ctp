<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
</head>
<body style="background:#F0F0F0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<div style="background:#F0F0F0; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
            <tr>
                <td valign="top"><?= $this->Html->link(
                    $this->Html->image(
                        'header.jpg',
                        array('style' => 'margin-bottom:10px;border:none', 'fullBase' => true)
                    ),
                    $this->Html->url('/', true),
                    array('escape' => false)
               	) ?></td>
            </tr>
            <tr>
                <td valign="top">
					<?= $this->fetch('content'); ?>
				</td>
            </tr>
        </table>
      </td>
</tr>
</table>
</div>
</body>
</html>