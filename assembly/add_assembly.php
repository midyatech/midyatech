<?php
require_once realpath(__DIR__ . '/..') . '/include/header.php';
include_once realpath(__DIR__ . '/..') . '/include/checksession.php';
include_once realpath(__DIR__ . '/..') . '/include/checkpermission.php';
require_once realpath(__DIR__ . '/..') . '/class/Assembly.class.php';
require_once realpath(__DIR__ . '/..') . '/class/Chart.php';

$chart = new Chart();
$assembly = new Assembly();
//$Survey = new Survey();

$html = new HTML($LANGUAGE);
$dictionary = new Dictionary($LANGUAGE);
$dictionary->GetAllDictionary ();

$StationArr = $assembly->GetStationByArea();
$FeederArr = $assembly->GetFeederByStation();

$code = $notes = $start_date = null;
$id= $project_items = null;
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $project_items = $assembly->getAssemblyOrderItems($id, 0);
    $project_items_extra = $assembly->getAssemblyOrderItems($id, 1);
    $order = $assembly->GetAssemblyOrder($id);
    $code = $order[0]["assembly_order_code"];
    $notes = $order[0]["notes"];
    $start_date = $order[0]["start_date"];
}

$options = array("class"=>"form-control", "flow"=>"horizental", "label-align"=>"opposite", "optional"=>true);
?>
<style>
#transformers .well {
    height: 225px;
    overflow: auto;
}
.well .form-group{
    margin-left: 0px;
    margin-right: 0px;
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/16.2.3/css/dx.common.css" />
<link rel="dx-theme" data-theme="generic.light" href="https://cdn3.devexpress.com/jslib/16.2.3/css/dx.light.css" />
<script src="https://cdn3.devexpress.com/jslib/16.2.3/js/dx.all.js"></script>
<?php
$cols = array();
$cols[] = array("column"=>"enclosure_type");
$cols[] = array("column"=>"enclosures_count");
/*$cols[] = array("column"=>"ACTION_COL", "style"=>"width:50px","action-type"=>"ajax",
            "buttons"=> array(
                array("action-class"=>"enclosure_delete", "button-icon"=>"fa fa-times", "title"=>$dictionary->GetValue("Delete"), "type"=>"button", "url"=>"href=javascript:;")
        )
    );*/

$tableoptions = array();


$html->OpenDiv("row");
{
    $html->OpenSpan(12);
    {
        if ($id == null) {
            $html->OpenWidget("Survey_Data", null, array('collapse' => true, 'fullscreen'=>true, "content"=>"form"));
            {
                $html->OpenForm( "code/assembly.order.add.code.php", "form2", "horizental");
                {
                    $html->OpenDiv("row");
                    {
                        $html->OpenSpan(4);
                        {
                            $dirTree = array (
                                array("type"=>"hidden", "name"=>"area", "value"=>null, "list"=>null, "options"=>null),
                                array("type"=>"text", "name"=>"area_text", "value"=>null, "list"=>null, "options"=>array("class"=>"form-control open_area_tree", "tree"=>"", "readonly"=>"readonly"))
                        );
                            $html->DrawGenericFormField("area_id", $dirTree, "horizental", $options);

                            $html->DrawFormField("select", "station_id", null, $StationArr, $options);
                            $html->DrawFormField("select", "feeder_id", null, $FeederArr, $options);
                        }
                        $html->CloseSpan();
                        $html->OpenSpan(4);
                        {
                            echo '<section id="transformers">';
                            require 'ui/transformers.checkboxlist.php';
                            echo '</section>';
                        }
                        $html->CloseSpan();
                        $html->OpenSpan(4);
                        {
                            print "<section class='Project_Items'>";
                            //$html->Table(array(), $cols, $tableoptions);
                            print "</section>";
                        }
                        $html->CloseSpan();
                    }
                    $html->CloseDiv();
                    if ($id != null) {
                        $html->HiddenField("assembly_order_id", $id);
                    }

                    //$formActions = array();
                    //if(isset($_REQUEST["calculated"]) && $_REQUEST["calculated"] == true ){
                        $formActions = array (
                            array("type"=>"submit", "name"=>"Save", "value"=>"Save", "list"=>null, "options"=>array ("class" => "btn btn-primary")),
                            array("type"=>"reset", "name"=>"Reset", "value"=>"Reset", "list"=>null, "options"=>array ("class" => "btn btn-default resetform"))
                        );
                    //}
                }
                $html->CloseForm($formActions);

            }
            $html->CloseWidget();
        }

        if ($id != null) {
            $html->OpenWidget("assembly_order", null, array('collapse' => true, 'fullscreen'=>true, "content"=>"form"));
            {
                $html->OpenForm( "code/assembly.order.update.code.php", "form3", "horizental");
                {
                    $html->OpenDiv("row");
                    {
                        $html->OpenSpan(6);
                        {
                            $html->DrawFormField("text", "assembly_order_code", $code, null, $options);
                            //$html->DrawFormField("text", "start_date", $start_date, null, $options);
                            $html->DrawFormField ("text", "start_date", $start_date, null, array("class"=>"form-control date-picker", "flow"=>"horizental", "label-align"=>"opposite", "data-date-format"=>"yyyy-mm-dd", "readonly"=>"readonly") );
                        }
                        $html->CloseSpan();
                        $html->OpenSpan(6);
                        {
                            $html->DrawFormField("textarea", "notes", $notes, null, $options);
                        }
                        $html->CloseSpan();
                    }
                    $html->CloseDiv();

                    $html->OpenDiv("row");
                    {
                        // $html->OpenSpan(6);
                        // {
                        //     $html->OpenWidget("Transformer_Items", null, array('collapse' => false, 'fullscreen'=>false, "content"=>"form"), "light", "no_padding");
                        //     {
                        //         // "<section class='Project_Items'>";
                        //         $html->Table($project_items, $cols, $tableoptions);
                        //         //print "</section>";
                        //     }
                        //     $html->CloseSpan();
                        // }
                        // $html->CloseWidget();

                        // if ($id !== null) {
                        //     $html->OpenSpan(6);
                        //     {
                        //         $actions = array (
                        //             array("type"=>"button", "name"=>"Aadd", "value"=>"", "list"=>null, "options"=>array ("class" => "btn btn-circle btn-icon-only btn-default add_extra_items", "icon"=>"fa fa-plus"))
                        //         );

                        //         $html->OpenWidget("Extra_Items", $actions, array('collapse' => false, 'fullscreen'=>false, "content"=>"form"), "light", "");
                        //         {
                        //             print '<section id="extra_items">';
                        //             $html->Table($project_items_extra, $cols, $tableoptions);
                        //             print '</section>';
                        //         }
                        //         $html->CloseWidget();
                        //     }
                        //     $html->CloseSpan();
                        // }
                    }
                    $html->CloseDiv();

                    $formActions = array (
                        array("type"=>"submit", "name"=>"Change", "value"=>"Save", "list"=>null, "options"=>array ("class" => "btn btn-primary")),
                        array("type"=>"hidden", "name"=>"id", "value"=>$id, "list"=>null, "options"=>null)
                    );
                }
                $html->CloseForm($formActions);
            }
            $html->CloseWidget();


            $actions = array (
                array("type"=>"button", "name"=>"Print", "value"=>"", "list"=>null, "options"=>array ("class" => "btn btn-icon-only btn-default print_order", "icon"=>"fa fa-print"))
            );

            $html->OpenWidget("Statistics", $actions, array('collapse' => true, 'fullscreen'=>true));
            {
                //$html->OpenForm( null, "stats", "horizental");
                //{
                    $html->OpenDiv("row");
                    {
                        echo '<section id="stats">';
                        require 'ui/assembly.stats.php';
                        echo '</section>';
                    }
                    $html->CloseDiv();
                //}
                //$html->CloseForm();
            }
            $html->CloseWidget();
        }
    }
    $html->CloseSpan();
}
$html->CloseDiv();

?>
<script src="js/assembly.js" ></script>
<script src="../js/printThis.js?v=1"></script>
<?php
require '../include/footer.php';
?>
<script>
$(function () {

    $(".resetform").click(function(){
        window.location.href="add_assembly.php";
    })


    $("body").on("click", ".print_order", function(){
        $("section#stats").printThis({
            importCSS: true,
            pageTitle: "",
        });
    });

    $("body").on("click", ".select_node_button", function() {
        area_id = $(this).data("id");
        FillStation(area_id);
        GetTransformer(area_id);
    });

    $('#station_id').on('change', function () {
        station_id = $(this).val();
        FillFeeder(station_id);
        GetTransformer(0, station_id);
    });

    $('#feeder_id').on('change', function () {
        feeder_id = $(this).val();
        GetTransformer(0, 0, feeder_id);
    });

    $("body").on("click", ".trnasformers", function() {
        GetEnclosures();
    });

    $("body").on("click", "#checkall", function() {
        if ($(this).is(":checked")) {
            $("input.trnasformers").prop("checked", true);
        } else {
            $("input.trnasformers").prop("checked", false);
        }
        GetEnclosures();
    });

    $("body").on("click", ".calculate_enclosure", function() {
        var checkbox_value = [];
        $.each($("input[name='trnasformers[]']:checked"), function(){
            checkbox_value.push($(this).val());
        });
        $.ajax({
            type: 'POST',
            url: 'code/enclosure.calculate.code.php',
            data: 'transformer_id='+checkbox_value,
            success: function(data)
            {
                $('.Project_Items').load('api/get.project.items.php?transformer_id=' + checkbox_value);
            }
        });
    });


});

function FillStation(area_id){
    FillListOptions("ui/get.station.php?area_id="+area_id, "station_id", true);
}

function FillFeeder(station_id){
    if( station_id > 0){
        FillListOptions("ui/get.feeder.php?station_id="+station_id, "feeder_id", true);
    } else {
        FillListOptions("ui/get.feeder.php", "feeder_id", true);
    }
}

function GetTransformer(area_id, station_id, feeder_id){
    if(!area_id) area_id = $("#area").val();
    if(!station_id) station_id = $("#station_id").val();
    if(!feeder_id) feeder_id = $("#feeder_id").val();
    //alert('area_id=' + area_id + '&station_id=' + station_id + '&feeder_id=' + feeder_id);
    $('#transformers').load('ui/transformers.checkboxlist.php?area_id=' + area_id + '&station_id=' + station_id + '&feeder_id=' + feeder_id);
}

function GetEnclosures()
{
    var checkbox_value = [];
    $(".trnasformers:checkbox").each(function () {
        var ischecked = $(this).is(":checked");
        if (ischecked) {
            checkbox_value.push($(this).val());
        }
    });
    //$('.Project_Items').load('api/get.project.items.php?transformer_id=' + checkbox_value);
    $('.Project_Items').html('');
}
</script>