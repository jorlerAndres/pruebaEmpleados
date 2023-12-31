let gestionarButton=document.getElementById('gestionar');
let tbody=document.getElementById("tbody");
let divTabla=document.getElementById("divTabla");
let divFormulario=document.getElementById("divFormulario");
let cancelar=document.getElementById("cancelar");
let modalBorrar=new bootstrap.Modal(document.getElementById('modal_borrar'));
let modalset=new bootstrap.Modal(document.getElementById('modal_set'));
let formulario=document.getElementById('formulario');

document.addEventListener("DOMContentLoaded",function(){
   
    getEmpleados();
    
})

formulario.addEventListener('submit',setEmpleado)

  async function getEmpleados(){
    
    let url = "app/Routes.php";
    let response = await fetch(url,{
      method: "GET",
    })
    .then(res =>  res.text())
    .then(res=>{
        document.getElementById("tbody").innerHTML=res;
    }) 
    .catch(error => {
        console.log(error);
    });
  
  }

  tbody.addEventListener('click',function(e){

    if(e.target.classList.contains("borrar")){

        let id=e.target.getAttribute("data-id")
        borrarEmpleado(id);
    }
    else if(e.target.classList.contains('editar')){

        let id=e.target.getAttribute("data-id")
        editarEmpleado(id);
        aparecerFormulario();
    }

})

async function editarEmpleado(id){

    let url = "app/Routes.php?id="+id;
    let response = await fetch(url,{
        method: "GET",
      })
      .then(res =>  res.json())
      .then(res=>{
        putdataEmpleado(res);
      }) 
      .catch(error => {
          console.log(error);
      });

}

function putdataEmpleado(data){

    document.getElementById('nombre').value=data.nombre;
    document.getElementById('email').value=data.email;
    document.getElementById('id').value=data.id
    document.querySelectorAll('#formulario input[type=checkbox]').forEach(function(checkElement) {
        checkElement.checked = false;
    });

    let sex= data.sexo =='M' ? 'masculino' : 'femenino';
    document.getElementById(sex).checked=true;

    document.getElementById('area').value=data.area_id;
    let boletin= data.boletin ==0 ? false : true;
    document.getElementById('boletin').checked=boletin;
    document.getElementById('descripcion').value=data.descripcion;
    data.roles.forEach(element => {
        let elemento=document.getElementById(element)
        if(elemento){
            document.getElementById(element).checked=true;
        }
    });
}


async function borrarEmpleado(id){
   
    let url = "app/Routes.php";
    let response = await fetch(url,{
        method: "DELETE",
        body: id,
      })
      .then(res =>  res.text())
      .then(res=>{
        if(res){
            modalBorrar.show();
        }
      }) 
      .catch(error => {
          console.log(error);
      });
}

async function setEmpleado(e){
    e.preventDefault();
    
    if(!document.getElementById('Desarrollador').checked && 
       !document.getElementById('Analista').checked &&
       !document.getElementById('Diseñador').checked){
       
        alert('Recuerda elegir al menos un rol');
        return;

    }
    
    let url = "app/Routes.php";
    
    let formData = new FormData(formulario);
    let response = await fetch(url,{
        method: "POST",
        body: formData,
      })
      .then(res =>  res.text())
      .then(res=>{
       if(res){
        modalset.show();
       }
      }) 
      .catch(error => {
          console.log(error);
      });
}


gestionarButton.addEventListener('click',aparecerFormulario)

function aparecerFormulario(){
   formulario.reset();
    divTabla.style='display:none';
    divFormulario.classList.add('movement_from_back');
    divFormulario.style='opacity:1';

}

cancelar.addEventListener('click',aparecerTabla)

function aparecerTabla(){
   
    divFormulario.style='display:none';
    divFormulario.classList.add('movement_from_face');
    divTabla.classList.add('movement_from_back');
    divTabla.style='display:block';
    divFormulario.classList.remove('movement_from_back');
    divFormulario.classList.remove('movement_from_face');
    
}

document.getElementById('cerrarRegistro').addEventListener('click',function(){
    getEmpleados();
    aparecerTabla();
   document.getElementById('id').value='no';
})

document.getElementById('cerrarDelete').addEventListener('click',function(){

    getEmpleados();
    aparecerTabla();
})

