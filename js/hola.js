"Use strict";
document.addEventListener("DOMContentLoaded", function() {
    get_productos();
});

let URL = 'http://localhost/catalogo/api/';
let container = document.getElementById('container');
let btn_agregar_producto = document.getElementById('btn-agregar-producto');

/**********************
 * EVENTOS
 **********************/
function mostrar_editor() {

}

/**********************
 * FUNCIONES AJAX
 **********************/
async function get_productos() {
    try {
        let response = await fetch(URL + 'productos');
        if (response.ok) {
            let e = await response.json();
            container.removeChild(container.firstChild);
            let section_container = document.createElement('section');
            e.forEach(i => {
                /*elementos DOM creados*/
                let section_card = document.createElement('section');
                let p = document.createElement('p');
                //let p = document.createElement('p');
                let img = document.createElement('img');
                let btn_vermas = document.createElement('button');

                /*text de los elementos creados*/
                //let descripcion = document.createTextNode(i.descripcion);
                let titulo = document.createTextNode(i.nombre);
                let btn_text = document.createTextNode("Ver mas");

                /*atributos de los elementos creados*/
                img.setAttribute('src', i.imagen);
                btn_vermas.setAttribute('id', "btn-vermas");
                btn_vermas.setAttribute('value', i.id_producto);
                btn_vermas.appendChild(btn_text);
                p.appendChild(titulo);
                //p.appendChild(descripcion);

                /*agregando elementos creados al section*/
                //section.appendChild(p);
                section_card.appendChild(img);
                section_card.appendChild(p);
                section_card.appendChild(btn_vermas);

                /*EVENTO a cada boton que se crea*/
                btn_vermas.addEventListener('click', ver_mas);
                /*asignando clase a la card para el estilo*/
                section_card.classList.add("section-card");
                /*agregando section card a section container*/
                section_container.appendChild(section_card);
            });
            section_container.classList.add("section-container");
            /*mostrando section en container*/
            container.appendChild(section_container);
        }
    } catch (response) {
        container.innerHTML = '<h3>404 Page Not Found</h3>';
    }
}


btn_agregar_producto.addEventListener('click', agregar_producto);

function agregar_producto() {

    let nom = document.getElementById('input-nombre').value;
    let img = document.getElementById('input-imagen').files[0];
    let cant = document.getElementById('input-cantidad').value;
    let desc = document.getElementById('input-descripcion').value;

    let data = new FormData();
    data.append('nombre', nom);
    data.append('file_imagen', img);
    data.append('cantidad', cant);
    data.append('descripcion', desc);

    fetch(URL + 'productos', {
            method: "POST",
            body: data
        })
        .then(
            function() {
                get_productos()
            }
        )
        .catch(err => console.log(err));
}

function editar_producto() {
    let nom = document.getElementById('input-editar-nombre').value;
    let cant = document.getElementById('input-editar-cantidad').value;
    let desc = document.getElementById('input-editar-descripcion').value;

    let data = {
        nombre: nom,
        descripcion: desc,
        cantidad: cant
    }
    fetch('http://localhost/catalogo/api/productos' + this.value, {
            method: "PUT",

            headers: {
                "Content-type": "application/json; charset=UTF-8"
            },
            body: JSON.stringify(data)
        })
        .then(
            function() {
                ver_mas(this.value)
            }
        )
        .catch(err => console.log(err));

}

function agregar_imagen_a_producto() {
    let id_prod = this.getAttribute('data-id');
    let newimg = document.getElementById('input-imagen-new').files[0];
    let data = new FormData();
    data.append('file_imagen', newimg);
    fetch(URL + 'productos/' + id_prod + 'imagen', {
            method: "POST",
            body: data
        })
        .then(
            function() {
                ver_mas(this.value)
            }
        )
        .chatch(err => console.log(err));
}

