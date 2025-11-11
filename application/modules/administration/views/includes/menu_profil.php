    <div class="ibox-head d-flex justify-content-between align-items-center">
      <div class="ibox-title">Gestion des Profils de Droits</div>
      <div>
        <a class="btn <?php if($this->router->method == 'ajouter') echo 'btn-primary';?> btn-sm"
         href="<?=base_url('administration/Profil_Droit/ajouter')?>">Ajouter</a>
         <a class="btn <?php if($this->router->method == 'index') echo 'btn-primary';?> btn-sm"
           href="<?=base_url('administration/Profil_Droit')?>">Liste</a>
         </div>
       </div>