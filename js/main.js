		function compare(){			
			var psw1 = $("input[name='wachtwoord']").val();
			var psw2 = $("input[name='herhaal_wachtwoord']").val();
			
			if(psw2 == "") return;
			
			if(psw1 != psw2){
				$("input[name='wachtwoord']").addClass('error');
				$("input[name='herhaal_wachtwoord']").addClass('error');
				$("input[name='wachtwoord']").removeClass('good');
				$("input[name='herhaal_wachtwoord']").removeClass('good');
				
				$("#wwSubmit").attr("disabled", true);
			} else {
				$("input[name='wachtwoord']").addClass('good');
				$("input[name='herhaal_wachtwoord']").addClass('good');
				$("input[name='wachtwoord']").removeClass('error');
				$("input[name='herhaal_wachtwoord']").removeClass('error');
				
				$("#wwSubmit").removeAttr("disabled");
			}
		}
