<?php /** @var \App\View\AppView $this */ ?>
<?php /** @var \Cake\Controller\Component\AuthComponent $Auth */ ?>
<?php /** @var array $menu */ ?>
<ul>
    <?php foreach ($menu as $label => $url): ?>
        <li<?= $this->request->param('controller') == $url['controller'] ? ' class="active"' : ''; ?>>
            <?= $this->Html->link($label, $url); ?>
        </li>
    <?php endforeach; ?>
</ul>
