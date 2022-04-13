function eliminar(valor) {
    var opcion = confirm("¿Desea Eliminar el proyecto "+valor+"? ");
    if (opcion == true) {
        location.href ="index.php?id="+valor+"&borrar";
	} else {
	    
	}
	
}
function listas(contador){
    // var fieldHTML2='<label>Opción</label><input type="text" name="opcion['+contador+'][]" value=""/>';
     var wrapper = $('#'+contador); //Input field wrapper
     $(wrapper).append(fieldHTML2); // Add field html
   }
   function tipos_asociados(tipo='varchar(255)',name){
      const url = 'tipos.php?tipo='+tipo
   const http = new XMLHttpRequest()
   http.open("GET", url)
   http.onreadystatechange = function(){
       if(this.readyState == 4 && this.status == 200){
           var resultado = JSON.parse(this.responseText)
           var res=resultado.tipo_input;
           console.log('tipo_'+name);
           var sel = document.getElementById('tipo_'+name); 
   for (var i = 0; i < sel.length; i++) {
      var opt = sel[i];
      if(res==opt.value){
       document.getElementById('tipo_'+name).options.selectedIndex = i;
      }
     
   }
      
   
       }
   }
   http.send()
   }
   
   function template(nombre="field_name",nombre2="tipo",nombre3="TipoCampo",contador=0){
     var fieldHTML = '<div id="'+contador+'" class="registros"><label>Campo </label><input placeholder="Nombre_Persona" type="text" name="'+nombre+'[]" value=""/><label>Tipo</label><input onkeyup="tipos_asociados(this.value,'+contador+');" placeholder="varchar(255)" type="text" name="'+nombre2+'[]" list="tipos"><datalist id="tipos">  <option>INT(11)</option><option>VARCHAR(255)</option><option>DATE</option>  <option>TIME</option></datalist><select onchange="listas('+contador+');" id="tipo_'+contador+'" name="'+nombre3+'[]"><option value="hidden">hidden</option><option value="textarea">textarea</option><option value="select">select</option><option value="text">text</option><option value="number">number</option><option value="date">date</option><option value="time">time</option><option value="datetime-local">datetime-local</option><option value="password">password</option><option value="tel">tel</option><option value="month">month</option><option value="Buscar..">Buscar..</option><option value="week">week</option><option value="range">range</option><option value="color">color</option><option value="image">image</option><option value="url">url</option></select><a href="javascript:void(0);" class="remove_button" title="Remove field"><a href="javascript:void(0);" class="remove_button" title="Remove field"> <img width="1.5%" src="multimedia/remove.png"/></a></div>'; //New input field html 
       return fieldHTML;
       console.log('Hola mundo');
   }
 
   function campos(nombre,conta=0){
    //console.log(document.getElementsByClassName('registros').length);
    //var conveniancecount = $("div[class*='registros']").length;
      var maxField = 100; //Input fields increment limitation
      var addButton = $('.add_button'); //Add button selector
      var wrapper = $('.field_wrapper'); //Input field wrapper
      
  //template 
    
      var x = 1; //Initial field counter is 1
  
      $(addButton).click(function(){ //Once add button is clicked
        var fieldHTML=template(nombre,nombre2,nombre3,x);
        //console.log(document.getElementsByClassName('registros').length);
          if(x <= maxField){ //Check maximum number of input fields
              x++; //Increment field counter
              $(wrapper).append(fieldHTML); // Add field html
          }
      });
      
      $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
          e.preventDefault();
          $(this).parent('div').remove(); //Remove field html
          x--; //Decrement field counter
      });
  }
