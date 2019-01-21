$(document).ready(function(){
    $("#user_inpt_frm").validate({        
		rules:{
			username:{
				required:true
			},
			password:{
				required:true
			},
			confirm_password:{
				required:true
			},
            ba_no:{
              required:true  
            },
			firstname:{
				required:true
			},
			rank:{
				required:true
			},
			mobile:{
				required:true,
				number:true
			},
			email:{
//				required:true,
				email: true
			},
//			date:{
//				required:true,
//				date: true
//			}
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parents('.form-group').removeClass('has-error').addClass('has-success');
		}
	});
});
