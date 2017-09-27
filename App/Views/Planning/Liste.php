<?php
/*
 * $ajoutPlanning
 * $titre
 * $message
 * $listIdUsed
 * $plannings
 * $canDelete
 * $lienModif
 */
?>
<?= $ajoutPlanning ?>
<h1><?= $titre ?></h1>
<?= $message ?>
<table class="table table-hover table-responsive table-condensed table-striped">
<thead>
    <tr><th><?= _('divers_nom_maj_1') ?></th><th style="width:10%"></th></tr>
</thead>
<tbody>
<?php if (empty($plannings)) : ?>
    <tr><td colspan="2"><center><?= _('aucun_resultat') ?></center></td></tr>
<?php else : ?>
    <?php foreach ($plannings->data as $planning) : ?>
        <tr><td><?= $planning->name ?></td>
            <td>
                <a title="<?= _('form_modif') ?>" href="<?= $lienModif ?>&amp;id=<?= $planning->id ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                <?php if ($canDelete) : ?>
                    <?php if (in_array($planning->id, $listIdUsed)) : ?>
                        <button title="<?= _('planning_used') ?>" type="button" class="btn btn-link disabled"><i class="fa fa-times-circle"></i></button>
                    <?php else : ?>
                        <form action="" method="post" accept-charset="UTF-8"
                        enctype="application/x-www-form-urlencoded">
                            <input type="hidden" name="planning_id" value="<?= $planning->id ?>" />
                            <input type="hidden" name="_METHOD" value="DELETE" />
                            <button type="submit" class="btn btn-link" title="<?= _('form_supprim') ?>"><i class="fa fa-times-circle"></i></button>
                        </form>
                    <?php endif ?>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach ?>
<?php endif ?>
</tbody>
</table>
