<template>
<div class="row justify-content-center">
        <div class="col-md-12">
            <procesos-list-component @changePage="changePage(...arguments)" :pagination="pagination" :procesos="procesos" @crear="crear()" @search="search(...arguments)" @editar="editar(...arguments)" v-if="list"> </procesos-list-component>
            <procesos-create-component  v-if="create" @regresar="regresar()" @new="regresar"></procesos-create-component>
            <procesos-edit-component  v-if="edit" :editando="editando" @regresar="regresar()" @new="regresar"> </procesos-edit-component>
        </div>
    </div>
</template>

<script>
    export default {

        data()
        {
            return {
                procesos: [],
                list: true,
                create: false,
                edit: false,
                editando: '',
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                }
            }
        },

        mounted() {
            const parametros = {
                nombre: '',
                lugar: '',
                usuario: '',
                type_user: $("#type_user").val(),
                usuario_id: $("#usuario_id").val(),
            }
            axios.post('/procesos/buscar?page=1', parametros).then((response) =>
            {
                this.procesos = response.data.proceso.data;
                this.pagination = response.data.pagination;
                console.log(this.procesos);
                console.log(this.pagination);
            })
        },
        methods: {
            changePage( page, params ){
                this.pagination.current_page = page;
                this.search(page, params);
            },
            search( page, params )
            {
                console.log(params);
                if(params.nombre.length == 0
                    && params.lugar.length == 0
                    && params.usuario.length == 0
                ) {
                    const parametros = {
                        nombre: '',
                        lugar: '',
                        usuario: '',
                        type_user: $("#type_user").val(),
                        usuario_id: $("#usuario_id").val(),
                    }
                    axios.post('/procesos/buscar?page='+page, parametros).then((response) =>
                    {
                        this.procesos = response.data.proceso.data;
                        this.pagination = response.data.pagination;
                        this.list = true;
                        this.create = false;
                        this.edit = false;
                    });
                } else {
                    const parametros = {
                        nombre: params.nombre,
                        lugar: params.lugar,
                        usuario: params.usuario,
                        type_user: $("#type_user").val(),
                        usuario_id: $("#usuario_id").val(),
                    }
                    axios.post('/procesos/buscar?page='+page, parametros).then((response) =>
                    {
                        this.procesos = response.data.proceso.data;
                        this.pagination = response.data.pagination;
                        this.list = true;
                        this.create = false;
                        this.edit = false;
                    });
                }
            },
            updateList() {
                const parametros = {
                    nombre: '',
                    lugar: '',
                    usuario: '',
                    type_user: $("#type_user").val(),
                    usuario_id: $("#usuario_id").val(),
                }
                axios.post('/procesos/buscar?page=', parametros).then((response) =>
                {
                    this.procesos = response.data.proceso.data;
                    this.pagination = response.data.pagination;
                    this.list = true;
                    this.create = false;
                    this.edit = false;
                });
            },
            crear()
            {
                    this.list = false;
                    this.create = true;
                    this.edit = false;
            },
            regresar()
            {
                this.updateList();
            },
            editar(proceso)
            {
                this.editando = proceso;
                this.list = false;
                    this.create = false;
                    this.edit = true;
            }
        }
    }
</script>
