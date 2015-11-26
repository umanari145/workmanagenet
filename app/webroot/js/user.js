$(function() {

	var entryUrl = $("#entry_url").val();

//	$("#room_id").change(function(){
//
//		var roomId =$("#room_id").val();
//
//		$.ajax({
//			type : "POST",
//			url : entryUrl+"user/reserveroom",
//			data :"" ,
//			success : function(res) {
//
//			}
//		});
//
//	});

	$(".dialog").click(function(){



		var user_id =$("#user_id").val();

		var ele = $(this).attr("id").split("_");

		$("#regist_room_id").val($("#room_id").val());
		$("#regist_date_id").val(ele[1]);
		$("#regist_date_disp_id").html(ele[1]);

		$("#regist_start_timeline_id").val(ele[2]);
		var startLabel="開始時刻<br><span id='start_time_disp_id'>"+ ele[3]+"</span>";

		$("#start_time_label_id").html(startLabel);

		$("#time_question").dialog('open');

	});

	$('#time_question').dialog({
		  autoOpen: false,
		  title: 'チャットルーム予約',
		  closeOnEscape: false,
		  modal: true,
		  buttons: {
		    "予約する": function(){
		      $(this).dialog('close');
		    }
		  }
		});

});