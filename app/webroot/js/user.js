$(function() {

	var entryUrl = $("#entry_url").val();

	$("#UserRoomId").change(function(){
		$("#room_change_form").submit();
	});

	$(".dialog").click(function(){

		var ele = $(this).attr("id").split("_");

		var startDate=ele[1];
		var startTime = ele[2].split("-")[0];


		$("#regist_room_id").val($("#UserRoomId").val());
		$("#regist_start_timeline_id").val(ele[2]);

		//開始時刻
		var startTimeDisp=startDate +" "+ startTime;
		$("#start_time_disp_id").html(startTimeDisp);
		$("#start_time_hidden_id").val(startTimeDisp);

		//終了時刻
		var endDateStart = Date.create(startDate+ " " + startTime,"ja");

		//削除しておかないと加算される
		$("#end_time_pull_down_id option").remove();

		for(var i=1;i<24;i++){
			var optionEle =$("<option>");
			var timeEle = (i).hoursAfter(endDateStart).format('{yyyy}/{MM}/{dd} {24hr}:00');
			optionEle.append(timeEle).text(timeEle);
			$("#end_time_pull_down_id").append(optionEle);
		}

		$("#time_question").dialog('open');

	});

	$('#time_question').dialog({
		  autoOpen: false,
		  title: 'チャットルーム予約',
		  closeOnEscape: false,
		  modal: true,
		  buttons: {
		    "予約する": function(){
		       $("#room_reserve_form").submit();
		    }
		  }
		});

});