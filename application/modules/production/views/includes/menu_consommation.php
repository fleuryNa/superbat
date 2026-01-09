    <div class="ibox-head d-flex justify-content-between align-items-center">
      <div class="ibox-title"></div>
      <div>
        <a class="btn <?php if($this->router->method == 'index') echo 'btn-primary';?> btn-sm"
         href="<?=base_url('production/Consommation_Matieres_Premieres')?>">Liste</a>
         <a class="btn <?php if($this->router->method == 'ajouter') echo 'btn-primary';?> btn-sm"
           href="<?=base_url('production/Consommation_Matieres_Premieres/ajouter')?>">Ajouter</a>
         </div>
       </div>