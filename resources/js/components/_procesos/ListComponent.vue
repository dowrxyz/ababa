<template>
<div class="card">
    <div class="card-header">
        <h3>Total Procesos : {{ pagination.total }}</h3>
        <button class="btn btn-success text-right" @click="crear"> <i class="fa fa-plus-circle"></i> Agregar</button>
        <button class="btn btn-danger text-right" @click="descargar()"> <i class="fa fa-list"></i> Exportar</button>
    </div>
    <div class="card-body">

      <el-row style="margin-left: 20px; margin-right:20px;" :gutter="10">

        <el-col :span="4">
            <el-input @keyup.enter.native="Search" @clear="Search"
                    size="small"
                    placeholder="Nombre Socio"
                    prefix-icon="el-icon-search"
                    v-model="nombre"
                    clearable
                    class="w-100">
            </el-input>
        </el-col>

        <el-col :span="4">
          <el-input @keyup.enter.native="Search" @clear="Search"
                    size="small"
                    placeholder="Lugar"
                    prefix-icon="el-icon-search"
                    v-model="lugar"
                    clearable
                    class="w-100">
            </el-input>
        </el-col>

        <el-col :span="4">
          <el-select class="w-100" v-model="usuario" placeholder="Usuarios" @change="Search()">
              <el-option
                  v-for="item in usuarios"
                  :key="item.id"
                  :label="item.user"
                  :value="item.id">
                  <span style="float: left">{{ item.user }}</span>
              </el-option>
          </el-select>
       </el-col>

    </el-row>

  <el-table
    :data="procesos"
    style="width: 100%">
    <div slot="empty">
            <span>No se encontraron resultados <el-icon name="search"></el-icon></span>
        </div>
    <el-table-column
      label="Socio"
      prop="nombres">
    </el-table-column>
     <el-table-column
      label="Lugar"
      prop="lugar">
    </el-table-column>
    <el-table-column
      label="Detalle"
      prop="detalle">
    </el-table-column>
    <el-table-column
      label="Valor"
      prop="valor">
    </el-table-column>
    <el-table-column
      label="FechaReg"
      prop="created_at">
    </el-table-column>
    <el-table-column
      label="Creado por"
      prop="user">
    </el-table-column>
    <el-table-column
      align="right">
      <template slot-scope="scope">
        <el-button
          size="mini"
          type="primary"
          @click="handleEdit(scope.$index)"><i class="fa fa-pencil" style="font-size: 15px;"></i></el-button>
      </template>
    </el-table-column>
  </el-table>

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
        props: ['procesos', 'pagination'],
        data()
        {
            return {
                search: '',
                descargando: '',
                nombre: '',
                lugar: '',
                usuarios: [],
                usuario: '',
                offset: 5
            }
        },

        mounted() {
          axios.get('/api/usuariosAdmin').then((response) => {
                this.usuarios = response.data
          });
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
                const params = {
                    nombre: this.nombre.toUpperCase(),
                    lugar: this.lugar.toUpperCase(),
                    usuario: this.usuario,
                }
                this.$emit('changePage', page, params);
            },
            Search()
            {
                const params = {
                    nombre: this.nombre.toUpperCase(),
                    lugar: this.lugar.toUpperCase(),
                    usuario: this.usuario,
                }
                this.$emit('search', 1, params);
            },
            handleEdit( index )
            {
                var proceso = this.procesos[index];
                this.$emit('editar', proceso)
            },
            crear()
            {
                this.$emit('crear');
            },
            descargar()
            {
              console.log("ejecutando");
              axios.get('/export_procesos_socios').then((response) => {
                  this.descargando = response.data;
              });

              var url = '/documentos_imagenes/procesos_excels/'+this.descargando;

              axios({
                    url: url,
                    method: 'GET',
                    responseType: 'blob',
                }).then((response) => {
                     var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                     var fileLink = document.createElement('a');

                     fileLink.href = fileURL;
                     fileLink.setAttribute('download', this.descargando);
                     document.body.appendChild(fileLink);

                     fileLink.click();
                });
            }
        }
    }
</script>
