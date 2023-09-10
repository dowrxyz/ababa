<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
      <socios-list-component
        v-if="list"
        :socios="socios"
        :pagination="pagination"
        @editar="editarSocio(...arguments)"
        @crear="crear"
        @search="search(...arguments)"
        @changePage="changePage(...arguments)"
        @createAporte="createAporte(...arguments)"
        @listAportes="listAportes(...arguments)"
      >
      </socios-list-component>
      <socios-create-component
        v-if="create"
        @new="store"
      ></socios-create-component>
      <socios-edit-component
        v-if="edit"
        :editando="editando"
        @regresar="store"
        @new="store"
      >
      </socios-edit-component>
      <socios-show-component v-if="show"> </socios-show-component>
      <aportes-socios-create-component
        v-if="create_aporte"
        :socio="aportante"
        :banco="banco_aportante"
        @regresar="store"
        @new="store"
      >
      </aportes-socios-create-component>
      <aportes-socios-list-component
        v-if="list_aporte"
        :aportes="aportes"
        :dependiente="dependiente"
        :nombre="nombres"
        @new="store"
        @regresar="store"
        @edit="editarAporte(...arguments)"
      >
      </aportes-socios-list-component>
      <aportes-socios-edit-component
        v-if="edit_aporte"
        :editando="editando"
        @regresarListado="listAportes(...arguments)"
      ></aportes-socios-edit-component>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        list: true,
        create: false,
        edit: false,
        show: false,
        socios: [],
        create_aporte: false,
        editando: "",
        aportante: "",
        banco_aportante: "",
        list_aporte: false,
        aportes: [],
        dependiente: true,
        edit_aporte: false,
        pagination: {
          total: 0,
          current_page: 0,
          per_page: 0,
          last_page: 0,
          from: 0,
          to: 0,
        },
      };
    },

    mounted() {
      axios.get("/socios/listado").then((response) => {
        this.socios = response.data.socio.data;
        this.pagination = response.data.pagination;
        console.log(this.socios);
        console.log(this.pagination);
      });
    },

    methods: {
      changePage(page, params) {
        this.pagination.current_page = page;
        this.search(page, params);
      },
      updateList(page) {
        axios.get("/socios/listado?page=" + page).then((response) => {
          this.socios = response.data.socio.data;
          this.pagination = response.data.pagination;
        });
      },
      crear() {
        (this.list = false),
          (this.edit = false),
          (this.show = false),
          (this.create = true);
        this.list_aporte = false;
        this.edit_aporte = false;
      },
      store() {
        this.updateList();
        (this.list = true),
          (this.edit = false),
          (this.show = false),
          (this.create = false);
        this.create_aporte = false;
        this.list_aporte = false;
        this.edit_aporte = false;
      },
      search(page, params) {
        //obtener cada uno de los datos
        console.log(params);
        if (
          params.documento.length == 0 &&
          params.nombre.length == 0 &&
          params.cuentaBancaria.length == 0 &&
          params.telefono.length == 0 &&
          params.usuario.length == 0 &&
          params.pago.length == 0
        ) {
          axios.get("/socios/listado?page=" + page).then((response) => {
            this.socios = response.data.socio.data;
            this.pagination = response.data.pagination;
            console.log("vacio");
          });
        } else {
          axios.post("/socios/buscar?page=" + page, params).then((response) => {
            this.socios = response.data.socio.data;
            this.pagination = response.data.pagination;
            console.log(this.socios);
          });
        }
      },
      editarSocio(socio) {
        this.editando = socio;
        (this.list = false),
          (this.edit = true),
          (this.show = false),
          (this.create = false),
          (this.list_aporte = false);
        this.edit_aporte = false;
      },
      createAporte(socio_id, banco_id) {
        (this.list = false),
          (this.edit = false),
          (this.show = false),
          (this.create = false);
        this.create_aporte = true;
        this.list_aporte = false;
        this.edit_aporte = false;

        this.aportante = socio_id;
        this.banco_aportante = banco_id;
      },
      listAportes(id_socio, nombres) {
        (this.list = false),
          (this.edit = false),
          (this.show = false),
          (this.create = false);
        this.create_aporte = false;
        this.list_aporte = true;
        this.nombres = nombres;
        this.edit_aporte = false;

        axios.get("/api/get-aportes-socios/" + id_socio).then((response) => {
          this.aportes = response.data;
        });
      },
      editarAporte(aporte) {
        this.editando = aporte;
        (this.list = false),
          (this.edit = false),
          (this.show = false),
          (this.create = false);
        this.create_aporte = false;
        this.list_aporte = false;
        this.edit_aporte = true;
      },
    },
  };
</script>
