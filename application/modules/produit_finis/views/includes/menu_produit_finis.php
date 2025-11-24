    <div class="ibox-head d-flex justify-content-between align-items-center">
      <div class="ibox-title"><?= $title?></div>
      <div>
        <a class="btn <?php if($this->router->method == 'ajouter') echo 'btn-primary';?> btn-sm"
         href="<?=base_url('produit_finis/Produits_Finis/ajouter')?>">Ajouter</a>
         <a class="btn <?php if($this->router->method == 'index') echo 'btn-primary';?> btn-sm"
           href="<?=base_url('produit_finis/Produits_Finis')?>">Liste</a>
         </div>
       </div>

