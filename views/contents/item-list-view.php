<!-- Page header -->
<div class="full-box page-header">
  <h3 class="text-left">
    <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ITEMS
  </h3>
  <p class="text-justify">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum delectus
    eos enim numquam fugit optio accusantium, aperiam eius facere architecto
    facilis quibusdam asperiores veniam omnis saepe est et, quod obcaecati.
  </p>
</div>

<div class="container-fluid">
  <ul class="full-box list-unstyled page-nav-tabs">
    <li>
      <a href="<?php echo APP_SERVER; ?>item-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ITEM</a>
    </li>
    <li>
      <a class="active" href="<?php echo APP_SERVER; ?>item-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp;
        LISTA DE ITEMS</a>
    </li>
    <li>
      <a href="<?php echo APP_SERVER; ?>item-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ITEM</a>
    </li>
  </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
  <!-- Table and Paginador -->
  <?php
  require_once "./controllers/itemController.php";

  $ins_item = new itemController();
  echo $ins_item->paginationItemsController($page[1], 15, $_SESSION['role_spm'], $page[0], "");
  ?>
</div>