async function ver_mas(id_prod = 0) {
    /*alert(this.value);*/ //OK - debe hacer fetch al producto con id_producto = this.value
    try {
        if (id_prod > 0) {
            id = id_prod;
        } else {
            id = this.value;
        }
        console.log(URL + 'productos/' + id);
        let response = await fetch(URL + 'productos/' + id);
        if (response.ok) {
            let e = await response.json();
            //console.log(e.datos[0].nombre); //OK
            container.removeChild(container.firstChild);
            let section_principal = document.createElement('section');
            section_principal.setAttribute('id', 'section-principal');
            /****************SECTION DETALLES****************/
            let section_detalle = document.createElement('section-detalle');
            //Nombre
            let h3 = document.createElement('h3');
            let nombre = document.createTextNode(e.datos[0].nombre);
            h3.appendChild(nombre);
            //Descripcion
            let p = document.createElement('p');
            let desc = document.createTextNode(e.datos[0].descripcion);
            p.appendChild(desc);
            //Cantidad
            let p2 = document.createElement('p');
            let cantidad = document.createTextNode('Cantidad disponible: ' + e.datos[0].cantidad);
            p2.appendChild(cantidad);
            //Button para editar
            let btn_editar = document.createElement('button'); //inicialmente visible
            let text_btn_editar = document.createTextNode("Editar producto");
            btn_editar.appendChild(text_btn_editar);
            btn_editar.setAttribute('class', 'btn btn-warning');
            btn_editar.setAttribute('id', 'btn-edicion');
            btn_editar.addEventListener('click', mostrar_edicion);
            //append...
            section_detalle.appendChild(h3);
            section_detalle.appendChild(p);
            section_detalle.appendChild(p2);
            section_detalle.appendChild(btn_editar);
            /****************fin SECTION DETALLES****************/
            /****************SECTION DE IMAGENES****************/
            let section_imagenes = document.createElement('section');
            section_imagenes.setAttribute('id', 'section-imagenes');
            //imagen principal
            let section_imagenprincipal = document.createElement('section');
            let img_principal = document.createElement('img');
            img_principal.setAttribute('src', e.datos[0].imagen);
            img_principal.setAttribute('class', 'img-principal');
            section_imagenprincipal.appendChild(img_principal);

            //sub-imagenes
            let section_subimagenes = document.createElement('section');
            e.arrayimagenes.forEach(x => {
                let article_subimagen = document.createElement('article');
                let subimg = document.createElement('img');
                subimg.setAttribute('src', x.path);
                subimg.setAttribute('value', x.id_producto);
                //button eliminar imagen : DELETE
                let btn_eliminar = document.createElement('button'); //inicialmente oculto
                btn_eliminar.setAttribute('id', 'btn-eliminar-imagen');
                btn_eliminar.setAttribute('data-idproducto', id);
                btn_eliminar.setAttribute('value', x.id_imagen);
                let text_btn_eliminar = document.createTextNode("E"); //va un icono
                btn_eliminar.appendChild(text_btn_eliminar);
                btn_eliminar.addEventListener('click', eliminar_imagen);

                article_subimagen.appendChild(btn_eliminar);
                article_subimagen.appendChild(subimg);
                section_subimagenes.appendChild(article_subimagen);
            });

            section_imagenes.appendChild(section_imagenprincipal);
            section_imagenes.appendChild(section_subimagenes);
            /*******SUB SECTION AGREGAR IMAGEN********/ //inicialmente oculta
            let section_agregarimagen = document.createElement('section');
            let input_file = document.createElement('input');
            input_file.setAttribute('type', 'file');
            input_file.setAttribute('id', 'input-imagen-new');
            input_file.setAttribute('name', 'imagennew');
            //button agregar imagen :POST
            let btn_addimagen = document.createElement('button');
            let text_btn_agregarimg = document.createTextNode("Subir");
            btn_addimagen.appendChild(text_btn_agregarimg);
            btn_addimagen.setAttribute('data-id', id);
            btn_addimagen.addEventListener('click', agregar_imagen_a_producto);

            section_agregarimagen.appendChild(input_file);
            section_agregarimagen.appendChild(btn_addimagen);
            section_imagenes.appendChild(section_agregarimagen);
            /*******fin SUB SECTION AGREGAR IMAGEN********/

            /****************fin SECTION DE IMAGENES ****************/
            /****************SECTION EDICION ****************/ //inicialmente oculta
            let section_edicion = document.createElement('section');
            //input nombre
            let input_text_nombre = document.createElement('input');
            input_text_nombre.setAttribute('type', 'text');
            input_text_nombre.setAttribute('id', 'input-editar-nombre');
            input_text_nombre.setAttribute('name', 'editar_nombre');
            //input descripcion
            let input_textarea_desc = document.createElement('input');
            input_textarea_desc.setAttribute('type', 'textarea');
            input_textarea_desc.setAttribute('id', 'input-editar-descripcion');
            input_textarea_desc.setAttribute('name', 'editar_descripcion');
            //input cantidad
            let input_number_cant = document.createElement('input');
            input_number_cant.setAttribute('type', 'number');
            input_number_cant.setAttribute('id', 'input-editar-cantidad');
            input_number_cant.setAttribute('name', 'editar_cantidad');
            //button editar producto :PUT
            let btn_update_producto = document.createElement('button');
            let text_btn_update = document.createTextNode("Confirmar cambios");
            btn_update_producto.appendChild(text_btn_update);
            btn_update_producto.setAttribute('value', id);
            btn_update_producto.setAttribute('class', 'btn btn-success');
            btn_update_producto.setAttribute('id', 'btn-confirmar-cambios');
            btn_update_producto.addEventListener('click', editar_producto);

            section_edicion.appendChild(input_text_nombre);
            section_edicion.appendChild(input_textarea_desc);
            section_edicion.appendChild(input_number_cant);
            section_edicion.appendChild(btn_update_producto);
            /****************fin SECTION EDICION ****************/
            section_principal.appendChild(section_imagenes);
            section_principal.appendChild(section_detalle);
            section_principal.appendChild(section_edicion);

            container.appendChild(section_principal);
        }
    } catch (response) {
        container.innerHTML = '<h3>404 - Page Not Found</h3>';
    }
}

function mostrar_edicion() {
    alert("Hola edicion");
}

function eliminar_imagen() {
    let id_prod = this.getAttribute('data-id');
    let id_img = this.value;
    console.log("prod: " + id_prod);
    console.log("img: " + id_img);
    /* fetch(URL + 'productos/' + id_prod + '/imagen/' + id_img + '/', {
             method: "DELETE"
         })
         .then(
             function() {
                 ver_mas(this.value)
             }
         )
         .chatch(err => console.log(err));*/
}