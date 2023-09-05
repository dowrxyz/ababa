<template>
<div class="row justify-content-center">
        <div class="col-md-12">
            <aportes-socios-list-component
                :aportes="aportes"
                :pagination="pagination"
                @changePage="changePage(...arguments)"
                @search="search(...arguments)"
                :dependiente="dependiente"
                :nombre="nombres"
                @new="store"
                @regresar="store"
                @edit="editarAporte(...arguments)"
                v-if="list"
                @crear="crearRegistro"
            > </aportes-socios-list-component>
            <aportes-socios-create-component v-if="create" @regresar="store" @new="updateList"></aportes-socios-create-component>
            <aportes-socios-edit-total-component v-if="edit" @new="updateList" @regresar="store" :editando="editando"> </aportes-socios-edit-total-component>
        </div>
    </div>
</template>

<script>
    export default {

        data()
        {
            return {
                aportes: [],
                dependiente: false,
                nombres: '',
                edit: false,
                create: false,
                list: true,
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
                type_user: $("#type_user").val(),
                usuario_id: $("#usuario_id").val(),
            }
            axios.post('/aportes-socios/buscar', parametros).then((response) =>
            {
                this.aportes = response.data.aporte.data;
                this.pagination = response.data.pagination;
                console.log(this.aportes);
                console.log(this.pagination);
            });
        },

        methods: {
            changePage( page, params ){
                this.pagination.current_page = page;
                this.search(page, params);
            },
            search( page, params )
            {
                console.log(params);
                if(params.nombre.length == 0) {
                    const parametros = {
                        nombre: '',
                        type_user: $("#type_user").val(),
                        usuario_id: $("#usuario_id").val(),
                    }
                    axios.post('/aportes-socios/buscar?page='+page, parametros).then((response) =>
                    {
                        this.aportes = response.data.aporte.data;
                        this.pagination = response.data.pagination;
                        this.list = true;
                        this.create = false;
                        this.edit = false;
                    });
                } else {
                    const parametros = {
                        nombre: params.nombre,
                        type_user: $("#type_user").val(),
                        usuario_id: $("#usuario_id").val(),
                    }
                    axios.post('/aportes-socios/buscar?page='+page, parametros).then((response) =>
                    {
                        this.aportes = response.data.aporte.data;
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
                    type_user: $("#type_user").val(),
                    usuario_id: $("#usuario_id").val(),
                }
                axios.post('/aportes-socios/buscar', parametros).then((response) =>
                {
                    this.aportes = response.data.aporte.data;
                    this.pagination = response.data.pagination;
                    this.list = true;
                    this.create = false;
                    this.edit = false;
                });
            },
            store() {
                this.updateList();
                this.list = true;
                this.edit = false;
                this.create = false;
            },
            editarAporte(aporte){
                this.editando = aporte;
                this.list = false;
                this.create = false;
                this.edit = true;
            },
            showUsuario(banco){
                console.log(banco);
                this.editando = banco;
                $("#showAporte").modal('show');
            },
            crearRegistro()
            {
                this.list = false;
                this.create = true;
                this.edit = false;
            }
        }
    }
</script>
