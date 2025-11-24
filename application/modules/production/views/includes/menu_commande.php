    <div class="ibox-head d-flex justify-content-between align-items-center">
      <div class="ibox-title"><?= $title ;?></div>
      <div>
        <a class="btn <?php if($this->router->method == 'index') echo 'btn-primary';?> btn-sm"
         href="<?=base_url('production/Commander')?>">Ajouter</a>
         <a class="btn <?php if($this->router->method == 'liste') echo 'btn-primary';?> btn-sm"
           href="<?=base_url('production/Commander/liste')?>">Liste</a>
         </div>
       </div>