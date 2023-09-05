<template>
<div class="row justify-content-center">
        <div class="col-md-12">
            <usuario-list-component :pagination="pagination" @changePage="changePage(...arguments)" @editar="editarUsuario(...arguments)" @show="showUsuario(...arguments)" :usuarios="usuarios">> </usuario-list-component>
            <usuario-create-component @new="updateList"></usuario-create-component>
            <usuario-edit-component @new="updateList" :editando="editando"> </usuario-edit-component>
            <usuario-show-component :editando="editando"> </usuario-show-component>
        </div>
    </div>
</template>


<script>
    export default {

        data()
        {
            return {
                usuarios: [],
                editando: '',
                id: '',
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
            axios.get('/api/usuarios').then((response) =>
            {
                this.usuarios = response.data.usuario.data;
                this.pagination = response.data.pagination;
                console.log(this.usuarios);
                console.log(this.pagination);
            });
        },

        methods: {
            changePage( page ){
                this.pagination.current_page = page;
                this.updateList(page);
            },
            updateList(page) {
                axios.get('/api/usuarios?page='+page).then((response) =>
                {
                    this.usuarios = response.data.usuario.data;
                    this.pagination = response.data.pagination;
                    console.log(this.usuarios);
                });
            },
            editarUsuario(usuario){
                this.editando = usuario;
                $("#editUser").modal('show');
            },
            showUsuario(usuario){
                console.log(usuario);
                this.editando = usuario;
                $("#showUser").modal('show');
            }
        }
    }
</script>
