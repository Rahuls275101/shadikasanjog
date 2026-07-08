<?php $pager->setSurroundCount(2); ?>

<nav aria-label="Page navigation">
    <ul class="pagination pagination-sm">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link pagination-link" href="javascript:void(0);" data-page="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>">
                    <?= lang('Pager.previous') ?>
                </a>
            </li>
        <?php endif; ?>

        <?php foreach ($pager->links() as $link): ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link pagination-link" href="javascript:void(0);" data-page="<?= $link['uri'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link pagination-link" href="javascript:void(0);" data-page="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>">
                    <?= lang('Pager.next') ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
