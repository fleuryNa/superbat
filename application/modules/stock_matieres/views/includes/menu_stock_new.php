    <div class="ibox-head d-flex justify-content-between align-items-center">
      <div class="ibox-title"></div>
      <div>
        <a class="btn <?php if($this->router->method == 'ajouter') echo 'btn-primary';?> btn-sm"
         href="<?=base_url('stock_matieres/Stock_Matieres_New/ajouter')?>">Ajouter</a>
         <a class="btn <?php if($this->router->method == 'index') echo 'btn-primary';?> btn-sm"
           href="<?=base_url('stock_matieres/Stock_Matieres_New')?>">Liste</a>
         </div>
       </div>