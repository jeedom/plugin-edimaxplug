<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$eqLogics=eqLogic::byType('edimaxplug');
sendVarToJS('eqType', 'edimaxplug');
?>

<div class="row row-overflow">
    <div class="col-lg-2">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true, true) . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
     <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
   <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
   <div class="eqLogicThumbnailContainer">
	 <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="text-align: center; background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
        <i class="fa fa-wrench" style="font-size : 5em;color:#767676;"></i>
      <br>
      <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Configuration}}</span>
    </div>

	<div class="cursor" id="bt_syncEqLogic" style="text-align: center; background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
        <i class="fa fa-refresh" style="font-size : 5em;color:#767676;"></i>
      <br>
      <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Synchroniser}}</span>
    </div>
  </div>
        <legend><i class="techno-cable1"></i> {{Mes edimaxplugs}}
        </legend>
        <?php
        if (count($eqLogics) == 0) {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore de edimaxplug, cliquez sur Synchroniser pour commencer}}</span></center>";
        } else {
            ?>
            <div class="eqLogicThumbnailContainer">
                <?php
                foreach ($eqLogics as $eqLogic) {
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
					if (file_exists('plugins/edimaxplug/doc/images/' . $eqLogic->getConfiguration('type') . '.png')) {
						echo '<img src="plugins/edimaxplug/doc/images/' . $eqLogic->getConfiguration('type') . '.png" height="105" width="95" />';
					}else{
						echo '<img src="plugins/edimaxplug/plugin_info/edimaxplug_icon.png" height="105" width="95" />';	
					}
                    echo "<br>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
                    echo '</div>';
                }
                ?>
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-10 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <div class="row">
        	<div class="col-sm-6">
		        <form class="form-horizontal">
		            <fieldset>
		                <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}<i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
		                <div class="form-group">
		                    <label class="col-lg-2 control-label">{{Nom de l'équipement edimaxplug}}</label>
		                    <div class="col-lg-4">
		                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
		                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement edimaxplug}}"/>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-lg-2 control-label" >{{Objet parent}}</label>
		                    <div class="col-lg-4">
		                        <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
		                            <option value="">{{Aucun}}</option>
		                            <?php
		                            foreach (jeeObject::all() as $object) {
		                                echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
		                            }
		                            ?>
		                        </select>
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-lg-2 control-label">{{Catégorie}}</label>
		                    <div class="col-lg-8">
		                        <?php
		                        foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
		                            echo '<label class="checkbox-inline">';
		                            echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
		                            echo '</label>';
		                        }
		                        ?>
		
		                    </div>
		                </div>
		                <div class="form-group">
		                    <label class="col-md-2 control-label" >{{Activer}}</label>
		                    <div class="col-md-1">
		                        <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" size="16" checked/>
		                    </div>
		                    <label class="col-md-2 control-label" >{{Visible}}</label>
		                    <div class="col-md-1">
		                        <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>
		                    </div>
		                </div>
		            </fieldset> 
		        </form>
			</div>
			<div class="col-sm-6">
				<legend><i class="fa fa-info"></i>  {{Informations}}</legend>
				<div class="form-group">
                    <label class="col-lg-2 control-label">{{Adresse IP}}</label>
                    <div class="col-lg-4">
                        <input type="text" id="address" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip" placeholder="{{Adresse IP}}" disabled/>
                    </div>                    
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Modèle}}</label>
                    <div class="col-lg-4">
                        <input type="text" id="type" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="model" placeholder="{{Modèle}}" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Version}}</label>
                    <div class="col-lg-4">
                        <input type="text" id="soft" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="version" placeholder="{{Version}}" disabled/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label">{{Adresse MAC}}</label>
                    <div class="col-lg-4">
                        <input type="text" id="hard" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="mac" placeholder="{{Mac}}" disabled/>
                    </div>
                </div>
                 <div class="form-group">	
                    <div style="text-align: center">
                     	<center><img src="plugins/edimaxplug/doc/images/edimaxplug_icon.png" id="img_Model"  onerror="this.src='plugins/edimaxplug/doc/images/edimaxplug_icon.png'" style="height : 280px;" /></center>
                    </div>
               	</div>
			</div>
		</div>		       

        <legend>Commandes</legend>
        <a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i>{{ Ajouter une commande}}</a><br/><br/>
        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>{{Nom}}</th>
                    <th style="width: 100px;">{{Type}}</th>
                    <th style="width: 200px;">{{Options}}</th>
                    <th style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
</div>
<?php include_file('desktop', 'edimaxplug', 'js', 'edimaxplug'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
