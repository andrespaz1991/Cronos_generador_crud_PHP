function nuevoAjax(){
    var xmlhttp=false;
    try {
    htmlhttp=new activeXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
     try {htmlhttp=new activeXObject("Microsoft.XMLHTTP");
    }
    catch (e) {
    xhtmlhttp=false;
    }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefineded'){
    xmlhttp=new XMLHttpRequest();
    }
    return xmlhttp;
    }

    function buscar(proyecto){
        //alert("esta recibiendo datos"+funcion+" "+nombre);
        ajax=nuevoAjax();
       
     ajax.open("POST","consulta_ajax.php?proyecto="+proyecto,true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {
                    console.log(ajax.responseText);
      var contenido=document.getElementById('txtsugerencias').innerHTML;
                    if(ajax.responseText!=0 ){
                        document.getElementById('txtsugerencias').innerHTML ='En el proyecto <strong>'+proyecto.toLowerCase()+'</strong> se sugiere usar <strong>'+ajax.responseText+'</strong>';
                    }
                    if(ajax.responseText=='0'){
                        console.log('vaciando..');
        document.getElementById('txtsugerencias').innerHTML = '';
                    }
 
                }
            }
        ajax.send("proyecto="+proyecto);
        }