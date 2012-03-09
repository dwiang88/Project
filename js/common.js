var wasDragged = false;


$(document).ready(function() {

	$("#addwidget").click(function() {
		$('#newwidget').dialog('open');
	});
	$("#newwidget").dialog({
		autoOpen: false,
		modal: true,
		width:440,
		height:380
	});
	
	$("#addmessage").click(function() {
		$('#newmessage').dialog('open');
	});
	$("#newmessage").dialog({
		autoOpen: false,
		modal: true,
		width:430,
		height:290
	});
	
	
	$("#addproject").click(function() {
    	$('#newproject').dialog('open');
    });
    
    $("#newproject").dialog({
		autoOpen: false,
		modal: true,
		width:480,
		height:600
	});
	
	$("#addclient").click(function() {
		$('#newclient').dialog('open');
	});
	
	$("#newclient").dialog({
		autoOpen: false,
		modal: true,
		width:350,
		height:440
	});
	
	
	$("#addproof").click(function() {
		$('#newProof').dialog('open');
	});
	
	$("#newProof").dialog({
		autoOpen: false,
		modal: true,
		width:480,
		height:300
	});

	
	$("#tasks div.task-content").hide();
	$("#tasks h3 a").click(function(){
		var task = this;
		if (wasDragged) {
			wasDragged = false;
		} else {
			$(this).parent().toggleClass('open');
			$(this).parent().next().slideToggle();
		}
	 });

	$("#addtask").click(function() {
		$('#newtask').dialog('open');
	});
	
	$("#addContact").click(function() {
		$('#newcontact').dialog('open');
	});
	$("#newcontact").dialog({
		autoOpen: false,
		modal: true,
		width:440,
		height:480
	});
	
	$("#proofs div.proof-content").hide();
	$("#proofs h3").click(function(){
		$(this).toggleClass('open');
	    $(this).next().slideToggle();
	 });
	
	
	
	$("#newtask").dialog({
		autoOpen: false,
		modal: true,
		width:430,
		height:460
	});

	$('.deadline-ui').each(function(){
		var altid = '#' + $(this).attr('id') + '-alt';
		$(this).datepicker({ showOn: 'focus', dateFormat: 'dd/mm/yy', altField: altid , altFormat: 'yy-mm-dd' });
	});
	
	$('.date-ui').each(function(){
		var altid = '#' + $(this).attr('id') + '-alt';
		$(this).datepicker({ showOn: 'focus', dateFormat: 'dd/mm/yy', altField: altid , altFormat: 'yy-mm-dd' });
	});
	
	$(".widget li:even").css("background-color","#f0f0f0");
	
	
	
	$(".trigger").change(function() {
		$(this).parents("form").submit();
	});
	
	$('#project_name').change(function() {
		if ($('input#name').val() == '' ) {
			$('input#name').val($('input#project_name').val());
		}
	});

	$("#colorSwitch").click(function() {
		$('body').toggleClass('mono');
		
		//save chosen theme to cookie
		if ($.cookie('theme') == 'mono') {
			$.cookie('theme','green', { path: '/', expires: 1000 })
		} else {
			$.cookie('theme','mono', { path: '/', expires: 1000 })
		}
	});
	
	
});