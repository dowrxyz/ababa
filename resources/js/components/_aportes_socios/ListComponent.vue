<template>
    <div class="card">
      <div class="card-header text-right">
        <h4 class="card-title">Listado de Aportes {{nombre}}</h4>
        <button class="btn btn-danger" v-if="dependiente" @click="regresar">Regresar</button>
        <button class="btn btn-success" v-else @click="agregarRegistro"> <i class="fa fa-plus-circle"></i> Agregar Nuevo</button>
        <button class="btn btn-danger" @click="descargar()">Exportar Excel</button>
      </div>
      <div class="table-responsive">

      <el-row style="margin-left: 20px; margin-right:20px;" :gutter="10">
        <el-col :span="4">
            <el-input @keyup.enter.native="Search" @clear="Search"
                    size="small"
                    placeholder="Nombre Socio"
                    prefix-icon="el-icon-search"
                    v-model="nombreSocio"
                    clearable
                    class="w-100">
            </el-input>
        </el-col>
      </el-row>

        <table class="table">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Pago</th>
              <th>Saldo</th>
              <th>Actualizacion</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(aporte, index) in aportes" :key="index">
              <td>{{aporte.created_at}}</td>
              <td>{{aporte.socio.documento}}</td>
              <td> {{aporte.socio.nombres}} </td>
              <td> {{aporte.total}} </td>
              <td> {{aporte.total}} </td>
              <td> {{aporte.updated_at}} </td>
              <td> <span :class="aporte.estado.class">{{aporte.estado.value}}</span> </td>
              <td>
                <i class="fa fa-edit btn btn-primary" v-on:click="editRegistro(index)"></i>
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
    props: ['aportes', 'dependiente', 'nombre', 'pagination'],
    data() {
        return {
            descargando : '',
            nombreSocio: '',
            offset: 5
        }
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
                nombre: this.nombreSocio.toUpperCase()
            }
            this.$emit('changePage', page, params);
        },
        Search()
        {
          const params = {
            nombre: this.nombreSocio.toUpperCase()
          }
          this.$emit('search', 1, params);
        },
        regresar()
            {
                this.$emit('regresar');
        },
        editRegistro(index)
            {
                var aporte = this.aportes[index];
                this.$emit('edit', aporte);
        },
        agregarRegistro()
            {
                this.$emit('crear');
        },
        descargar()
            {
              axios.get('/export_aportes_socios').then((response) => {
                  this.descargando = response.data;
              });

              var url = '/documentos_imagenes/aportes_excels/'+this.descargando;

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
