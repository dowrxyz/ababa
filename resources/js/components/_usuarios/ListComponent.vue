<template>
    <div class="card">
      <div class="card-header text-right">
        <h4 class="card-title">Listado de Usuarios</h4>
        <button class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#createUser"> <i class="fa fa-plus"></i> Agregar Nuevo</button>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Id</th>
              <th>Nombre</th>
              <th>Usuario</th>
              <th>Status</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(usuario, index) in usuarios" :key="index">
              <td>
                <span class="fw-bold">{{usuario.id}}</span>
              </td>
              <td>{{usuario.name}}</td>
              <td> {{usuario.user}} </td>
              <td>
                  <span class="badge rounded-pill badge-light-success me-1" v-if="usuario.status_user === 1">Activo</span>
                  <span class="badge rounded-pill badge-light-danger me-1" v-else>Inactivo</span>
                  </td>
              <td>
                <i class="fa fa-edit btn btn-primary" v-on:click="editRegistro(index)"></i>
                <i class="fa fa-eye btn btn-info" v-on:click="showRegistro(index)"></i>
              </td>
            </tr>
          </tbody>
        </table>

        <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item" v-if="pagination.current_page > 1">
                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1)">Atrás</a>
            </li>
            <li class="page-item" v-for="page in pagesNumber" :key="page" v-bind:class="[ page == isActived ? 'active' : '']">
                <a class="page-link" href="#" @click.prevent="changePage(page)">
                    {{ page }}
                </a>
            </li>
            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">Siguiente</a>
            </li>
        </ul>
    </nav>

      </div>
    </div>
</template>

<script>
    export default {
        props: ['usuarios', 'pagination'],
        data()
        {
            return {
                data_usuario_edit: '',
                modeEdit: 0,
                offset: 5
            }
        },

        mounted() {

        },
        computed: {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }

                var to = from + (2 * this.offset);
                if(to > this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            changePage( page ){
                this.$emit('changePage', page);
            },
            editRegistro(index){
                let usuario = this.usuarios[index];
                console.log(usuario);
                this.$emit('editar', usuario)
            },
            showRegistro(index){
                let usuario = this.usuarios[index];
                console.log(usuario);
                this.$emit('show', usuario);
            }
        }
    }
</script>
