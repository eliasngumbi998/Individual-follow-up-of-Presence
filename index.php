<?
if($pSView==1){
	$dt  = explode("-",date("Y-m-d"));
	$date1 = strftime("%Y-%m-20", mktime(0,0,0,$dt[1],1,$dt[0]));
	$dt  = explode("-",date('Y-m-d', strtotime('-1 month')));
	$date2 = strftime("%Y-%m-21", mktime(0,0,0,$dt[1]+1,0,$dt[0]));
	$date1a = ereg_replace('^([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})$', '\\3/\\2/\\1', $date1);
	$date2a = ereg_replace('^([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})$', '\\3/\\2/\\1', $date2);
	if($action){
		$query=query("SELECT date_format(hrm_suivie.date,'%d/%m/%Y') AS date, hrm_object.designation FROM hrm_suivie LEFT JOIN hrm_object ON hrm_suivie.action = hrm_object.id WHERE hrm_suivie.emplyee='$action' AND hrm_suivie.date between '$date2' and '$date1' ORDER BY hrm_suivie.date");
		newtab(50,"Pointage $action De $date2a � $date1a");
		table_header(array("Date","D�signation"));
//		echo "<caption>Pointage $action De $date2a � $date1a</caption>";
		while($row=fetch($query)){
			echo "<tr><td>$row->date<td>$row->designation";
		}
		endtab();
	}

	$query = query("SELECT
	CONCAT(hrm_perso.nom, ' ', hrm_perso.prenom) AS nom,
	hrm_object.designation,
	COUNT(hrm_suivie.`action`) AS nb,
	hrm_suivie.emplyee
	FROM hrm_suivie
	INNER JOIN hrm_perso ON hrm_suivie.emplyee = hrm_perso.id
	INNER JOIN hrm_object ON hrm_suivie.action = hrm_object.id
	WHERE hrm_suivie.date between '$date2' and '$date1' AND hrm_object.designation !='R�cup�ration'
	GROUP BY hrm_suivie.emplyee, hrm_suivie.action
	ORDER BY nom, hrm_object.designation");
	pprint(0,"suivoint");
	newtab(50);
	echo "<caption>Pointage De $date2a � $date1a</caption>";
	table_header(array("Nom & Prenom","D�signation","Nb",""));
	while($row=fetch($query)){
		echo "<tr><td>$row->nom<td>$row->designation<td>$row->nb";
		echo "<td><a href='index.php?" . encode("modules=suivpoint&action=$row->emplyee")."' alt='Voir' title='Voir'><spam class='fa-eye grey'></span></a>";
	}
	endtab();
}
?>
    <script>
	$(document)
	.ready(function(){
		$('#grid')
		.dataTable({
			oLanguage: {sSearch: "Recherche : "},
			iDisplayLength: <?=$page;?>,
			aaSorting: [[0, "asc"]],
			aoColumnDefs: [
				{"aTargets": [0],'sClass': "bold"},
				{"aTargets": [1]},
				{"aTargets": [2]},
				{"aTargets": [3]}
			],
			sPaginationType: "full_numbers",
			sDom: "<'row-fluid' <'widget-header' <'span2'l> <'span10'<'table-reset-wrapper'>f> > <'table-tool'> >  Rrt <'row-fluid' <'widget-footer' <'span4' <'table-action-wrapper'> i> <'span8'p> >",
		});
	});
    </script>
