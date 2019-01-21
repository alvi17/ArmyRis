$(function() {
    $('#category').on('change', function() {
        if('Complementary' === $(this).val()) {
            $("#compl-amt-blk").removeClass("disp-off");
        } else{
            $("#compl-amt-blk").addClass("disp-off");
            $("#complementary_amount").val("");
        }
    });
});

/*$(function() {
    $("#subscriber_add_upd").validate({        
		rules:{
			username:{
				required:true
			},
			password:{
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
            area:{
				required:true
			},
            house_no:{
				required:true
			},
			official_mobile:{
				required:true,
				number:true
			},
			email:{
				email: true
			},
            package:{
				required:true
			},
            category:{
				required:true
			}
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
});*/