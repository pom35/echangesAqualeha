"use strict";
/**
 * @class  elFinder command "sendmail"
 * Envoi un mail avec un token permettant d'accéder au dossier
 *
 **/
elFinder.prototype.commands.sendmail= function() {
	var fm  = this.fm;
	var filename;
	var filepath;
	var dialog;
    this.exec = function(hashes) {
        //implement what the custom command should do her
    	//afficher un form en popup avec commentaire et date d'expiration
    	
    	var files   = this.files(hashes);
    	var urls = $.map(files[0], function(f) { return f.url; });
    	filename = files[0].name;
    	var dialog_html = '<div id="dialog-form" title="Envoyer un accès">'
    	  +'<form>'
    	      +'<label for="email">Email</label><br/>'
    	      +'<input type="text" name="email" id="email" value="" class=""><br/>'
    	      +'<label for="name">Commentaire</label><br/>'
    	      +'<input type="text" name="Commentaire" id="Commentaire" value="" class=""><br/>'
    	      +'<label for="password">Date d\'expiration</label><br/>'
    	      +'<input type="date" name="date_exp" id="date_exp" value="" class=""><br/>'
    	 
    	      +'<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">'
    	 +'</form>'
    	+'</div>';
    	
    	 $('#dialog-form').remove();
    	 $('body').append(dialog_html);
    	 
	 	filepath = this.fm.path(files[0].hash);
	    dialog = $( "#dialog-form" ).dialog({
	        autoOpen: false,
	        height: 400,
	        width: 350,
	        modal: true,
	        buttons: {
	          "Envoyer un accès": sendmail,
	          Cancel: function() {
	            dialog.dialog( "close" );
	          }
	        },
	        close: function() {
	          form[ 0 ].reset();
	        }
	      });
	    var form;
	    form = dialog.find( "form" ).on( "submit", function( event ) {
	        event.preventDefault();
	        dialog.dialog( "close" );
	      });
	    
	    dialog.dialog( "open" );
	    $( "#date_exp" ).datepicker({ dateFormat: 'dd-mm-yy' });
    }
    
    function sendmail(){
    	var $form;
    	$form = $('#dialog-form').find('form');
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};

        $.map(unindexed_array, function(n, i){
            indexed_array[n['name']] = n['value'];
        });
        
        //vérification de la date
        var date_isvalid = false;
        var d = $.datepicker.parseDate('dd-mm-yy', indexed_array.date_exp);
        if ( Object.prototype.toString.call(d) === "[object Date]" ) {
        	if (!isNaN(d.getTime())) {
	        	date_isvalid = true;
        	}
    	}
        if(!date_isvalid){
        	alert('la date n\'est pas dans un format correct');
        	return false;
        }
        indexed_array['filename'] = filename;
        indexed_array['filepath'] = filepath;
        //appel ajax pour récupérer le token
        $.post( {
			url: 'token.php',
			data: indexed_array,
			success: function( data ) {
				genLink(indexed_array,data);
			},
			error: function() {
			}
		} );
        return false;
    }
    
    function genLink(indexed_array,data){
    	var hash = location.hash.replace('#','');
    	var this_url = window.location.href.replace('#'+hash, '');
    	var comment = encodeURIComponent(indexed_array.Commentaire);
    	
    	var link = '<a id="send_mail" href="mailto:'+indexed_array.email+'?subject=acc%C3%A8s%20aux%20fichiers&amp;body='+comment+'%0AVeuillez%20trouver%20les%20fichiers%20%C3%A0%20t%C3%A9l%C3%A9charger%20en%20cliquant%20sur%20ce%20lien%20:%0A'+this_url+'?t='+data+'">send mail</a>'
    	dialog.dialog( "close" );
    	$('body').append(link);
    	$('#send_mail')[0].click();
    	$('#send_mail').remove();
    }
    
    this.getstate = function() {
          //return 0 to enable, -1 to disable icon access
          return 0;
    }
}